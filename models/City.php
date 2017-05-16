<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property integer $state_id
 * @property string $name
 * @property integer $active
 *
 * @property State $state
 * @property ZipCode[] $zipCodes
 */
class City extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['state_id', 'name', 'latitude', 'longitude'], 'required'],
            [
                [
                    'state_id',
                    'name'
                ],
                'required'
            ],
            [
                [
                    'state_id',
                    'active'
                ],
                'integer'
            ],
            // [['latitude', 'longitude'], 'number'],
            [
                ['name'],
                'string',
                'max' => 255
            ],
            [
                ['state_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => State::className(),
                'targetAttribute' => ['state_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state_id' => 'State',
            'name' => 'Name',
            //   'latitude' => 'Latitude',
            //  'longitude' => 'Longitude',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZipCodes()
    {
        return $this->hasMany(ZipCode::className(), ['city_id' => 'id']);
    }

    /**
     * @return null|State
     */
    public function getCurrentState()
    {
        return State::findOne(['id' => $this->state_id]);
    }


    public static function searchByName($name)
    {
        $name = strtolower(str_replace('-', ' ', $name));

        return self::find()
            ->where('name=:name', ['name' => $name])
            ->one();

    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'name',
        ];
    }
}
