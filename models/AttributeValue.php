<?php

namespace app\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "attribute_value".
 *
 * @property integer $id
 * @property integer $attr_id
 * @property string $value
 *
 * @property AdditionalAttribute $attr
 */
class AttributeValue extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'attribute_value';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['attr_id', 'value'], 'required'],
                [['attr_id'], 'integer'],
                [['value'], 'string', 'max' => 255],
                [
                        ['attr_id'], 'exist', 'skipOnError' => true,
                        'targetClass' => AdditionalAttribute::className(), 'targetAttribute' => ['attr_id' => 'id']
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                'id' => 'ID',
                'attr_id' => 'Attr ID',
                'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttr() {
        return $this->hasOne(AdditionalAttribute::className(), ['id' => 'attr_id']);
    }
    
       /**
     * @return \yii\db\ActiveQuery
     */
    public function getPricing() {
        return $this->hasMany(Pricing::className(), ['id_attr_val' => 'id']);
    }

    /**
     * Gettting values for prices templates by menu_id
     * @param type $menu_id
     */
    public static function getAttributeValuesByMenu($menu_id) {
        return (new Query())->select([
                                'attribute_value.id as value_id',
                                'attribute_value.value as value'])
                        ->from(AttributeValue::tableName())
                        ->leftJoin(AdditionalAttribute::tableName(), 'additional_attribute.id = attribute_value.attr_id')
                        ->leftJoin(Industry::tableName(), 'industry.addit_attr_id = additional_attribute.id')
                        ->leftJoin(Menu::tableName(), 'menu.ind_id = industry.id')
                        ->where('menu.id=:menu_id')->addParams([':menu_id' => $menu_id])
                        ->all();
    }

}
