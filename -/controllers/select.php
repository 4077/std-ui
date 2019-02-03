<?php namespace std\ui\controllers;

class Select extends \Controller
{
    public function bind()
    {
        $data = ['selector' => $this->data['selector']];

        if (isset($this->data['path'])) {
            $data['requestPath'] = $this->_caller()->_p($this->data['path']);
        }

        if (isset($this->data['data'])) {
            $data['requestData'] = $this->data['data'];
        }

        $this->js(':', $data);
    }

    public function view($tag = false)
    {
        $instance = k(8);

        $select = $this->c('@tag', [
            'attrs' => [
                'instance'     => $instance,
                'autocomplete' => 'off'
            ]
        ]);

        $data = [
            'selector' => ".std_ui__select[instance='" . $instance . "']"
        ];

        if (isset($this->data['path'])) {
            $data['requestPath'] = $this->_caller()->_p($this->data['path']);
        }

        if (isset($this->data['data'])) {
            $data['requestData'] = $this->data['data'];
        }

        $class[] = 'std_ui__select';

        if (isset($this->data['class'])) {
            $select->attr('hover', 'hover');

            $class[] = $this->data['class'];
        }

        $select->attr('class', implode(' ', $class));

        if (isset($this->data['items'])) {
            $items = $this->data['items'];

            if (!empty($this->data['combine'])) {
                $items = array_combine($items, $items);
            }

            if ($empty = $this->data('empty')) {
                $items = ['' => $empty] + $items;
            }

            $content = '';
            foreach ($items as $key => $name) {
                $selected = false;

                if (isset($this->data['selected'])) {
                    if (!empty($this->data['case_sensitivity'])) {
                        $selected = $this->data['selected'] == $key;
                    } else {
                        $selected = strtolower($this->data['selected']) == strtolower($key);
                    } // todo tests
                }

                $content .= '<option value="' . $key . '"' . ($selected ? ' selected="selected"' : '') . '>' . $name . '</option>';
            }

            $select->content($content);
        }

        $this->js(':', $data);

        return $select->view($tag ? $tag : 'select');
    }
}