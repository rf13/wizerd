<?php

namespace app\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "additional_attribute".
 *
 * @property integer $id
 * @property string $title
 * @property integer $display_type  0-as input  1 -as dropbox
 * 
 * @property AttributeValue[] $attributeValues
 */
class AdditionalAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'additional_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','display_type'], 'required'],
            [['title'], 'string', 'max' => 255],          
            [['display_type'], 'integer'],          
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
            'display_type' => 'Display Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeValues()
    {
        return $this->hasMany(AttributeValue::className(), ['attr_id' => 'id']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry()
    {
        return $this->hasMany(Industry::className(), ['addit_attr_id' => 'id']);
    }
    public static function getAdditionalAttributeByMenu($menu_id){
           return (new Query())->select([
                                'additional_attribute.id as id',
                                'additional_attribute.title as title'])
                        ->from(AdditionalAttribute::tableName())
                        ->leftJoin(Industry::tableName(), 'industry.addit_attr_id = additional_attribute.id')
                        ->leftJoin(Menu::tableName(), 'menu.ind_id = industry.id')
                        ->where('menu.id=:menu_id')->addParams([':menu_id' => $menu_id])
                        ->one();
    }
}