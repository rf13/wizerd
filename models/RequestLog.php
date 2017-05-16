<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request_log".
 *
 * @property integer $id
 * @property integer $cons_id
 * @property string $ip
 * @property string $search_ts
 */
class RequestLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cons_id'], 'integer'],
            [['ip'], 'required'],
            [['search_ts'], 'safe'],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cons_id' => 'Cons ID',
            'ip' => 'Ip',
            'search_ts' => 'Search Ts',
        ];
    }
}
