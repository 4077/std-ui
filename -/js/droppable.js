// head {
var __nodeId__ = "std_ui__droppable";
var __nodeNs__ = "std_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var widget = this;

            var droppableOptions = {
                activeClass: 'droppable_active',
                hoverClass:  'droppable_hover',
                tolerance:   'pointer',

                drop: function (e, ui) {
                    var requestData = {};

                    var sourceId = ui.draggable.attr(widget.options.sourceIdAttr);

                    if (sourceId) {
                        $.extend(requestData, widget.options.requestData);

                        $.extend(requestData, {
                            source_id: sourceId,
                            target_id: $(this).attr(widget.options.targetIdAttr)
                        });

                        request(widget.options.requestPath, requestData);
                    }
                }
            };

            $.extend(droppableOptions, widget.options.pluginOptions);

            $("[" + widget.options.targetIdAttr + "]", widget.element).droppable(droppableOptions);
        }
    });
})(__nodeNs__, __nodeId__);
