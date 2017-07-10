<?php

namespace app\modules\rbac\processors;

use Yii;
use app\modules\bases\BaseProcessor;
use app\models\AuthItem;

class RbacSaveModulePermissionsProcessor extends BaseProcessor
{
    public function process()
    {
        try {
            $module = Yii::$app->request->post('module');
            $permissions = Yii::$app->request->post($module);

            AuthItem::find()->saveModulePermissions($module, $permissions);

            return $this->json_helper->jsonOutput(0, 'success', null);
        } catch(\Exception $e) {
            return $this->json_helper->jsonOutput(self::SYSTEM_ERROR_CODE, 'error', $e->getMessage());
        }
    }
}
