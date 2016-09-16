<?php

    namespace app\components\helpers;

    use Yii;
    use app\models\AuthItem;
    
    class PermissionHelper
    {
        public function processPermissions($module, $permission)
        {
            $view = false;

            $user = Yii::$app->user;
            $is_super_user = Yii::$app->user->identity->superuser == 1 ? true : false;

            $permission = AuthItem::find()->getPermissions($module, $permission);

            if(!$permission)
            {
                return true;
            }
            else
            {
                //if($permission && ($user->can($permission) || $is_super_user))
                if($permission && ($user->can($permission)))
                {
                    return true;
                }
            }

            return false;
        }

        public function getConfig()
        {
            $config = require('../config/rbac.php');
            return $config;
        }

        public function setRbacView($non_rbac_view, $rbac_view)
        {
            $view_filename = $non_rbac_view;

            $rbac_config = $this->getConfig();

            if($rbac_config['enable'])
            {
                $view_filename = $rbac_view;
            }

            return $view_filename;
        }

        public function checkRbac()
        {
            $rbac_config = Yii::$app->permission_helper->getConfig();

            if ($rbac_config['enable'])
            {
                return true;
            }

            return false;
        }
    }
