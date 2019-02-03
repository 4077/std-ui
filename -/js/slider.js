var std_ui_slider = function (data) {
    var void_request = false;

    $(data.selector)

            .each(function () {
                      var tmp = $(this);

                      var onslide = data.onslide || false;
                      var onchange = data.onchange || false;
                      var oncreate = data.oncreate || false;

                      var min = data.min || 0;
                      var max = data.max || 100;
                      var min_limit = data.min_limit || min;
                      var max_limit = data.max_limit || max;
                      var step = data.step || 1;

                      var range = false;
                      if (data.range) {
                          if (data.range == 'true' || data.range == '1')
                              range = true;
                          else
                              if (data.range == 'false' || data.range == '0')
                                  range = false;
                              else
                                  range = data.range;
                      }

                      var value = false;
                      if (data.value)
                          value = eval(data.value);

                      var values = false;
                      if (!value) {
                          values = [];
                          values[0] = data.min_value || min;
                          values[1] = data.max_value || max;
                      }

                      var min_container = data.min_container || false;
                      var max_container = data.max_container || false;
                      var min_caption = data.min_caption || min;
                      var max_caption = data.max_caption || max;

                      if (data.min_caption_container)
                          $(data.min_caption_container).html(min_caption);

                      if (data.max_caption_container)
                          $(data.max_caption_container).html(max_caption);

                      var value_container = data.value_container || false;

                      var update_ = function (ui) {
                          if (range) {
                              if (min_container) {
                                  if ($(min_container).is('input')) {
                                      $(min_container).data("is_min_container", true);
                                      handle_($(min_container), ui.values[0]);
                                  }
                                  else {
                                      $(min_container).html(ui.values[0]);
                                  }
                              }

                              if (max_container) {
                                  if ($(max_container).is('input')) {
                                      $(max_container).data("is_max_container", true);
                                      handle_($(max_container), ui.values[1]);
                                  }
                                  else {
                                      $(max_container).html(ui.values[1]);
                                  }
                              }

                              $(tmp).data("value", ui.values);
                          }
                          else {
                              if (value_container) {
                                  ui.value = ui.value || 0;

                                  if ($(value_container).is('input'))
                                      handle_(value_container, ui.value);
                                  else
                                      $(value_container).html(ui.value);
                              }

                              $(tmp).data("value", ui.value);
                          }
                      };

                      var handle_ = function (selector, value) {
                          $(selector)
                                  .attr("value", value)
                                  .rebind("change.std_ui_slider", function (e) {
                                              if (e.type == 'keyup' && e.keyCode != 13)
                                                  void_request = true;

                                              var correct_value = parseFloat(str_replace(',', '.', $(this).attr("value")));

                                              if (range) {
                                                  if (isNaN(correct_value)) correct_value = 0;
                                                  correct_value = constrains(correct_value, min_limit, max_limit);

                                                  $(this).attr("value", correct_value);

                                                  if ($(this).data("is_min_container"))
                                                      values[0] = correct_value;

                                                  if ($(this).data("is_max_container"))
                                                      values[1] = correct_value;

                                                  if (values[0] > values[1])
                                                      values[0] = values[1];

                                                  $(tmp).data("value", values);
                                                  $(tmp).slider("option", "values", values);
                                              }
                                              else {
                                                  if (isNaN(correct_value)) correct_value = 0;
                                                  correct_value = constrains(correct_value, min_limit, max_limit);

                                                  $(this).attr("value", correct_value);

                                                  $(tmp).data("value", correct_value);
                                                  $(tmp).slider("option", "value", correct_value);
                                              }
                                          });
                      };

                      update_({values: values, value: value});

                      $(this).slider({
                          min:    eval(min),
                          max:    eval(max),
                          step:   eval(step),
                          range:  data.range_visible || range,
                          value:  value,
                          values: values,

                          disabled: Boolean($(this).attr("disabled_")),

                          change: function (event, ui) {
                              if (!$(this).data("block_request")) {
                                  var request_data = data.request_data || {};

                                  $.extend(request_data, {
                                      value: $(tmp).data("value"),
                                      void:  void_request
                                  });

                                  request(data.request_path, request_data);

                                  void_request = false;
                                  if (onchange) eval(onchange + '($(this));');
                              }
                          },

                          slide: function (event, ui) {
                              update_(ui);

                              if (onslide) eval(onslide + '($(this));');
                          },

                          create: function (event, ui) {
                              if (oncreate) eval(oncreate + '($(this));');
                          }
                      });
                  });
};