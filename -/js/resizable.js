// head {
var __nodeId__ = "std_ui__resizable";
// }

(function (__nodeId__) {
    window[__nodeId__] = function (data) {
        $(data.selector).each(function () {
            var pluginOptions = {

                start: function (e, ui) {

                },

                stop: function (e, ui) {
                    var requestData = data.data || {};

                    $.extend(requestData, {
                        width:  ui.element.width(),
                        height: ui.element.height()
                    });

                    request(data.path, requestData);

                    e.stopPropagation();

                    ewma.cancelFollow = false;
                }
            };

            if (data.pluginOptions) {
                $.extend(pluginOptions, data.pluginOptions);
            }

            $(this).resizable(pluginOptions);
        });
    }
})(__nodeId__);
