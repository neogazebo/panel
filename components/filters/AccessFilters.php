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
	public $allowActions = ['site/*'];

	public function init()
	{
		// parent::init();

		// $auth = Yii::$app->authManager;
		// $user = Yii::$app->user;
		// $obj = Yii::$app->controller;
		// $id = $obj->id;
		// $actionId = $obj->getRoute();
		// if ($user->can('/'.$actionId)) {
		// 	return true;
		// }
		// do {
		// 	if ($user->can('/'. ltrim($obj->getUniqueId() . '/*', '/'))) {
		// 		return true;
		// 	}
		// 	$obj = $obj->module;
		// } while ($obj !== null);

		// foreach ($this->allowActions as $action) {
		// 	$action = (substr($action, 0, 1) == '/') ? $action : '/' . $action;
		// 	if ($action === '*' or $action === '*/*') {
		// 		return true;
		// 	} else if (substr($action, -1) === '*') {
		// 		$length = strlen($action) - 1;
		// 		return (substr($action, 0, $length) == substr($actionId, 0, $length));
		// 	} else {
		// 		return ($action == $actionId);
		// 	}
		// }

		// $this->denyAccess($user);
  //       return false; 
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