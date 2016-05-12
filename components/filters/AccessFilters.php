<?php
namespace app\components\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\web\User;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use app\models\User as UserAdmin;

/**
* 
*/
class AccessFilters extends ActionFilter
{
	
	public function init()
	{
		// parent::init();
		// $auth = Yii::$app->authManager;
		// if(Yii::$app->user->identity->type != UserAdmin::TYPE_ADMIN)
  //       {
  //           return true;
  //       }

		// $auth = Yii::$app->authManager;
		// $usrId = Yii::$app->user->identity->id;
		// $rolename = $auth->getRolesByUser($usrId);
		// $currentUrl = Url::current();
		// if(!$rolename) {
		// 	throw new ForbiddenHttpException;
		// }
		// foreach ($rolename as $key) {
		// 	$permissions = $auth->getPermissionsByRole($key->name);
		// 	foreach ($permissions as $child) {
		// 		if($child->type == UserAdmin::TYPE_ROLE) {
		// 			$childPremissions = $auth->getPermissionsByRole($child->name);
		// 			if (array_key_exists($currentUrl, $childPremissions)) {
		// 			    return true;
		// 			}
		// 		}
		// 	}
		// 	if (array_key_exists($currentUrl, $permissions)) {
		// 	    return true;
		// 	}else{
		// 		throw new ForbiddenHttpException; 
		// 	}
		// }
  //       return true;
	}
}