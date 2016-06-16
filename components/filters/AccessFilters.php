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
// use app\models\User as UserAdmin;
// use app\components\helpers\GetRoutes;
// use app\models\AuthItem;
// use app\models\AuthItemChild;

/**
* 
*/
class AccessFilters extends ActionFilter
{
	// public $allowActions = ['site/*'];

 	public function init()
 	{
		parent::init();
		$auth = Yii::$app->authManager;
		$user = Yii::$app->user;
		$obj = Yii::$app->controller;
		$actionId = $obj->getRoute();
		$permisionName = $auth->getPermissionsByUser($user->id);
		foreach ($permisionName as $value) {
			if ($user->can('/'.$actionId)) {
				return true;
		}

		do {
			if ($user->can('/'. ltrim($obj->getUniqueId() . '/*', '/'))) {
			return true;
		}

		$obj = $obj->module;
			} while ($obj !== null);
			$this->denyAccess($user);
	        return false;
 		}
  
	}

	public static function checkRoute()
	{
		$auth = Yii::$app->authManager;
		$user = Yii::$app->user;
		$roles = $auth->getRolesByUser($user->id);
		if (!empty($roles)) {
			foreach ($roles as $role) {
				return $role->name;exit;
			}
		}
		$this->denyAccess($user);
		return false;

	}

	protected function denyAccess($user)
 	{
		if ($user->getIsGuest()) {
			$user->loginRequired();
		} else {
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
		}
 	}

}