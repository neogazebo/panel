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
		if(!$rolename) {
			throw new ForbiddenHttpException;
		}

		foreach ($rolename as $key) {
			$child = AuthItemChild::find()->where("parent = '$key->name'")->all();
			foreach ($child as $keyin => $value) {
				$getItemType = AuthItem::findOne($value->child);
				if($getItemType->type == 1) {
					$this->getChildRole($getItemType->name);
				}
			}
			$permissionName = $auth->getPermissionsByRole($key->name);
			foreach ($permissionName as $keyinto => $value) {
				if($value->type == UserAdmin::TYPE_ROLE) {
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
		if(!empty($role)) {
			$auth = Yii::$app->authManager;
			$userId = Yii::$app->user->identity->id;
			$rolename = AuthItem::findOne($role);
			$currentUrl = '/'.Yii::$app->requestedRoute;
			$permissionName = $auth->getPermissionsByRole($rolename->name);
			if (array_key_exists($currentUrl, $permissionName)) {
			    return true;
			}else{
				throw new ForbiddenHttpException; 
			}
			if($permissionName->type == UserAdmin::TYPE_ROLE) {
				$this->reUseGettingRole($value->name);
			}
	        return true;
		}
		
	}

	public function reUseGettingRole($role)
	{
		return $this->getChildRole($role);
	}
}