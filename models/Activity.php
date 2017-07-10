<?php

namespace app\models;

use Yii;
use app\components\helpers\Utc;

/**
 * This is the model class for table "tbl_activity".
 *
 * @property integer $acv_id
 * @property integer $acv_acc_id
 * @property integer $acv_notification
 * @property integer $acv_com_id
 * @property integer $acv_act_id
 * @property integer $acv_datetime
 * @property integer $acv_view
 * @property integer $acv_read
 * @property integer $acv_hide
 * @property string $acv_col1
 * @property string $acv_col2
 * @property string $acv_col3
 * @property string $acv_col4
 * @property string $acv_col5
 * @property string $acv_col6
 * @property string $acv_col7
 * @property string $acv_col8
 * @property string $acv_col9
 * @property string $acv_col10
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_activity';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function insertAct($usrid, $type, $params, $customData = [])
    {
        $fields = [];
        for ($i = 0; $i < count($params); $i++) {
            $fields[] = '`acv_col' . ($i + 1) . '` = "' . $params[$i] . '"';
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = new Activity;
            $model->acv_acc_id = $usrid;
            $model->acv_act_id = $type;
            $model->acv_datetime = Utc::getNow();

            $count = 1;
            foreach ($params as $k => $v) {
                $field = 'acv_col' . $count;
                $model->$field = $v;
                $count++;
            }
            $model->save();

            $acvId = Yii::$app->db->getLastInsertID();

            if (empty($customData))
                $customData = NULL;
            else
                $customData = json_encode($customData);

            $actSent = new ActivitySent;
            $actSent->acs_acv_id = $acvId;
            $actSent->acs_acc_id = $usrid;
            $actSent->acs_datetime = Utc::getNow();
            $actSent->acs_android_pushed = 0;
            $actSent->acs_custom_data = $customData;
            $actSent->save();
            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollBack();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acv_acc_id', 'acv_notification', 'acv_com_id', 'acv_act_id', 'acv_datetime', 'acv_view', 'acv_read', 'acv_hide'], 'integer'],
            [['acv_col1', 'acv_col2', 'acv_col3', 'acv_col4', 'acv_col5', 'acv_col6', 'acv_col7', 'acv_col8', 'acv_col9', 'acv_col10'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'acv_id' => 'ID',
            'acv_acc_id' => 'Account',
            'acv_notification' => 'Notification',
            'acv_com_id' => 'Merchant',
            'acv_act_id' => 'Type',
            'acv_datetime' => 'Datetime',
            'acv_view' => 'View',
            'acv_read' => 'Read',
            'acv_hide' => 'Hide',
            'acv_col1' => 'Col1',
            'acv_col2' => 'Col2',
            'acv_col3' => 'Col3',
            'acv_col4' => 'Col4',
            'acv_col5' => 'Col5',
            'acv_col6' => 'Col6',
            'acv_col7' => 'Col7',
            'acv_col8' => 'Col8',
            'acv_col9' => 'Col9',
            'acv_col10' => 'Col10',
        ];
    }
}
