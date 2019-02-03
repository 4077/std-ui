// head {
var __nodeId__ = "std_ui_quantify__main";
var __nodeNs__ = "std_ui_quantify";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            this.bind();
        },

        _destroy: function () {

        },

        _setOption: function (key, value) {
            $.Widget.prototype._setOption.apply(this, arguments);
        },

        bind: function () {
            var widget = this;

            var updateTimeout = 0;

            $("input", widget.element).rebind("click", function (e) {
                e.stopPropagation();
            });

            $("input", widget.element).rebind("keyup", function (e) {
                if (e.keyCode == 13) {
                    var input = $(this);
                    var updateTimeoutValue = widget.options.updateTimeout || 0;

                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(function () {
                        var updateData = widget.options.updateData;

                        updateData.value = input.val();
                        //updateData.control_id = widget.options.id;

                        request(widget.options.updatePath, updateData);
                    }, updateTimeoutValue);
                }
            });

            $("input", widget.element).rebind("blur", function (e) {
                var input = $(this);

                var updateTimeoutValue = widget.options.updateTimeout || 0;

                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(function () {
                    var updateData = widget.options.updateData;

                    updateData.value = input.val();
                    //updateData.control_id = widget.options.id;

                    request(widget.options.updatePath, updateData);
                }, updateTimeoutValue);
            });
        }
    });
})(__nodeNs__, __nodeId__);
