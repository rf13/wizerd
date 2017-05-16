<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Business;

/**
 * ProfileReviewForm is the model for change URL.
 */
class ProfileReviewForm extends Model
{
    public $example;
    public $yelp_url;

    private $_user = false;

    public function attributeLabels()
    {
        return [
            'example'  => 'Yelp profile URL example',
            'yelp_url' => 'Yelp profile URL'
        ];
    }

    public function loadDefaultValues()
    {
        $user = $this->getUser();
        $this->yelp_url = $user->yelp_url;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['yelp_url', 'url','defaultScheme' => 'http', 'message' => 'This is not a valid URL. Please look at the example format and try again.'],
            ['yelp_url', 'validateYelp'],
        ];
    }

    /**
     * Validate URL for Yelp
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateYelp($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!strstr($this->yelp_url, 'www.yelp.com/biz/')) {
                $this->addError($attribute, 'Yelp profile URL must contain http://www.yelp.com/biz/');
            }
            if (($this->yelp_url == 'www.yelp.com/')||($this->yelp_url == 'www.yelp.com/')) {
                $this->addError($attribute, 'You can not use a link to the Yelp home page');
            }
        }
    }

    /**
     * @return boolean whether the settings was saved
     */
    public function changeReview()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->yelp_url = $this->yelp_url;
            if ($user->update() !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Business|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Business::findByUserId(Yii::$app->user->id);
        }
        return $this->_user;
    }
}