<?php
/**
 * @var $info array
 */

use yii\helpers\Html;

$this->title = 'Admin panel';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-panel">
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-briefcase glyphicon-big"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $info['bus'] ?></div>
                            <div>Business<?php if ($info['bus'] > 1) echo 'es'; ?></div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-user glyphicon-big"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $info['con'] ?></div>
                            <div>User<?php if ($info['con'] > 1) echo 's'; ?></div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-star glyphicon-big"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $info['act'] ?></div>
                            <div>Active zip<?php if ($info['act'] > 1) echo 's'; ?></div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-signal glyphicon-big"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <?php $req = $info['bus'] - $info['req']; ?>
                            <div class="huge"><?= $req ?></div>
                            <div>Requested zip<?php if ($req > 1) echo 's'; ?></div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
