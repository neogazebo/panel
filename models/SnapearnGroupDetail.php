<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_snapearn_group_detail".
 *
 * @property integer $spgd_id
 * @property string $spgd_usr_id
 * @property integer $spgd_spg_id
 */
class SnapearnGroupDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn_group_detail';
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
            [['spgd_usr_id', 'spgd_spg_id'], 'required'],
            [['spgd_spg_id'], 'integer'],
            [['spgd_usr_id'], 'string', 'max' => 255],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'spgd_usr_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(SnapearnGroup::className(), ['spg_id' => 'spgd_spg_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spgd_id' => 'ID',
            'spgd_usr_id' => 'User',
            'spgd_spg_id' => 'Group',
        ];
    }
}