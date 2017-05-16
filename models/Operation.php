<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "operation".
 *
 * @property integer $id
 * @property integer $bus_id
 * @property integer $day
 * @property string $open
 * @property string $end
 * @property integer $active
 *
 * @property Business $bus
 */
class Operation extends ActiveRecord
{
    const MONDAY = 0;
    const TUESDAY = 1;
    const WEDNESDAY = 2;
    const THURSDAY = 3;
    const FRIDAY = 4;
    const SATURDAY = 5;
    const SUNDAY = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bus_id', 'day', 'open', 'end'], 'required'],
            [['bus_id', 'day', 'active'], 'integer'],
            [['open', 'end'], 'safe'],
            ['day', 'in', 'range' => [
                self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY,
                self::SATURDAY, self::SUNDAY
            ]],
            [
                ['bus_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Business::className(),
                'targetAttribute' => ['bus_id' => 'id']
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
            'bus_id' => 'Business',
            'day' => 'Day',
            'open' => 'Open',
            'end' => 'End',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(Business::className(), ['id' => 'bus_id']);
    }

    /**
     * Finds operations by Business ID
     *
     * @param  string $bus_id
     * @return null|ActiveRecord
     */
    public static function findByBusId($bus_id)
    {
        return static::findAll(['bus_id' => $bus_id]);
    }
}
