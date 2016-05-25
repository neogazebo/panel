<?php
/*
 * Name : ImageCropper
 * Desc : Widget for handling image cropping
 * Author : tajhul <tajhul@ebizu.com>
 */

namespace common\components\widgets;

use yii;
use yii\base\Widget;

class ImageCropper extends Widget {

    public $prefix = 'img_';
    public $modal_id;
    public $modal_title = 'Upload an image';
    public $wsmall = 180;
    public $hsmall = 78;
    public $wbig = 600;
    public $hbig = 260;
    public $ratio = 2.3;
    public $skipAndResize = true; // 1= true, 0 = false
    public $createThumbnail = true; // 1= true, 0 = false

    public function run(){
        return $this->render('image-cropper',[
            'wsmall' => $this->wsmall,
            'hsmall' => $this->hsmall,
            'wbig' => $this->wbig,
            'hbig' => $this->hbig,
            'ratio' => $this->ratio,
            'modal_title' => $this->modal_title,
            'prefix' => $this->prefix,
            'skipAndResize' => $this->skipAndResize ? 1 : 0,
            'createThumbnail' => $this->createThumbnail ? 1 : 0,
        ]);
    }
}