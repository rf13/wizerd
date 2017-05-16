<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "save_search".
 *
 * @property integer $id
 * @property integer $ip
 * @property integer $cons_id
 * @property integer $zip_id
 * @property string $search
 * @property string $search_ts
 */
class SaveSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'save_search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cons_id', 'zip_id'], 'integer'],
            [['zip_id', 'search','ip'], 'required'],
            [['search_ts'], 'safe'],
            [['ip'], 'string', 'max' => 15],
            [['search'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip'=>'cons IP',
            'cons_id' => 'Cons ID',
            'zip_id' => 'Zip ID',
            'search' => 'Search',
            'search_ts' => 'Search Ts',
        ];
    }

    public static function findBySearch($search,$zip_id){
        $q=SaveSearch::find()->select('count(*)')->where('search_ts>now()-interval 1 hour')->andWhere('zip_id=:zip_id',['zip_id'=>$zip_id])->andWhere('search=:search',['search'=>$search])->scalar();
        return ($q>0)? true:false;
}
}
