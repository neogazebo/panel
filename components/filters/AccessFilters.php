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
use app\models\AuthItem;
use app\models\AuthItemChild;

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

			$child = AuthItemChild::find()->where("parent = '$key->name'")->all();
			foreach ($child as $key => $value) {
				$getItemType = AuthItem::findOne($value->child);
				if($getItemType->type == 1) {
					$this->getChildRole($value->name);
				}
			}
			exit;
			$permissionName = $auth->getPermissionsByRole($key->name);
			var_dump($permissionName);exit;
			foreach ($permissionName as $key => $value) {
				echo $value;exit;
				if($value->type == UserAdmin::TYPE_ROLE) {
					echo $value->name;exit;
					$this->getChildRole($value->name);
				}
			}
			if (array_key_exists($currentUrl, $permissionName)) {
			    return true;
			}else{
				throw new ForbiddenHttpException; 
			}
		}
        return true;
	}

	public function getChildRole($role)
	{
		echo $role;exit;
		if(!empty($role)) {
			$auth = Yii::$app->authManager;
			$userId = Yii::$app->user->identity->id;
			$rolename = $auth->getRole($role);
			$currentUrl = '/'.Yii::$app->requestedRoute;
			if(!$rolename) {
				throw new ForbiddenHttpException;
			}
			foreach ($rolename as $key) {
				$permissionName = $auth->getPermissionsByRole($key->name);
				if($key->type == UserAdmin::TYPE_ROLE) {
					$chilPermision = $auth->getPermissionsByRole($key->name);
					foreach ($chilPermision as $value) {	
						$this->reUseGettingRole($value->name);
					}
				}
				if (array_key_exists($currentUrl, $permissionName)) {
				    return true;
				}else{
					throw new ForbiddenHttpException; 
				}
			}
	        return true;
		}
		
	}

	public function reUseGettingRole($role)
	{
		return $this->getChildRole($role);
	}
}