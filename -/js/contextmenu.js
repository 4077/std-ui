var std_ui_contextmenu = {

    show: function (data) {
        p(88888888888888888);
        if (data.selector) {
            var container = $("#std_ui_contextmenu");
            var lt_corner = this.get_lt_corner(data.selector);

            container.css({
                position: 'absolute',
                left:     lt_corner.left + 'px',
                top:      lt_corner.top + 'px',
                zIndex:   data.zIndex || 1000 // todo as option
            });

            container.show();
            //container.rebind("click", function (e) {
            //    e.stopPropagation();
            //});
        }

        $(window).rebind("click.std_ui_contextmenu", function () {
            $("#std_ui_contextmenu").remove();
        });
    },

    get_lt_corner: function (selector) {
        var element = $(selector);

        return {left: element.offset().left, top: element.offset().top + element.height() + parseInt(element.css("padding-top")) + parseInt(element.css("padding-bottom"))};
    },

    create_container: function () {
        p(999999999999);
        if (!$("#std_ui_contextmenu").length) {
            $('<div id="std_ui_contextmenu" class="hidden"></div>').appendTo("body");
        }
    }
};
