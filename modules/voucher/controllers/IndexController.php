<?php

namespace app\modules\voucher\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use app\controllers\BaseController;
use app\models\AuditReport;
use app\models\Voucher;
use app\models\VoucherRedeemed;
use app\models\VoucherBought;
/**
 * Default controller for the `voucher` module
 * 
 * @author Ilham Fauzi <ilham@ebizu.com>
 */
class IndexController extends BaseController
{

    public function actionIndex()
    {
        $model = Voucher::find();
        $totalReward = Voucher::find()->count();
        $totalRedeemed = VoucherRedeemed::find()->count();
        
		if (Yii::$app->user->identity->type == 3){
		    $model = Voucher::find()->voucherMall();
	            $totalReward = Voucher::find('count');
	            $totalRedeemed = VoucherRedeemed::find()->totalRedeemmed();
		}

		$dataProvider = new ActiveDataProvider([
		    'query' => $model,
		    'pagination' => [
			'pageSize' => 20
		    ],
		]);


		$voucher = new Voucher();
		$amountVoucherThisMonth = $voucher->getTotalCurrentMonth();
		$amountVoucherLastMonth = $voucher->getTotalLastMonth();

		$rewardPercentage = (((int) $amountVoucherThisMonth - (int) $amountVoucherLastMonth) / 100);

		$amountRedeemedThisMonth = $voucher->getTotalRedeemCurrentMonth();
		$amountRedeemedLastMonth = $voucher->getTotalRedeemLastMonth();
		$redeemedPercentage = (((int) $amountRedeemedThisMonth - (int) $amountRedeemedLastMonth) / 100);

		$totalMemberRedeem = $voucher->getTotalMemberRedeem();
		$totalMemberRedeemThisMonth = $voucher->getTotalMemberRedeemThisMonth();

		$totalMemberRedeemLastMonth = $voucher->getTotalMemberRedeemLastMonth();
		$memberPercentage = (((int) $totalMemberRedeemThisMonth - (int) $totalMemberRedeemLastMonth) / 100);

		$itemRedeemed = $voucher->getTotalItemRedeem();
		$itemRedeemedThisMonth = $voucher->getTotalItemRedeemThisMonth();
		$itemRedeemedLastMonth = $voucher->getTotalItemRedeemLastMonth();
		$itemRedeemedPercentage = (((int) $itemRedeemedThisMonth - (int) $itemRedeemedLastMonth) / 100);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'totalReward' => $totalReward,
			'rewardPercentage' => $rewardPercentage,
			'totalRedeemed' => $totalRedeemed,
			'redeemedPercentage' => $redeemedPercentage,
			'totalMember' => $totalMemberRedeem,
			'memberPercentage' => $memberPercentage,
			'totalItemRedeemed' => $itemRedeemed,
			'itemRedeemedPercentage' => $itemRedeemedPercentage,
		]);
    }

    public function actionDetail($id)
    {
		$model = Voucher::findOne($id);
		$bought = VoucherBought::find()->where('vob_vou_id = :vouId', [':vouId' => $model->vou_id]);
		$dataProvider = new ActiveDataProvider([
		    'query' => $bought,
		    'pagination' => [
			'pageSize' => 20
		    ],
		]);

		return $this->render('detail', [
			'dataProvider' => $dataProvider,
			'voucher' => $model
		]);
    }
}
