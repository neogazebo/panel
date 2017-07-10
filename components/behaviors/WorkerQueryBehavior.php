<?php
/**
 * Class WorkerQueryBehavior
 *
 * @author Ahmad GM <ahmad@ebizu.com>
 */

namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;

class WorkerQueryBehavior extends Behavior
{
    public $tableName = NULL;
    public $fieldIdName = NULL;
    public $appId = NULL;
    public $version = NULL;
    public $topic = NULL;
    public $url = NULL;
    public $type = NULL;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'getRawSqlInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'getRawSqlUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'getRawSqlDelete'
        ];
    }

    public function getRawSqlInsert($event)
    {
        $attributes = $event->sender;

        $keys = [];
        $records = [];

        $query = "";

        if(isset($attributes)) 
        {
            foreach ($attributes->attributes as $key => $value) {
                $keys[] = $key;
                $records[] = $this->validateDataType($value);
            }
            $index = "(". implode(', ', $keys) .")";
            $records = "(". implode(', ', $records).")" ;

            $query = "INSERT INTO `{$this->tableName}` {$index} VALUES {$records};";

            // Execute worker
            $this->sendToWorker($query, ActiveRecord::EVENT_AFTER_INSERT);
        }    

        return $query;
    }

    public function getRawSqlUpdate($event)
    {
        $query = "UPDATE `{$this->tableName}` SET ";
        $keys = [];
        $records = [];

        if(isset( $event->changedAttributes )) {
            foreach ($event->changedAttributes as $key => $value) {
                foreach($event->sender->attributes as $index => $row) {
                    if($index == $key) {
                        $keys[] = $key ."='".$row."'";
                    }
                }
            }
            $index = implode(', ', $keys) ;
            $id = $this->validateDataType($event->sender->attributes[$this->fieldIdName]);
            if($index) {
                $query .= " {$index} WHERE {$this->fieldIdName}={$id};";
            } else {
                $query = "";
            }
        }

        $attributes = [];
        foreach ($event->changedAttributes as $key => $value) {
            $attr[$key] = $value;
        }
        
        // Execute worker
        if($query) {
            $this->sendToWorker($query, ActiveRecord::EVENT_AFTER_UPDATE);
        }

        return $query;
    }

    public function getRawSqlDelete($event)
    {
        $id = $this->validateDataType($event->sender->attributes[$this->fieldIdName]);
        $query = "DELETE FROM {$this->tableName} WHERE {$this->fieldIdName} = {$id};";

        // Execute worker
        $this->sendToWorker($query, ActiveRecord::EVENT_AFTER_DELETE);

        return $query;
    }

    public function setParameters() {
        // Default parameters value
        $type = $this->type ? $this->type : 'test';

        $topic = Yii::$app->params['sns_topic'][$type];
        $worker_uri = Yii::$app->params['worker_uri']['prod'];

        // Dynamic parameters
        switch ($this->getEnv()) {
            case 'prod':
                $app_id = Yii::$app->params['app_id']['prod'];
                break;
            case 'staging':
                $app_id = Yii::$app->params['app_id']['staging'];
                break;
            case 'dev':
                $app_id = Yii::$app->params['app_id']['dev'];
                break;
            default:
                $app_id = Yii::$app->params['app_id']['dev'];
                break;
        }

        $this->url = $this->url ? $this->url : $worker_uri;
        $this->appId = $this->appId ? $this->appId : $app_id;
        $this->topic = $this->topic ? $this->topic : $topic;

        return $this;
    }

    // This method aims for validate and returning value based on data type
    public function validateDataType($record) {
        if(is_integer($record)) {
            return $record;
        } else if(is_float($record)) {
            return $record;
        } else if(is_double($record)) {
            return $record;
        } else if(is_string($record)) {
            return "'".$record."'";
        } else if($record == NULL) {
            return "''";
        }
    }

    // This method aims for validate and returning value based on data type
    public function getEnv() {
        return YII_ENV;
    }

    // This method aims for sending custom data to aws
    // This function temporary disabled : ask @tajhulfaijin
    public function sendToWorker($query, $actionName) {
        /*$this->setParameters();

        $sql = $query;
        $fields = array( 
            'app_id'=> $this->appId,  // application id
            'message_body' => $sql, // SQL to be execute
            'version' => $this->version, // version queue
            'topic' => $this->topic // sns topic
        );

        $postvars = '';
        foreach($fields as $key=>$value) {
            $postvars .= $key . "=" . $value . "&";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);*/
        /*
        //        $this->setParameters();
        //
        //        $sql = $query;
        //        $fields = array( 
        //            'app_id'=> $this->appId,  // application id
        //            'message_body' => $sql, // SQL to be execute
        //            'version' => $this->version, // version queue
        //            'topic' => $this->topic // sns topic
        //        );
        //
        //        $postvars = '';
        //        foreach($fields as $key=>$value) {
        //            $postvars .= $key . "=" . $value . "&";
        //        }
        //        $ch = curl_init();
        //        curl_setopt($ch, CURLOPT_URL, $this->url);
        //        curl_setopt($ch, CURLOPT_POST, 1);
        //        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        //        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);        
        //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //        $data = curl_exec($ch);
        //        curl_close($ch);
        */
    }
}