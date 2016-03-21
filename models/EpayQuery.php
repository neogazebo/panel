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
        //$this->select('epa_id,vou_reward_name');
        $this->join('LEFT JOIN', 'tbl_voucher b', 'vou_id = epa_vou_id');
        $this->join('LEFT JOIN', 'tbl_epay_product c', 'epp_id = vou_epp_id');
        $this->where('epa_vou_id IS NOT NULL');
        if(isset($_GET['search'])) {
        	$this->andWhere(['LIKE', 'b.vou_reward_name', $_GET['search']]);
        	$this->orWhere(['LIKE', 'b.vou_description', $_GET['search']]);
        	$this->orWhere(['LIKE', 'c.epp_title', $_GET['search']]);
        	$this->orWhere(['LIKE', 'c.epp_product_code', $_GET['search']]);
        	$this->orWhere(['LIKE', 'c.epp_product_type', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epa_admin_name', $_GET['search']]);
        }
        return $this;
    }
    
}
