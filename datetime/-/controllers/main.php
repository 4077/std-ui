<?php namespace std\ui\datetime\controllers;

class Main extends \Controller
{
    public function __create()
    {
        $d = &$this->d(':|', [
            'datepicker' => true,
            'timepicker' => true
        ]);

        remap($d, $this->data, 'datepicker, timepicker, datetime, callbacks');
    }

    public function performCallback($name, $data)
    {
        $callbacks = $this->d(':callbacks|');

        if (isset($callbacks[$name])) {
            $this->_call($callbacks[$name])->ra($data)->perform();
        }

        return $this;
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function reloadTimepicker()
    {
        $this->jquery($this->_selector('|') . ' > .timepicker')->html($this->timePickerView());
    }

    public function view()
    {
        $d = $this->d('|');
        $v = $this->v('|');

        $datetime = \Carbon\Carbon::parse($d['datetime']);

        if ($d['datepicker']) {
            $v->assign([
                           'DATEPICKER' => $this->c('\eyecon\datepicker~:view|' . $this->_nodeInstance(), [
                               'path'    => '~xhr:updateDate|',
                               'date'    => $datetime->format('d.m.Y'),
                               'current' => $datetime->format('d.m.Y'),
                               'format'  => 'd.m.Y',
                               'flat'    => true,
                               'content' => ''
                           ])
                       ]);
        }

        if ($d['timepicker']) {
            $v->assign([
                           'TIMEPICKER' => $this->timePickerView()
                       ]);
        }

        $this->css();

        return $v;
    }

    private function timePickerView()
    {
        $d = $this->d('|');

        $datetime = \Carbon\Carbon::parse($d['datetime']);

        return $this->c('\std\ui\timepicker~:view', [
            'path'   => '~xhr:updateTime|',
            'hour'   => $datetime->hour,
            'minute' => $datetime->minute
        ]);
    }
}
