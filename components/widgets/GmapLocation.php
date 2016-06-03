<?php
/*
 * Name : Google Maps
 * Email : ambikuk@gmail.com
 */

namespace app\components\widgets;

use Yii;
use yii\base\Widget;


class GmapLocation extends Widget {

    public $lat;
    public $long;
    public $width = 500;
    public $height = 500;
    public $type = 'dynamic';

    public function run()
    {
        return $this->render('gmap-location', [
            'lat' => $this->lat,
            'long' => $this->long,
            'width' => $this->width,
            'height' => $this->height,
            'type' => $this->type
        ]);
    }

}

?>