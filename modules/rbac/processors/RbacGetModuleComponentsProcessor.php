<?php

    namespace app\modules\rbac\processors;

    use Yii;
    use app\modules\bases\BaseProcessor;

    class RbacGetModuleComponentsProcessor extends BaseProcessor
    {
        public function process()
        {
            try {
                $module_name = Yii::$app->request->post('module');

                $module_components = Yii::$app->mod_component_helper->getComponents($module_name);

                $output = Yii::$app->controller->renderPartial('/partials/res_table', [
                    'module_name' => $module_name,
                    'module_components' => $module_components
                ]);

                return $this->json_helper->jsonOutput(0, 'success', null, $output);
            } catch(\Exception $e) {
                return $this->json_helper->jsonOutput(self::SYSTEM_ERROR_CODE, 'error', $e->getMessage());
            }
        }
    }
