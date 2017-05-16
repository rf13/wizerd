<?php

namespace app\models;

use yii\db\Query;
use Yii;

/**
 * This is the model class for table "tier".
 *
 * @property integer $id
 * @property integer $srv_id
 * @property string $price
 * @property integer $char_length
 *
 * @property FieldValue[] $fieldValues
 * @property Field[] $fields
 * @property CustomService $srv
 */
class Tier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['srv_id'],
                'required'
            ],
            [
                [
                    'srv_id',
                    'char_length'
                ],
                'integer'
            ],
            [
                ['price'],
                'number'
            ],
            [
                ['srv_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CustomService::className(),
                'targetAttribute' => ['srv_id' => 'id']
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (!$this->isNewRecord) {
            $this->char_length = self::getCharCount($this->id);

        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'srv_id' => 'Srv ID',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValues()
    {
        return $this->hasMany(FieldValue::className(), ['tier_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['id' => 'field_id'])
            ->viaTable('field_value', ['tier_id' => 'id'])
            ->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(CustomService::className(), ['id' => 'srv_id']);
    }

    public function getFieldsValueOrderedModels()
    {
        return FieldValue::find()
            ->leftJoin('field', 'field.id=field_value.field_id')
            ->where('field_value.tier_id=:id', ['id' => $this->id])
            ->orderBy('field.sort')
            ->all();
    }

    public function getFieldsValueOrdered()
    {

        return (new Query())->select('field_value.value as val')
            ->from('tier')
            ->leftJoin('field_value', 'field_value.tier_id=tier.id')
            ->leftJoin('field', 'field.id=field_value.field_id')
            ->where('tier.id=:id', ['id' => $this->id])
            ->andWhere('tier.price is not null')
            ->orderBy('field.sort')
            ->all();

    }

    /**
     * Creating Field Values for add fields of this tier-service-category
     * @return boolean
     */
    public function appendFieldValues()
    {
        $category = $this->service->category;

        foreach ($category->srvFields as $field) {
            $fieldValue = new FieldValue();
            $fieldValue->tier_id = $this->id;
            $fieldValue->field_id = $field->id;

            if (!$fieldValue->save()) {
                return false;
            }


        }

        return true;
    }

    public function isFilled()
    {
        if (($this->id !== null) && ($this->price !== null)) {
            return true;
        }

        return false;

    }

    public function getActivePromo()
    {

        return Promo::find()
            ->leftJoin('promo_group', 'promo.id=promo_group.promo_id')
            ->leftJoin('custom_category', 'custom_category.id=promo_group.cat_id')
            ->leftJoin('custom_service', 'custom_service.cat_id=custom_category.id')
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->where('tier.id=:tier_id ', ['tier_id' => $this->id])
            ->andWhere(' (promo.start <now()+interval :interval hour) and(promo.end>=now())',
                ['interval' => Promo::PROMO_SHOW_BEFORE])
            ->all();

    }

    public function createTKW()
    {
        $start = microtime(true);

        TierKwValue::deleteAll('tier_id=:tier_id', ['tier_id' => $this->id]);

        $query = "
insert into tier_kw_value (tier_id,kw_id,value)
            select
    :tier_id as tier_id,
	s.id as kw_id,
	max(s.value) as value
from
(
	select
		k.id as id,
		k.word,
		2 as value
	from
		business b
		left join menu m on m.bus_id=b.id
		left join custom_category cc on cc.menu_id=m.id
		left join custom_service cs on cs.cat_id=cc.id
		left join tier t on t.srv_id=cs.id
		left join keyword_group kwg1 on kwg1.id=m.kw_group_id or kwg1.id=cc.kw_group_id or kwg1.id=cs.kw_group_id
		left join kwg_kw kk1 on kk1.kwg_id=kwg1.id
		left join keyword k on k.id=kk1.kw_id
	where
		t.id= :tier_id

union

	select
		k.id as id,
		k.word,
		1 as value
	from
		business b
		left join menu m on m.bus_id=b.id
		left join custom_category cc on cc.menu_id=m.id
		left join custom_service cs on cs.cat_id=cc.id
		left join tier t on t.srv_id=cs.id
		left join field f on f.cat_id=cc.id
		left join field_value fv on fv.tier_id=t.id and fv.field_id=f.id
		left join keyword_group kwg1 on kwg1.id=fv.kw_group_id
		left join kwg_kw kk1 on kk1.kwg_id=kwg1.id
		left join keyword k on k.id=kk1.kw_id
	where
		t.id= :tier_id
)s

where  s.id is not null
group by s.id


    ";

        $result = Yii::$app->db->createCommand($query, ['tier_id' => $this->id])
            ->execute();


        $sql = microtime(true);
        $spend = $sql - $start;


    }


    private static function getCharCount($id)
    {
        return (new Query())->select('sum(length(ifnull(field_value.value,"")))+length(menu.title)+length(custom_category.title)+length(custom_service.title)')
            ->from('menu')
            ->leftJoin('custom_category', 'custom_category.menu_id=menu.id')
            ->leftJoin('custom_service', 'custom_service.cat_id=custom_category.id')
            ->leftJoin('tier', 'tier.srv_id=custom_service.id')
            ->leftJoin('field_value', 'field_value.tier_id=tier.id')
            ->where('tier.id=:id', ['id' => $id])
            ->groupBy('tier.id')
            ->scalar();

    }

    public function updateCharCount()
    {

        $this->char_length = self::getCharCount($this->id);
        $this->save(false);

    }

    public function getPromoPrice()
    {
        $promo_price = false;
        if (($promo = $this->service->category->promo)) {
            $promo_percent = $this->price * ($promo->discount / 100);
            $promo_price = $this->price - $promo_percent;
            if ($promo->round == Promo::ROUND_UP) {
                $promo_price = round(ceil($promo_price), 0, PHP_ROUND_HALF_UP);
            } elseif ($promo->round == Promo::ROUND_DOWN) {
                $promo_price = round(floor($promo_price), 0, PHP_ROUND_HALF_DOWN);
            }
            $promo_price = number_format($promo_price, 2, '.', '');
        }

        return $promo_price;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'price',
            'promoPrice',
        ];
    }
}
