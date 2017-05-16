<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "video".
 *
 * @property integer $id
 * @property integer $bus_id
 * @property string $url
 * @property string $title
 * @property integer $main
 *
 * @property Business $bus
 */
class Video extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bus_id', 'url'], 'required'],
            [['bus_id', 'main'], 'integer'],
            [['url', 'title'], 'string', 'max' => 255],
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
            'url' => 'URL',
            'title' => 'Title',
            'main' => 'Is main',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(Business::className(), ['id' => 'bus_id']);
    }
}
