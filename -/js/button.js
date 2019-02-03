// head {
var __nodeId__ = "std_ui__button";
// }

(function (__nodeId__) {
    window[__nodeId__] = function (data) {
        var $button = $(data.selector);

        var $eventTrigger;
        if (data.eventTriggerClosestSelector) {
            $eventTrigger = $button.closest(data.eventTriggerClosestSelector);
        } else {
            $eventTrigger = $button;
        }

        var eventName = data.event || 'click';

        $eventTrigger.rebind(eventName + '.' + __nodeId__, function (e) {
            e.stopPropagation();

            if (ewma.cancelFollow) {
                ewma.cancelFollow = false;

                return false;
            }

            if (e.ctrlKey) {
                if (data.ctrl) {
                    trigger(data.ctrl.path, data.ctrl.data);
                } else {
                    if (data.path) {
                        trigger(data.path, data.data);
                    }
                }
            } else {
                if (data.path) {
                    trigger(data.path, data.data);
                }
            }

            return false;
        });

        var trigger = function (path, data) {
            request(path, data);
        };
    }
})(__nodeId__);
