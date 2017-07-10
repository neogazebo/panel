<?php

    namespace app\components\widgets\traits;

    use Yii;

    use Carbon\Carbon;

    trait ClassProcessorTrait
    {
    	public function processClass($class_names)
        {
            $class = '';

            if($class_names)
            {
                if(is_array($class_names))
                {
                    $class_imploded = implode(' ', $class_names);
                    $class = $class_imploded;
                }
            }

            return $class;
        }
    }
