var std_ui_qqfileuploader = function (data) {
    $(data.selector)

            .each(function () {
                      var element = $("#" + $(this).attr("id"))[0];

                      var plugin_options = {
                          element:  element,
                          action:   '/index.php',
                          params:   {call: JSON.stringify([data.request_path, data.request_data])},
                          debug:    false,
                          template: data.template,

                          onComplete: function (id, fileName, response) {
                              ewma.processResponse(JSON.parse(response));
                          }
                      };

                      $.extend(plugin_options, data.plugin_options);

                      var uploader = new qq.FileUploader(plugin_options);

                      $("[hover]", element).each(function () {
                          var hover_class = $(this).attr("hover");

                          $(this)
                                  .bind("mouseover", function () {
                                            $(this).addClass(hover_class);
                                        })
                                  .bind("mouseout", function () {
                                            $(this).removeClass(hover_class);
                                        });
                      });
                  });
};
