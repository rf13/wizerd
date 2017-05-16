<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "field".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property string $title
 * @property integer $visible
 * @property integer $sort
 * @property CustomCategory $cat
 * @property FieldValue[] $fieldValues
 * @property Tier[] $tires
 */
class Field extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'field';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['cat_id', 'title'], 'required', 'message' => '{attribute} can’t be blank.'],
                [['cat_id', 'visible'], 'integer'],
                [['title'], 'string', 'max' => 255],
                [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomCategory::className(), 'targetAttribute' => ['cat_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                'id' => 'ID',
                'cat_id' => 'Cat ID',
                'title' => 'Title',
                'visible' => 'visible,'
        ];
    }
 public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->sort = $this->getMaxSort() + 1;
            }
            return true;
        }
        return false;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(CustomCategory::className(), ['id' => 'cat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValues() {
        return $this->hasMany(FieldValue::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTires() {
        return $this->hasMany(Tier::className(), ['id' => 'tire_id'])->viaTable('field_value', ['field_id' => 'id']);
    }
    /**
     * Init sort field value
     */
    public function getMaxSort() {
        return (new Query())->from(self::tableName())->where(['cat_id' => $this->cat_id])->max('sort');
    }
     public function getMinSort() {
        return (new Query())->from(self::tableName())->where(['cat_id' => $this->cat_id])->min('sort');
    }
     /**
     * @param  boolean $up
     * @return boolean whether sortable were changed
     */
    public function changeSort($up) {
        $sort = $this->sort;
        if ($up) {
            $new_sort = (new Query())->select('MAX(sort) as new_sort')->from(self::tableName())
                            ->where(['<', 'sort', $this->sort])
                            ->andWhere('cat_id=:cat_id')->addParams([':cat_id' => $this->cat_id]);
        } else {
            $new_sort = (new Query())->select('MIN(sort) as new_sort')->from(self::tableName())
                            ->where(['>', 'sort', $this->sort])
                            ->andWhere('cat_id=:cat_id')->addParams([':cat_id' => $this->cat_id]);
        }
        /* @var Staff $emp */
        $field = self::findOne(['cat_id' => $this->cat_id, 'sort' => $new_sort]);
        if (empty($field))
            return false;
        $new_sort = $field->sort;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $field->sort = $sort;
            if ($field->save(false)) {
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
     * Creating field and adding it to All services of current category
     * @return boolean
     */
    public function createCustomField() {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->save(false)) {
                $transaction->rollBack();
                return false;
            }

            if (!$this->setForAllServices()) {
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

    private function setForAllServices() {

        $category = CustomCategory::find()->where('id=:id', ['id' => $this->cat_id])->one();

        foreach ($category->services as $srv) {

            foreach ($srv->tiers as $tier) {
                //  print_r($tier);
                $fieldValue = new FieldValue();
                $fieldValue->tier_id = $tier->id;
                $fieldValue->field_id = $this->id;

                if (!$fieldValue->save())
                    return false;
            }
        }
        return true;
    }
   

}
