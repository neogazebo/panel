<?php
namespace app\components\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\web\User;
use yii\helpers\Url;
use yii\base\Module;
use yii\web\ForbiddenHttpException;
use app\models\User as UserAdmin;
use app\components\helpers\GetRoutes;

/**
* 
*/
class AccessFilters extends ActionFilter
{
	
	public function init()
	{
		parent::init();
		$auth = Yii::$app->authManager;
		if(Yii::$app->user->identity->type != UserAdmin::TYPE_ADMIN)
        {
            return true;
        }

		$auth = Yii::$app->authManager;
		$userId = Yii::$app->user->identity->id;
		$rolename = $auth->getRolesByUser($userId);
		$currentUrl = '/'.Yii::$app->requestedRoute;
		// var_dump($currentUrl);
		// var_dump($auth->checkAccess($userId, $currentUrl,$param));exit;
		if(!$rolename) {
			throw new ForbiddenHttpException;
		}
		foreach ($rolename as $key) {
			$permissionName = $auth->getPermissionsByRole($key->name);
			// if($child->type == UserAdmin::TYPE_ROLE) {
			// 	$chilPermision = $auth->getPermissionsByRole($key->name);

			// }
			if (array_key_exists($currentUrl, $permissionName)) {
			    return true;
			}else{
				throw new ForbiddenHttpException; 
			}
		}
        return true;
	}
}