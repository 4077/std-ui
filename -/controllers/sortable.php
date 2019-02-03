<?php namespace std\ui\controllers;

class Sortable extends \Controller
{
    private $default_options = [
        'items_id_attr'  => 'item_id',
        'plugin_options' => []
    ];

    private $default_plugin_options = [];

    public function bind()
    {
        $data = ['selector' => $this->data['selector']];

        // path&data

        if (isset($this->data['path'])) {
            $data['request_path'] = $this->_caller()->_p($this->data['path']);
        }

        if (isset($this->data['data'])) {
            $data['request_data'] = $this->data['data'];
        }

        // options

        foreach (array_keys($this->default_options) as $option) {
            if (isset($this->data[$option])) {
                $data[$option] = $this->data[$option];
            } else {
                $data[$option] = $this->default_options[$option];
            }
        } // todo opt

        // plugin_options

        foreach (array_keys($this->default_plugin_options) as $option) {
            if (isset($this->data[$option])) {
                $data['plugin_options'][$option] = $this->data[$option];
            }
        }

        //

        $this->js(':std_ui_sortable', $data);
    }
}