<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "faq".
 *
 * @property integer $id
 * @property string $question
 * @property string $answer
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Faq extends ActiveRecord
{
    const TYPE_BUSINESS = 1;
    const TYPE_CONSUMER = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer', 'created_at'], 'required'],
            [['type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['question', 'answer'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];
    }

    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive'
        ];
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatusesArray();
        return $statuses[$this->status];
    }

    /**
     * @return array
     */
    public static function getTypesArray()
    {
        return [
            self::TYPE_BUSINESS => 'Business',
            self::TYPE_CONSUMER => 'Consumer'
        ];
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        $types = self::getTypesArray();
        return $types[$this->type];
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return date('m/d/Y H:i:s', $this->created_at);
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return date('m/d/Y H:i:s', $this->updated_at);
    }
}