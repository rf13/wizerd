<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "service".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property string $title
 * @property string $description
 *
 * @property Menu[] $menus
 * @property Promo[] $promos
 * @property Category $cat
 */
class Service extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'title'], 'required'],
            [['cat_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            [
                ['cat_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['cat_id' => 'id']
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
            'cat_id' => 'Category',
            'title' => 'Title',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['serv_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromos()
    {
        return $this->hasMany(Promo::className(), ['serv_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Category::className(), ['id' => 'cat_id']);
    }
}
