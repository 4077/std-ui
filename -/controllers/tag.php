<?php namespace std\ui\controllers;

use ewma\Views\View;

class Tag extends \Controller
{
    private $name = 'div';

    private $attrs = [];

    private $content = '';

    private $notClose = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr'];

    public function name($name)
    {
        $this->name = $name;
    }

    public function attr($name, $value)
    {
        $this->attrs[$name] = $value;
    }

    public function attrs($attrs)
    {
        $this->attrs = array_merge($this->attrs, $attrs);
    }

    public function content($content)
    {
        if ($content instanceof View) {
            $this->content = $content->render();
        } else {
            $this->content = $content;
        }
    }

    public function view($name = false, $type = false)
    {
        if (!(isset($this->data['visible']) && !$this->data['visible'])) {
            if ($name) {
                $this->name = $name;
            }

            if ($instance = $this->_instance()) {
                $this->attr('instance', $instance);
            }

            if (isset($this->data['attrs'])) {
                $this->attrs($this->data['attrs']);
            }

            if (isset($this->data['content'])) {
                $this->content($this->data['content']);
            }

            $attrs = '';

            foreach ($this->attrs as $name => $value) {
                $attrs .= ' ' . $name . '="' . $value . '"';
            }

            if (in_array($this->name, $this->notClose)) {
                return '<' . $this->name . $attrs . '>';
            } else {
                if ($type == 'open') {
                    return '<' . $this->name . $attrs . '>';
                } elseif ($type == 'close') {
                    return '</' . $this->name . '>';
                } else {
                    return '<' . $this->name . $attrs . '>' . $this->content . '</' . $this->name . '>';
                }
            }
        }
    }
}
