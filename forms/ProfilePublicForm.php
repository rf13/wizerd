<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Business;
use app\models\ZipCode;

class ProfilePublicForm extends Model
{
    const SCENARIO_ADDRESS = 'address';
    const SCENARIO_WITHOUT = 'without';

    public $home;
    public $contractor;
    public $agency_url;
    public $agency_zip;
    public $agency_address;
    public $agency;
    public $name;
    public $address;
    public $suite;
    public $city;
    public $state;
    public $zip_code;
    public $phone;
    public $website;
    public $contact_email;
    public $description;


    private $_user = false;

    public function attributeLabels()
    {
        return [
            'home'          => 'Home based business ONLY',
            'contractor'    => 'Agency contractor',
            'name'          => 'Business name',
            'address'       => 'Physical address',
            'suite'         => 'Suite',
            'city'          => 'City',
            'state'         => 'State',
            'zip_code'      => 'Zip code',
            'phone'         => 'Business phone',
            'website'       => 'Website',
            'contact_email' => 'Contact us email',
            'description'   => 'About us'
        ];
    }

    public function loadDefaultValues()
    {
        parent::init();
        $user = $this->getUser();
        if($user->isContractor()) {
            $this->contractor =1;
            $agency=$user->getAgency();

            $this->agency_url=$agency->makeBusinessLink();
            $this->agency_address=$agency->address;
            $this->agency_zip=$agency->zipCode->zip;

        }
        else $this->contractor =0;
        $this->home = $user->is_home;
        $this->name = $user->name;
        $this->address = $user->address;

        $this->suite = $user->suite;
        $this->phone = $user->phone;
        $this->website = $user->website;
        $this->contact_email = $user->contact_email;
        $this->description = $user->description;
        $zip_code = $user->getCurrentZipCode();
        if ($zip_code != null) {
            $this->zip_code = $zip_code->zip;
            $city = $zip_code->getCurrentCity();
            $this->city = $city->name;
            $state = $city->getCurrentState();
            $this->state = $state->name;
            unset($zip_code);
            unset($city);
            unset($state);
        } else {
            $this->zip_code = $user->zip_notice;
            $this->city = 'Not active';
            $this->state = 'Not active';
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['home','contractor','agency'], 'integer'],
            [['name', 'address', 'city', 'state', 'zip_code', 'description'], 'required', 'message' => '{attribute} can’t be blank.'],
            [['suite', 'website', 'contact_email'], 'default'],
            [['name', 'address', 'suite', 'city', 'state', 'phone', 'description'], 'string'],
            ['zip_code', 'validateUSAZip'],
            ['website', 'url', 'defaultScheme' => 'http','message' => 'This is not a valid URL. Here is the correct URL format – “www.example.com” OR “example.com”.'],
            ['contact_email', 'email', 'message' => 'This is not a valid email address.']
        ];
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUSAZip($attribute, $params)
    {
        if (!preg_match("/^([0-9]{5})(-[0-9]{4})?$/i", $this->zip_code)) {
            $this->addError('zip_code', 'Zip code must be 5 digits.');
        }
        $zip = new ZipCode();
        if ($zip->checkZipCode($this->zip_code) == 0) {
            $this->addError('zip_code', 'Sorry, but we have not launched in your city yet. Please check back later.');
        }
    }

    /**
     * @return boolean whether the settings was saved
     */
    public function changePublic()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->is_home = $this->home;
            $user->name = $this->name;
            if (($this->contractor)&&($this->agency)) {
                $agency_biz=Business::find()->where('id=:id',['id'=>$this->agency])->one();
                if($agency_biz!==null) {
                    $user->parent_bus_id = $agency_biz->id;
                    $user->address = $agency_biz->address;
                    $user->suite = $agency_biz->suite;
                    $user->zip_id = $agency_biz->zip_id;
                }else
                {
                    return false;
                }
            }
            else
            {
                $user->parent_bus_id=0;
                $user->address = $this->address;
                $user->suite = $this->suite;
                $user->zip_id = ZipCode::find()->where('zip=:zip',['zip'=>$this->zip_code])->one()->id ;
            }
            $user->phone = $this->phone;
            $user->website = $this->website;
            $user->contact_email = $this->contact_email;
            $user->description = $this->description;
            $user->getGeoByAddress();

            if (!$user->save(false)) {
                return false;
            }
            return true;
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
    
    public function isFilled(){
        if (($this->name)&&($this->address)&&($this->zip_code)&&($this->city)&&($this->state)&&($this->description))
            return true;
        else
            return false;
    }

}