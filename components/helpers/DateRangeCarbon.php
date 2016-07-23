<?php

namespace app\components\helpers;

use Yii;
use Carbon\Carbon;

/**
 *
 */
class DateRangeCarbon
{

    // set timezone by user login
    private function setTimeZone()
    {
        switch(Yii::$app->user->identity->country) {
			case 'ID':
				$dt = Carbon::now('Asia/Jakarta');
				break;
			case 'MYR':
				$dt = Carbon::now('Asia/Kuala_Lumpur');
				break;
		}
        return $dt;
    }

    // testing date
    public function getTestDate()
    {
        return $this->setTimeZone();
    }

    public function getDay()
    {
        $dt = $this->setTimeZone();
        $thisDay = $dt->startOfDay(). ' to '. $dt->endOfDay();
        return $thisDay;
    }

    // get daterange month active
    public function getThisMonth()
    {
        $dt = $this->setTimeZone();
        $thisMonth = $dt->startOfMonth().' to '.$dt->endOfMonth();
        return $thisMonth;
    }

    // get daterange last month active
    public function getLastMonth()
    {
        $dt = $this->setTimeZone();
        $dt->subMonth();
        $lastMonth = $dt->startOfMonth().' to '.$dt->endOfMonth();
        return $lastMonth;
    }

    // get daterange week active
    public function getThisWeek()
    {
        $dt = $this->setTimeZone();
        $thisWeek = $dt->startOfWeek().' to '.$dt->endOfWeek();
        return $thisWeek;
    }

    // get daterange last week active
    public function getLastWeek()
    {
        $dt = $this->setTimeZone();
        $dt->subWeek();
        $lastWeek = $dt->startOfWeek().' to '.$dt->endOfWeek();
        return $lastWeek;
    }

}
