<?php
namespace app\components;

use yii\base\Component;
use yii\bootstrap\Collapse;

class TipComponent extends Component{
    public $tip;

    public function init(){
        parent::init();
        $content = ''; //get from DB
        $this->tip= $content;
    }

    public function display($tip = null){
        if($tip != null){
            $this->tip = Collapse::widget([
                'items' => [
                    [
                        'encode' => false,
                        'label' => 'Tip <i class="glyphicon glyphicon-hand-left"></i>',
                        'content' => $tip
                    ]
                ]
            ]);
        }
        echo $this->tip;
    }
}
?>