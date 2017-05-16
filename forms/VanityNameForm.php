<?php
namespace app\forms;


use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Business;

class VanityNameForm extends Model{

    public $vanity_name;
    public $vanity_changed;
    private $_user = false;

    public function rules()
    {
        return [
            ['vanity_name', 'required', 'message' => '{attribute} can’t be blank.'],
            ['vanity_changed', 'integer'],
            ['vanity_name', 'string', 'min' => 5, 'max' => 150],
            ['vanity_name','validateVanityName'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'vanity_name' => 'Wizerd URL'
        ];
    }

    public function validateVanityName($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'Access error!');
            }
            if (Business::searchByVanityName($this->vanity_name)!=null)
            {
                $this->addError($attribute, 'The URL you are trying to register is already taken. Please try a different name.');
            }
        }
    }

    /**
     * Get current user
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findIdentity(Yii::$app->user->id);
        }

        return $this->_user;
    }


}
?>