<?php namespace std\ui\switcher\controllers;

class Main extends \Controller
{
    private $classes = [
        'selected' => 'selected',
        'first'    => 'first',
        'last'     => 'last',
        'disabled' => 'disabled' // todo
    ];

    private $buttons;

    private $selectedValue;

    private $strictValueComparsion = false;

    public function __create()
    {
        if ($this->data('buttons')) {
            $this->buttons = $this->data['buttons'];
            $this->selectedValue = $this->data('value');
            ra($this->classes, $this->data('classes'));
        } else {
            $this->lock();
        }
    }

    public function reload()
    {
        $this->jquery()->replace($this->view());
    }

    public function view()
    {
        $v = $this->v();

        if ($this->data('class')) {
            $v->assign([
                           'CLASS' => $this->data['class']
                       ]);
        }

        $buttons = $this->data['buttons'];
        $lastButtonNumber = count($buttons) - 1;

        foreach ($buttons as $n => $button) {
            $requestData = $this->data['data'];
            $requestData['value'] = $button['value'];

            $class = ['button'];

            if ($n == 0) {
                $class[] = $this->classes['first'];
            }

            if ($n == $lastButtonNumber) {
                $class[] = $this->classes['last'];
            }

            if (
                ($this->strictValueComparsion && $button['value'] === $this->selectedValue) ||
                (!$this->strictValueComparsion && $button['value'] == $this->selectedValue)
            ) {
                $class[] = $this->classes['selected'];
            }

            if (!empty($button['class'])) {
                $class[] = $button['class'];
            }

            remap($buttonData, $button, 'title, label, icon, content');

            ra($buttonData, [
                'path'  => $this->_caller()->_p($this->data['path']),
                'data'  => $requestData,
                'class' => implode(' ', $class),
            ]);

            $v->assign('button', [
                'CONTENT' => $this->c('\std\ui button:view', $buttonData)
            ]);
        }

        $this->css();

        return $v;
    }
}
