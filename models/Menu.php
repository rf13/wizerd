<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Query;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $bus_id
 * @property string $title
 * @property integer $kw_group_id
 * @property integer $nonamed  flag that menu is nonamed
 * @property string $description
 * @property string $disclaimer
 * @property integer $sort
 *
 * @property Business $bus
 * @property CustomCategory[] $categories
 */
class Menu extends ActiveRecord
{

    const SCENARIO_UPDATE = 'update';
    const SCENARIO_WITH_TITLE = 'have_title';
    //const DEFAULT_MENU_NAME = 'Default Menu';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['bus_id'],
                'required'
            ],
            [
                ['title'],
                'required',
                'on' => self::SCENARIO_WITH_TITLE,
                'message' => '{attribute} can’t be blank.'
            ],
            [
                [
                    'id',
                    'bus_id',
                    'sort',
                    'nonamed'
                ],
                'integer'
            ],
            [
                'title',
                'string',
                'max' => 100
            ],
            [
                [
                    'description',
                    'disclaimer'
                ],
                'string',
                'max' => 5000
            ],
            [
                [
                    'description',
                    'disclaimer'
                ],
                'default'
            ],
            [
                ['bus_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Business::className(),
                'targetAttribute' => ['bus_id' => 'id']
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
            'bus_id' => 'Business',
            'title' => 'Section',
            'nonamed' => 'Nonamed Flag',
            'description' => 'Description',
            'disclaimer' => 'Disclaimer',
            'sort' => 'Sort Position'
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {


            if ($this->title == '') {
                $this->title = "Section not named " . $this->getNextNoname();
                $this->nonamed = $this->getNextNoname();
                $this->sort = 0;
                $text = '';

            } else {
                $this->nonamed = 0;
                $this->sort = $this->getMaxSort() + 1;
                $text = $this->title;
            }

        } else {
            if ($this->title == '') {
                $this->title = "Section not named " . $this->getNextNoname();
                $this->nonamed = $this->getNextNoname();
                $this->sort = 0;
                $text = '';
            } else {
                $this->nonamed = 0;
                if ($this->sort == 0) {
                    $this->sort = $this->getMaxSort() + 1;
                }
                $text = $this->title;
            }
        }
        //  if($text!='')
        if (!$key = Keyword::createKeywords($text, $this->kw_group_id)) {

            return false;
        } else {
            $this->kw_group_id = $key;
        }

        foreach ($this->categories as $category) {
            foreach ($category->services as $service) {
                foreach ($service->tiers as $tier) {
                    $tier->createTKW();
                    $tier->updateCharCount();
                }
            }
        }
        // exit;
        if (parent::beforeSave($insert)) {


            return true;
        }


        return false;
    }

    public function beforeDelete()
    {
        $oldKWG = KeywordGroup::find()
            ->where('id=:id', ['id' => $this->kw_group_id])
            ->one();
        if ($oldKWG !== null) {
            $oldKWG->delete();
        }

        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }



    /*
        public function beforeSave($insert) {
            if ($this->isNewRecord) {
                if ($this->title == '') {
                    $this->title = "no name menu ".$this->getNextNoname();
                    $this->nonamed = $this->getNextNoname();
                    $this->sort=0;


                } else {
                    $this->nonamed = 0;

                    $this->sort = $this->getMaxSort() + 1;
                }
            }

            if (parent::beforeSave($insert)) {


                return true;
            }


            return false;
        }
        */
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = [
            'id',
            'description',
            'disclaimer'
        ];

        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'bus_id']);
    }

    public function getTitleText()
    {
        return $this->hasOne(Text::className(), ['id' => 'title_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(CustomCategory::className(), ['menu_id' => 'id'])
            ->alias('category')
            ->orderBy(['category.sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesWithPromoOnly($id)
    {
        return $this->hasMany(CustomCategory::className(), ['menu_id' => 'id'])//->select()
        ->innerJoinWith('promoGroups')
            ->innerJoin('promo', 'promo.id = promo_group.promo_id')
            ->where('promo.id=:promo_id', ['promo_id' => $id])
            ->orderBy(['sort' => SORT_ASC])
            ->all();
    }

    /**
     * @param  int $menu
     * @return null|array
     */
    public static function getCategoriesById($menu, $includeNonamed = true)
    {
        if ($includeNonamed) {
            return CustomCategory::find()
                ->select([
                    'id',
                    'title'
                ])
                ->where('menu_id=:menu_id')
                ->addParams([':menu_id' => $menu])
                ->asArray()
                ->all();
        } else {
            return CustomCategory::find()
                ->select([
                    'id',
                    'title'
                ])
                ->where('menu_id=:menu_id')
                ->addParams([':menu_id' => $menu])
                ->andWhere('custom_category.title!=""')
                ->asArray()
                ->all();
        }
    }

    public function getMaxSort()
    {
        return (new Query())->from(self::tableName())
            ->where(['bus_id' => $this->bus_id])
            ->andWhere('sort>0')
            ->max('sort');
    }

    public function getMinSort()
    {
        return (new Query())->from(self::tableName())
            ->where(['bus_id' => $this->bus_id])
            ->andWhere('sort>0')
            ->min('sort');
    }


    private function getNextNoname()
    {
        return (new Query())->select('max(nonamed)')
            ->from(self::tableName())
            ->where(['bus_id' => $this->bus_id])
            ->scalar() + 1;
    }

    public function getMinCatSort()
    {
        return (new Query())->from(CustomCategory::tableName())
            ->where(['menu_id' => $this->id])
            ->andWhere('sort>0')
            ->min('sort');
    }

    /**
     * getting count of services for menu
     * @return type
     */
    public function countServices()
    {
        return (new Query())->select('count(custom_service.id)')
            ->from(self::tableName())
            ->leftJoin('custom_category', 'custom_category.menu_id=menu.id')
            ->leftJoin('custom_service', 'custom_service.cat_id=custom_category.id')
            ->where('menu.id=:id', ['id' => $this->id])
            ->scalar();

    }

    public function countNonamed()
    {
        return (new Query())->select('count(*)')
            ->from(self::tableName())
            ->where(['bus_id' => $this->bus_id])
            ->andWhere('nonamed>0')
            ->scalar();
    }
    /**
     * @return boolean

    public function setDefaultCatSrv() {
     *
     *
     * $defaults = Industry::getDefaults($this->ind_id);
     *
     * foreach ($defaults->categories as $cat) {
     * $category = new CustomCategory();
     * $category->menu_id = $this->getPrimaryKey();
     * $category->title = $cat->title;
     * $category->description = $cat->description;
     * $category->disclaimer = $cat->disclaimer;
     * //$category->type = CustomCategory::TYPE_DEFAULT;
     * $category->type = $cat->id;
     * if (!$category->save(false)) {
     * return false;
     * }
     *
     * // For services names= only names OR name=name/time(name part)
     * foreach ($cat->services as $srv) {
     * $service = new CustomService();
     * $service->cat_id = $category->getPrimaryKey();
     * $service->title = $srv->title;
     * //$service->type = CustomService::TYPE_DEFAULT;
     * $service->type = $srv->id;
     *
     * if (count(Template::getAvailableTemplatesByIndustry($this->ind_id)) == 1) {
     * $service->temp_id = Template::getAvailableTemplatesByIndustry($this->ind_id)[0]->id;
     * } else {
     * $service->temp_id = 1;
     * }
     * if (!$service->save(false)) {
     * return false;
     * }
     * }
     * if (($cat->srv_types == Category::SRV_TYPE_NAMETIME) || ($cat->srv_types == Category::SRV_TYPE_TIME)) {
     * foreach ($cat->timetable as $time) {
     * $service = new CustomService();
     * $service->cat_id = $category->getPrimaryKey();
     * $service->title = $srv->title;
     * }
     * }
     *
     * if ($cat->min_custom_srv_count > 0) {
     *
     * for ($i = 0; $i < $cat->min_custom_srv_count; $i++) {
     * $service = new CustomService();
     * $service->cat_id = $category->getPrimaryKey();
     * $service->title = '';
     * $service->type = CustomService::TYPE_FIXED;
     *
     * if (count(Template::getAvailableTemplatesByIndustry($this->ind_id)) == 1) {
     * $service->temp_id = Template::getAvailableTemplatesByIndustry($this->ind_id)[0]->id;
     * } else {
     * $service->temp_id = 1;
     * }
     *
     * if (!$service->save(false)) {
     * return false;
     * }
     * }
     * }
     * }
     * return true;
     * }
     *
     */

    /**
     * Creating  category foe services only in menu
     * @return boolean
     */
    public function setDefaultCat()
    {


        $category = new CustomCategory();
        $category->menu_id = $this->getPrimaryKey();
        $category->is_menu_cat = 1;
        $category->title = '';
        $category->sort = 0;
        //$category->title = "No title";
        //  $category->srv_title = CustomCategory::SRV_TITLE;
        //  $category->price_title = CustomCategory::PRICE_TITLE;

        //$category->description = $cat->description;
        // $category->disclaimer = $cat->disclaimer;
        //$category->type = CustomCategory::TYPE_DEFAULT;

        if (!$category->save(false)) {
            return false;
        }


        return true;
    }

    /**
     * @param  int $bus_id
     * @return null|array
     */
    public static function findByBusId($bus_id)
    {
        return static::find()
            ->where(['bus_id' => $bus_id])
            ->with('categories.services')
            ->orderBy(['sort' => SORT_ASC])
            ->all();
    }

    /**
     * Add menu to Business account.
     * @return boolean
     */
    public function addToBus()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$this->save(false)) {
                    $transaction->rollBack();

                    return false;
                }
                /*
                 * Set current menu as main
                  if ($this->main) $this->makeMain();
                 */
                if (!$this->setDefaultCat()) {
                    $transaction->rollBack();

                    return false;
                }

                $transaction->commit();

                return true;
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return false;
    }

    /**
     * Change description and disclaimer
     * @return boolean
     */
    public function changeDesc()
    {
        if ($this->validate()) {
            $menu = self::findOne($this->id);
            $menu->description = $this->description;
            $menu->disclaimer = $this->disclaimer;
            if ($menu->update() !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  boolean $up
     * @return boolean whether sortable were changed
     */
    public function changeSort($up)
    {
        $sort = $this->sort;
        if ($up) {
            $new_sort = (new Query())->select('MAX(sort) as new_sort')
                ->from(self::tableName())
                ->where([
                    '<',
                    'sort',
                    $this->sort
                ])
                ->andWhere('bus_id=:bus_id')
                ->addParams([':bus_id' => $this->bus_id]);
        } else {
            $new_sort = (new Query())->select('MIN(sort) as new_sort')
                ->from(self::tableName())
                ->where([
                    '>',
                    'sort',
                    $this->sort
                ])
                ->andWhere('bus_id=:bus_id')
                ->addParams([':bus_id' => $this->bus_id]);
        }
        /* @var Staff $emp */
        $menu = self::findOne([
            'bus_id' => $this->bus_id,
            'sort' => $new_sort
        ]);
        if (empty($menu)) {
            return false;
        }
        $new_sort = $menu->sort;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $menu->sort = $sort;
            if ($menu->save(false)) {
                $this->sort = $new_sort;
                if ($this->save(false)) {
                    $transaction->commit();

                    return true;
                }
            }
        } catch (Exception $e) {
            $transaction->rollback();
        }

        return false;
    }

    /**
     *  Returning true if menu have Active Promos
     * @return boolean
     */
    public function haveActivePromos()
    {
        $promos = Menu::find()
            ->select(['count(*)'])
            ->leftJoin('custom_category', 'custom_category.menu_id=menu.id')
            ->leftJoin('custom_service', 'custom_category.id=custom_service.cat_id')
            ->leftJoin('promo_group', 'promo_group.cat_id=custom_category.id')
            ->leftJoin('promo', 'promo_group.promo_id=promo.id')
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->where('menu.id=:id and promo.active=:active and promo.start <= date(now()) and promo.end >= date(now()) and tier.price is not null',
                [
                    'id' => $this->id,
                    'active' => Promo::STATUS_ACTIVE
                ])//->andWhere('custom_service.fill_status=:fill_status', ['fill_status' => CustomService::STATUS_FILLED])
//            ->andWhere(' (promo.start <now()+interval :interval hour) and(promo.end>=now())', ['interval' => Promo::PROMO_SHOW_BEFORE])
            ->scalar();
        /*
          $promos = Promo::find()
          ->select(['count(*)'])
          ->leftJoin('menu','menu.bus_id=promo.bus_id')
          ->where('promo.bus_id=:bus_id', ['bus_id' => $this->bus_id])
          ->andWhere('promo.active=:active', ['active' => Promo::STATUS_ACTIVE])
          ->andWhere('menu.id=:id',['id'=>$this->id])
          ->scalar();
         * 
         */
        if ($promos > 0) {
            return true;
        }

        return false;
    }

    public function getActivePromos()
    {
        return Promo::find()
            ->select(['promo.*'])
            ->leftJoin('custom_category', 'custom_category.menu_id=menu.id')
            ->leftJoin('custom_service', 'custom_category.id=custom_service.cat_id')
            ->leftJoin('promo_group', 'promo_group.cat_id=custom_category.id')
            ->leftJoin('promo', 'promo_group.promo_id=promo.id')
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->where('menu.id=:id and promo.active=:active and promo.start <= date(now()) and promo.end >= date(now()) and tier.price is not null',
                [
                    'id' => $this->id,
                    'active' => Promo::STATUS_ACTIVE
                ])
            ->all();
    }

    public function countFilledTiers()
    {
        $count = Menu::find()
            ->select(['count(*)'])
            ->leftJoin('custom_category', 'custom_category.menu_id=menu.id')
            ->leftJoin('custom_service', 'custom_service.cat_id=custom_category.id')
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->where('menu.id=:id', ['id' => $this->id])
            ->andWhere('tier.price is not null')
            ->scalar();
        if ($count > 0) {
            return $count;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'title',
            'description',
            'disclaimer',
        ];
    }
}