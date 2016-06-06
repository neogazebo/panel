<?php

namespace app\models;

use Yii;
use app\components\helpers\Utc;

/**
 * This is the model class for table "tbl_email_queue".
 *
 * @property integer $emq_id
 * @property integer $emq_priority
 * @property integer $emq_time
 * @property integer $emq_sent
 * @property string $emq_subject
 * @property string $emq_from
 * @property string $emq_to
 * @property string $emq_cc
 * @property string $emq_bcc
 * @property string $emq_body
 * @property string $emq_body_plain
 * @property string $emq_attachment
 * @property string $emq_additional_header
 * @property string $emq_aws_message_id
 */
class EmailQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_email_queue';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emq_priority', 'emq_time', 'emq_sent'], 'integer'],
            [['emq_cc', 'emq_bcc', 'emq_body', 'emq_body_plain', 'emq_attachment', 'emq_additional_header'], 'required'],
            [['emq_cc', 'emq_bcc', 'emq_body', 'emq_body_plain', 'emq_attachment', 'emq_additional_header'], 'string'],
            [['emq_subject', 'emq_from', 'emq_to', 'emq_aws_message_id'], 'string', 'max' => 100],
        ];
    }

    public static function logSave(
        $from, 
        $to, 
        $cc, 
        $bcc, 
        $subject, 
        $body
    ) {
        $model = new EmailQueue();
        $model->emq_priority = 5;
        $model->emq_time = Utc::getNow();
        $model->emq_sent = Utc::getNow();
        $model->emq_from = $from;
        $model->emq_to = $to;
        $model->emq_cc = $cc;
        $model->emq_bcc = $bcc;
        $model->emq_subject = $subject;
        $model->emq_body = $body;
        if ($model->save())
            return true;
        return false;
    }

    public static function insertToSendingMail(
        $from, 
        $to, 
        $cc, 
        $bcc, 
        $subject, 
        $body, 
        $body_plain, 
        $attachments = '', 
        $additional_headers = array(), 
        $priority = 5
    ) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $to = json_encode($to);
            $model = new EmailQueue;
            $model->emq_priority = $priority;
            $model->emq_from = $from;
            $model->emq_to = $to;
            $model->emq_cc = $cc;
            $model->emq_bcc = $bcc;
            $model->emq_body = $body;
            $model->emq_body_plain = $body_plain;
            // $model->emq_attachment = $attachment;
            $model->save();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    public static function sendMail($params = [])
    {
        $transaction = Yii::$app->db->beginTransaction();
        // try {
        //     $time = Yii::$app->db
        //         ->createCommand("SELECT DATE_ADD(NOW(), INTERVAL 1 MINUTE) AS time")
        //         ->queryScalar();

        //     $model = new EmailQueue;
        //     $model->emq_time = intval($time);
        //     foreach ($params as $k => $v) {
        //         $model->emq_
        //     }
        // }
        $query = 'INSERT INTO `kemail_queue` (`time`, ';
        $values = 'VALUES (DATE_ADD(NOW(), INTERVAL 1 MINUTE), ';
        foreach($params as $p => $k) {
            if($p == 'to') {
                $k = trim($k);
                $k = ltrim($k, '"');
                $k = rtrim($k, '"');
            }
            $query .= '`' . $p . '`, ';
            $values .= "'" . $k . "', ";
        }
        $query = rtrim($query, ', ') . ') ';
        $values = rtrim($values, ', ') . ')';
        $sql = $query . $values;
        try {
            $command = Yii::$app->db
                ->createCommand($sql)
                ->execute();
            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollBack();
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'emq_id' => 'ID',
            'emq_priority' => 'Priority',
            'emq_time' => 'Time',
            'emq_sent' => 'Sent',
            'emq_subject' => 'Subject',
            'emq_from' => 'From',
            'emq_to' => 'To',
            'emq_cc' => 'Cc',
            'emq_bcc' => 'Bcc',
            'emq_body' => 'Body',
            'emq_body_plain' => 'Body Plain',
            'emq_attachment' => 'Attachment',
            'emq_additional_header' => 'Additional Header',
            'emq_aws_message_id' => 'Aws Message ID',
        ];
    }
}
