<?php
/* 
 * @var $model app\models\Business 
 * @var $savedIds

  */
use yii\bootstrap\Nav;
use yii\helpers\Url;
use yii\bootstrap\Html;

$script = <<< JS
    $(document).scroll(function () {
        if($(this).scrollTop() > 50) {
            $(".menu-container").css({
                "position":"fixed",
                'top':"0",
                "width":"100%",
                "left":"0",
                "z-index":"1010",
                 //"marginLeft":"0",
                 'paddingBottom': '8px',
                 'borderTop': '5px solid #B9CDE7'
            });
        }
        if($(this).scrollTop() < 50) {
            $(".menu-container").css({
                "position":"relative",
                "z-index":"0",
                'paddingBottom': '35px',
                 'borderTop': 'none'
            });
        }
		$('.menu ul li').on('click', 'a', function(){
		$('.menu ul li a').removeClass('active');
		$(this).addClass('active');
		});
    });
JS;
$this->registerJs($script);
$items = [
    [
        'label' => 'Home',
        'url' => '#home',
    ],
    [
        'label' => 'Menu',
        'url' => '#menu',
    ],
    [
        'label' => 'Promo',
        'url' => '#promo',
    ],
    [
        'label' => 'Photo',
        'url' => '#photo',
    ],
    [
        'label' => 'About',
        'url' => '#about',
    ],
];

//if(count($model->staffs)>0) $items[]=['label' => 'Staff', 'url' => '#staff'];
//$items[]=['label' => 'About', 'url' => '#about'];

?>
<?php
if (!$model->isFilled()) {
    ?>
    <div>
        Your Wizerd site is not visible to consumers. First you must complete all mandatory profile sections.
        <?= Html::a('Click here', Url::to([
            '/user/account',
            'active' => 'setup'
        ])) ?>
        for details. But this is how it will look once complete.
    </div>
    <?php
}
?>
<!--<div class="hidden-lg hidden-md hidden-sm visible-xs-visible businesspage-wrapper">
<div class="site-name"><h3>Wizerd</h3></div>
</div>-->
<div class="menu-container narrow">
    <div class="header n_business_header">
        <div class="buz-page-narrow-container clearfix">
            <div class="back-arrow">
                <?php
                echo Html::button(Html::img('@web/images/back-arrow.png'), [
                    'onClick' => "if ((url = window.sessionStorage.getItem('history.back'))) {window.location.href=url;window.sessionStorage.removeItem('history.back');}"
                ]);
                ?>
            </div>
            <div class="row row-mrg-none">
                <div class="col-sm-6 col-xs-9 title">
                    <p class="n_business_name">
                        <?= Html::encode($model->name) ?>
                        <?php
                        if ($model->isContractor()) {
                            $agency = $model->getAgency();
                            if ($agency !== null) {
                                echo Html::a("@" . Html::encode($agency->name),
                                    Url::to('/' . Html::encode($agency['vanity_name'])), [
                                        'target' => '_blank',
                                        "class" => ''
                                    ]);
                            }
                        }
                        ?>
                    </p>
                </div>
                <div class="col-sm-6 menu">
                    <nav class="navbar navbar-default biz-profiletab-menu">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <?php
                            echo Nav::widget([
                                'id' => 'bus_page_menu',
                                'options' => ['class' => 'navbar-nav navbar-right'],
                                'items' => $items
                            ]);
                            ?>
                        </div> <!--./navbar-collapse-->
                    </nav> <!--navigation-->
                </div>
            </div>

        </div>
    </div>
</div>


<div class="container-fluid narrow">
    <?php
    /**
     * renderng Base Part
     */
    echo $this->render('business/_base_part', ['model' => $model,]);
    ?>
</div>


<div class="container-fluid narrow bg-color-panel">
    <?php
    /**
     * renderng menus
     */
    echo $this->render('business/_menus', ['model' => $model,]);
    ?>
</div> <!--./bg-color-panel-->

<div class="container-fluid narrow">
    <div class="row content light-opacity-green">
        <?php
        /**
         * renderng Promo
         */

        //    if ($model->haveActivePromos()):
        echo $this->render('business/_promo', [
            'model' => $model,
            'savedIds' => $savedIds
        ]);
        ?>
        <?php //endif; ?>
    </div>

</div>

<!--<div class="container-fluid narrow">-->
<!--    --><?php
//    /**
//     * renderng Staff
//     */
//    echo $this->render('business/_staff', ['model' => $model,]);
//    ?>
<!--</div>-->

<div class="container-fluid narrow">
    <?php
    /**
     * renderng About Us
     */
    echo $this->render('business/_about', ['model' => $model,]);
    ?>
</div>

<div class="container-fluid narrow">
    <?php
    /**
     * renderng Photo
     */
    echo $this->render('business/_photo', ['model' => $model,]);
    ?>
</div>
