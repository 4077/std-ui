<?php namespace std\ui\controllers;

class Txt extends \Controller
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

        $data = $this->remapOptions($data, $this->data);

        $this->widget(':|', $data);
    }

    public function view($tag = false)
    {
        $visible = !(isset($this->data['visible']) && !$this->data['visible']);

        if ($visible) {
            if (isset($this->data['path'])) {
                $this->_instance(true);

                $txt = $this->c('@tag|');

                $data['selector'] = $this->_selector('|');

                $data['requestPath'] = $this->_caller()->_p($this->data['path']);

                if (isset($this->data['data'])) {
                    $data['requestData'] = $this->data['data'];
                }

                $data = $this->remapOptions($data, $this->data);

                if (!empty($data['mask'])) {
                    $this->c('\plugins\maskedinput~:provide');
                }

                $class[] = $this->_nodeId();

                if ($this->data('class')) {
                    $class[] = $this->data['class'];

                    $txt->attr('hover', 'hover');
                }

                $txt->attr('class', implode(' ', $class));

                $attrs = [];
                remap($attrs, $this->data, 'hover, hover_group, hover_listen, hover_broadcast, title');
                ra($attrs, $this->data('attrs'));

                $txt->attrs($attrs);
                $txt->content($this->data('content'));

                $this->widget(':|', $data);

                return $txt->view($tag);
            }
        }
    }

    private function remapOptions($target, $source)
    {
        remap($target, $source, '
            editable,
            type,
            editTriggerSelector,
            editTriggerClosestSelector,
            editTriggerEvent,
            editTriggerHide,
            contentOnInit,
            inputClass,
            inputHover,
            inputFocus,
            emptyContent,
            emptyClass,
            placeholder,
            activeClass,
            minWidth,
            maxWidth,
            fitToClosest,
            fitInputToClosest,
            selectOnFocus,
            noSetPaddings,
            padding,
            mask
        ');

        return $target;
    }
}
