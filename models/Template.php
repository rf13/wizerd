<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "template".
 *
 * @property integer $id
 * @property integer $count
 * @property integer $desc_type
 * @property integer $ind_id   Id of Industry
 * @property integer $display_addit_attr Flag display additional Arrtibute in template
 *
 * @property CustomService[] $customServices
 */
class Template extends \yii\db\ActiveRecord
{
    const TYPE_ALONG = 0;
    const TYPE_APART = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['count'], 'required'],
            [['count', 'desc_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'count' => 'Count fields',
            'desc_type' => 'Description type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomServices()
    {
        return $this->hasMany(CustomService::className(), ['temp_id' => 'id']);
    }
    
   /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'ind_id']);
    }
    /**
     * @return array
     */
    public static function getDescArray()
    {
        return [
            self::TYPE_ALONG => 'general description',
            self::TYPE_APART => 'description for each item'
        ];
    }

    /**
     * @return string
     */
    public function getDescType()
    {
        $types = self::getDescArray();
        return $types[$this->desc_type];
    }
    
    
    
    public function getTemplatesByMenu($menu_id)
    {
        return (new Query())->select(['template.id as t_id',
                'template.count as t_count',
                'template.desc_type as t_desc_type',
                'template.display_addit_attr as t_disp_add_atr',
               // 'additional_attribute.title as type_title',
                'additional_attribute.display_type as display_type'])
            ->from(Template::tableName())
            //->where('bus_id=:bus_id')->addParams([':bus_id' => $this->id])
            ->leftJoin(Industry::tableName(), 'industry.id = template.ind_id')
            ->leftJoin(Menu::tableName(), 'industry.id = menu.ind_id')
            ->where('menu.id=:menu_id')->addParams([':menu_id' => $menu_id])
            ->leftJoin(AdditionalAttribute::tableName(), 'additional_attribute.id = industry.addit_attr_id')
            ->all();
    }
    public static function getTemplateById($id){
     return self::findOne(['id'=>$id]);
    }
    public static function getAvailableTemplatesByIndustry($id)
    {
     return self::findAll(['ind_id'=>$id]);   
    }
}