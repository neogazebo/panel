<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * Description of EpayQuery
 *
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 */
class EpayQuery extends ActiveQuery
{
    public function voucher($state = true)
    {
        $this->leftJoin('tbl_epay_product', 'epp_id = vou_epp_id');
        $this->where('epa_vou_id IS NOT NULL');
        if(isset($_GET['search'])) {
        	$this->andWhere('
                vou_reward_name LIKE :get OR 
                vou_description LIKE :get OR 
                epp_title LIKE :get OR 
                epp_product_code LIKE :get OR 
                epp_product_type LIKE :get OR 
                epa_admin_name LIKE :get
            ', [':get' => '%' . $_GET['search'] . '%']);
        }
        return $this;
    }
}
