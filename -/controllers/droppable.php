<?php namespace std\ui\controllers;

class Droppable extends \Controller
{
    public function bind()
    {
//        $data = ['selector' => $this->data['selector']];

        if (isset($this->data['path'])) {
            $data['requestPath'] = $this->_caller()->_p($this->data['path']);
        }

        if (isset($this->data['data'])) {
            $data['requestData'] = $this->data['data'];
        }

        remap($data, $this->data, 'accept, targetIdAttr target_id_attr, sourceIdAttr source_id_attr');

        ra($data['pluginOptions'], $this->data('pluginOptions'));

        $this->widget(':' . $this->data('selector'), $data);
    }
}
