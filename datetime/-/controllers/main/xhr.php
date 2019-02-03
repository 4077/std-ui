<?php namespace std\ui\datetime\controllers\main;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    private function getStorageDatetime()
    {
        $datetime = $this->d('~:datetime|');

        return \Carbon\Carbon::parse($datetime);
    }

    private function setStorageDatetime($datetime)
    {
        $this->d('~|', [
            'datetime' => $datetime
        ], RA);
    }

    public function updateDate()
    {
        $storageDate = $this->getStorageDatetime();
        $inputDate = \Carbon\Carbon::parse($this->data('value'));

        $storageDate->day($inputDate->day);
        $storageDate->month($inputDate->month);
        $storageDate->year($inputDate->year);

        $datetime = $storageDate->toDateTimeString();

        $this->setStorageDatetime($datetime);

        $this->c('~|')->performCallback('updateDate', [
            'datetime' => $datetime
        ]);

        $this->c('~|')->performCallback('update', [
            'datetime' => $datetime
        ]);
    }

    public function updateTime()
    {
        $storageDate = $this->getStorageDatetime();

        $storageDate->hour($this->data('hour'));
        $storageDate->minute($this->data('minute'));
        $storageDate->second(0);

        $datetime = $storageDate->toDateTimeString();

        $this->setStorageDatetime($datetime);

        if ($this->data('set') == 'hour') {
            $this->c('~|')->performCallback('updateHour', [
                'datetime' => $datetime
            ]);
        }

        if ($this->data('set') == 'minute') {
            $this->c('~|')->performCallback('updateMinute', [
                'datetime' => $datetime
            ]);
        }

        $this->c('~|')->performCallback('updateTime', [
            'datetime' => $datetime
        ]);

        $this->c('~|')->performCallback('update', [
            'datetime' => $datetime
        ]);

        $this->c('~:reloadTimepicker|');
    }
}
