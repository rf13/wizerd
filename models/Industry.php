<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "industry".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $disclaimer
 * @property string $search
 * @property integer $sort_order
 *
 * @property Business[] $businesses
 * @property Category[] $categories
 */
class Industry extends ActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const TEMPLATE_ONE = 0;
    const TEMPLATE_MULTIPLE = 1;
    const TIME_NOT = 0;
    const TIME_USE = 1;
    const TITLE_HIDE = 0;
    const TITLE_SHOW = 1;
    const DESC_OPTIONAL = 1;
    const DESC_REQUIRED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'industry';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['title'], 'required'],

                [['title', 'description', 'disclaimer','search'], 'string', 'max' => 255],
                [['description', 'disclaimer'], 'default']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                'id' => 'ID',
                'title' => 'Title',
                'description' => 'Description',
                'search' => 'search string',
                'sort_order' => 'sort order',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories() {
        return $this->hasMany(Category::className(), ['ind_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu() {
        return $this->hasMany(Menu::className(), ['ind_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalAttribute() {
        return $this->hasOne(AdditionalAttribute::className(), ['id' => 'addit_attr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceTemplate() {
        return $this->hasMany(Template::className(), ['ind_id' => 'id']);
    }

    /**
     * @return null|array
     */
    public static function getIndustryTitles() {
        return Industry::find()->select(['id', 'title'])->orderBy('sort_order')->asArray()->all();
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
     * @return array
     */
    public static function getTemplateArray() {
        return [
                self::TEMPLATE_ONE => 'one price',
                self::TEMPLATE_MULTIPLE => 'multiple'
        ];
    }

    /**
     * @return array
     */
    public static function getTimeArray() {
        return [
                self::TIME_USE => 'with time',
                self::TIME_NOT => 'without time'
        ];
    }

    /**
     * @return array
     */
    public static function getTitleShowArray() {
        return [
                self::TITLE_SHOW => 'yes',
                self::TITLE_HIDE => 'not'
        ];
    }

    /**
     * @return array
     */
    public static function getDescReqArray() {
        return [
                self::DESC_REQUIRED => 'yes',
                self::DESC_OPTIONAL => 'not'
        ];
    }

    /**
     * @return string
     */
    public function getStatusName() {
        $statuses = self::getStatusesArray();
        return $statuses[$this->display];
    }

    /**
     * @return string
     */
    public function getTemplate() {
        $templates = self::getTemplateArray();
        return $templates[$this->price];
    }

    /**
     * @return string
     */
    public function getDuration() {
        $times = self::getTimeArray();
        return $times[$this->time];
    }

    /**
     * @return string
     */
    public function getShow() {
        $show = self::getTitleShowArray();
        return $show[$this->srv_title];
    }

    /**
     * @return string
     */
    public function getRequire() {
        $req = self::getDescReqArray();
        return $req[$this->srv_desc];
    }
    public static function searchBySearchString($str){

        $str=strtolower($str);
        return self::find()->where('search=:search',['search'=>$str])->one();
    }
    /**
     * @param int $id
     * @return null|mixed
     */
    public static function getDefaults($id) {
        return Industry::find($id)->with('categories.services')
                        ->where('id=:id')->addParams([':id' => $id])->one();
    }

    public function searchByZipcode($zip) {
        $latitude = ZipCode::find()->where('zip = :zip ')->addParams([':zip' => $zip])->one()->latitude;
        $longitude = ZipCode::find()->where('zip = :zip ')->addParams([':zip' => $zip])->one()->longitude;

        $result = Industry::find()
                ->leftJoin('menu', 'industry.id=menu.ind_id')
                ->leftJoin('business', 'business.id=menu.bus_id')
                ->leftJoin('zip_code', 'zip_code.id=business.zip_id')
                ->leftJoin('custom_category', 'custom_category.menu_id=menu.id')
                ->leftJoin('custom_service','custom_service.cat_id=custom_category.id')
               
                ->where('ABS(business.latitude- :f_latitude)<:delta', ['f_latitude' => $latitude, 'delta' => ZipCode::BASE_DIST_DELTA])
                ->andWhere('ABS(business.longitude- :f_longitude)<:delta', ['f_longitude' => $longitude, 'delta' => ZipCode::BASE_DIST_DELTA])
                ->andWhere('((custom_service.type>0) and(custom_service.fill_status=1))')
                ->groupBy('industry.id')
                ->all();
        return $result;
    }
   public function searchBy($id) {
       $result = Industry::find()->where('id=:id',['id'=>$id])->one();
       
       return $result;
   }
}
