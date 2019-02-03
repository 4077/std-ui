<?php namespace std\ui\controllers;

class LiveInput extends \Controller
{
    public function bind()
    {
        $data = [
            'selector' => $this->data['selector']
        ];

        if (isset($this->data['path'])) {
            $data['requestPath'] = $this->_caller()->_p($this->data['path']);
        }

        $events = l2a($this->data('events')) or
        $events = ['input'];

        foreach ($events as $n => $event) {
            $events[$n] = $event . '.' . $this->_nodeId();
        }

        $data['events'] = implode(' ', $events);

        remap($data, $this->data, 'requestData data, timeout, decimal');

        $this->js(':', $data);
    }
}
