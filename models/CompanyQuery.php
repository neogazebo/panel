<?php

namespace app\models;

use Yii;
use app\components\helpers\DateRangeCarbon;
use app\components\helpers\Utc;
use app\models\CompanySpeciality;
use yii\db\Expression;

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
        $this->andWhere('com_id = :com_id', [
            ':com_id'=>$com_id
        ]);
        return $this->one();
    }

    public function searchExistingMerchant()
    {
        $parent_is_first = false;
        $search = $_GET['q'];
        $keyword = preg_split("/[\s,]+/", $search);

        $this->select('com_id, com_name');
        $this->leftJoin('tbl_company_category', 'tbl_company_category.com_category_id = tbl_company.com_subcategory_id');

        if (!in_array('@', $keyword)) {
            $parent_is_first = true;
        }

        foreach ($keyword as $key) {
            $this->andWhere('com_name LIKE "%' . $key . '%" ');
        }
        
        $this->andWhere('
            tbl_company_category.com_category_type = :type 
            AND com_status != :status
        ', [
            ':type' => 1,
            ':status' => 2
        ]);

        $order = 'com_name ASC';

        if ($parent_is_first) {
            $order = new Expression('FIELD (com_is_parent, 1) DESC, com_name ASC');
        }
        
        $this->orderBy($order);
        return $this->all();
    }

    public function getParentMerchants()
    {
        return $this->select('
                com_id, com_name, com_created_date, com_subcategory_id
            ')
            ->joinWith('category')
            ->where('
                com_is_parent = :is_parent
            ', [
                ':is_parent' => 1
            ])
            ->orderBy([
                'com_id' => SORT_DESC
            ]);
    }

    public function getMerchantSpecialityGroup($speciality_id,$cty_id)
    {
        return $this->select('com_id, com_name')->where('com_speciality = :speciality_id AND com_currency = :cty_id', [':speciality_id' => $speciality_id, ':cty_id' => $cty_id])->orderBy(['com_id' => SORT_DESC]);
    }

    public function getChildMerchants($parent_id)
    {
        return $this
            ->select('com_id, com_name')
            ->where('
                com_hq_id = :hq_id
            ', [
                ':hq_id' => $parent_id
            ])
            ->orderBy(['com_id' => SORT_DESC]);
    }

    public function getAllChildMerchants()
    {
        return \Yii::$app
            ->getDb()
            ->createCommand('
                SELECT com_id, com_name 
                FROM tbl_company 
                WHERE com_hq_id = :hq_id
            ', [
                ':hq_id' => 0
            ]);
    }

    public function searchMerchant($keyword, $hq_id = null)
    {
        $parent_is_first = false;
        $keyword = preg_split("/[\s,]+/", $keyword);

        $this->select('com_id, com_name');
        $this->leftJoin('tbl_company_category', 'tbl_company_category.com_category_id = tbl_company.com_subcategory_id');

        /*
        if (!in_array('@', $keyword)) {
            $parent_is_first = true;
        }
        */

        foreach ($keyword as $key) {
            $this->andWhere('com_name LIKE "%' . $key . '%"');
        }
        
        $this->andWhere('
            tbl_company_category.com_category_type = :type 
            AND com_status != :status 
            AND com_hq_id = :hq
            AND com_is_parent = :is_parent
        ', [
            ':type' => 1,
            ':status' => 2,
            ':hq' => 0,
            ':is_parent' => 0
        ]);

        $order = 'com_name ASC';

        /*
        if ($parent_is_first) {
            $order = new Expression('FIELD (com_is_parent, 1) DESC, com_name ASC');
        }
        */
        
        $this->orderBy($order);
        return $this;
    }

    public function searchMerchantSpeciality($keyword,$spt,$cty)
    {
        $this->select('com_id, com_name');
        $this->leftJoin('tbl_company_category', 'tbl_company_category.com_category_id = tbl_company.com_subcategory_id');
        $this->andWhere('
            com_name LIKE "%' . $keyword . '%" 
            AND com_hq_id = 0 
            AND com_status != 2 
            AND com_is_parent = 0
            AND com_speciality <> "'.$spt.'"
            AND com_currency = "'.$cty.'"');
        
        $this->andWhere('tbl_company_category.com_category_type = :cat_id',[':cat_id' => 1]);

        $this->orderBy('com_name');
        return $this;
    }

    public function saveMerchantChildren($parent_id, $children, $removed = false)
    {
        $new_parent = $parent_id;

        $parent = Company::findOne($parent_id);

        if($removed)
        {
            $new_parent = 0;
        }

        if ($removed == true)
            $is_removed = 'removed';
        else
            $is_removed = 'saved';

        foreach($children as $child) {
            $company = Company::findOne($child);
            $company->com_hq_id = $new_parent;
            $company->save(false);

            $activities = [
                'Company',
                'Company - ' . $is_removed . ' Merchant HQ Children with parent is ' . $parent->com_email . ', ' . $parent->com_name . ' (' . $parent_id . ') and child is (' . $child . ') ' . $company->com_name . ' (' . $company->com_email . ')',
                Company::className(),
                $company->com_id
            ];
            Logging::saveLog($activities);
        }
    }

    public function changeSpecialityMerchant($new_speciality_id,$changes)
    {
        var_dump($new_speciality_id);exit;
        foreach($changes as $com_id) {
            $company = Company::findOne($com_id);
            $company->com_speciality = $new_speciality_id;
            var_dump($company->save(false));

            $activities = [
                'Company',
                'Company '.$company->com_name.' has been set to '.$new_speciality_id,
                Company::className(),
                $company->com_id
            ];
            Logging::saveLog($activities);
        }
    }

    public function getChildMerchantsId($parent_id)
    {
        $result = [];
        $children = $this->getChildMerchants($parent_id)->all();

        if($children) {
            foreach($children as $child) {
                array_push($result, $child->com_id);
            }
        }

        return $result;
    }

    public function getGroupMerchantSpeciality($speciality_id)
    {
        $result = [];
        $special = CompanySpeciality::find()->with('type','country')->where('com_spt_id = :id',[
            ':id' => $speciality_id])->one();

        $children = Company::find()
            ->select('com_id')
            ->where('com_speciality = :type_id AND com_currency = :type_cty',[
                ':type_id' => $special->type->com_type_id,
                ':type_cty' => $special->country->cty_currency_name_iso3
            ])
            ->andWhere('com_status = :status',[
                ':status' => 1
            ])
            ->asArray()->all();

        if($children) {
            foreach($children as $child) {
                array_push($result, $child['com_id']);
            }
        }

        return $result;
    }

    public function getChildrenNames($children)
    {
        $result = [];

        if($children) {
            foreach($children as $child) {
                $company = Company::findOne($child);
                array_push($result, $company->com_name);
            }
        }

        return $result;
    }

    public function checkCompanyIsParent($com_id)
    {
        $company = Company::findOne($com_id);
        $is_parent = $company->com_is_parent;

        if($is_parent) {
            return true;
        }

        return false;
    }

    /* Inquiry */
    public static function getListInquiry()
    {
        $dt = new DateRangeCarbon();
        $model = Company::find();
        if (!empty($_GET['com_daterange'])) {
            $com_daterange = explode(' to ', ($_GET['com_daterange']));
            $first = "(
                SELECT com_id 
                FROM tbl_company 
                WHERE com_created_date >= UNIX_TIMESTAMP('$com_daterange[0] 00:00:00') 
                LIMIT 1
            )";
            $second = "(
                SELECT com_id 
                FROM tbl_company 
                WHERE com_created_date <= UNIX_TIMESTAMP('$com_daterange[1] 23:59:59') 
                ORDER BY com_id DESC 
                LIMIT 1
            )";
            $model->andWhere("com_id BETWEEN $first AND $second");
        } else {
            $com_daterange = explode(' to ', ($dt->getDay()));
            $first = "(
                SELECT com_id 
                FROM tbl_company 
                WHERE com_created_date >= UNIX_TIMESTAMP('$com_daterange[0]') 
                LIMIT 1
            )";
            $second = "(
                SELECT com_id 
                FROM tbl_company 
                WHERE com_created_date <= UNIX_TIMESTAMP('$com_daterange[1]') 
                ORDER BY com_id DESC 
                LIMIT 1
            )";
            $model->andWhere("com_id BETWEEN $first AND $second");
        }
        if (!empty($_GET['com_name']))
            $model->andWhere('com_id = :com_id', [':com_id' => $_GET['com_name']]);
        if (!empty($_GET['ops_name']))
            $model->andWhere('com_created_by = :id', [':id' => $_GET['ops_name']]);
        $model->andWhere('com_created_date > 1473008400');
        return $model;
    }

    public function getExcelColumns()
    {
        return  [
            'A' => [
                'name' => 'Outlet Name',
                'width' => 30,
                'height' => 5,
                'db_column' => 'com_name',
                // 'have_relations' => true,
                // 'relation_name' => 'com_name'
            ], 
            'B' => [
                'name' => 'Email',
                'width' => 30,
                'height' => 5,
                'db_column' => 'com_email',
                // 'have_relations' => true,
                // 'relation_name' => 'acc_screen_name'
            ], 
            'C' => [
                'name' => 'City',
                'width' => 30,
                'height' => 5,
                'db_column' => 'com_city',
                // 'have_relations' => true,
                // 'relation_name' => 'acc_facebook_email'
            ], 
            'D' => [
                'name' => 'Category',
                'width' => 30,
                'height' => 5,
                'db_column' => 'category',
                'have_relations' => true,
                'relation_name' => 'com_category_id'
            ],
            'E' => [
                'name' => 'Created On',
                'width' => 30,
                'height' => 5,
                'db_column' => 'com_created_date',
                'format' => function($data) {
                    return Yii::$app->formatter->asDateTime(Utc::convert($data));
                }
            ],
            'F' => [
                'name' => 'Operator',
                'width' => 30,
                'height' => 5,
                'db_column' => 'userCreated',
                'have_relations' => true,
                'relation_name' => 'username'
            ],
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
