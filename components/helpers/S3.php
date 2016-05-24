<?php

namespace app\components\helpers;

use Yii;
use yii\base\Event;
use yii\db\Expression;
use yii\db\ActiveRecord;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use PHPImageWorkshop\ImageWorkshop;

// require_once Yii::$app->basePath . '/lib/' . 'aws' . DIRECTORY_SEPARATOR . 'aws.phar';
// require_once Yii::$app->basePath . '/lib/' . 'PHPImageWorkshop' . DIRECTORY_SEPARATOR . 'ImageWorkshop.php';

class S3
{
    /*
    * Upload file to S3 server
    */
    public static function Upload($key, $source)
    {
        $client = S3Client::factory([
            'key' => Yii::$app->params['s3key'],
            'secret' => Yii::$app->params['s3secret'],
            'region' => Yii::$app->params['s3region']
        ]);

        try {
            $bucket = Yii::$app->params['s3bucket'];
            $result = $client->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'CacheControl' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
                'SourceFile' => $source,
                'ACL' => 'public-read',
            ]);
        } catch (S3Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /*
    * delete temporary file
    */
    public static function Delete($file)
    {
        $client = S3Client::factory([
            'key' => Yii::$app->params['s3key'],
            'secret' => Yii::$app->params['s3secret'],
            'region' => Yii::$app->params['s3region']
        ]);

        try {
            $bucket = Yii::$app->params['s3bucket'];
            $result = $client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $file,
            ]);
        } catch (S3Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

}
