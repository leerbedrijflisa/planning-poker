var selected_card;

$(function () {
    if (undefined !== selected_card_id) {
        selected_card = $('div[data-card-id="' + selected_card_id + '"]');
    }


    var webSocket = WS.connect("ws://127.0.0.1:8080");

    webSocket.on("socket/connect", function (session) {
        //session is an Autobahn JS WAMP session.

        console.log("Successfully Connected!");
        $('.card-panel').click(function () {
            var _this = $(this),
                url = Routing.generate('planning_card_select', {
                    'token': $(this).data('group-token'),
                    'id': $(this).data('card-id')
                });
            $.ajax({
                url: url,
                success: function (response) {
                    session.call(
                        'pp/select_card',
                        {
                            'group-token': $(_this).data('group-token'),
                            'card-id': $(_this).data('card-id')
                        })
                        .then(function (response) {
                            alert(response.result.reveal ? 'Tonen' : 'Niet tonen');
                        }, function (err, desc) {

                        });
                    if (undefined !== selected_card) {
                        $(selected_card)
                            .removeClass('card-selected');
                    }

                    var card = $('div[data-group-token="' + $(_this).data('group-token') + '"][data-card-id="' + $(_this).data('card-id') + '"]');

                    if ($(card).data('card-id') === $(selected_card).data('card-id')) {
                        $(card)
                            .removeClass('card-selected');
                        card = undefined;
                    }

                    if ($(card).data('card-id') !== $(selected_card).data('card-id')) {
                        $(card)
                            .addClass('card-selected');
                        selected_card = card;
                    }
                }
            });
        });
    });

    webSocket.on("socket/disconnect", function (error) {
        //error provides us with some insight into the disconnection: error.reason and error.code

        console.log("Disconnected for " + error.reason + " with code " + error.code);
    });
});