<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Why local business owners love';
?>
<div class="business_welcome_wrapper">
    <div class="how-it-works-wrapper">
        <div class="jumbotron">
            <div class="container">
                <h1><?= Html::encode($this->title) ?> <span>Wiz<em style="color: #81aff9; font-style: normal;">e</em>rd</span>
                </h1>
                <ul class="new_customers_panel">
                    <li style="padding-bottom: 10px;">1. Get new customers
                        <span><?php echo Html::img('@web/images/new-customers.png',
                                ['class' => 'img-responsive']); ?></span> by showing up on Wizerd search engine.
                    </li>
                    <li>2. It takes less than 15 minutes to setup, anyone can do
                        it<span><?php echo Html::img('@web/images/business-badge.png',
                                ['class' => 'img-responsive']); ?></span>.
                    </li>
                    <li>3. It's Free … and pretty much grows <span><?php echo Html::img('@web/images/much-grows.png',
                                ['class' => 'img-responsive']); ?></span> you money!
                    </li>
                </ul> <!--./new_customers_panel-->
            </div>  <!--./container-->
        </div> <!--./jumbotron-->

        <!--signup-now block starts here-->
        <div class="container-fluid how-it-works-business-panel signup-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 style="color: #81aff9;">Space is limited.</h2>
                        <div class="sinuptext-wrapper">
                            <h3>Don't miss out.</h3>
                            <h3><a class="orng-clr" href="/sign-up" title="sign-up">Sign up today, click here.</a></h3>
                        </div>    <!--./sinuptext-wrapper-->
                    </div>
                </div>
                <br/>               
                <!--./row-->
            </div>
            <!--./container-->
        </div>
        <!--signup-now block ends here-->

        <!--consumer block starts here-->
        <div class="container-fluid how-it-works-business-panel consumer-wrapper how-wizerd-works">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2>How Wiz<em style="color: #81aff9; font-style: normal;">e</em>rd works</h2>
                        <h4>Free for business & consumer</h4>
                    </div>
                </div>
                <!--./row-->

                <div class="row">
                    <div class="tellus-wrapper padding-wrapper clearfix">
                        <div class="col-md-6 col-sm-6">
                            <h3>1. Tell us who you are</h3>

                            <p>Fill out a couple simple forms so we can get to know you. For example: business name,
                                address, hours of service, phone, etc. Everything you already know :).</p>
                        </div>
                        <div class="col-md-6 col-sm-6"> <?php echo Html::img('@web/images/tellus-laptop-img.png',
                                ['class' => 'img-responsive center-block']); ?> </div>
                    </div>
                    <!--./tellus-wrapper-->

                    <div class="tellus-servofferings padding-wrapper clearfix">
                        <div
                            class="col-md-6 col-sm-6 hidden-xs visible-lg"> <?php echo Html::img('@web/images/service-offerings-img.png',
                                ['class' => 'img-responsive center-block']); ?> </div>
                        <div class="col-md-6 col-sm-6">
                            <h3>2. Tell us your service offerings</h3>

                            <p>Create a menu for all the services your business offers. It's fully customizable to your
                                business needs. The process is crazy simple and takes less than 10 minutes to
                                complete.</p>
                        </div>
                        <div
                            class="col-md-6 col-sm-6 visible-xs hidden-lg"> <?php echo Html::img('@web/images/service-offerings-img.png',
                                ['class' => 'img-responsive center-block']); ?> </div>
                    </div>

                    <div class="tellus-promotions padding-wrapper clearfix">
                        <div
                            class="col-md-6 hidden-sm hidden-xs hidden-lg"> <?php echo Html::img('@web/images/how-it-works-promotions.png',
                                ['class' => 'img-responsive']); ?> </div>
                        <div class="col-md-6 col-sm-6">
                            <h3>3. Run promotions (optional)</h3>

                            <p>Send out real-time promotions to attract new customers. They can also be customized to
                                increase sales during slow days, even specific times. Promotions are a breeze and take
                                less than 60 seconds to setup.</p>
                        </div>
                        <div class="col-md-6"> <?php echo Html::img('@web/images/how-it-works-promotions.png',
                                ['class' => 'img-responsive center-block']); ?> </div>
                    </div>
                    <!--./tellus-promotions-->

                    <div class="tellus-relax padding-wrapper clearfix" style="padding-bottom:100px !important;">
                        <div
                            class="col-md-6 hidden-xs visible-lg"> <?php echo Html::img('@web/images/how-it-works-relax.png',
                                ['class' => 'img-responsive center-block']); ?> </div>
                        <div class="col-md-6 col-sm-6">
                            <h3>4. Relax</h3>

                            <p>Wizerd's local search engine takes over. As consumers search, Wizerd will promote your
                                business, services and promotions. This will drive new sales. Yep, it's pretty much new
                                customers hand delivered.</p>
                        </div>
                        <div
                            class="col-md-6 visible-xs hidden-lg"> <?php echo Html::img('@web/images/how-it-works-relax.png',
                                ['class' => 'img-responsive center-block']); ?> </div>
                    </div>
                </div>
            </div>
            <!--./container-->
        </div>

        <div class="container-fluid how-it-works-business-panel signup-wrapper-end signup-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2>The End</h2>
                        <h4>Space is limited.</h4>
                        <h3>Sign up for Free.</h3>
                    </div>
                </div>
			<?php echo $this->renderFile(dirname(__FILE__) . '/../user/register.php', [
                    'waitEmail' => $waitEmail,
                    'model' => $model,
                    'singlePage' => $singlePage
             ]); ?>
            </div>    <!--./container-->
        </div>    <!--./signup-wrapper-end-->

        <!--./how-it-works-business-panel-->
        <!--consumer block ends here-->
        <!--./how-it-works-business-panel-->
        <!--consumer block ends here-->
    </div>    <!--./how-it-works-wrapper-->
</div> <!--./business_welcome_wrapper-->
