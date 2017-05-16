<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $status
 * @property integer $created_at
 */
class Page extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug'], 'required'],
            [['description', 'meta_keywords', 'meta_description'], 'string'],
            [['status', 'created_at'], 'integer'],
            [['title', 'slug', 'meta_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'meta_title' => 'Meta title',
            'meta_keywords' => 'Meta keywords',
            'meta_description' => 'Meta description',
            'status' => 'Status',
            'created_at' => 'Created',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }


    /**
     * @param string $slug
     * @return Page|null
     */
    public static function findBySlug($slug)
    {
        return static::findOne([
            'slug' => $slug,
            'status' => self::STATUS_ACTIVE
        ]);
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
     * @return string
     */
    public function getCreated()
    {
        return date('m/d/Y H:i:s', $this->created_at);
    }
}