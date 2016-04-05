<?php

namespace app\models;

/**
 * This model is an extend from yii\db\ActiveRecord.
 *
 * @author febri@ebizu.com
 */
use Yii;
use yii\db\ActiveRecord;

class EbizuActiveRecord extends ActiveRecord
{
    /**
     * Returns a value indicating whether the named attribute has been changed.
     * @param string $name the name of the attribute.
     * @param bool $identical whether the comparison of new and old value is made for
     * identical values using `===`, defaults to `true`. Otherwise `==` is used for comparison.
     * This parameter is available since version 2.0.4.
     * @return bool whether the attribute has been changed
     */
    public function isAttributeChanged($name, $identical = true)
    {
        if (!empty($this->{$name}) && !empty($this->getOldAttribute($name)))
        {
            if ($identical)
            {
                return $this->{$name} !== $this->getOldAttribute($name);
            }
            else
            {
                return $this->{$name} != $this->getOldAttribute($name);
            }
        }
        else
        {
            return !empty($this->{$name}) || !empty($this->getOldAttribute($name));
        }
    }

    /**
     * author : febri@ebizu
     * @param array $manualy_checked_attr the list of the attribute that need to mannually checked
     * this attribute needs to be checked mannually because the data type on the database is integer
     * example '20' !== 20
     * @param array $date_attr the list of date attribute
     * @param array $skip_checked the list of the attribute that not checked
     * @return array the changed attribute values (label-value pairs)
     */
    public function getChangedAttribute($manualy_checked_attr = [], $date_attr = [], $skip_checked = [])
    {
        $dirty_attrs = $this->getDirtyAttributes();

        if (!empty($manualy_checked_attr))
        {
            foreach ($manualy_checked_attr as $attr)
            {
                $is_attr_changed = $this->isAttributeChanged($attr, false);
                if (!$is_attr_changed)
                {
                    unset($dirty_attrs[$attr]);
                }
            }
        }


        if (!empty($date_attr))
        {
            foreach ($date_attr as $attr)
            {
                $is_date_changed = strtotime($this->{$attr}) != $this->getOldAttribute($attr);
                if (!$is_date_changed)
                {
                    unset($dirty_attrs[$attr]);
                }
            }
        }


        if (!empty($skip_checked))
        {
            foreach ($skip_checked as $attr)
            if (array_key_exists($attr, $dirty_attrs))
            {
                unset($dirty_attrs[$attr]);
            }
        }

        if (!empty($dirty_attrs))
        {
            foreach ($dirty_attrs as $key => $val)
            {
                $dirty_attrs[$this->getAttributeLabel($key)] = $val;
                unset($dirty_attrs[$key]);
            }
        }

        return $dirty_attrs;
    }

    /**
     * function to encode to utf8
     * @param type $str
     * @return string
     */
    public static function encodeUTF8($str)
    {
        $isUTF8 = mb_detect_encoding($str, 'UTF-8', TRUE);
        if ($isUTF8 === false)
        {
            return mb_convert_encoding($str, 'UTF-8');
        }

        return $str;
    }

    public function truncate($text, $max = 50)
    {
        $tok = strtok($text, ' ');
        $string = '';

        while ($tok !== false && strlen($string) < $max)
        {
            if (strlen($string) + strlen($tok) <= $max)
            {
                $string .= $tok . ' ';
            }
            else
            {
                break;
            }

            $tok = strtok(' ');
        }

        return trim($string) . '...';
    }

}
