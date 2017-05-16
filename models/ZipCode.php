<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "zip_code".
 *
 * @property integer $id
 * @property string $zip
 * @property integer $city_id
 * @property double $latitude
 * @property double $longitude
 * @property double $timezone
 * @property integer $active
 *
 * @property Business[] $businesses
 * @property City $city
 */
class ZipCode extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const BASE_DIST_DELTA = 0.3; // eq 20miles (33km)
    //const BASE_DIST_DELTA=.6; // eq 40miles (66km)
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zip_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'zip',
                    'city_id'
                ],
                'required'
            ],
            [
                [
                    'city_id',
                    'active'
                ],
                'integer'
            ],
            [
                ['zip'],
                'string',
                'max' => 255
            ],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::className(),
                'targetAttribute' => ['city_id' => 'id']
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
            'zip' => 'Zip code',
            'city_id' => 'City',
            'active' => 'Active',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'timezone' => 'timezone',

        ];
    }

    /**
     * @return array|null
     */
    public static function getPanelInfo()
    {
        $busQuery = (new Query())->select('COUNT(*)')
            ->from('auth_assignment')
            ->where([
                'item_name' => User::ROLE_BUSN
            ]);
        $conQuery = (new Query())->select('COUNT(*)')
            ->from('auth_assignment')
            ->where([
                'item_name' => User::ROLE_CONS
            ]);
        $actQuery = (new Query())->select('COUNT(*)')
            ->from('zip_code')
            ->where([
                'active' => self::STATUS_ACTIVE
            ]);
        $reqQuery = (new Query())->select('COUNT(*)')
            ->from('business')
            ->where([
                'zip_notice' => null
            ]);

        return (new Query())->select([
            'bus' => $busQuery,
            'con' => $conQuery,
            'act' => $actQuery,
            'req' => $reqQuery
        ])
            ->one();
    }

    /**
     * @param string $zip Zip code
     * @return null|ZipCode
     */
    public static function getZipAddress($zip)
    {
        return self::find()
            ->where(['zip' => $zip])
            ->with('city.state')
            ->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesses()
    {
        return $this->hasMany(Business::className(), ['zip_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return null|City
     */
    public function getCurrentCity()
    {
        return City::findOne(['id' => $this->city_id]);
    }

    /**
     * @param string $zip Zip code to exist
     * @return int
     */
    public function checkZipCode($zip)
    {
        return self::find()
            ->where([
                'zip' => $zip,
                'active' => 1
            ])
            ->count();
    }

    /**
     * @param string $zip Zip code
     * @return null|ZipCode
     */
    public function getZipCode($zip)
    {
        return self::find()
            ->where(['zip' => $zip])
            ->one();
    }


    /**
     * @param string $zip Zip code to exist
     * @return int
     */
    public static function isActiveZipCode($zip)
    {
        $count = self::find()
            ->where([
                'zip' => $zip,
                'active' => 1
            ])
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'zip',
            'latitude',
            'longitude',
            'timezone',
        ];
    }
}
