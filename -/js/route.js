std_ui__route = function (data) {
    if (data.path) {
        $(data.selector).rebind((data.event || "click") + ".std_ui__route", function (e) {
            e.stopPropagation();

            if (ewma.cancelFollow) {
                ewma.cancelFollow = false;

                return false;
            }

            href(data.path);

            return false;
        });
    }
};
