var PPClient = function () {
    this.webSocket = WS.connect("ws://127.0.0.1:8000");
};

$.extend(PPClient.prototype, {
    data: {
        selectedCard: null
    },

    onConnect: function (callback) {
        this.webSocket.on("socket/connect", function (session) {
            callback(session);
        });
    },

    onDisconnect: function (callback) {
        this.webSocket.on("socket/disconnect", function (error) {
            callback(error);
        });
    },

    joinGroup: function (session, options, callback, error_callback) {
        session.call('pp/join_group', options).then(function (result) {
            callback(result);
        }, function (error, desc) {
            error_callback(error, desc);
        });
    },

    getSelectedCard: function () {
        return this.data.selectedCard;
    },

    setSelectedCard: function (card) {
        this.data.selectedCard = card;

        return this;
    }

});