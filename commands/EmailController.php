<?php
namespace app\commands;

use Yii;
use app\models\PosVoucher;
use yii\console\Controller;

class EmailController extends Controller
{
    public function actionSend()
    {
    	$model = PosVoucher::find()->where('pvo_stock_left <= 10')->all();
    	foreach ($model as $row) {
    		if (!empty($row->business)) {
    			$params = [];
    			// if ($row->pvo_valid_end > time()) {
		    		// $to = $row->business->com_email;
		    		$to = 'Ebizu Project <project@ebizu.com>';

		    		$pvo_name = $row->pvo_name;
		    		$com_name = $row->business->com_name;
		    		$com_address = $row->business->com_address;
		    		$pvo_stock_left = $row->pvo_stock_left;
		    		$pvo_valid_end = $row->pvo_valid_end;

		    		$params = [
		    			'pvo_name' => $pvo_reward_name,
		    			'com_name' => $com_name,
		    			'com_address' => $com_address,
		    			'pvo_stock_left' => Yii::$app->formatter->asDecimal($pvo_stock_left),
		    			'pvo_valid_end' => Yii::$app->formatter->asDatetime($pvo_valid_end)
		    		];
		    		$subject = $pvo_reward_name . ' from ' . $com_name . (!empty($com_address) ? ' in ' . $com_address : '') . ' has balance of ' . $pvo_stock_left;
		    		var_dump($subject);
		    		exit;

			        Yii::$app
				        ->AdminMail
				        ->backend($to, $params)
				        ->stockVoucher($subject)
				        ->send()
				        ->view();
		    	}
	    	// }
	    }
    }
}
