<?php namespace std\ui\controllers;

class Resizable extends \Controller
{
    public function bind()
    {
        $data = ['selector' => $this->data['selector']];

        if (isset($this->data['path'])) {
            $data['path'] = $this->_caller()->_p($this->data['path']);
        }

        $data['data'] = $this->data('data');

        remap($data, $this->data, 'pluginOptions');

        $this->js(':', $data);
    }
}
