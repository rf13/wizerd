<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "consumer".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $nickname
 * @property string $first_name
 * @property string $last_name
 *
 * @property User $user
 * @property WishList[] $wishLists
 */
class Consumer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'consumer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'nickname', 'first_name', 'last_name'], 'required'],
            [['user_id'], 'integer'],
            [['nickname', 'first_name', 'last_name'], 'string', 'max' => 255],
            [
                ['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
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
            'user_id' => 'User',
            'nickname' => 'Nickname',
            'first_name' => 'First name',
            'last_name' => 'Last name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishLists()
    {
        return $this->hasMany(WishList::className(), ['user_id' => 'id']);
    }
}
