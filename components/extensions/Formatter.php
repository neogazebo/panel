<?php

namespace app\components\extensions;

use Yii;
use yii\base\Component;
use NumberFormatter;

class Formatter extends \yii\i18n\Formatter {

    public $decimalFormat;

    public function asDecimal($value, $format = null, $options = [], $textOptions = [])
    {
        if ($value === null) {
            return $this->nullDisplay;
        }

        if ($format === null) {
            $format = $this->decimalFormat;
        }
        
        $value = $this->createNumberFormatter(NumberFormatter::DECIMAL, $format)->format($value);
        $value = str_replace('.00', '', $value);
        
        return $value;
    }

    public function asTimeAgo($value)
    {
        $estimate_time = time() - $value;
        if( $estimate_time < 1 ) {
            return 'less than 1 second ago';
        }

        $condition = [
            12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        ];

        foreach( $condition as $secs => $str ) {
            $d = $estimate_time / $secs;
            if( $d >= 1 ) {
                $r = round( $d );
                return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
            }
        }
    }

}
