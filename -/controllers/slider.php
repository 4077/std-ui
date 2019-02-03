<?php namespace std\ui\controllers;

class Slider extends \Controller
{
    private $default_options_names = [
        'disabled',
        'min',
        'max',
        'min_limit',
        'max_limit',
        'value',
        'min_value',
        'max_value',
        'min_container',
        'max_container',
        'value_container',
        'min_caption',
        'max_caption',
        'min_caption_container',
        'max_caption_container',
        'range',
        'range_visible',
        'step',
        'onslide',
        'onchange',
        'oncreate'
    ];

    public function view($tag = false)
    {
        if (!(isset($this->data['visible']) && !$this->data['visible'])) {
            $id = k(8);

            $data = ['selector' => '#' . $id];

            $slider = $this->c('@tag', ['attrs' => ['id' => $id]]);

            // path&data

            if (isset($this->data['path'])) {
                $data['request_path'] = $this->_caller()->_p($this->data['path']);
            }

            if (isset($this->data['data'])) {
                $data['request_data'] = $this->data['data'];
            }

            // options

            foreach ($this->default_options_names as $option) {
                if (isset($this->data[$option])) {
                    $data[$option] = $this->data[$option];
                }
            }

            // attrs

            foreach (['hover', 'hover_group'] as $attr) {
                if (isset($this->data[$attr])) {
                    $slider->attr($attr, $this->data[$attr]);
                }
            }

            if (isset($this->data['attrs'])) {
                $slider->attrs($this->data['attrs']);
            }

            //

            $this->js(':std_ui_slider', $data);

            return $slider->view($tag ? $tag : '');
        }
    }
}