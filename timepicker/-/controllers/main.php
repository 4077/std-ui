<?php namespace std\ui\timepicker\controllers;

class Main extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $updatePath = $this->_caller()->_p($this->data('path'));

        $hour = $this->data('hour');
        $minute = $this->data('minute');

        $hw = 6;
        $hh = 4;
        for ($y = 0; $y < $hh; $y++) {
            $v->assign('hour_row');

            for ($x = 0; $x < $hw; $x++) {
                $setHour = $y * $hw + $x;

                $v->assign('hour_row/hour_column', [
                    'BUTTON' => $this->c('\std\ui button:view', [
                        'path'    => $updatePath,
                        'data'    => [
                            'hour'   => $setHour,
                            'minute' => $minute,
                            'set'    => 'hour'
                        ],
                        'class'   => 'hour_button ' . ($setHour == $hour ? 'selected' : ''),
                        'content' => str_pad($setHour, 2, '0', STR_PAD_LEFT)
                    ])
                ]);
            }
        }

        $mw = 6;
        for ($x = 0; $x < $mw; $x++) {
            $setMinute = $x * 10;

            $v->assign('minute_column', [
                'BUTTON' => $this->c('\std\ui button:view', [
                    'path'    => $updatePath,
                    'data'    => [
                        'hour'   => $hour,
                        'minute' => $setMinute,
                        'set'    => 'minute'
                    ],
                    'class'   => 'minute_button ' . ($setMinute == $minute ? 'selected' : ''),
                    'content' => str_pad($setMinute, 2, '0', STR_PAD_LEFT)
                ])
            ]);
        }

        $this->css();

        return $v;
    }
}
