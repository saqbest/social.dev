<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\web\View;
use dosamigos\tinymce\TinyMce;
use yii\widgets\ActiveForm;

$this->title = 'User page';
//$this->registerJsFile('@web/js/box.js');

?>
    <div class="site-index" style="background-color: #FFFFFF;border-radius: 10px">

        <div class="jumbotron">


            <div class="row">
                <div class="col-md-3 " >
                    <div class="ava_pic" >

                        <?php
                        if (empty($pic_avatar)) {
                            echo Html::img('@web/uploads/def.jpg', ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '200px', 'height' => '200px'], 'class' => 'img-thumbnail ', 'id' => 'avatar']);

                        } else {
                            ?>
                            <?php foreach ($pic_avatar as $pc): ?>

                                <?$b= Html::img('@web/' . $pc->pic_name, ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '200px', 'height' => '200px'], 'class' => 'img-thumbnail ', 'id' => 'avatar']) ?>
                                <?= Html::a($b, ['@web/'.$pc->pic_name], ['data-lightbox'=>"roadtrip",'data-toggle'=>"confirmation"]) ?>
                            <?php endforeach;
                        } ?>

                    </div>


                </div>

                <div class="col-md-8">
                    <div class="container">
                    <div style="width: 450px;height: 50px">
                                        <?php foreach ($info_user as $inf_us): ?>
                      <h2 class="usr" style="display: inline" id="<?= $inf_us->id ?>"><?= $inf_us->first_name ?> <?= $inf_us->last_name ?></h2>    &nbsp;<span style="color: #4c4c4c"><?=floor((strtotime(date('Y-m-d'))-strtotime($inf_us->bd_date))/60/60/24/365) ?> years ,</span><span style="color: #4c4c4c;" ><?= $inf_us->country ?> </span>
                                    <?php endforeach; ?>

                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#timelinea">Timeline</a></li>
                            <li ><a data-toggle="tab" href="#home">Friends</a></li>
                            <li><a data-toggle="tab" href="#menu1">Photos</a></li>

                        </ul>

                        <div class="tab-content">
                        <div id="timelinea" class="tab-pane fade in active">
                                                  <div class="time_line">
        <h2>Timeline</h2>
        <ul id='timeline'>



        </ul>
    </div>
                                </div>


                            <div id="home" class="tab-pane fade ">
                                <h3>Friends</h3>

                                <ul class="list-group">
                                    <?php
                                  //  print_r($user_list);
                                   // print_r($id);
                                    if (!empty($user_list)) {
                                        foreach ($user_list as $us_li) {
                                            if ($us_li['user_id'] !== $id) {
                                                echo ' <li class="list-group-item fr_list" style="cursor:pointer;width:300px;" id="' . $us_li['user_id'] . '">' . Html::img('@web/' . $us_li['pic'] . '', ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '50px', 'display' => 'inline', 'position' => 'relative', 'right' => '70px', 'bottom' => '0px', 'height' => '50px'], 'class' => 'img-responsive ', 'id' => 'avatar']) . $us_li['first_name'] . ' ' . $us_li['last_name'] . '</li>';

                                            }
                                        }
                                    }

                                    ?>
                                </ul>
                                </div>
                            <div id="menu1" class="tab-pane fade">
                                <h3>Photos</h3>
                                <?php foreach ($pic as $pc): ?>

                                    <div class=" col-xs-6 col-sm-3" style="position: relative;width: 19%">   <? $a=Html::img('@web/'.$pc->pic_name, ['alt' => ')))))))','data-lightbox'=>"roadtrip",'id'=>$pc->id,'style'=>['cursor'=>'pointer','width'=>'100px','height'=>'100px'],'class'=>'img-thumbnail']) ?>
                                        <?= Html::a($a, ['@web/'.$pc->pic_name], ['data-lightbox'=>"roadtrip",'data-toggle'=>"confirmation"]) ?>


                                            </div>
                                                                             <?php endforeach; ?>


                                             </div>


                                            </div>


                            </div>

                        </div>
                    </div>



                </div>


            </div>

        </div>
    </div>




    <style>

        .ava_pic{
            position: relative;
            width: 200px;
            height: 200px;
        }
    </style
<?php
$script = <<< JS

$(document).ready(function(){
var id=$('.usr').attr('id')
console.log(id)
 $.post("/site/editor",{id:id})
        .done(function (data) {
            obj = jQuery.parseJSON(data)
            $.each(obj, function (key, value) {

                $("#timeline").prepend("<li class='work'>\
            <input class='radio' id='"+value.post_id+"' name='works' type='radio' checked>\
            <div class='relative'>\
                <label for='"+value.post_id+"'>"+value.post_title+"</label>\
                <span class='date'>"+value.posted_time+"</span>\
                <span class='circle'></span>\
            </div>\
            <div class='content'>\
                "+value.post+" \
            </div>\
        </li>");
            })
        })

})

JS;
$this->registerJs($script);
?>
<?php
$this->registerJsFile('@web/js/lightbox.js');
$this->registerCssFile('@web/css/chat.css', ['depends' => ['frontend\assets\AppAsset']]);
$this->registerCssFile('@web/css/timeline.css', ['depends' => ['frontend\assets\AppAsset']]);

$jsOptions = array( 'position' => View::POS_END );
?>