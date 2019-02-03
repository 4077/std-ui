<?php namespace std\ui;

class Txt
{
    public $instance;

    private $response;

    public $value;

    public static function value(\Controller $controller, $valuePath = 'value')
    {
        $txt = new self;

        $txt->instance = $controller->data('txt_instance');
        $txt->value = $controller->data($valuePath);

        $txt->response = ['content' => $txt->value];

        return $txt;
    }

    public function response($value = null, $contentOnInit = null)
    {
        if (null !== $value) {
            $this->response['content'] = $value;
        }

        if (null !== $contentOnInit) {
            $this->response['contentOnInit'] = $contentOnInit;
        }

        $txtc = appc('\std\ui txt');

        $txtc->widget('|' . $this->instance, 'option', 'content', $this->response['content']);

        if (isset($this->response['contentOnInit'])) {
            $txtc->widget('|' . $this->instance, 'option', 'contentOnInit', $this->response['contentOnInit']);
        }

        $txtc->widget('|' . $this->instance, 'render');
    }
}
