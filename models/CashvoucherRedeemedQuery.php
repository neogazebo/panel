<?php

namespace app\models;

use Yii;

/**
 * This is the ActiveQuery class for [[CashvoucherRedeemed]].
 *
 * @see CashvoucherRedeemed
 */
class CashvoucherRedeemedQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return CashvoucherRedeemed[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CashvoucherRedeemed|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getList()
    {
        $member = Yii::$app->request->get('member');
        $com_name = Yii::$app->request->get('merchant');
        $pvo_name = Yii::$app->request->get('voucher');
        $daterange = Yii::$app->request->get('update');
        $country = Yii::$app->request->get('acc_cty_id');

        if ($member)
            $this->andFilterWhere(['LIKE', 'acc_screen_name', $member]);
        if ($com_name)
            $this->andFilterWhere(['LIKE', 'cvr_com_name', $com_name]);
        if ($pvo_name)
            $this->andFilterWhere(['LIKE', 'cvr_pvo_name', $pvo_name]);
        if (!empty($daterange)) {
            $daterange = explode(' to ', $daterange);
            $this->andFilterWhere(['BETWEEN', 'FROM_UNIXTIME(cvr_pvd_update_datetime)', $daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
        }

        if($country)
        {
            if($country == 'ID' || $country == 'MY')
            {
                $this->andWhere(['acc_cty_id'=> $country]);
            }
        }

        $this->leftJoin('tbl_account', 'tbl_account.acc_id = cvr_acc_id');
        $this->orderBy('cvr_pvd_update_datetime DESC');
        //echo $this->createCommand()->getRawSql();
        //exit;
        return $this;
    }   

    public function getExcelColumns()
    {
        return  [
            'A' => [
                'name' => 'Screen Name',
                'width' => 30,
                'height' => 5,
                'db_column' => 'account',
                'have_relations' => true,
                'relation_name' => 'acc_screen_name'
            ], 
            'B' => [
                'name' => 'Transaction Time',
                'width' => 30,
                'height' => 5,
                'db_column' => 'cvr_pvd_update_datetime',
                'format' => function($data) {
                    return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data));
                }
            ], 
            'C' => [
                'name' => 'Voucher Name',
                'width' => 30,
                'height' => 5,
                'db_column' => 'cvr_pvo_name',
            ], 
            'D' => [
                'name' => 'Merchant',
                'width' => 30,
                'height' => 5,
                'db_column' => 'cvr_com_name',
            ],
            'E' => [
                'name' => 'SN',
                'width' => 30,
                'height' => 5,
                'db_column' => 'cvr_pvd_sn',
            ],
            'F' => [
                'name' => 'Code',
                'width' => 30,
                'height' => 5,
                'db_column' => 'cvr_pvd_code',
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
}
