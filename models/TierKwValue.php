<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tier_kw_value".
 *
 * @property integer $id
 * @property integer $tier_id
 * @property integer $kw_id
 * @property integer $value
 *
 * @property Tier $idTier
 */
class TierKwValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tier_kw_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tier_id', 'kw_id', 'value'], 'required'],
            [['tier_id', 'kw_id', 'value'], 'integer'],
            [['tier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tier::className(), 'targetAttribute' => ['tier_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tier_id' => 'Id Tier',
            'kw_id' => 'Id Kw',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTier()
    {
        return $this->hasOne(Tier::className(), ['id' => 'tier_id']);
    }

    public function deleteByTier($tier_id){
        self::deleteAll('tier_id=:tier_id',['tier_id'=>$tier_id]);

    }

}
