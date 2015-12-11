<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\web\View;
use dosamigos\tinymce\TinyMce;
use yii\widgets\ActiveForm;

$this->title = 'My page';
$this->registerJsFile('@web/js/box.js');

?>
<div class="site-index" style="background-color: #FFFFFF;border-radius: 10px">
    <div class="container">

    <div style="position: absolute;right: 0px;width: 280px;"><div class="" style="border: 1px solid #0b3e6f;background-color: white;">
            <div class="alert alert-success">Friends</div>
            <ul class="list-group">

                <?php
                //print_r(Yii::$app->user->identity->id);
                //print_r($user_list);
                if (!empty($user_list)) {
                    foreach ($user_list as $us_li) {
                        if ($us_li['user_id'] != Yii::$app->user->identity->id) {
                            echo ' <li class="list-group-item fr_list" style="cursor:pointer" id="' . $us_li['user_id'] . '">' . Html::img('@web/' . $us_li['pic'] . '', ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '50px', 'display' => 'inline','margin-right'=>'30px' , 'height' => '50px'], 'class' => 'img-responsive ']). $us_li['first_name'] . ' ' . $us_li['last_name'] . '</li>';

                        }
                    }
                }

                ?>
            </ul>

        </div></div>
    <div class="jumbotron">


        <div class="row">
            <div class="col-md-3 " style="border-right: 1px solid silver;height: 450px" >
                <div class="ava_pic" >
                    <div class="avatar_change"><div class="update_text">Update Profile Picture</div></div>

                    <?php
                if (empty($pic)) {
                    echo Html::img('@web/uploads/def.jpg', ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '200px', 'height' => '200px'], 'class' => 'img-thumbnail ', 'id' => 'avatar']);

                } else {
                    ?>
                    <?php foreach ($pic as $pc): ?>

                        <?$b= Html::img('@web/' . $pc->pic_name, ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '200px', 'height' => '200px'], 'class' => 'img-thumbnail ', 'id' => 'avatar']) ?>
                        <?= Html::a($b, ['@web/'.$pc->pic_name], ['data-lightbox'=>"roadtrip",'data-toggle'=>"confirmation"]) ?>
                    <?php endforeach;
                } ?>

                </div>

                <div style="">
                    <?php foreach ($info_user as $inf_us): ?>
                        <h2 class="usr" style="display: inline" id="<?= $inf_us->id ?>"><?= $inf_us->first_name ?> <?= $inf_us->last_name ?></h2>
                      <p>  <span style="color: #4c4c4c"><?=floor((strtotime(date('Y-m-d'))-strtotime($inf_us->bd_date))/60/60/24/365) ?> years ,</span><span style="color: #4c4c4c;" ><?= $inf_us->country ?> </span></p>
                    <?php endforeach; ?>

                </div>
            </div>
            <div class="col-md-8">
                <div class="container">

                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']], ['data-pjax' => '']) ?>
                    <span>Post title</span><?= $form->field($edit, 'post_title')->label(false) ?>

                    <?= $form->field($edit, 'post')->label(false)->widget(TinyMce::className(), [
                        'options' => ['rows' => 6],
                        'language' => 'en_GB',
                        'clientOptions' => [
                            'plugins' => [
                                "advlist autolink lists link charmap print preview anchor",
                                "searchreplace visualblocks code fullscreen",
                                "insertdatetime media table contextmenu paste"
                            ],
                            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                        ]
                    ]);?>
                    <button  style="float: right" type="button" class="btn btn-primary post_status">Post</button>
                    <?php ActiveForm::end() ?>
                </div>

            </div>

        </div>
        <?php
        Modal::begin([
            'id' => 'ura',
            'header' => '<h3>Change picture</h3>',
            //'toggleButton' => ['label' => 'click me'],
            'footer' => '<button type="button" id="change" class="btn btn-primary  btn-xs">Change</button>',
        ]);
        foreach ($pic_avatar as $avatar) {
            echo Html::img('@web/' . $avatar->pic_name, ['alt' => ')))))))', 'id' => $avatar->id, 'style' => ['cursor' => 'pointer', 'width' => '100px', 'display' => 'inline', 'height' => '100px', 'margin' => '5px'], 'class' => 'avatari']);
        }

        Modal::end();
        ?>
        <div class="time_line">
            <h2>Timeline</h2>
            <ul id='timeline'>

        </div>
</div>
</div>

<div style="position: relative; z-index: 111;" id="dialog"></div>




    </ul>
    </div>
<style>
    .avatar_change{
        position: absolute;
        bottom: 5px;
        right: 4px;
        cursor: pointer;
        width: 191px;
        height: 40px;
        background-color: black;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        opacity: 0.7;
        color: white;
        -webkit-transition: height 1s; /* For Safari 3.1 to 6.0 */
        transition: height 1s;
    }
    .popover-content {
        color: #0a0a0a;
    }
    .update_text{
      margin: 10px auto 0px;
    }

    .avatar_change:hover{
        height: 80px;
    }
    .ava_pic{
        position: relative;
        width: 200px;
        height: 200px;
    }
    .selected {
        border: 3px solid deepskyblue;
    }
</style
<?php
$script = <<< JS

$(document).ready(function(){


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