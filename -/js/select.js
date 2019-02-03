std_ui__select = function (data) {
    $(data.selector).rebind('change select', function () {
        var requestData = data.requestData || {};

        $.extend(requestData, {
            value: $("option:selected", $(this)).val()
        });

        request(data.requestPath, requestData);
    });
};
