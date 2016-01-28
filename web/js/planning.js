var client = new PPClient(),
    group_token = (undefined !== group_token) ? group_token : undefined;

client.onConnect(function (session) {
    log("Connected to server.");

    client.joinGroup(session, {'group-token': group_token}, function (res) {
        log('Group joined successfully! Resource Id %s'.replace(/%s/g, res.result.resource_id));

        // Listen to the revelation topic
        session.subscribe('pp/revelation', function (msg, result) {
            if (true === result.in_reveal_state) {
                $('.card-selection').html('');
                $.each(result.selected_cards, function (k, v) {
                    $('.card-selection').append('<div class="col-md-2 col-xs-4"><div class="panel panel-default card-panel card-selected"><div class="panel-body text-center"><h3>' + v.points + '</h3></div></div></div>');
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
            });
        });
    }, function (error) {
        console.error(error.desc);
    });
});

client.onDisconnect(function (error) {
    console.error('Error: ' + error.reason);
});
