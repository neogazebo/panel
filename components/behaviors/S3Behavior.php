<?php

namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\Expression;
use yii\db\ActiveRecord;
use app\models\ShipmentMeta;
use Aws\S3\S3Client;
use PHPImageWorkshop\ImageWorkshop;
use app\components\helpers\S3;

require_once Yii::getAlias('@app'). '/lib/' . 'aws' . DIRECTORY_SEPARATOR . 'aws.phar';
require_once Yii::getAlias('@app') . '/lib/' . 'PHPImageWorkshop' . DIRECTORY_SEPARATOR . 'ImageWorkshop.php';

class S3Behavior extends Behavior {

    public $field;
    public $size;
    public $path;
    public $format = array("jpg", "png");
    public $max = 3;

    public function events() {
        $data[ActiveRecord::EVENT_BEFORE_INSERT] = 's3Function';
        $data[ActiveRecord::EVENT_BEFORE_UPDATE] = 's3Function';
        $data[ActiveRecord::EVENT_BEFORE_DELETE] = 's3Delete';
        return $data;
    }

    public function s3Delete($event, $param = 'businessUrl') {
        $attr = $this->field;
        if(file_exists(Yii::$app->params[$param].$attr))
            S3::Delete($this->owner->$attr);
    }

    public function s3Function($event) {
        $attr = $this->field;
        if (!$this->owner->isNewRecord)
            $oldAttr = '/' . $this->path . '/' . $this->owner->oldAttributes[$attr];
        $fileImage = $this->owner->$attr;
        if (isset($fileImage) && is_object($fileImage) && !empty($fileImage->name)) {
            list($txt, $ext) = explode(".", $fileImage->name);
            if (in_array(strtolower($ext), $this->format)) {
                if ($fileImage->size < (1024 * 1024 * $this->max)) {
                    $newName = $txt . '-' . substr(md5(time()), 0, 10);

                    $crop = $this->crop($fileImage->tempName, $newName, $ext, $this->size);

                    if (!$this->owner->isNewRecord)
                        S3::Delete($oldAttr);

                    $this->owner->$attr = $newName . "." . strtolower($ext);
                } else {
                    $this->owner->addError($this->field, "Image file size max {$this->max} MB");
                    $this->owner->$attr = $this->owner->oldAttributes[$attr];
                }
            } else {
                $this->owner->addError($attr, 'Invalid file format');
                $this->owner->$attr = $this->owner->oldAttributes[$attr];
            }
        } else {
            if (!$this->owner->isNewRecord)
                $this->owner->$attr = $this->owner->oldAttributes[$attr];
        }
    }

    protected function crop($temp, $newName, $ext, $size) {

        $dir = '/' . $this->path . '/';
        $dirTemp = Yii::$app->getRuntimePath() . '/';

        if (!file_exists($dirTemp)) {
            if (!mkdir($dirTemp, 0777, true)) {
                die('Failed to create folders...');
            }
        }

        $sourceSize = getimagesize($temp);

        $targetWidht = $size['width'];
        $targetHeight = $size['height'];

        $rasio = $targetWidht / $targetHeight;

        $resizeWidht = $targetWidht;
        $resizeHeight = $targetWidht * $sourceSize[1] / $sourceSize[0];

        $pathAws = $dir . $newName . "." . strtolower($ext);
        $pathTemp = $dirTemp . $newName . "." . strtolower($ext);

        $pathAws = strtolower(str_replace(' ', '-', $pathAws));
        $pathTemp = strtolower(str_replace(' ', '-', $pathTemp));

        switch ($ext) {
            case 'jpg' :
                $source = imagecreatefromjpeg($temp);
                break;
            case 'png' :
                $source = imagecreatefrompng($temp);
                break;
        }

        $image = ImageCreateTrueColor($targetWidht, $targetHeight);
        $whiteBackground = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $whiteBackground);

        imagecopyresampled($image, $source, 0, 0, 0, 0, $resizeWidht, $resizeHeight, $sourceSize[0], $sourceSize[1]);

        switch ($ext) {
            case 'jpg' :
                imagejpeg($image, $pathTemp, 90);
                break;
            case 'png' :
                imagepng($image, $pathTemp, 9);
                break;
        }

        S3::Upload($pathAws, $pathTemp);

        return $pathAws;
    }

}
