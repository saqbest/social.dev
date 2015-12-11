<?php
use yii\widgets\Pjax;

use yii\helpers\Html;
use yii\widgets\ListView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\userSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User search';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index" style="background-color: #FFFFFF;border-radius: 10px">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <div class="row">
        <div class="col-sm-5" style="">
            <ul class="list-group">

            <?= ListView::widget([
                'dataProvider' => $dataProvider,

                'summary'=>false,
                'itemView' => function($model) {
                    $sql = 'SELECT * FROM `friend_list` WHERE  `from`=' . Yii::$app->user->identity->id . ' and `to`=' . $model->id . ' or   `from`=' . $model->id . ' and `to`=' . Yii::$app->user->identity->id . '';
                    $res2 = \app\models\FriendList::findBySql($sql)->asArray()->all();


                    if ($model->id !== Yii::$app->user->identity->id && $res2[0]['status'] == 0 ) {
                        if ($model->id == $res2[0]['to']) {


                            $res = \frontend\models\Page::find()->select('pic_name')->where(['user_id' => $model->id, 'status' => 1])->asArray()->all();
                            $pic_url = (empty($res)) ? 'uploads/def.jpg' : $res[0]['pic_name'];

                            return '<div class="list-group">
  <li  class="list-group-item"  style="height: 125px;width: 350px;margin-left: 30px;">
  ' . Html::img('@web/' . $pic_url, ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '100px', 'height' => '100px', 'display' => 'inline', 'float' => 'left', 'margin-right' => '5px'], 'class' => 'img-thumbnail ']) . '
   <a href="/site/profile/'.$model->id.'" <h4 class="list-group-item-heading"> '. $model->first_name . " " . $model->last_name . '</h4></a>

    <p class="list-group-item-text">' . $model->country . '</p>
    <p class="list-group-item-text">' . floor((strtotime(date('Y-m-d')) - strtotime($model->bd_date)) / 60 / 60 / 24 / 365) . ' years' . '</p>
    <p class="list-group-item-text but" style="margin-top: 6px;"><span class="label label-info">Request sended</span>
</p>
</li>
</div>';
                        } elseif(Yii::$app->user->identity->id == $res2[0]['to']) {
                            $res = \frontend\models\Page::find()->select('pic_name')->where(['user_id' => $model->id, 'status' => 1])->asArray()->all();
                            $pic_url = (empty($res)) ? 'uploads/def.jpg' : $res[0]['pic_name'];

                            return '<div class="list-group">
  <li   class="list-group-item"  style="height: 125px;width:350px;margin-left: 30px;">
  ' . Html::img('@web/' . $pic_url, ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '100px', 'height' => '100px', 'display' => 'inline', 'float' => 'left', 'margin-right' => '5px'], 'class' => 'img-thumbnail ']) . '
   <a href="/site/profile/'.$model->id.'" <h4 class="list-group-item-heading">' . $model->first_name . " " . $model->last_name . '</h4></a>
    <p class="list-group-item-text">' . $model->country . '</p>
    <p class="list-group-item-text">' . floor((strtotime(date('Y-m-d')) - strtotime($model->bd_date)) / 60 / 60 / 24 / 365) . ' years' . '</p>
    <p class="list-group-item-text but" style="margin-top: 6px;"><p class="list-group-item-text but" style="margin-top: 6px;"><button data_req="'.$model->id.'" type="button" class="btn btn-primary post_status add_fr">Accept</button> <button data_req="'.$model->id.'" type="button" class="btn btn-danger post_status del"> Cancel</button>

</p>
  </li>
</div>';
                        }

                        else{
                            $res = \frontend\models\Page::find()->select('pic_name')->where(['user_id' => $model->id, 'status' => 1])->asArray()->all();
                            $pic_url = (empty($res)) ? 'uploads/def.jpg' : $res[0]['pic_name'];

                            return '<div class="list-group">
  <li  class="list-group-item"  style="height: 125px;width:350px;margin-left: 30px;">
  ' . Html::img('@web/' . $pic_url, ['alt' => 'Chenge picture', 'style' => ['cursor' => 'pointer', 'width' => '100px', 'height' => '100px', 'display' => 'inline', 'float' => 'left', 'margin-right' => '5px'], 'class' => 'img-thumbnail ']) . '
    <a href="/site/profile/'.$model->id.'" <h4 class="list-group-item-heading">' . $model->first_name . " " . $model->last_name . '</h4></a>
    <p class="list-group-item-text">' . $model->country . '</p>
    <p class="list-group-item-text">' . floor((strtotime(date('Y-m-d')) - strtotime($model->bd_date)) / 60 / 60 / 24 / 365) . ' years' . '</p>
    <p class="list-group-item-text but" style="margin-top: 6px;"><button id="' . $model->id . '" type="button" class="btn btn-primary post_status add">Add to friends</button>
</p>
  </li>
</div>';
                        }
                    }

                },

            ]); ?>
                </ul>
        </div>
        <div class="col-sm-3" style="">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>        </div>
        <?php Pjax::end(); ?>
    </div>



</div>
<?php
$script = <<< JS

$(document).ready(function(){
$( "#w2" ).change(function() {
$(".btn-primary").click();
})


    ///////////////////
    $.post("/user/sendrequest")
                .done(function (data) {
                 obj = jQuery.parseJSON(data)


            $.each(obj, function (key, value) {
                    $(".but").each(function () {
                 var fr_id = $(this).find(".add").attr("id");
                    if(fr_id==value.to){
                    $(this).html('  <a href="#" class="btn btn-info disabled" role="button">Request sended</a>')
                    }
                    else {
                   // $(this).removeClass("disabled")

                    }
                })

            })

                })
    ///////////////////////
$(".add").click(function(){
 var req_id=$(this).attr("id");
 if(req_id){
 $.post("/user/sendrequest", {req_id: req_id})
                .done(function (data) {
                 obj = jQuery.parseJSON(data)


            $.each(obj, function (key, value) {
                    $(".but").each(function () {
                 var fr_id = $(this).find(".add").attr("id");
                    if(fr_id==value.to){
                    $(this).html('<span class="label label-info">Request sended</span>')
                    }
                    else {
                   // $(this).removeClass("disabled")

                    }
                })

            })

                })
                }
})


})

JS;
$this->registerJs($script);
?>