<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\Html;
use yii\bootstrap\Alert;

class AlertComponent extends Component{
    public $content;

    public function init(){
        parent::init();
        $content = '';
        $keys = array('success', 'info', 'warning', 'danger');
        foreach (\Yii::$app->session->getAllFlashes(true) as $key => $message) {
            if (!in_array($key, $keys)) {
                if ($key == 'error') $key = 'danger';
                else $key = 'info';
            }
            $content .= Alert::widget([
                'options' => ['class' => 'alert-' . $key],
                'body' => $message
            ]);
        }
        $this->content= $content;
    }

    public function display($content=null){
        if($content!=null){
            $this->content= Html::encode($content);
        }
        echo $this->content;
    }
}
?>