var selected;

$('.card-panel').click(function () {
    selectCard($(this).data('group-token'), $(this).data('card-id'));
});

function selectCard(group_token, card_id) {
    var url = Routing.generate('planning_card_select', {
        'token': group_token,
        'id': card_id
    });
    $.ajax({
        url: url,
        success: function (response) {
            if (undefined !== selected) {
                $(selected)
                    .removeClass('card-selected');
            }

            var card = $('div[data-group-token="' + group_token + '"][data-card-id="' + card_id + '"]');

            if ($(card).data('card-id') === $(selected).data('card-id')) {
                $(card)
                    .removeClass('card-selected');
                card = undefined;
            }

            if ($(card).data('card-id') !== $(selected).data('card-id')) {
                $(card)
                    .addClass('card-selected');
                selected = card;
            }
        }
    });
}