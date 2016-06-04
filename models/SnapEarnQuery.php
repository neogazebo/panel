<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AccountDevice]].
 *
 * @see AccountDevice
 */
class SnapEarnQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AccountDevice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccountDevice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getLastUpload($id)
    {
        $this->andWhere('sna_acc_id = :id',[
                ':id' => $id
            ]);
        $this->orderBy('sna_id DESC');
        $this->limit(1);
        return $this;
    }

    public function saveNext($id,$ctr)
    {
        $this->leftJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('sna_id < :id', [':id' => $id]);
        $this->andWhere('acc_cty_id = :ctr',[
                ':ctr' => $ctr
            ]);
        $this->andWhere('sna_status = 0');
        $this->orderBy('sna_id DESC');
        $this->limit(1);
        return $this->one();
    }
}
