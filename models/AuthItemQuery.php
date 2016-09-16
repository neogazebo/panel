<?php

namespace app\models;

use Yii;
use app\models\AuthRule;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;

class AuthItemQuery extends ActiveQuery
{
    const TYPE_ROLE = 1;

    const ACTIVE_STATUS = 1;
    
    const INACTIVE_STATUS = 0;
    
	public function getList()
	{
		$this->where('type = :type AND status = :status', [
			':type' => self::TYPE_ROLE,
			':status' => self::ACTIVE_STATUS
		]);
		$this->orderBy('from_unixtime(created_at) DESC');
		return $this;
	}

	public function getListPermission($name)
	{
		$this->andWhere('name != :name',[
				':name' => $name
		]);
		$this->orderBy('date(from_unixtime(created_at)) DESC');
		return $this;
	}

	public function getModulePermissions($module)
	{
		return $this->select(['name','description'])->where('data = :module', [':module' => $module])->all();
	}

	public function savePermissions($name, $type, $description, $data, $status)
	{
		// check exisiting data
		$existing_permission = AuthItem::findOne(['description' => $description]);

		// add or update
		if($name != '')
		{
			if($existing_permission)
			{
				// update name
				if($name != $existing_permission->name)
				{
					$existing_permission->name = $name;
					$existing_permission->update();
				}
			}
			else
			{
				// add new item
				$auth_item = new AuthItem();
				$auth_item->name = $name;
				$auth_item->type = $type;
				$auth_item->description = $description;
				$auth_item->data = $data;
				$auth_item->status = $status;
				$auth_item->save();
			}
		}
		// delete
		else 
		{
			if($existing_permission)
			{
				$existing_permission->delete();
			}
		}
	}

	public function saveModulePermissions($module, $permissions)
	{
		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			if(isset($permissions['Menus']))
			{
				foreach($permissions['Menus'] as $key => $menu_permission)
				{
					$description = $module . "[Menus]" . "[$key]";
					$this->savePermissions($menu_permission, 2, $description, $module, 1);
				}
			}

			if(isset($permissions['Pages']))
			{
				foreach($permissions['Pages'] as $key => $menu_permission)
				{
					$description = $module . "[Pages]" . "[$key]";
					$this->savePermissions($menu_permission, 2, $description, $module, 1);
				}
			}

			if(isset($permissions['Page_Components']))
			{
				foreach($permissions['Page_Components'] as $key => $menu_permission)
				{
					$description = $module . "[Page_Components]" . "[$key]";
					$this->savePermissions($menu_permission, 2, $description, $module, 1);
				}
			}

			$transaction->commit();
		}
		catch(\Exception $e)
		{
			$transaction->rollBack();
			return $e->getMessage();
		}
	}

	public function getPermissions($module_name, $component_name)
    {
        $permission_name = null;

        $module_permissions = $this->getModulePermissions($module_name);

        if($module_permissions)
        {
            foreach($module_permissions as $permission)
            {
                if($permission->description == $component_name)
                {
                    $permission_name = $permission->name;
                }
            }
        }


        return $permission_name;
    }
}