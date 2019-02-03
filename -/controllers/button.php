<?php namespace std\ui\controllers;

class Button extends \Controller
{
    public function bind()
    {
        $data = ['selector' => $this->data['selector']];

        if (!(isset($this->data['clickable']) && !$this->data['clickable'])) {
            if (isset($this->data['path'])) {
                $data['path'] = $this->_caller()->_p($this->data['path']);
                $data['data'] = $this->data('data');
            }

            if (isset($this->data['ctrl']['path'])) {
                $data['ctrl']['path'] = $this->_caller()->_p($this->data['ctrl']['path']);
                $data['ctrl']['data'] = $this->data('ctrl/data');
            }
        }

        $data = $this->remapOptions($data, $this->data);

        $this->js(':', $data);
    }

    public function view($tag = false)
    {
        if (!(isset($this->data['visible']) && !$this->data['visible'])) {
            $this->_instance(true);

            $button = $this->c('@tag|');

            $data['selector'] = $this->_selector('|');

            if (!(isset($this->data['clickable']) && !$this->data['clickable'])) {
                if (isset($this->data['path'])) {
                    $data['path'] = $this->_caller()->_p($this->data['path']);
                    $data['data'] = $this->data('data');
                }

                if (isset($this->data['ctrl']['path'])) {
                    $data['ctrl']['path'] = $this->_caller()->_p($this->data['ctrl']['path']);
                    $data['ctrl']['data'] = $this->data('ctrl/data');
                }
            }

            $data = $this->remapOptions($data, $this->data);

            $class[] = $this->_nodeId();

            if (isset($this->data['class'])) {
                $button->attr('hover', 'hover');

                $class[] = $this->data['class'];
            }

            $button->attr('class', implode(' ', $class));

            $attrs = [];
            remap($attrs, $this->data, 'hover, hover_group, hover_listen, hover_broadcast, title');
            ra($attrs, $this->data('attrs'));

            $button->attrs($attrs);

            $content = $this->data('content');

            if ($icon = $this->data('icon')) {
                $content = '<i class="icon ' . $icon . '"></i>' . $content;
            }

            if ($label = $this->data('label')) {
                $content .= '<span class="label">' . $label . '</span>';
            }

            $button->content($content);

            $this->js(':', $data);

            return $button->view($tag ? $tag : '');
        }
    }

    private function remapOptions($target, $source)
    {
        remap($target, $source, '
            event,
            eventTriggerClosestSelector
        ');

        return $target;
    }
}
