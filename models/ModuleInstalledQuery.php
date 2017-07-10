<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ModuleInstalled]].
 *
 * @see ModuleInstalled
 */
class ModuleInstalledQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ModuleInstalled[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ModuleInstalled|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
