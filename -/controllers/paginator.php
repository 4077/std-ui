<?php namespace std\ui\controllers;

class Paginator extends \Controller
{
    private $itemsCount;

    private $perPage;

    private $page;

    private $range = 4;

    public function __create()
    {
        if ($this->dataHas('items_count number, page number') && $this->data('per_page')) {
            $this->itemsCount = $this->data['items_count'];
            $this->perPage = $this->data['per_page'];
            $this->page = $this->data['page'];

            if ((int)$this->data('range')) {
                $this->range = $this->data['range'];
            }
        } else {
            $this->lock();
        }
    }

    public function view()
    {
        $pagesCount = ceil($this->itemsCount / $this->perPage);

        if ($pagesCount > 1) {
            $this->flatControlsData();

            $v = $this->v();

            $numbers = $this->getNumbers($pagesCount);

            foreach ($numbers as $number) {
                $controlType = 'skipped_pages';
                if ($number) {
                    if ($number == $this->page) {
                        $controlType = 'current_page';
                    } else {
                        $controlType = 'page';
                    }
                }

                $control = $this->tokenizeControlData($controlType, $number);

                $v->assign('button', [
                    'CONTENT' => $this->c($this->_caller()->_p($control[0]), $control[1])
                ]);
            }

            return $v;
        }
    }

    private $flattenControlsData;

    private function flatControlsData()
    {
        foreach ($this->data['controls'] as $controlType => $control) {
            if (!is_array($control)) {
                $control[0] = $control;
            }

            if (!isset($control[1])) {
                $control[1] = [];
            }

            $this->flattenControlsData[$controlType] = a2f($control);
        }
    }

    protected function tokenizeControlData($controlType, $number)
    {
        $flattenControlData = [];

        foreach ($this->flattenControlsData[$controlType] as $path => $value) {
            $flattenControlData[$path] = str_replace('%page', $number, $value);
        }

        $output = f2a($flattenControlData);

        return $output;
    }

    private function getNumbers($pagesCount)
    {
        $page = $this->page;
        $range = $this->range;

        $left = $page - $range;
        $right = $page + $range;

        if ($left < 1) {
            $right += 1 - $left;
        }

        if ($right > $pagesCount) {
            $left -= $right - $pagesCount;
        }

        $numbers = range($left, $right);

        while ($left < 1) {
            array_shift($numbers);
            $left = current($numbers);
        }

        while ($right > $pagesCount) {
            array_pop($numbers);
            $right = end($numbers);
        }

        if ($left > 3) {
            array_unshift($numbers, false);
            array_unshift($numbers, 1);
        } else {
            for ($number = $left - 1; $number > 0; $number--) {
                array_unshift($numbers, $number);
            }
        }

        if ($pagesCount - $right > 2) {
            array_push($numbers, false);
            array_push($numbers, $pagesCount);
        } else {
            for ($number = $right; $number < $pagesCount; $number++) {
                array_push($numbers, $number + 1);
            }
        }

        return $numbers;
    }
}
