<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_snapearn_point_detail".
 *
 * @property integer $snd_id
 * @property integer $snd_snp_id
 * @property integer $snd_sna_id
 * @property integer $snd_by
 * @property integer $snd_datetime
 * @property integer $snd_point
 */
class SnapearnPointDetail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn_point_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['snd_snp_id', 'snd_sna_id', 'snd_by', 'snd_datetime', 'snd_point'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'snd_id' => 'Snd ID',
            'snd_snp_id' => 'Snd Snp ID',
            'snd_sna_id' => 'Snd Sna ID',
            'snd_by' => 'Snd By',
            'snd_datetime' => 'Snd Datetime',
            'snd_point' => 'Snd Point',
        ];
    }

    /**
     * @inheritdoc
     * @return SnapearnPointDetailQuery the active query used by this AR class.
     */

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'snd_datetime'
                ],
            ],
        ];
    }

    public function getAdmin()
    {
        return $this->hasOne(User::className(), ['id' => 'snd_by']);
    }

    public function getSnapearn()
    {
        return $this->hasOne(SnapEarn::className(), ['sna_id' => 'snd_sna_id']);
    }

    public function getPoint()
    {
        return $this->hasOne(SnapEarnPoint::className(), ['snp_id' => 'snd_snp_id']);
    }

    public static function savePoint($id, $id_point)
    {
        // insert point
        $point = SnapEarnPoint::findOne($id_point)->snp_point;

        $model = new SnapEarnPointDetail();
        $model->snd_sna_id = $id;
        $model->snd_snp_id = $id_point;
        $model->snd_by = Yii::$app->user->id;
        $model->snd_point = $point;
        $model->save();
    }

    public static function find()
    {
        return new SnapearnPointDetailQuery(get_called_class());
    }
}
