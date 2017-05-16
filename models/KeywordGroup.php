<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "keyword_group".
 *
 * @property integer $id
 *
 * @property Keyword[] $keywords
 * @property Menu[] $menus
 */
class KeywordGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keyword_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeywords()
    {
        return $this->hasMany(Keyword::className(), ['kw_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['kw_group' => 'id']);
    }

    public function deleteKeywords(){
        foreach ($this->keywords as $word){
            if(!$word->delete())
                return false;
        }
        return true;
    }
}
