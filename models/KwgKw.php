<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kwg_kw".
 *
 * @property string $id
 * @property integer $kwg_id
 * @property integer $kw_id
 *
 * @property Keyword $kw
 * @property KeywordGroup $kwg
 */
class KwgKw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kwg_kw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kwg_id', 'kw_id'], 'required'],
            [['kwg_id', 'kw_id'], 'integer'],
            [['kw_id'], 'exist', 'skipOnError' => true, 'targetClass' => Keyword::className(), 'targetAttribute' => ['kw_id' => 'id']],
            [['kwg_id'], 'exist', 'skipOnError' => true, 'targetClass' => KeywordGroup::className(), 'targetAttribute' => ['kwg_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kwg_id' => 'Kwg ID',
            'kw_id' => 'Kw ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKw()
    {
        return $this->hasOne(Keyword::className(), ['id' => 'kw_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKwg()
    {
        return $this->hasOne(KeywordGroup::className(), ['id' => 'kwg_id']);
    }
}
