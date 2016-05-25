<?php

/*
 * Name : CropProcessor
 * Desc : Helper Depedencies wirt ImageCropper Widget
 * Author : tajhul <tajhul@ebizu.com>
 * Dependency :
 * 1. -> frontend\controllers\CropperController
 * 2. -> common\components\widgets\ImageCropper
 */

namespace common\components\helpers;

use PHPImageWorkshop\ImageWorkshop;
use Yii;

require_once Yii::$app->basePath . '/../common/lib/' . 'PHPImageWorkshop' . DIRECTORY_SEPARATOR . 'ImageWorkshop.php';

class MyCropProcessor {

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 1;
    const TMP_FILE_PREFIX = 'cpf_';

    public $server_dir;
    public $local_dir;
    public $file;
    public $name;

    public function __construct() {
        parent::__construct();
        get_called_class(new ImageWorkshop());
    }

    /*
     * Upload base image
     */
    public static function upload(){
        $noCacheOptions = '?tid='.time();

        $valid_formats = array("jpg", "jpeg", "png", "gif", "bmp");

        $max_size = isset($_POST['max_size']) ? $_POST['max_size'] : 300;// maximum dimension 300 x 300
        $name = $_FILES['photoimg']['name'];
        $size = $_FILES['photoimg']['size'];

        if (strlen($name)) {
            list($txt, $ext) = explode(".", $name);
            if (in_array(strtolower($ext), $valid_formats)) {
                if ($size < (1024 * 1024 * 3)) {

                    $path = Yii::$app->getBasePath() . '/web/runtime/';
                    $actual_image_name = 'img_itm_' . time() . "." . strtolower($ext);
                    $yiiFile = \yii\web\UploadedFile::getInstanceByName('photoimg');

                    /*
                     * Check existing directory
                     */
                    if (!file_exists($path)) {
                        if (!mkdir($path, 0777, true)) {
                            echo json_encode(['error' => 'Directory not exist and Failed to create directory!']);
                        }
                    }
                    $fileToUpload = $path . '/' . $actual_image_name;

                    if ($yiiFile->saveAs($fileToUpload)) {

                        /*
                        * Counting x-factor & new dimension
                        */
                        list($width, $height, $type, $attr) = getimagesize($fileToUpload);

                        $xFactor = 0;
                        $heightNew = 0;
                        $widthNew = 0;
                        if ($width > $height) {
                            if ($width > $max_size) {
                                $widthNew = $max_size;
                                $heightNew = ($widthNew * $height) / $width;
                                $xFactor = $width / $widthNew;
                            } else if ($height > $max_size) {
                                $heightNew = $max_size;
                                $widthNew = ($width * $heightNew) / $height;
                                $xFactor = $height / $heightNew;
                            } else {
                                $heightNew = $height;
                                $widthNew = $width;
                                $xFactor = $height / $heightNew;
                            }
                        } else if ($height > $width) {
                            if ($height > $max_size) {
                                $heightNew = $max_size;
                                $widthNew = ($width * $heightNew) / $height;
                                $xFactor = $height / $heightNew;
                            } else if ($width > $max_size) {
                                $widthNew = $max_size;
                                $heightNew = ($widthNew * $height) / $width;
                                $xFactor = $width / $widthNew;
                            } else {
                                $heightNew = $height;
                                $widthNew = $width;
                                $xFactor = $height / $heightNew;
                            }
                        } else {
                            if (($height > $max_size) && ($width > $max_size)) {
                                $heightNew = $max_size;
                                $widthNew = $max_size;
                                $xFactor = $height / $heightNew;
                            } else {
                                $heightNew = $height;
                                $widthNew = $width;
                                $xFactor = $height / $heightNew;
                            }
                        }

                        return [
                            'width' => $widthNew,
                            'height' => $heightNew,
                            'xFactor' => $xFactor,
                            'success' => self::STATUS_SUCCESS,
                            'src_file' => $actual_image_name,
                            'image_url' => Yii::$app->params['frontendUrl'] . '/runtime/'.$actual_image_name . $noCacheOptions,
                            'message' => 'Image has been uploaded',
                        ];
                    } else {
                        return [
                            'success' => self::STATUS_FAIL,
                            'src_file' => null,
                            'image_url' => null,
                            'message' => 'Something wrong while uploading file, please try again.',
                        ];
                    }
                } else {
                    return [
                        'success' => self::STATUS_FAIL,
                        'src_file' => null,
                        'image_url' => null,
                        'message' => 'Image file size max 3 MB.',
                    ];
                }
            } else{
                return [
                    'success' => self::STATUS_FAIL,
                    'src_file' => null,
                    'image_url' => null,
                    'message' => 'Invalid file format..',
                ];
            }
        } else{
            return [
                'success' => self::STATUS_FAIL,
                'src_file' => null,
                'image_url' => null,
                'message' => 'Please select an image..',
            ];
        }
    }

