<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Business;
use app\models\Operation;

class ProfileOperationForm extends Model
{
    public $monday_open;
    public $monday_close;
    public $monday_active;
    public $tuesday_open;
    public $tuesday_close;
    public $tuesday_active;
    public $wednesday_open;
    public $wednesday_close;
    public $wednesday_active;
    public $thursday_open;
    public $thursday_close;
    public $thursday_active;
    public $friday_open;
    public $friday_close;
    public $friday_active;
    public $saturday_open;
    public $saturday_close;
    public $saturday_active;
    public $sunday_open;
    public $sunday_close;
    public $sunday_active;

    private $_user = false;
    private $_operations = false;

    protected function to24Hour($time) {
        return date("H:i:s", strtotime($time));
    }

    protected function to12Hour($time) {
        return date("g:i a", strtotime($time));
    }

    public function loadDefaultValues()
    {
        $operations = $this->getOperations();
        $days = $this->getDays();
        foreach ($operations as $operation) {
            foreach ($days as $key => $day) {
                if ($operation->day == $key) {
                    $open = $day . '_open';
                    $end = $day . '_close';
                    $active = $day . '_active';
                    $this->$open = $this->to12Hour($operation->open);
                    $this->$end = $this->to12Hour($operation->end);
                    $this->$active = $operation->active;
                }
            }
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'monday_open', 'monday_close',
                    'tuesday_open', 'tuesday_close',
                    'wednesday_open', 'wednesday_close',
                    'thursday_open', 'thursday_close',
                    'friday_open', 'friday_close',
                    'saturday_open', 'saturday_close',
                    'sunday_open', 'sunday_close'
                ],
                'date', 'format'=>'HH:mm a'
            ],
            [
                [
                    'monday_active', 'tuesday_active', 'wednesday_active', 'thursday_active',
                    'friday_active', 'saturday_active', 'sunday_active'
                ],
                'integer'
            ]
        ];
    }

    /**
     * @return boolean whether the operations was saved
     */
    public function changeOperation()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $days = array();
            foreach ($this->getDays() as $key => $day) {
                $open = $day . '_open';
                $end = $day . '_close';
                $active = $day . '_active';
                $days[$key]['open'] = $this->to24Hour($this->$open);
                $days[$key]['end'] = $this->to24Hour($this->$end);
                $days[$key]['active'] = intval($this->$active);
            }
            $operations = $this->getOperations();
            if ($operations) {
                foreach ($operations as $operation) {
                    $operation->open = $days[$operation->day]['open'];
                    $operation->end = $days[$operation->day]['end'];
                    $operation->active = $days[$operation->day]['active'];
                    $operation->update();
                }
                $this->_operations = false;
            } else {
                foreach ($days as $day => $hours) {
                    $operation = new Operation();
                    $operation->bus_id = $user->id;
                    $operation->day = $day;
                    $operation->open = $hours['open'];
                    $operation->end = $hours['end'];
                    $operation->active = intval($hours['active']);
                    if (!$operation->save(false)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @return null|Business
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Business::findByUserId(Yii::$app->user->id);
        }
        return $this->_user;
    }

    /**
     * @return null|Operation array
     */
    public function getOperations()
    {
        $user = $this->getUser();
        if ($this->_operations === false) {
            $this->_operations = Operation::findByBusId($user->id);;
        }
        return $this->_operations;
    }
    
    public function hasOperations()
    {
      
        if (count($this->getOperations())>0) {
            return true;
        }
        return false;
    }
    
    

    /**
     * @return array
     */
    public static function getDays()
    {
        return [
            Operation::MONDAY => 'monday',
            Operation::TUESDAY => 'tuesday',
            Operation::WEDNESDAY => 'wednesday',
            Operation::THURSDAY => 'thursday',
            Operation::FRIDAY => 'friday',
            Operation::SATURDAY => 'saturday',
            Operation::SUNDAY => 'sunday'
        ];
    }
    public function isFilled(){
        
    }
}