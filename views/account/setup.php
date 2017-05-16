<?php
/* @var $this yii\web\View */
/* @var $info_blocks array */
use yii\helpers\Html;

?>
<div class="row">
    <div class="container">
        <div class="form-group col-md-12">
            <div class="btn-group pull-right">
                <?= Html::button('Tip', ['class' => 'btn btn-default btn-tip-show',
                                         'onclick' => 'for_setup_tip()'
                ]); ?>
                <?= Html::button('Hide tip', ['class' => 'btn btn-default btn-tip-hide hidden',
                                              'onclick' => 'for_setup_tip()'

                ]); ?>
            </div>
        </div>
        <div class="panel panel-default panel-tip col-md-12">
            <div class="panel-body">

                <p>Tip: Completing your account setup</p>

                <p>
                    All mandatory information must be complete in order to make your Wizerd site live to consumers.
                    Customers love knowing all details about a business, so we encourage you to fill out the Recommended section too.
                    Businesses who do this are seeing an increase in sales.
                </p>
            </div>
        </div>
    </div>
</div>
<?php
//print_r($info_blocks['m']);
//print_r($info_blocks['h']);
//
//?>

<div class="business_setup clearfix">
    <?php foreach ($info_blocks as $info_block): ?>
        <div class="panel <?= $info_block['class'] ?>">

			<div class="col-sm-7">
            <div class="panel-heading account-panel-heading">
                <div class="row">
                    <div class="col-sm-6"><?= $info_block['header'] ?></div>
                    <div class="col-sm-6 text-right">Complete</div>
                </div>
            </div>
            </div>    
            <div class="col-sm-7">	
            <table class="table">
                <?php foreach ($info_block['data'] as $title => $complete): ?>
                    <tr>
                        <td class="setup_info"><?=Html::a($title,$complete['url']) ?></td>
                        <td class="setup_compare">
                            <?php if ($complete['value']): ?>
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            </div>        
            
        </div>
    <?php endforeach; ?>
</div>