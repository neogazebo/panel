<?php
namespace app\models;

use Yii;
use yii\base\Model;

class PdfForm extends Model
{
    public $date_range;
    public $username;
    public $order;
    public $download_is;

    public function rules()
    {
        return [
            [['date_range', 'username'], 'required'],
            [['order', 'download_is'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date_range' => Yii::t('app', 'Date Range'),
            'username' => Yii::t('app', 'Who Examined'),
            'order' => Yii::t('app', 'Sort'),
            'download_is' => '',
        ];
    }

}
