<?php
/* @var $this yii\web\View */
use kartik\file\fileinput;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;




$this->title = 'Conversation';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/msgconvr.js');
$this->registerCssFile('@web/css/msgconvr.css',['depends' => ['frontend\assets\AppAsset']]);

?>
<div class="site-index">

    <div class="jumbotron" style="background-color: white;border-radius: 10px">


        <div class="row">
            <div class="col-sm-4 user_list" style="height: 720px">
                <?php
                foreach ($user_list as $us_li) {
                    if ($us_li['user_id'] != Yii::$app->user->identity->id) {


                                echo ' <a class="list-group-item" style="cursor:pointer" id="' . $us_li['user_id'] . '">'.Html::img('@web/'.$us_li['pic'], ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '50px', 'height' => '50px','display'=>'inline','position'=>'relative','right'=>'70px','bottom'=>'0'], 'class' => 'img-responsive ', 'id' => 'avatar']) . $us_li['first_name'] . ' ' . $us_li['last_name'] . '<div class="msgcount" style=""></div></a>';



                        }

                }


                ?></div>
            <div class="col-sm-8" style="height: 720px" >
                <div id="dialog"style="position: relative;top: -29px;left: -11px;" ></div>

            </div>

        </div>

    </div>
</div>


<style>
    .user_list{
        height: 100%;
    }
</style>

