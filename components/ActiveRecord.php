<?php
namespace app\components;

use yii\db\ActiveRecord as BaseActiveRecord;

class ActiveRecord extends BaseActiveRecord
{
    public function crop($value)
    {
        $max = 20;
        if (strlen($value) > $max) {
            $value = substr($value, 0, $max) . '...';
        }

        return $value;
    }
}
