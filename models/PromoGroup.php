<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "promo_group".
 *
 * @property integer $id
 * @property integer $promo_id
 * @property integer $cat_id
 *
 * @property CustomCategory $cat
 * @property Promo $promo
 */
class PromoGroup extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promo_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promo_id', 'cat_id'], 'required'],
            [['promo_id', 'cat_id'], 'integer'],
            [
                ['cat_id'], 'exist', 'skipOnError' => true,
                'targetClass' => CustomCategory::className(), 'targetAttribute' => ['cat_id' => 'id']
            ],
            [
                ['promo_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Promo::className(), 'targetAttribute' => ['promo_id' => 'id']
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
            'promo_id' => 'Promo',
            'cat_id' => 'Category',
        ];
    }

    /**
     * @return CustomCategory
     */
    public function getCategory()
    {
        return $this->hasOne(CustomCategory::className(), ['id' => 'cat_id']);
    }

    /**
     * @return Promo
     */
    public function getPromo()
    {
        return $this->hasOne(Promo::className(), ['id' => 'promo_id']);
    }
}