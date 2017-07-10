<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\helpers;
use yii\helpers\BaseHtml;

/**
 * Html provides a set of static methods for generating commonly used HTML tags.
 *
 * Nearly all of the methods in this class allow setting additional html attributes for the html
 * tags they generate. You can specify for example. 'class', 'style'  or 'id' for an html element
 * using the `$options` parameter. See the documentation of the [[tag()]] method for more details.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Html extends BaseHtml
{
    public static function listData($models, $valueField, $textField, $groupField = '')
    {
        $listData = array();
        if ($groupField === '') {
            foreach ($models as $model) {
                $value = self::value($model, $valueField);
                $text = self::value($model, $textField);
                $listData[$value] = $text;
            }
        } else {
            foreach ($models as $model) {
                $group = self::value($model, $groupField);
                $value = self::value($model, $valueField);
                $text = self::value($model, $textField);
                if ($group === null)
                    $listData[$value] = $text;
                else
                    $listData[$group][$value] = $text;
            }
        }
        return $listData;
    }

    public static function value($model, $attribute, $defaultValue = null)
    {
        if (is_scalar($attribute) || $attribute === null)
            foreach (explode('.', $attribute) as $name) {
                if (is_object($model) && isset($model->$name))
                    $model = $model->$name;
                elseif (is_array($model) && isset($model[$name]))
                    $model = $model[$name];
                else
                    return $defaultValue;
            } else
                return call_user_func($attribute, $model);

        return $model;
    }

}
