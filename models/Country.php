<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $name
 * @property string $iso_code_2
 * @property string $iso_code_3
 *
 * @property State[] $states
 */
class Country extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'iso_code_2', 'iso_code_3'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['iso_code_2'], 'string', 'max' => 2],
            [['iso_code_3'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'iso_code_2' => 'ISO2 code',
            'iso_code_3' => 'ISO3 code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(State::className(), ['country_id' => 'id']);
    }
}
