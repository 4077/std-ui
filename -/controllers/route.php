<?php namespace std\ui\controllers;

class Route extends \Controller
{
    public function bind()
    {
        $data = ['selector' => $this->data['selector']];

        if (isset($this->data['path']) && !(isset($this->data['clickable']) && !$this->data['clickable'])) {
            $data['path'] = $this->data['path'];
        }

        $this->js(':std_ui__route', $data);
    }

    public function view($tag = false)
    {
        if (!(isset($this->data['visible']) && !$this->data['visible'])) {
            $id = k(8);

            $route = $this->c('@tag', ['attrs' => ['id' => $id]]);

            // options

            $data = ['selector' => '#' . $id];

            foreach (['event'] as $option) {
                if (isset($this->data[$option])) {
                    $data[$option] = $this->data[$option];
                }
            }

            // path

            if (!empty($this->data['path']) && !(isset($this->data['clickable']) && !$this->data['clickable'])) {
                $data['path'] = $this->data['path'];
            }

            //

            if (isset($this->data['class'])) {
                $route->attr('hover', 'hover');
            }

            foreach (['class', 'hover', 'hover_group'] as $attr) {
                if (isset($this->data[$attr])) {
                    $route->attr($attr, $this->data[$attr]);
                }
            }

            if (isset($this->data['content'])) {
                $route->content($this->data['content']);
            }

            if (isset($this->data['attrs'])) {
                $route->attrs($this->data['attrs']);
            }

            //

            $this->js(':std_ui__route', $data);

            return $route->view($tag ? $tag : '');
        }
    }
}