<?php

namespace app\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @see Account
 */
class AccountQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Account[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Account|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function findAccount()
    {
        $search = $_GET['q'];
        $this->select('acc_id, acc_screen_name');
        $this->andWhere('acc_screen_name LIKE "%'.$search.'%" ');
        return $this->all();
    }

    public function getAll()
    {
        return $this->find()->all();
    }

    public function getExcelColumns()
    {
        return  [
            'A' => [
                'name' => 'Full Name',
                'width' => 30,
                'height' => 5,
                'db_column' => 'acc_screen_name',
            ], 
            'B' => [
                'name' => 'Email',
                'width' => 30,
                'height' => 5,
                'db_column' => 'acc_facebook_email'
            ], 
            'C' => [
                'name' => 'Facebook Id',
                'width' => 30,
                'height' => 5,
                'db_column' => 'acc_facebook_id'
            ], 
            'D' => [
                'name' => 'Registered Date',
                'width' => 30,
                'height' => 5,
                'db_column' => 'acc_created_datetime',
                'format' => function($data) {
                    return Yii::$app->formatter->asDate($data);
                }
            ], 
            'E' => [
                'name' => 'Current Point',
                'width' => 30,
                'height' => 5,
                'db_column' => 'acc_id',
                'format' => function($data) {
                    $model = LoyaltyPointHistory::find()->getTotalPointMember($data);
                    $point = (!empty($model->lph_total_point)) ? $model->lph_total_point : '0';
                    return Yii::$app->formatter->asDecimal($point);
                }
            ]
        ];
    }

    public function getExcelColumnsStyles()
    {
        return [
            'font' => [
                 'bold'  => true,
            ]
        ];
    }

    public function searchMemberEmail()
    {
        $parent_is_first = false;
        $search = $_GET['q'];
        $this->select('acc_facebook_email');
        $keyword = preg_split("/[\s,]+/", $search);

        $keyword = implode(' ', $keyword);
        $this->andWhere('acc_facebook_email LIKE "%' . $keyword . '%" ');
        
        $this->andWhere('acc_status = :status', [
            'status' => 1
        ]);

        return $this->all();
    }
}
