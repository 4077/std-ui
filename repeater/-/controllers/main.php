<?php namespace std\ui\repeater\controllers;

class Main extends \Controller
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $items = $this->data('items');

        $itemRenderer = $this->data('item_renderer');

        foreach ($items as $item) {
            if ($itemRenderer) {
                $content = $this->c($itemRenderer, [
                    'item' => $item
                ]);
            } else {
                $content = 'item';
            }

            $v->assign('item', [
                'CONTENT' => $content
            ]);


        }

        $this->css();

        return $v;
    }
}
