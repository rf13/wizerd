<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\db\Exception;

/**
 * This is the model class for table "custom_service".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property string $title
 * @property integer $kw_group_id
 * @property integer $sort
 * @property integer $fill_status
 *
 * @property Business $business
 * @property CustomCategory $category
 */
class CustomService extends ActiveRecord
{

    const TYPE_DEFAULT = 1; // Created by Default
    const TYPE_CUSTOM = 0; // custom
    const TYPE_FIXED = 2; // custom not deleteble
    const STATUS_NEW = 0; //new record, not filled 
    const STATUS_FILLED = 1; // filled record
    const SCENARIO_WITH_INDUSTRY = 'with_industry';
    const SCENARIO_WITHOUT_CAT = 'without_cat';
    const SCENARIO_UPDATE_TITLE = 'any';
    const SCENARIO_WITH_TITLE = 'with_title';

    public $menu_id;
    public $fields = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_service';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_WITHOUT_CAT] = ['title'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['title', 'cat_id', 'temp_id'], 'required'],
            //[['title', 'cat_id'], 'required'],
            //  [['cat_id'], 'required'],
            [
                'title',
                'required',
                'on' => self::SCENARIO_WITH_TITLE,
                'message' => '{attribute} can’t be blank.'
            ],
            [
                'menu_id',
                'required',
                'on' => self::SCENARIO_WITH_INDUSTRY
            ],
            [
                [
                    'cat_id',
                    'sort',
                ],
                'integer'
            ],
            [
                ['title'],
                'string',
                'max' => 90
            ],
            [
                ['cat_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CustomCategory::className(),
                'targetAttribute' => ['cat_id' => 'id']
            ],
            [
                ['menu_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Menu::className(),
                'targetAttribute' => ['menu_id' => 'id'],
                'on' => self::SCENARIO_WITH_INDUSTRY
            ],
            [
                'fields',
                'safe'
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
            'menu_id' => 'Section',
            'cat_id' => 'Category',
            'title' => 'Service',
            'sort' => 'Sort',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        // if(strlen($this->title)>0)

        if (!$key = Keyword::createKeywords($this->title, $this->kw_group_id)) {
            return false;
        } else {
            $this->kw_group_id = $key;
        }

        foreach ($this->tiers as $tier) {
            $tier->createTKW();
            $tier->updateCharCount();

        }


        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->sort = $this->getMaxSort() + 1;
                //$this->temp_id=1;

            }

            return true;
        }

        return false;
    }

    public function  afterSave($insert, $changedAttributes)
    {


        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
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
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'bus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CustomCategory::className(), ['id' => 'cat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery

    public function getTemplate() {
     * return $this->hasOne(Template::className(), ['id' => 'temp_id']);
     * }
     */
    /**
     * @return \yii\db\ActiveQuery

    public function getPricing() {
     * return $this->hasMany(Pricing::className(), ['ser_id' => 'id']);
     * }
     *
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiers()
    {
        return $this->hasMany(Tier::className(), ['srv_id' => 'id']);
    }


    /**
     * @return array
     */
    public static function getTypesArray()
    {
        return [
            self::TYPE_DEFAULT => 'default',
            self::TYPE_CUSTOM => 'custom'
        ];
    }

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
            ->where(['cat_id' => $this->cat_id])
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
                ->andWhere('cat_id=:cat_id')
                ->addParams([':cat_id' => $this->cat_id]);
        } else {
            $new_sort = (new Query())->select('MIN(sort) as new_sort')
                ->from(self::tableName())
                ->where([
                    '>',
                    'sort',
                    $this->sort
                ])
                ->andWhere('cat_id=:cat_id')
                ->addParams([':cat_id' => $this->cat_id]);
        }
        /* @var Staff $emp */
        $srv = self::findOne([
            'cat_id' => $this->cat_id,
            'sort' => $new_sort
        ]);
        if (empty($srv)) {
            return false;
        }
        $new_sort = $srv->sort;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $srv->sort = $sort;
            if ($srv->save(false)) {
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
            array_unshift($this->fields, $this->title);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($this->fields as $title) {
                    if (!empty($title)) {
                        $custom = new CustomService();
                        $custom->cat_id = $this->cat_id;
                        $custom->title = $title;


                        $cat = CustomCategory::find()
                            ->where(['id' => $this->cat_id])
                            ->one();


                        if (!$custom->save(false)) {
                            $transaction->rollBack();

                            return false;
                        }
                        $tier = new Tier();
                        $tier->srv_id = $custom->id;
                        if (!$tier->save()) {
                            $transaction->rollBack();

                            return false;
                        }
                        foreach ($cat->srvFields as $srvField) {
                            $fieldValue = new FieldValue();
                            $fieldValue->tier_id = $tier->id;
                            $fieldValue->field_id = $srvField->id;
                            if (!$fieldValue->save()) {
                                $transaction->rollBack();

                                return false;
                            }
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


    public static function getServiceById($id)
    {
        return self::findOne(['id' => $id]);
    }

    /*
      public function havePricing() {
      $count = CustomService::find()
      ->select(['count(*)'])
      ->leftJoin('pricing', 'pricing.ser_id=custom_service.id')
      ->where('custom_service.id=:srv', ['srv' => $this->id])
      ->andWhere('pricing.price is not null')
      ->scalar();
      if ($count > 0)
      return true;
      return false;
      }
     */

    public function countFilledTiers()
    {
        $count = CustomService::find()
            ->select(['count(*)'])
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->where('custom_service.id=:srv', ['srv' => $this->id])
            ->andWhere('tier.price is not null')
            ->scalar();
        if ($count > 0) {
            return $count;
        }

        return false;
    }

    public function createTier()
    {
        $tier = new Tier();
        $tier->srv_id = $this->id;


        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$tier->save(false)) {
                $transaction->rollBack();

                return false;
            }

            if (!$tier->appendFieldValues()) {

                $transaction->rollBack();

                return false;
            }
            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return false;
    }

    public function isAllFilled()
    {
        $result = (new Query())->select('count(*)')
            ->from(Tier::tableName())
            ->where('srv_id=:srv_id and price is null', ['srv_id' => $this->id])
            ->scalar();
        if ($result > 0) {
            return false;
        }

        return true;
    }

    public function deleteEmptyTiers()
    {
        foreach ($this->tiers as $tier) {
            if (!$tier->isFilled()) {
                if (!$tier->delete()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return ['title'];
    }
}
