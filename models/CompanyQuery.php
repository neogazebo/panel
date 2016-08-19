<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AccountDevice]].
 *
 * @see AccountDevice
 */
class CompanyQuery extends \yii\db\ActiveQuery
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

    public function getCurrentPoint($com_id)
    {
        $this->andWhere('com_id = :com_id',[
            ':com_id'=>$com_id
        ]);
        return $this->one();
    }

    public function searchExistingMerchant()
    {
        $search = $_GET['q'];
        $this->select('com_id, com_name');
        $keyword = preg_split("/[\s,]+/",$search);
        $this->select('com_id, com_name');
        $this->leftJoin('tbl_company_category','tbl_company_category.com_category_id = tbl_company.com_subcategory_id');
        foreach($keyword as $key){
            $this->andWhere('com_name LIKE "%'.$key.'%" ');
        }
        $this->andWhere('tbl_company_category.com_category_type = :type',[
            'type' => 1
        ]);
        $this->andWhere('com_status != 2');
        $this->orderBy('com_name');
        return $this->all();
    }

    public function getParentMerchants()
    {
        return $this->select('com_id, com_name, com_created_date, com_subcategory_id')->where('com_is_parent = :is_parent', [':is_parent' => 1])->orderBy(['com_id' => SORT_DESC]);
        return $parents;
    }

    public function getChildMerchants($parent_id)
    {
        return $this->select('com_id, com_name')->where('com_hq_id = :hq_id', [':hq_id' => $parent_id])->orderBy(['com_id' => SORT_DESC]);
    }

    public function getAllChildMerchants()
    {
        return \Yii::$app->getDb()->createCommand('SELECT com_id, com_name FROM tbl_company WHERE com_hq_id = :hq_id', [':hq_id' => 0]);
    }

    public function searchMerchant($keyword, $hq_id)
    {
        $this->select('com_id, com_name');
        $this->andWhere('com_name LIKE "%' . $keyword . '%"');
        $this->andWhere('com_hq_id = 0');
        $this->andWhere('com_status != 2');
        $this->andWhere('com_is_parent = 0');
        $this->orderBy('com_name');

        return $this->all();
    }

    public function saveMerchantChildren($parent_id, $children)
    {
        foreach($children as $child)
        {
            $company = Company::findOne($child);
            $company->com_hq_id = $parent_id;
            $company->save(false);
        }
    }

    public function getChildMerchantsId($parent_id)
    {
        $result = [];

        $children = $this->getChildMerchants($parent_id)->all();

        if($children)
        {
            foreach($children as $child)
            {
                array_push($result, $child->com_id);
            }
        }

        return $result;
    }

    public function getChildrenNames($children)
    {
        $result = [];

        if($children)
        {
            foreach($children as $child)
            {
                $company = Company::findOne($child);
                array_push($result, $company->com_name);
            }
        }

        return $result;
    }
}
