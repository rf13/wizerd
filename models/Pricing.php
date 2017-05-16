<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\db\Exception;

/**
 * This is the model class for table "pricing".
 *
 * @property integer $id
 * @property integer $ser_id
 * @property integer $id_attr_val
 * @property string $price
 * @property string $description
 *
 * @property CustomService $ser
 */
class Pricing extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'pricing';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                //[['ser_id', 'id_attr_val', 'price'], 'required'],
                [['ser_id', 'id_attr_val'], 'required'],
                [['ser_id', 'id_attr_val'], 'integer'],
                [['price'], 'number'],
                [['description'], 'string', 'max' => 2550],
                [['ser_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomService::className(), 'targetAttribute' => ['ser_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                'id' => 'ID',
                'ser_id' => 'Service',
                'id_attr_val' => 'Attribute',
                'price' => 'Price',
                'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSer() {
        return $this->hasOne(CustomService::className(), ['id' => 'ser_id']);
    }

    public function getAttributeValue() {
        return $this->hasOne(AttributeValue::className(), ['id' => 'id_attr_val']);
    }

    public static function getPricingByService($service_id) {
        return self::findAll(['ser_id' => $service_id]);
    }

    public function setDefaults($service_id, $template_id) {
        $service = CustomService::getServiceById($service_id);
        $template = Template::getTemplateById($template_id);
        $currentPricing = Pricing::getPricingByService($service_id);
        //find(['service_id'=>$service_id])->all();
      // $flag = false;
        if (!$currentPricing) {
            //print_r($template);
            if ($template->industry->additionalAttribute->display_type == 0) {
                $attrVal = $template->industry->additionalAttribute->attributeValues;
                foreach ($attrVal as $attr) {
                    $new = new Pricing();
                    $new->ser_id = $service_id;
                    $new->id_attr_val = $attr['id'];
                    // $new->price = 0;

                    if (!$new->save())
                        return false;
                }
            } else {
                for ($i = 0; $i < $template->count; $i++) {
                    $new = new Pricing();
                    $new->ser_id = $service_id;
                    $new->id_attr_val = 0;
                    //$new->price = 0;

                    if (!$new->save())
                        return false;
                }
            }
           // if ($flag)
          //      return true;
            return true;
        }
    }

    public static function clearPricingsByCustomService($service_id) {
        $models = self::find()->where('ser_id=:ser_id')->addParams([':ser_id' => $service_id])->all();
        foreach ($models as $model)
            $model->delete();
    }

}
