<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "wish_list".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $promo_id
 * @property string  $added
 * @property integer $pricing_id
 * @property integer $standart_price
 * @property integer $promo_price
 * @property string  $promo_cat_title
 * @property string  $promo_srv_title
 * 
 * 
 * 
 * @property Promo $promo
 * @property Consumer $user
 */
class WishList extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wish_list';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_id', 'promo_id', 'standart_price', 'promo_price','promo_cat_title','promo_srv_title' ], 'required'],
                [['user_id', 'promo_id'], 'integer'],
                [['added'], 'safe'],
                [
                        ['promo_id'], 'exist', 'skipOnError' => true,
                        'targetClass' => Promo::className(),
                        'targetAttribute' => ['promo_id' => 'id']
                ],
                [
                        ['user_id'], 'exist', 'skipOnError' => true,
                        'targetClass' => Consumer::className(),
                        'targetAttribute' => ['user_id' => 'id']
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                'id' => 'ID',
                'user_id' => 'User',
                'promo_id' => 'Promo',
                'added' => 'Added',
                'pricing_id'=>'Pricing Id',
                'standart_price'=>'Service Standart Price',
                'promo_price'=>'Service Promo Price',
                'promo_cat_name'=>'Promo Category Name',
                'promo_srv_name'=>'Promo Service Name'
                
                
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromo() {
        return $this->hasOne(Promo::className(), ['id' => 'promo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(Consumer::className(), ['id' => 'user_id']);
    }

}
