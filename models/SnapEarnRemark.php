<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_snapearn_remark".
 *
 * @property integer $sem_id
 * @property string $sem_remark
 */
class SnapEarnRemark extends \yii\db\ActiveRecord
{
    const FORCE_REJECTED_MAX_PER_DAY = 8;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn_remark';
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
            [['sem_remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sem_id' => 'ID',
            'sem_remark' => 'Remark',
        ];
    }
}
