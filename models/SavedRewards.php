<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_saved_rewards".
 *
 * @property integer $svr_id
 * @property integer $svr_acc_id
 * @property string $svr_pvo_id
 * @property integer $svr_datetime
 */
class SavedRewards extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_saved_rewards';
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
            [['svr_acc_id', 'svr_pvo_id', 'svr_datetime'], 'required'],
            [['svr_acc_id', 'svr_datetime'], 'integer'],
            [['svr_pvo_id'], 'string', 'max' => 250],
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['acc_id' => 'svr_acc_id']);
    }

    public function getPosVoucher()
    {
        return $this->hasOne(PosVoucher::className(), ['pvo_id' => 'svr_pvo_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svr_id' => 'ID',
            'svr_acc_id' => 'Account',
            'svr_pvo_id' => 'Pos Voucher',
            'svr_datetime' => 'Datetime',
        ];
    }
}
