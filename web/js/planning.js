var client = new PPClient(),
    selected_card,
    group_token = (undefined !== group_token) ? group_token : undefined;

client.onConnect(function (session) {
    log("Connected to server.");

    client.joinGroup(session, {'group-token': group_token}, function (res) {
        log('Group joined successfully! Resource Id %s'.replace(/%s/g, res.result.resource_id));
        session.subscribe('pp/revelation', function (msg, result) {
            // returns bool if cards have to be revealed

            var card = $('div[data-group-token="' + result.group_token + '"][data-card-id="' + result.card_id + '"]');
            if (undefined !== selected_card) {
                $(selected_card)
                    .removeClass('card-selected');
            }
            if ($(card).data('card-id') == $(selected_card).data('card-id')) {
                $(card)
                    .removeClass('card-selected');
                card = undefined;
            }
            if ($(card).data('card-id') !== $(selected_card).data('card-id')) {
                $(card)
                    .addClass('card-selected');
                selected_card = card;
            }
            console.log(result);
            if (true === result.reveal) {
                console.log('Reveal cards');
                //$('.card-selection').fadeOut(1000);
            }
            alert('Card action: ' + result.reveal);
        });

        $('.card-panel').click(function () {
            // on card click

            var _this = $(this),
                token = $(_this).data('group-token'),
                card_id = $(_this).data('card-id');

            session.call('pp/select_card', {'group-token': token, 'card-id': card_id}).then(function (res) {
                // card selection successfull

                session.publish('pp/revelation', {'msg': 'selection'});
            });

        });
    }, function (error) {
        console.error(error.desc);
    });
});

//var webSocket = WS.connect("ws://127.0.0.1:8000"),
//    selected_card;
//
//webSocket.on("socket/connect", function (session) {
//    //session is an Autobahn JS WAMP session.
//
//    log("Connected to server.");
//    session.call('pp/join_group', {'group-token': group_token}).then(function (res) {
//        // successfull group join
//
//
//

//    }, function () {
//        // Unable to join group
//
//        console.error('Unable to join group');
//    });
//});
//
client.onDisconnect(function (error) {
    console.error('Error: ' + error.reason);
});
