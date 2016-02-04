var client = new PPClient(),
    group_token = (undefined !== group_token) ? group_token : undefined,
    active_ticket = (undefined !== active_ticket) ? active_ticket : false,
    $cardSelection = $('.card-selection'),
    $cardTemplate = $('#card-template');

client.onConnect(function (session) {
    log("Connected to server.");
    $('.overlay').fadeOut(400);
    alertify.success("<i class='fa fa-fw fa-check'></i> Verbinding successvol!");

    if (false === active_ticket) {
        console.log('VOERT ICKET');
    } else {
        client.joinGroup(session, {'group-token': group_token}, function (res) {
            log('Group joined successfully! Resource Id %s'.replace(/%s/g, res.result.resource_id));

            // Listen to the revelation topic
            session.subscribe('pp/revelation', function (msg, result) {
                console.log(res.result.resource_id);
                console.log(result);
                if (true === result.in_reveal_state) {
                    $cardSelection.html('');
                    $.each(result.selected_cards, function (k, v) {
                        var tmpl = $cardTemplate.html();
                        $cardSelection.append($.tmpl(tmpl, {
                            'Points': v.card.points,
                            'CardClass': (res.result.resource_id == v.session.resource_id) ? 'card-selected card-current' : 'card-selected'
                        }));
                    });
                    log('All clients have selected. Revealing cards');
                }
            });

            // Click listener for a card panel
            $('.card-panel').click(function () {

                var token = $(this).data('group-token'),
                    card_id = $(this).data('card-id');

                // Send to the server which card has been selected
                session.call('pp/select_card', {'group-token': token, 'card-id': card_id}).then(function () {

                    // Remove current selection
                    if (undefined !== client.getSelectedCard()) {
                        $(client.getSelectedCard())
                            .removeClass('card-selected');
                    }

                    // Get the selected card
                    var card = $('div[data-group-token="' + token + '"][data-card-id="' + card_id + '"]');

                    // Deselect the selected card if selected again
                    if ($(card).data('card-id') == $(client.getSelectedCard()).data('card-id')) {
                        $(card).removeClass('card-selected');
                        card = undefined;
                    }

                    // Select a card if that one has not been selected
                    if ($(card).data('card-id') !== $(client.getSelectedCard()).data('card-id')) {
                        $(card).addClass('card-selected');
                        client.setSelectedCard(card);
                    }

                    // Tell the server to check for card revelations
                    session.publish('pp/revelation', {'msg': 'selection'});
                }, function (error) {
                    alertify.error(error.desc);
                });
            });
        }, function (error) {
            alertify.error(error.desc);
        });
    }
});

client.onDisconnect(function (error) {
    var msg;
    switch (error.code) {
        case 2:
            msg = "Aantal verbinding pogingen overschreden";
            break;
        case 3:
            msg = "Verbinding kon niet worden vastgesteld.";
            break;
        case 5:
            msg = "Verbinding verloren - geplande poging word uitgevoerd over 5 seconden";
            break;
        case 6:
            msg = "Verbinding verloren - geplande poging word uitgevoerd over 5 seconden";
            break;
    }
    $('.overlay').fadeIn(400);
    alertify.error("<i class='fa fa-fw fa-exclamation-triangle'></i> " + msg);
});
