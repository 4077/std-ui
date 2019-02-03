<?php namespace std\ui\quantify\controllers;

class Main extends \Controller
{
    public function view()
    {
        $v = $this->v('|');

        list($updateCallPath, $updateCallData) = $this->_caller()->_call($this->data('update_call'))->explode();

        $v->assign([
                       'VALUE'     => $this->data('quantity'),
                       'VALUE_TXT' => $this->c('\std\ui txt:view', [
                           'path'            => $updateCallPath,
                           'data'            => $updateCallData,
                           'select_on_focus' => true,
                           'content'         => $this->data['quantity']
                       ])
                   ]);

        if ($this->data('dec_call')) {
            list($decCallPath, $decCallData) = $this->_caller()->_call($this->data['dec_call'])->explode();

            $this->c('\std\ui button:bind', [
                'selector' => $this->_selector('|') . ' .dec.button',
                'path'     => $decCallPath,
                'data'     => $decCallData
            ]);
        }

        if ($this->data('inc_call')) {
            list($incCallPath, $incCallData) = $this->_caller()->_call($this->data['inc_call'])->explode();

            $this->c('\std\ui button:bind', [
                'selector' => $this->_selector('|') . ' .inc.button',
                'path'     => $incCallPath,
                'data'     => $incCallData
            ]);
        }

        $this->css();

        $this->widget(':|', [
            'updatePath'    => $updateCallPath,
            'updateData'    => $updateCallData,
            'updateTimeout' => isset($this->data['update_timeout']) ? $this->data['update_timeout'] : null,
        ]);

        return $v;
    }
}