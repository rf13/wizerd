<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $ind_id
 * @property string $title
 * @property string $description
 * @property string $disclaimer
 * @property integer $display
 * @property integer $min_custom_srv_count
 * @property integer $srv_types  // types of Default services that can 
 *
 * @property Industry $ind
 * @property Service[] $services
 */
class Category extends ActiveRecord {

    const SRV_TYPE_NAME=0; //the default services names can be only a name and no seachable
    const SRV_TYPE_TIME=1; //the default services names = time from timetable
    const SRV_TYPE_NAMETIME=2; //the default services names can be name from Services or created by Timetable
    const SRV_TYPE_HAVETIME=3; //the default services names = name, but servisec must  have time as a field
    
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ind_id', 'title'], 'required'],
                [['ind_id', 'display'], 'integer'],
                [['title', 'description', 'disclaimer'], 'string', 'max' => 255],
                [['description', 'disclaimer'], 'default'],
                [
                        ['ind_id'], 'exist', 'skipOnError' => true,
                        'targetClass' => Industry::className(),
                        'targetAttribute' => ['ind_id' => 'id']
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                'id' => 'ID',
                'ind_id' => 'Industry',
                'title' => 'Title',
                'description' => 'Description',
                'display' => 'Display',
                'min_custom_srv_count' => 'Minimum of custom Services count'
        ];
    }

    /**
     * @return \app\models\Industry
     */
    public function getIndustry() {
        return $this->hasOne(Industry::className(), ['id' => 'ind_id']);
    }

    /**
     * @return null|array of app\models\Timetable
     */
    public function getTimetable() {
        return $this->hasMany(Timetable::className(), ['cat_id' => 'id']);
    }

    /**
     * @return null|array of app\models\Service
     */
    public function getServices() {
        return $this->hasMany(Service::className(), ['cat_id' => 'id']);
    }

    /**
     * @return null|array of app\models\Service
     */
    public function getСurServices() {
        return Service::find()->select(['id', 'title'])->where('cat_id=:cat_id')
                        ->addParams([':cat_id' => $this->id])->asArray()->all();
    }

    /**
     * @return array
     */
    public static function getStatusesArray() {
        return [
                self::STATUS_ACTIVE => 'active',
                self::STATUS_INACTIVE => 'inactive'
        ];
    }

    /**
     * @return string
     */
    public function getStatusName() {
        $statuses = self::getStatusesArray();
        return $statuses[$this->display];
    }

}
