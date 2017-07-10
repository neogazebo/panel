<?php

namespace app\components\helpers;

/**
 * Description of Permission
 *
 * @author febri
 */

use yii\base\Component;
use Yii;
use yii\base\InvalidConfigException;

class Encode extends Component
{
    /**
     * 
     * @param string $string
     * @return string encoded string
     */
    public function utf8($string)
    {
	$isUTF8 = mb_detect_encoding($string, 'UTF-8', TRUE);
        if ($isUTF8 === false)
        {
            return mb_convert_encoding($string, 'UTF-8');
        }

        return $string;
    }
}
