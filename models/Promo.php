<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "promo".
 *
 * @property integer $id
 * @property integer $discount
 * @property integer $nco
 * @property integer $combine
 * @property string $start
 * @property string $end
 * @property string $terms
 * @property integer $active
 * @property integer $round
 * @property integer $bus_id
 *
 * @property PromoGroup[] $promoGroups
 * @property WishList[] $wishLists
 */
class Promo extends ActiveRecord
{

    const ROUND_UP = 1;
    const ROUND_DOWN = 2;
    const ROUND_NOT = 3;

    const NCO_YES = 1;
    const NCO_NO = 2;

    const COMBINE_YES = 1;
    const COMBINE_NO = 2;

    const STATUS_CREATE = 10;
    const STATUS_ACTIVE = 1;
    const STATUS_ENDED = 2;
    const STATUS_DELETED = 0;  //was used and been deleted

    const PROMO_SHOW_BEFORE = 48;  //in hours
    public $services = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'discount',
                    'nco',
                    'combine',
                    'active',
                    'round',
                    'bus_id'
                ],
                'integer'
            ],
            [
                [
                    'discount',
                    'start',
                    'end',
                    'round',
                    'terms',
                    'services',
                    'bus_id'
                ],
                'required'
            ],
            [
                'services',
                function ($attribute, $params) {
                    $error = '';
                    foreach ($this->services as $key => $cat) {
                        $category = CustomCategory::find()
                            ->where('id=:id', ['id' => $cat])
                            ->one();

                        if (($this->start != '') && ($this->end != '')) {
                            $start = date("Y-m-d", strtotime($this->start));
                            $end = date("Y-m-d", strtotime($this->end));
                            if (self::haveByCategoryDates($category->id, $start, $end, $this->id) > 0) {
                                $error = 'One or more categories is already active in another promo. See Tip for more info.';
                            }
                        }

                    }

                    if ($error != '') {
                        $this->addError($attribute, $error);
                    }

                }
            ],
            [
                'discount',
                'compare',
                'compareValue' => 100,
                'operator' => '<='
            ],
            [
                [
                    'start',
                    'end'
                ],
                'safe'
            ],
            [
                [
                    'start',
                    'end'
                ],
                'date',
                'format' => 'php:m/d/Y'
            ],
            //[['start'], 'compare', 'compareAttribute'=>'end', 'operator'=>'<=', 'skipOnEmpty'=>true],
            [
                'start',
                function ($attribute, $params) {
                    if (is_null($this->$attribute)) {
                        return;
                    }
                    if (($value = \DateTime::createFromFormat('m/d/Y H:i', $this->$attribute . ' 00:00'))) {
                        $now = \DateTime::createFromFormat('m/d/Y H:i', date('m/d/Y') . ' 00:00');
                        if ($value->getTimestamp() < $now->getTimestamp()) {
                            $this->addError($attribute,
                                $this->attributeLabels()[$attribute] . ' must be greater than or equal to '
                                . $now->format('m/d/Y'));
                        }
                    }
                }
            ],
            [
                'end',
                function ($attribute, $params) {
                    if (is_null($this->$attribute) || is_null($this->start)) {
                        return;
                    }
                    $value = \DateTime::createFromFormat('m/d/Y', $this->$attribute)
                        ->getTimestamp();
                    $compare = \DateTime::createFromFormat('m/d/Y', $this->start)
                        ->getTimestamp();
                    if ($value < $compare) {
                        $this->addError($attribute,
                            $this->attributeLabels()[$attribute] . ' must be greater than Valide');
                    }
                }
            ],
            [
                ['terms'],
                'string',
                'max' => 255
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
            'discount' => 'Discount percentage',
            'nco' => 'New Customer Only',
            'combine' => 'Combine with Other Offers',
            'start' => 'Valid',
            'end' => 'Expiration',
            'terms' => 'Terms',
            'active' => 'Active',
            'round' => 'Price rounding',
            'services' => 'Section - Category',
            'bus_id' => 'Business',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->active = self::STATUS_CREATE;
            }
            $this->start = date("Y-m-d", strtotime($this->start));
            $this->end = date("Y-m-d", strtotime($this->end));

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(Business::className(), ['id' => 'bus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(PromoGroup::className(), ['promo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasMany(CustomCategory::className(), ['id' => 'cat_id'])
            ->viaTable(PromoGroup::tableName(), ['promo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishLists()
    {
        return $this->hasMany(WishList::className(), ['promo_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        switch ($this->active) {
            case self::STATUS_CREATE:
                $status = 'Waiting to be published';
                break;
            case self::STATUS_ACTIVE:
                $status = 'Active';
                break;
            case self::STATUS_ENDED:
                $status = 'Ended';
                break;
            case self::STATUS_DELETED:
                $status = 'Deleted';
                break;
            default:
                throw new \RuntimeException();
        }

        return $status;
    }

    /**
     * @param  int $bus_id
     * @return null|array
     */
    public static function findByBusId($bus_id)
    {
        return static::find()
            ->where(['bus_id' => $bus_id])
            ->andWhere('active>0')
            ->with('groups.category')
            ->with('wishLists')
            ->orderBy(['active' => SORT_ASC])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    /**
     * @param  int $bus_id
     * @return null|array
     */
    public static function getActivePromos($bus_id)
    {
        return static::find()
            ->where(['bus_id' => $bus_id])
            ->andWhere('active = :active and promo.start <= date(now()) and promo.end >= date(now())',
                [':active' => self::STATUS_ACTIVE])//->with('groups.category')
            //->with('wishLists')
            ->orderBy(['active' => SORT_ASC])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    /**
     * For Business Promo-page
     *
     * @param  int $bus_id
     * @return null|array
     */
    public static function getBusinessPromos($bus_id)
    {
        return static::find()
            ->with('groups.category')
            ->with('wishLists')
            ->where(['bus_id' => $bus_id])
            ->andWhere('active <> :active',
                [':active' => self::STATUS_DELETED])
            ->orderBy(['active' => SORT_ASC])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    /**
     * @return boolean
     */
    public function addPromoGroup()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$this->save(false)) {
                    $transaction->rollBack();

                    return false;
                }
                $promo_id = $this->getPrimaryKey();
                foreach ($this->services as $cat_id) {
                    $group = new PromoGroup();
                    $group->promo_id = $promo_id;
                    $group->cat_id = $cat_id;
                    if (!$group->save(false)) {
                        $transaction->rollBack();

                        return false;
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param $services array
     * @return boolean
     */
    public function updatePromoGroup($services)
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $this->active = self::STATUS_CREATE;
                if (!$this->save(false)) {
                    $transaction->rollBack();

                    return false;
                }
                $promo_id = $this->getPrimaryKey();
                foreach ($services as $cat_id) {
                    $group = PromoGroup::findOne([
                        'promo_id' => $promo_id,
                        'cat_id' => $cat_id
                    ]);
                    //Get WishList, send message and remove WishList
                    if (!$group->delete()) {
                        $transaction->rollBack();

                        return false;
                    }
                }
                foreach ($this->services as $cat_id) {
                    $group = new PromoGroup();
                    $group->promo_id = $promo_id;
                    $group->cat_id = $cat_id;
                    if (!$group->save(false)) {
                        $transaction->rollBack();

                        return false;
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @return boolean whether
     */
    public function publishPromoGroup()
    {
        return (bool)$this->updateAttributes([
            'active' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @return boolean whether
     */
    public function endNowGroup()
    {
        return (bool)$this->updateAttributes([
            'active' => self::STATUS_ENDED,
            //'end' => date('Y-m-d')
        ]);
    }

    public function markDeleted()
    {
        $this->active = self::STATUS_DELETED;
        if (!$this->save(false)) {

            return false;
        }

        return true;
    }

    public function haveByCategoryDates($cat_id, $start, $end, $promo_id = null)
    {
        if (isset($promo_id)) {
            $result = Promo::find()
                ->select(['count(*)'])
                ->leftJoin('promo_group', 'promo_group.promo_id=promo.id')
                ->where('promo_group.cat_id=:cat_id', ['cat_id' => $cat_id])
                ->andWhere(' (:start between promo.start and promo.end) or(:end between promo.start and promo.end)', [
                    'start' => $start,
                    'end' => $end
                ])
                ->andWhere([
                    'promo.active' => [
                        self::STATUS_CREATE,
                        self::STATUS_ACTIVE
                    ]
                ])
                ->andWhere('promo_id!=:promo_id', ['promo_id' => $promo_id])
                ->scalar();
        } else {
            $result = Promo::find()
                ->select(['count(*)'])
                ->leftJoin('promo_group', 'promo_group.promo_id=promo.id')
                ->where('promo_group.cat_id=:cat_id', ['cat_id' => $cat_id])
                ->andWhere([
                    'promo.active' => [
                        self::STATUS_CREATE,
                        self::STATUS_ACTIVE
                    ]
                ])
                ->andWhere(' (:start between promo.start and promo.end) or(:end between promo.start and promo.end)', [
                    'start' => $start,
                    'end' => $end
                ])
                ->scalar();
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'discount',
            'start',
            'end',
            'terms',
        ];
    }
}
