<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "save_wait_email".
 *
 * @property integer $id
 * @property string $email
 * @property string $zip
 * @property string $search_ts
 * @property string $ip
 */
class SaveWaitEmail extends \yii\db\ActiveRecord
{
    const SCENARIO_REGISTER='registration';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'save_wait_email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'zip'], 'required', 'message' => '{attribute} can’t be blank.'],
            [['industry'], 'required', 'on' => self::SCENARIO_REGISTER, 'message' => '{attribute} can’t be blank.'],
            ['email', 'email', 'message' => 'This is not a valid email address.'],
            [['industry'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 7],
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
            'email' => 'Email',
            'zip' => 'Zip',
            'search_ts' => 'Search Ts',
            'industry' => 'Industry'
        ];
    }
    public function beforeSave($insert)
    {
        $this->ip=Yii::$app->request->userIP;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
