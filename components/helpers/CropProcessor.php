<?php

/*
 * Name : CropProcessor
 * Desc : Helper Depedencies wirt ImageCropper Widget
 * Author : tajhul <tajhul@ebizu.com>
 * Dependency :
 * 1. -> frontend\controllers\CropperController
 * 2. -> common\components\widgets\ImageCropper
 */

namespace app\components\helpers;

use PHPImageWorkshop\ImageWorkshop;
use Yii;

require_once Yii::$app->basePath . '/lib/' . 'PHPImageWorkshop' . DIRECTORY_SEPARATOR . 'ImageWorkshop.php';

class CropProcessor {

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 0;
    const TMP_FILE_PREFIX = 'rsz_';
    const THUMBNAIL_PREFIX = 's_';
    const JPEG_QUALITY = 100;
    
    public static $thumb_width = 0;
    public static $thumb_height = 0;
    public static $thumb_src = '';
    public static $thumb_filename = '';

    public function __construct() {
        parent::__construct();
        get_called_class(new ImageWorkshop());
    }

    /*
     * Upload base image
     */

    public static function upload() {
        $noCacheOptions = '?tid=' . time();
        $valid_formats = array("jpg", "jpeg", "png", "gif", "bmp");
        $max_size = isset($_POST['max_size']) ? $_POST['max_size'] : 300; // maximum dimension 300 x 300
        $name = $_FILES['photoimg']['name'];
        $size = $_FILES['photoimg']['size'];
        $prefix = isset($_POST['prefix']) ? $_POST['prefix'] : 'ebzimg_';

        if (strlen($name)) {
            //list($txt, $ext) = explode(".", $name);
            $getFileInfo = explode(".", $name);
            $ext = end($getFileInfo);
            if (in_array(strtolower($ext), $valid_formats)) {
                if ($size > 0 && $size < (1024 * 1024 * 3)) {

                    $path = Yii::$app->getBasePath() . '/web/runtime/';
                    $actual_image_name = $prefix . time().'_'. uniqid() . '.' . strtolower($ext);
                    $yiiFile = \yii\web\UploadedFile::getInstanceByName('photoimg');

                    /*
                     * Check existing directory
                     */
                    if (!file_exists($path)) {
                        if (!mkdir($path, 0777, true)) {
                            return [
                                'success' => self::STATUS_FAIL,
                                'src_file' => null,
                                'image_url' => null,
                                'message' => 'Directory not exist and Failed to create directory!',
                            ];
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
                            'image_url' => Yii::$app->urlManager->hostInfo . '/runtime/' . $actual_image_name . $noCacheOptions,
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
            } else {
                return [
                    'success' => self::STATUS_FAIL,
                    'src_file' => null,
                    'image_url' => null,
                    'message' => 'Invalid file format..',
                ];
            }
        } else {
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

    public static function crop() {

        $path = Yii::$app->getBasePath() . '/web/runtime/';
        $noCacheOptions = '?tid=' . time();

        $xFactor = $_POST['xFactor'];
        $wsmall = isset($_POST['wsmall']) ? $_POST['wsmall'] : null;
        $hsmall = isset($_POST['hsmall']) ? $_POST['hsmall'] : null;
        $wbig = isset($_POST['wbig']) ? $_POST['wbig'] : null;
        $hbig = isset($_POST['hbig']) ? $_POST['hbig'] : null;
        $original_name = $_POST['name'];
        $prefix = isset($_POST['prefix']) ? $_POST['prefix'] : self::TMP_FILE_PREFIX;

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

        try {
            $dst_r = ImageCreateTrueColor($widthNew, $heightNew);

            $type = strtolower(substr(strrchr($_POST['name'], '.'), 1));
            $filename = $prefix . $original_name;
            switch ($type) {
                case 'bmp':
                    $img_r = imagecreatefromwbmp($src);
                    break;
                case 'gif':
                    $img_r = imagecreatefromgif($src);
                    break;
                case 'jpg':
                case 'jpeg':
                    $img_r = imagecreatefromjpeg($src);
                    break;
                case 'png':
                case 'x-png':
                    $img_r = imagecreatefrompng($src);
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
                    $tmp = imagejpeg($dst_r, $path . $filename, self::JPEG_QUALITY);
                    break;
                case 'png':
                case 'x-png':
                    $tmp = imagepng($dst_r, $path . $filename, 0);
                    break;
            }

            list($width, $height) = getimagesize($path . $filename);
            
//            self::$thumb_height = $hsmall;
//            self::$thumb_width = $wsmall;
//            self::$thumb_filename = $filename;
//            self::$thumb_src = $path . $filename;
//            self::createThumnail();

            return [
                'success' => self::STATUS_SUCCESS,
                'src_file' => $filename,
                'width' => $width,
                'height' => $height,
                'image_url' => Yii::$app->urlManager->hostInfo . '/runtime/' . $filename . $noCacheOptions,
                'message' => 'success',
            ];
        } catch (Exception $e) {
            return [
                'success' => self::STATUS_FAIL,
                'src_file' => null,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    public static function createThumnail(){
        $path = Yii::$app->getBasePath() . '/web/runtime/';

        try {

            $type = strtolower(substr(strrchr(self::$thumb_filename, "."), 1));
            switch ($type) {
                case 'bmp':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefromwbmp(self::$thumb_src));
                    break;
                case 'gif':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefromgif(self::$thumb_src));
                    break;
                case 'jpg':
                case 'jpeg':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefromjpeg(self::$thumb_src));
                    break;
                case 'png':
                case 'x-png':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefrompng(self::$thumb_src));
                    break;
            }


            // resize($unit = self::UNIT_PIXEL, $newWidth = null, $newHeight = null, $converseProportion = false, $positionX = 0, $positionY = 0, $position = 'MM')
//            $layer->resizeInPixel(self::$thumb_height, self::$thumb_width, true, 0, 0, 'MM');
            $layer->resizeInPixel(self::$thumb_height, self::$thumb_width, true, 0, 0, 'MM');
//            $layer->resize('pixel',self::$thumb_width, self::$thumb_height,true,0,0,'MM');
//            $image = $layer->getResult('ffffff');
            $image = $layer->getResult();
            
            switch ($type) {
                case 'bmp':
                    $tmp = imagewbmp($image, $path . self::THUMBNAIL_PREFIX . self::$thumb_filename);
                    break;
                case 'gif':
                    $tmp = imagegif($image, $path . self::THUMBNAIL_PREFIX . self::$thumb_filename);
                    break;
                case 'jpg':
                case 'jpeg':
                    $tmp = imagejpeg($image, $path . self::THUMBNAIL_PREFIX . self::$thumb_filename);
                    break;
                case 'png':
                case 'x-png':
                    $tmp = imagepng($image, $path . self::THUMBNAIL_PREFIX . self::$thumb_filename, 0);
                    break;
            }            

            list($width, $height) = getimagesize(self::$thumb_src);
            return [
                'success' => self::STATUS_SUCCESS,
                'src_file' => self::THUMBNAIL_PREFIX . self::$thumb_filename,
                'width' => $width,
                'height' => $height,
                'image_url' => Yii::$app->urlManager->hostInfo . '/runtime/' .  self::THUMBNAIL_PREFIX . self::$thumb_filename . '?tid='.  time(),
                'message' => 'success',
            ];
        } catch (Exception $e) {
            return [
                'success' => self::STATUS_FAIL,
                'src_file' => null,
                'image_url' => null,
                'message' => $e->getMessage(),
            ];
        }        
    }

    /*
     * Duplicate image
     */

    public static function duplicate() {

        $noCacheOptions = '?tid=' . time();
        $path = Yii::$app->getBasePath() . '/web/runtime/';
        $sourceFile = $path . $_POST['name'];
        $filename = self::TMP_FILE_PREFIX . $_POST['name'];
        $destinationFile = $path . $filename;
        try {
            // skip crop is mean destination file is exactly same with source file, so just copy it :)
            if (copy($sourceFile, $destinationFile)) {
                list($width, $height) = getimagesize($destinationFile);
                return [
                    'success' => self::STATUS_SUCCESS,
                    'src_file' => $filename,
                    'width' => $width,
                    'height' => $height,
                    'image_url' => Yii::$app->urlManager->hostInfo . '/runtime/' . $filename . $noCacheOptions,
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

    /*
     * Skip Crop image
     */

    public static function skip() {

        $noCacheOptions = '?tid=' . time();
        $path = Yii::$app->getBasePath() . '/web/runtime/';
        $filename = $_POST['name'];
        $new_filename = self::TMP_FILE_PREFIX . $filename;
        $src = $path . $filename;

        try {

            $type = strtolower(substr(strrchr($filename, "."), 1));
            switch ($type) {
                case 'bmp':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefromwbmp($src));
                    break;
                case 'gif':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefromgif($src));
                    break;
                case 'jpg':
                case 'jpeg':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefromjpeg($src));
                    break;
                case 'png':
                case 'x-png':
                    $layer = ImageWorkshop::initFromResourceVar(imagecreatefrompng($src));
                    break;
            }


            $layer->resizeInPixel($_POST['wbig'], $_POST['hbig'], true, 0, 0, 'MM');
            $image = $layer->getResult('ffffff');
            
            switch ($type) {
                case 'bmp':
                    $tmp = imagewbmp($image, $path . $new_filename);
                    break;
                case 'gif':
                    $tmp = imagegif($image, $path . $new_filename);
                    break;
                case 'jpg':
                case 'jpeg':
                    $tmp = imagejpeg($image, $path . $new_filename, self::JPEG_QUALITY);
                    break;
                case 'png':
                case 'x-png':
                    $tmp = imagepng($image, $path . $new_filename, 0);
                    break;
            }            

            list($width, $height) = getimagesize($src);
            return [
                'success' => self::STATUS_SUCCESS,
                'src_file' => $new_filename,
                'width' => $width,
                'height' => $height,
                'image_url' => Yii::$app->urlManager->hostInfo . '/runtime/' . $new_filename . $noCacheOptions,
                'message' => 'success',
            ];
        } catch (Exception $e) {
            return [
                'success' => self::STATUS_FAIL,
                'src_file' => null,
                'image_url' => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    /*
     * Publish to Aws S3
     */

    public static function publish() {

        $path = Yii::$app->getBasePath() . '/web/runtime/';
        $awsPath = 'images/media/web/business/';

        if (isset($_POST['name'])) {
            try {
                $originalFileName = $_POST['name'];
                $srcFile = $path . $originalFileName;

                $extension = explode('.', $originalFileName);
                $extension = end($extension);
                $filename = $originalFileName;

                $pathAws = $awsPath . $filename;

                // Upload to cloud
                S3::Upload($pathAws, $srcFile);

                return [
                    'success' => self::STATUS_SUCCESS,
                    'src_file' => $filename,
                    'image_url' => Yii::$app->params['businessUrl'] . $filename,
                ];
            } catch (Exception $e) {
                return [
                    'success' => self::STATUS_FAIL,
                    'src_file' => null,
                    'image_url' => null,
                    'message' => $e->getMessage(),
                ];
            }
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
    public static function clearLocal() {
        // Erase yesterday temporary images
        $files = glob(Yii::$app->getBasePath() . '/web/runtime/*'); // get all file names
        foreach($files as $file){ // iterate files
            if (filemtime($file) <= strtotime("-2 hours")) {
                if(is_file($file))
                    unlink($file); // delete file
            }
        }

        if (!empty($_POST['name'])) {
            $path = Yii::$app->getBasePath() . '/web/runtime/';
            $original_file = $path . $_POST['name'];
            $tmpcrop_file = $path . self::TMP_FILE_PREFIX . $_POST['name'];
            if (file_exists($original_file))
                unlink($original_file);
            if (file_exists($tmpcrop_file))
                unlink($tmpcrop_file);
        }
    }

}
