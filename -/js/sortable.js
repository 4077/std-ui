std_ui_sortable = function (data) {
    $(data.selector).each(function () {
        var tmp = $(this);

        var items_id_attr = data.items_id_attr || 'item_id';

        var plugin_options = {

            zIndex:   10000,
            items:    "[" + items_id_attr + "]",
            distance: 1,
            //helper:   'clone',

            helper: function (e, tr) // http://stackoverflow.com/questions/1307705/jquery-ui-sortable-with-table-and-tr-width
                    {
                        var $originals = tr.children();
                        var $helper = tr.clone();

                        $helper.children().each(function (index) {
                            // Set helper cell sizes to match the original sizes

                            $(this).width($originals.eq(index).width());
                        });
                        return $helper;
                    },

            start: function (e, ui) {

            },

            stop: function (e, ui) {

            },

            update: function (e, ui) {
                var i = 0;
                var sequence = [];

                var collection = data.plugin_options.items
                    ? $(data.plugin_options.items, tmp)
                    : $('[' + items_id_attr + ']', tmp);

                collection.each(function () {
                    sequence[i] = $(this).attr(items_id_attr);
                    i++;
                });

                var request_data = data.request_data || {};

                $.extend(request_data, {sequence: sequence});

                request(data.request_path, request_data);

                e.stopPropagation();
            }
        };

        if (data.plugin_options) {
            $.extend(plugin_options, data.plugin_options);
        }

        $(this).sortable(plugin_options);
    });
};