    /*
     * Crop Image
     */
    public static function crop(){

        $path = Yii::$app->getBasePath() . '/web/runtime/';
        $noCacheOptions = '?tid='.time();
        $jpeg_quality = 100;

        $xFactor = $_POST['xFactor'];
        $wsmall = isset($_POST['wsmall']) ? $_POST['wsmall'] : null;
        $hsmall = isset($_POST['hsmall']) ? $_POST['hsmall'] : null;
        $wbig = isset($_POST['wbig']) ? $_POST['wbig'] : null;
        $hbig = isset($_POST['hbig']) ? $_POST['hbig'] : null;

        $w = $_POST['w'] * $xFactor;
        $h = $_POST['h'] * $xFactor;
        $x = $_POST['x'] * $xFactor;
        $y = $_POST['y'] * $xFactor;

        $src = $path . $_POST['name'];

        if ($wsmall !== null) {
            $targ_w = $wsmall;
            $targ_h = $hsmall;
            if ($targ_w == $targ_h) {
                if ($w < $targ_w) {
                    $widthNew = $w;
                    $heightNew = $w;
                } else if ($h < $targ_h) {
                    $widthNew = $h;
                    $heightNew = $h;
                } else {
                    $widthNew = $targ_w;
                    $heightNew = $targ_h;
                }
            } else if ($targ_w > $targ_h) {
                if ($w < $targ_w) {
                    $widthNew = $w;
                    $heightNew = $w / ($targ_w / $targ_h);
                } else if ($h < $targ_h) {
                    $heightNew = $h;
                    $widthNew = $h * ($targ_w / $targ_h);
                } else {
                    $widthNew = $targ_w;
                    $heightNew = $targ_h;
                }
            }
        }

        if ($wbig !== null) {
            $targ_w = $wbig;
            $targ_h = $hbig;

            if ($targ_w == $targ_h) {
                if ($w < $targ_w) {
                    $widthNew = $w;
                    $heightNew = $w;
                } else if ($h < $targ_h) {
                    $widthNew = $h;
                    $heightNew = $h;
                } else {
                    $widthNew = $targ_w;
                    $heightNew = $targ_h;
                }
            } else if ($targ_w > $targ_h) {
                if ($w < $targ_w) {
                    $widthNew = $w;
                    $heightNew = $w / ($targ_w / $targ_h);
                } else if ($h < $targ_h) {
                    $heightNew = $h;
                    $widthNew = $h * ($targ_w / $targ_h);
                } else {
                    $widthNew = $targ_w;
                    $heightNew = $targ_h;
                }
            }
        }

        try{
            $dst_r = ImageCreateTrueColor($widthNew, $heightNew);

            $type = strtolower(substr(strrchr($_POST['name'], '.'), 1));
            switch ($type) {
                case 'bmp':
                    $img_r = imagecreatefromwbmp($src);
                    $filename = self::TMP_FILE_PREFIX . substr($_POST['name'], 0, strpos($_POST['name'], '.')) . '.' . $type;
                    break;
                case 'gif':
                    $img_r = imagecreatefromgif($src);
                    $filename = self::TMP_FILE_PREFIX . substr($_POST['name'], 0, strpos($_POST['name'], '.')) . '.' . $type;
                    break;
                case 'jpg':
                case 'jpeg':
                    $img_r = imagecreatefromjpeg($src);
                    $filename = self::TMP_FILE_PREFIX . substr($_POST['name'], 0, strpos($_POST['name'], '.')) . '.' . $type;
                    break;
                case 'png':
                    $img_r = imagecreatefrompng($src);
                    $filename = self::TMP_FILE_PREFIX . substr($_POST['name'], 0, strpos($_POST['name'], '.')) . '.' . $type;
                    break;
            }

            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $widthNew, $heightNew, $w, $h);

            switch ($type) {
                case 'bmp':
                    $tmp = imagewbmp($dst_r, $path . $filename);
                    break;
                case 'gif':
                    $tmp = imagegif($dst_r, $path . $filename);
                    break;
                case 'jpg':
                case 'jpeg':
                    $tmp = imagejpeg($dst_r, $path . $filename, $jpeg_quality);
                    break;
                case 'png':
                    $tmp = imagepng($dst_r, $path . $filename, 0);
                    break;
            }
            return [
                'success' => self::STATUS_SUCCESS,
                'src_file' => $filename,
                'image_url' =>  Yii::$app->params['frontendUrl'] . '/runtime/'.$filename . $noCacheOptions,
                'message' => 'success',
            ];
        } catch(Exception $e){
            return [
                'success' => self::STATUS_FAIL,
                'src_file' => null,
                'message' => $e->getMessage(),
            ];
        }

    }

    /*
     * Skip Crop image
     */
    public static function skip(){

        $noCacheOptions = '?tid='.time();
        $path = Yii::$app->getBasePath() . '/web/runtime/';
        $sourceFile = $path . $_POST['name'];
        $filename = self::TMP_FILE_PREFIX . $_POST['name'];
        $destinationFile = $path . $filename;
        try {
            // skip crop is mean destination file is exactly same with source file, so just copy it :)
            if(copy($sourceFile, $destinationFile)){
                return [
                    'success' => self::STATUS_SUCCESS,
                    'src_file' => $filename,
                    'image_url' =>  Yii::$app->params['frontendUrl'] . '/runtime/'.$filename . $noCacheOptions,
                    'message' => 'success',
                ];
            } else {
                return [
                    'success' => self::STATUS_FAIL,
                    'src_file' => null,
                    'image_url' => null,
                    'message' => 'unable to copy file.',
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => self::STATUS_FAIL,
                'src_file' => null,
                'image_url' => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function publish() {

        $path = Yii::$app->getBasePath() . '/web/runtime/';
        $awsPath = 'images/media/web/business/';

        if (isset($_POST['name'])) {

            $originalFileName = $_POST['name'];
            $srcFile = $path . $originalFileName;

            $extension = explode('.', $originalFileName);
            $extension = end($extension);
            $filename = 'ebpic_' . uniqid() . time() . '.' . $extension;

            $pathAws = $awsPath . $filename;

            // Upload to cloud
            S3::Upload($pathAws, $srcFile);

            return [
                'success' => self::STATUS_SUCCESS,
                'src_file' => $filename,
                'image_url' => Yii::$app->params['businessUrl'] . $filename,
            ];


        } else {
            return [
                'success' => self::STATUS_FAIL,
                'src_file' => null,
                'image_url' => null,
            ];
        }
    }

    /*
     * Clear temporary local image
     */
    public static function clearLocal(){
        if(!empty($_POST['name'])){
            $path = Yii::$app->getBasePath() . '/web/runtime/';
            $original_file = $path . $_POST['name'];
            $tmpcrop_file = $path . self::TMP_FILE_PREFIX .$_POST['name'];
            if(file_exists($original_file))
                unlink ($original_file);
            if(file_exists($tmpcrop_file))
                unlink ($tmpcrop_file);
        }
    }

}
