<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\db\Exception;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "custom_category".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $is_menu_cat
 * @property string $title
 * @property integer $kw_group_id
 * @property string $description
 * @property string $disclaimer
 * @property string $srv_title
 * @property integer $srv_title_vis
 * @property string $price_tile
 * @property integer $price_title_vis
 * @property integer $sort
 * @property integer $created_at
 *
 *
 * @property Menu $menu
 * @property CustomService[] $services
 */
class CustomCategory extends ActiveRecord
{

    // const TYPE_DEFAULT = 1; //default category that creates by Admin
    //  const TYPE_CUSTOM = 0; //custom category that creates by Business
    const SCENARIO_DYNAMIC = 'dynamic';
    const SRV_TITLE = 'Service';
    const PRICE_TITLE = 'Price';
    const NONAME_CATEGORY = 'no category';

    public $category;
    public $fields = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'menu_id',
                'required'
            ],
            //[['title', 'sort', 'created_at'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [
                'category',
                'required',
                'on' => self::SCENARIO_DYNAMIC,
                'message' => '{attribute} can’t be blank.'
            ],
            [
                'fields',
                'safe',
                'on' => self::SCENARIO_DYNAMIC
            ],
            [
                [
                    'menu_id',
                    'sort',
                    'created_at',
                    'srv_title_vis',
                    'price_title_vis',
                    'is_menu_cat'
                ],
                'integer'
            ],
            [
                'created_at',
                'safe'
            ],
            [
                'title',
                'string',
                'max' => 100
            ],
            [
                'srv_title',
                'default',
                'value' => self::SRV_TITLE
            ],
            [
                'price_title',
                'default',
                'value' => self::PRICE_TITLE
            ],
            [
                [
                    'description',
                    'disclaimer',
                    'category'
                ],
                'string',
                'max' => 2550
            ],
            [
                [
                    'description',
                    'disclaimer'
                ],
                'default'
            ],
            [
                ['menu_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Menu::className(),
                'targetAttribute' => ['menu_id' => 'id']
            ],
            //['fields', 'validateFields', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function validateFields($attribute, $params)
    {
        foreach ($this->$attribute as $custom_field) {
            if (empty($custom_field)) {
                $this->addError($custom_field, 'Category name can not be empty');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Section',
            'title' => 'Title',
            'description' => 'Description',
            'disclaimer' => 'Disclaimer',
            'srv_title' => 'Service Head Title',
            'price_titel' => 'Price Head title',
            'sort' => 'Sort',
            'created_at' => 'Created',
            'category' => 'Category',
            'fields' => 'Category'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {

        // if(($this->is_menu_cat==0)&&(strlen($this->title)>0))
        if (!$key = Keyword::createKeywords($this->title, $this->kw_group_id)) {
            return false;
        } else {
            $this->kw_group_id = $key;
        }

        foreach ($this->services as $service) {
            foreach ($service->tiers as $tier) {
                $tier->createTKW();
                $tier->updateCharCount();
            }
        }

        $this->srv_title = CustomCategory::SRV_TITLE;
        $this->price_title = CustomCategory::PRICE_TITLE;
        if (parent::beforeSave($insert)) {
            if (($this->isNewRecord) && (!isset($this->sort))) {
                $this->sort = $this->getMaxSort() + 1;
            }

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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    public function getTitleText()
    {
        return $this->hasOne(Text::className(), ['id' => 'title_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSrvFields()
    {
        return $this->hasMany(Field::className(), ['cat_id' => 'id'])
            ->alias('field')
            ->orderBy(['field.sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromoGroups()
    {
        return $this->hasMany(PromoGroup::className(), ['cat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(CustomService::className(), ['cat_id' => 'id'])
            ->alias('service')
            ->orderBy(['service.sort' => SORT_ASC]);
    }

    /**
     * @return array

    public static function getTypesArray() {
     * return [
     * self::TYPE_DEFAULT => 'default',
     * self::TYPE_CUSTOM => 'custom'
     * ];
     * }
     */
    /**
     * @return string

    public function getTypeName() {
     * $statuses = self::getTypesArray();
     * return $statuses[$this->type];
     * }
     */
    /**
     * Init sort field value
     */
    public function getMaxSort()
    {
        return (new Query())->from(self::tableName())
            ->where(['menu_id' => $this->menu_id])
            ->max('sort');
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
                ->andWhere('menu_id=:menu_id')
                ->addParams([':menu_id' => $this->menu_id]);
        } else {
            $new_sort = (new Query())->select('MIN(sort) as new_sort')
                ->from(self::tableName())
                ->where([
                    '>',
                    'sort',
                    $this->sort
                ])
                ->andWhere('menu_id=:menu_id')
                ->addParams([':menu_id' => $this->menu_id]);
        }
        /* @var Staff $emp */
        $cat = self::findOne([
            'menu_id' => $this->menu_id,
            'sort' => $new_sort
        ]);
        if (empty($cat)) {
            return false;
        }
        $new_sort = $cat->sort;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $cat->sort = $sort;
            if ($cat->save(false)) {
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

    public function attachCustom()
    {
        if ($this->validate()) {
            array_unshift($this->fields, $this->category);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($this->fields as $title) {
                    if (!empty($title)) {
                        $custom = new CustomCategory();
                        $custom->menu_id = $this->menu_id;
                        $custom->title = $title;
                        $custom->srv_title = CustomCategory::SRV_TITLE;
                        $custom->price_title = CustomCategory::PRICE_TITLE;
                        //  $custom->type = self::TYPE_CUSTOM;
                        if (!$custom->save(false)) {
                            $transaction->rollBack();

                            return false;
                        }
                    }
                }
                $transaction->commit();

                return true;
            } catch (Exception $e) {
                $transaction->rollBack();

                return false;
            }
        }

        return false;
    }

    /*
        public function havePricing() {
            $count = CustomCategory::find()
                    ->select(['count(*)'])
                    ->leftJoin('custom_service', 'custom_service.cat_id=custom_category.id')
                    ->leftJoin('pricing', 'pricing.ser_id=custom_service.id')
                    ->where('custom_category.id=:cat', ['cat' => $this->id])
                    ->andWhere('pricing.price is not null')
                    ->scalar();
            if ($count > 0)
                return true;
            return false;
        }
      */
    public function countFilledTiers()
    {
        $count = CustomCategory::find()
            ->select(['count(*)'])
            ->leftJoin('custom_service', 'custom_service.cat_id=custom_category.id')
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->where('custom_category.id=:cat', ['cat' => $this->id])
            ->andWhere('tier.price is not null')
            ->scalar();
        if ($count > 0) {
            return $count;
        }

        return false;
    }

    public function getFilledServices()
    {
        $services = CustomService::find()
            ->where('fill_status=1')
            ->all();

        return $services;
    }

    public function getFilledServicesWithPromo()
    {
        $services = CustomService::find()
            ->leftJoin('promo_group', 'promo_group.cat_id=custom_service.cat_id')
            ->leftJoin('promo', 'promo_group.promo_id=promo.id')
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->where('tier.price is not null and custom_service.cat_id=:cat_id and active=:active and promo.start <= date(now()) and promo.end >= date(now())',
                [
                    'cat_id' => $this->id,
                    'active' => Promo::STATUS_ACTIVE
                ])//->andWhere(' (promo.start <now()+interval :interval hour) and(promo.end>=now())', ['interval' => Promo::PROMO_SHOW_BEFORE])
            ->groupBy('custom_service.id')
            ->all();

        return $services;
    }

    public function getPromo()
    {
        // Old version ->andWhere(' (promo.start <now()+interval :interval hour) and(promo.end>=now())', ['interval' => Promo::PROMO_SHOW_BEFORE])
        $result = Promo::find()
            ->leftJoin('promo_group', 'promo_group.promo_id=promo.id')
            ->where('promo_group.cat_id=:cat_id and promo.active=:active and promo.start <= date(now()) and promo.end >= date(now())',
                [
                    'cat_id' => $this->id,
                    'active' => Promo::STATUS_ACTIVE
                ])
            ->orderBy('promo.start')
            ->one();

        return $result;
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
