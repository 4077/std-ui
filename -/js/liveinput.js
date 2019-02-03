// head {
var __nodeId__ = "std_ui__liveinput";
// }

(function (__nodeId__) {
    window[__nodeId__] = function (data) {
        if (data.requestPath) {
            var $input = $(data.selector);

            var timeout = 0;

            $input.rebind(data.events, function (e) {
                setTimeout(function () {
                    if (e.which !== 9) {
                        var requestData = data.requestData || {};

                        var val = $input.val();

                        var lastSymbol = val.substr(-1);

                        clearTimeout(timeout);
                        if (!data.decimal || (lastSymbol !== ',' && lastSymbol !== '.')) {
                            timeout = setTimeout(function () {

                                $.extend(requestData, {
                                    value: val
                                });

                                request(data.requestPath, requestData);
                            }, data.timeout || 400);
                        }
                    }
                }, 0);
            });
        }
    }
})(__nodeId__);
