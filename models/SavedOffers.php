<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_saved_offers".
 *
 * @property integer $svo_id
 * @property integer $svo_acc_id
 * @property integer $svo_del_id
 * @property integer $svo_datetime
 */
class SavedOffers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_saved_offers';
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
            [['svo_acc_id', 'svo_del_id', 'svo_datetime'], 'required'],
            [['svo_acc_id', 'svo_del_id', 'svo_datetime'], 'integer'],
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['acc_id' => 'svo_acc_id']);
    }

    public function getPromotion()
    {
        return $this->hasOne(Deal::className(), ['del_id' => 'svo_del_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svo_id' => 'ID',
            'svo_acc_id' => 'Account',
            'svo_del_id' => 'Promotion',
            'svo_datetime' => 'Datetime',
        ];
    }
}
