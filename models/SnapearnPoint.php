<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_snapearn_point".
 *
 * @property integer $snp_id
 * @property string $snp_name
 * @property integer $snp_point
 * @property integer $snp_created_by
 * @property integer $snp_created_date
 * @property integer $snp_updated_by
 * @property integer $snp_updated_date
 * @property integer $snp_status
 */
class SnapearnPoint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn_point';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['snp_point', 'snp_created_by', 'snp_created_date', 'snp_updated_by', 'snp_updated_date', 'snp_status'], 'integer'],
            [['snp_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'snp_id' => 'Snp ID',
            'snp_name' => 'Snp Name',
            'snp_point' => 'Snp Point',
            'snp_created_by' => 'Snp Created By',
            'snp_created_date' => 'Snp Created Date',
            'snp_updated_by' => 'Snp Updated By',
            'snp_updated_date' => 'Snp Updated Date',
            'snp_status' => 'Snp Status',
        ];
    }

    /**
     * @inheritdoc
     * @return SnapearnPointQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SnapearnPointQuery(get_called_class());
    }
}
