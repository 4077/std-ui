<?php namespace std\ui\controllers;

class Contextmenu extends \Controller
{
    private $corners = [];

    public function show()
    {
        // todo require jquery ui position

        if ($this->dataHas('selector')) {
            $this->js(':std_ui_contextmenu.create_container');

            $my = $this->data('my') or $my = 'left top';
            $at = $this->data('at') or $at = 'left bottom';

//            $caller = $this->_caller()->_call($this->data('content_call'))->perform();
//            $contentCall = $caller;
//
//            $content = $contentCall;

            $this->jquery('#std_ui_contextmenu')->html($this->_caller()->_call($this->data('content_call'))->perform());
//                ->position([
//                               'my' => $my,
//                               'at' => $at,
//                               'of' => $this->data('selector')
//                           ]);

            $this->js(':std_ui_contextmenu.show', [
                'selector' => $this->data['selector'],
                'zIndex'   => $this->data('zindex')
            ]);
        }
    }
}
