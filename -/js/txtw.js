(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {
            editable:                   true,
            type:                       'input',
            editTriggerSelector:        false,
            editTriggerClosestSelector: false,
            editTriggerEvent:           'click',
            editTriggerHide:            true,
            contentOnInit:              false,
            inputClass:                 false,
            inputHover:                 false,
            inputFocus:                 false,
            emptyContent:               '...',
            emptyClass:                 'empty',
            placeholder:                false,
            activeClass:                false,
            minWidth:                   30,
            maxWidth:                   false,
            fitToClosest:               false,
            fitInputToClosest:          false,
            selectOnFocus:              false,
            noSetPaddings:              false,
            padding:                    false
        },

        _create: function () {
            this.render();
        },
        
        $input: null,

        render: function () {
            var widget = this;
            var $widget = widget.element;
            var options = widget.options;

            $(widget.$input).remove();

            if (options.editable) {
                $widget.html(options.content);
                $widget.css('position', 'relative');

                var empty = $widget.html() == '';

                if (empty) {
                    var emptyContent = options.placeholder ? options.placeholder : options.emptyContent;

                    $widget.html(emptyContent);

                    if (options.emptyClass) {
                        $widget.addClass(options.emptyClass);
                    }
                } else {
                    if (options.emptyClass) {
                        $widget.removeClass(options.emptyClass);
                    }
                }

                var $fitTo;
                if (options.fitToClosest) {
                    if (options.fitToClosest == true) {
                        $fitTo = $widget.parent();
                    } else {
                        $fitTo = $widget.closest(options.fitToClosest);
                    }

                    $widget.height($fitTo.height());
                }

                var $editTrigger;
                if (options.editTriggerSelector) {
                    $editTrigger = $(options.editTriggerSelector);
                }
                else if (options.editTriggerClosestSelector) {
                    $editTrigger = $widget.closest(options.editTriggerClosestSelector);
                }
                else {
                    $editTrigger = $widget;
                }

                var editTriggerEvent = options.editTriggerEvent || 'click';

                $($editTrigger).rebind(editTriggerEvent + '.' + __nodeId__, function (e) {
                    if (!$($editTrigger).data('block')) {
                        $($editTrigger).data('block', true);

                        if (options.editTrigger && options.editTriggerHide) {
                            $editTrigger.hide();
                        }

                        //var widget.$input;

                        if (options.type == 'textarea') {
                            widget.$input = $('<textarea></textarea>');
                        }

                        if (options.type == 'input') {
                            widget.$input = $('<input type="text">');
                        }

                        if (options.activeClass) {
                            $widget.addClass(options.activeClass);
                        }

                        var inputContent = empty ? '' : $widget.text();

                        var contentOnInit = null;
                        if (typeof options.contentOnInit == 'string' || typeof options.contentOnInit == 'number') {
                            contentOnInit = options.contentOnInit;
                        }

                        if (null !== contentOnInit) {
                            inputContent = contentOnInit;
                        }

                        if (options.placeholder) {
                            widget.$input.attr('placeholder', options.placeholder);
                        }

                        widget.$input.val(inputContent).css({
                            fontSize: $widget.css('font-size')
                        });

                        var $fitInputTo;
                        if (options.fitInputToClosest) {
                            if (options.fitInputToClosest == true) {
                                $fitInputTo = $widget.parent();
                            } else {
                                $fitInputTo = $widget.closest(options.fitInputToClosest);
                            }
                        } else {
                            $fitInputTo = $widget;
                        }

                        var width = $fitInputTo.width();

                        if (options.minWidth && width < options.minWidth) {
                            width = Number(options.minWidth);
                        }

                        if (options.maxWidth && width > options.maxWidth) {
                            width = Number(options.maxWidth);
                        }

                        var height = $fitInputTo.outerHeight();

                        if (options.type == 'textarea') {
                            height += 20;
                        }

                        // paddings

                        if (!options.noSetPaddings) {
                            widget.$input.css({
                                paddingLeft:  parseInt($widget.css("padding-left")),
                                paddingRight: parseInt($widget.css("padding-right"))
                            });
                        }

                        if (options.padding) {
                            widget.$input.css({
                                padding: options.padding
                            });
                        }

                        var paddingLeft = parseInt(widget.$input.css("padding-left"));
                        var paddingRight = parseInt(widget.$input.css("padding-right"));

                        widget.$input.css({
                            position: 'absolute',
                            left:     0,
                            top:      0,
                            width:    width - paddingLeft - paddingRight,
                            height:   height
                        });

                        if (options.inputClass) {
                            widget.$input.addClass(options.inputClass);
                        }

                        if (options.inputHover) {
                            widget.$input.bind('mouseover.' + __nodeId__, function () {
                                $(this).addClass(options.inputHover);
                            }).bind('mouseout.' + __nodeId__, function () {
                                $(this).removeClass(options.inputHover);
                            });
                        }

                        $($widget).unbind(editTriggerEvent + '.' + __nodeId__);

                        $($fitInputTo).append(widget.$input);

                        if (empty || options.selectOnFocus) {
                            $(widget.$input).select();
                        }

                        widget.$input.focus();

                        if (!(empty || options.selectOnFocus)) {
                            widget.$input.setCursorPosition(widget.$input.val().length);
                        }

                        if (options.mask) {
                            widget.$input.mask(options.mask, options.maskData);
                        }

                        widget.$input.bind('blur.' + __nodeId__ + ' keydown.' + __nodeId__, function (e) {
                            if (e.type == 'blur' ||
                                (e.type == 'keydown' && e.keyCode == 13 &&
                                    (options.type == 'input' || (options.type == 'textarea' && e.ctrlKey))
                                )) {

                                var requestData = options.requestData || {};

                                $.extend(requestData, {
                                    value:        $(this).val(),
                                    txt_instance: $widget.attr("instance")
                                });

                                request(options.requestPath, requestData);

                                $($editTrigger).data('block', false);
                                $($editTrigger).unbind(editTriggerEvent + '.' + __nodeId__);

                                if (options.editTrigger && options.editTriggerHide) {
                                    $editTrigger.show();
                                }
                            }

                            if (e.type == 'keydown' && e.keyCode == 27) {
                                $($editTrigger).data('block', false);

                                widget.render();
                            }

                            if (options.activeClass) {
                                $widget.removeClass(options.activeClass);
                            }
                        }).bind('click.' + __nodeId__, function (e) {
                            e.stopPropagation();
                        });

                        e.stopPropagation();

                        return false;
                    }
                });

            }
        }
    });
})(__nodeNs__, __nodeId__);
