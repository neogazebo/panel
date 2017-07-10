<?php

    namespace app\components\helpers;

    use Yii;

    class ArrayHelper
    {
    	public function sortUniqueByValue($array, $key)
        {
            $temp_array = array();

            foreach ($array as &$v) 
            {
                if (!isset($temp_array[$v[$key]]))
                {
                    $temp_array[$v[$key]] =& $v;
                }
            }

           $array = array_values($temp_array);

           return $array;
        }
    }