<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * Description of EpayQuery
 *
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 */
class EpayDetailQuery extends ActiveQuery
{
    public function voucher($state = true)
    {
        $this->join('INNER JOIN', 'tbl_voucher_bought_detail b', 'vod_code = epd_pin');
        if(isset($_GET['search'])) {
        	$this->andWhere(['LIKE', 'b.vod_code', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epd_amount', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epd_request', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epd_operator_id', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epd_product_code', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epd_terminal_id', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epd_ret_trans_ref', $_GET['search']]);
        	$this->orWhere(['LIKE', 'epd_msisdn', $_GET['search']]);
        }
        // $this->select('epa_id,vou_reward_name');
        // $this->join('LEFT JOIN', 'tbl_voucher', 'vou_id=epa_vou_id');
        // $this->join('LEFT JOIN', 'tbl_epay_product', 'epp_id = vou_epp_id');
        // $this->where('epa_vou_id IS NOT NULL');
        $this->orderBy('epd_id DESC');
        return $this;
    }

}
