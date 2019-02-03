// head {
var __nodeId__ = "std_ui__resizable";
// }

(function (__nodeId__) {
    window[__nodeId__] = function (data) {
        $(data.selector).each(function () {
            var $container = $(this);

            $container.scrollLeft(data.scroll[0]).scrollTop(data.scroll[1]);

            var scrollTimeout;

            $container.rebind("scroll." + __nodeId__, function () {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }

                scrollTimeout = setTimeout(function () {
                    var data = data.data;

                    request(data.path, {
                        viewport: 'modules',
                        scroll:   {
                            top:  $container.scrollTop(),
                            left: $container.scrollLeft()
                        }
                    }, null, true);
                }, data.delay || 400);
            });
        });
    }
})(__nodeId__);
