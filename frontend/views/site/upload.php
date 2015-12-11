<?php
use yii\widgets\ActiveForm;

use yii\helpers\Html;

?>
<div class="containerqq" style="background-color: #FFFFFF;border-radius: 10px">
<div style=" clear:both;display:block;">
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']], ['data-pjax' => '']) ?>

    <?php echo $form->field($model, 'imageFiles')->widget(\kartik\file\FileInput::classname(), [
    'options' => ['accept' => 'image/*'],
    ]);?>

<?php ActiveForm::end() ?>
</div>
<?php foreach ($pic as $pc): ?>

    <div class=" col-xs-6 col-sm-3" style="position: relative;width: 19%">   <? $a=Html::img('@web/'.$pc->pic_name, ['alt' => ')))))))','data-lightbox'=>"roadtrip",'id'=>$pc->id,'style'=>['cursor'=>'pointer','width'=>'206px','height'=>'206px'],'class'=>'']) ?>
               <?= Html::a($a, ['@web/'.$pc->pic_name], ['data-lightbox'=>"roadtrip",'data-toggle'=>"confirmation"]) ?> <div class="nkr  "> <div aria-expanded="false" data-toggle="tooltip" title="Edit options" class=" dropdown ">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle edopt"> </a>

                <ul class="dropdown-menu list-opt">
               <li> <a href="#" class="list-group-item change">Make profile picture</a></li>
               <li> <a href="#" class="list-group-item delete">Delete</a></li>
        </ul>

            </div> </div></div>


<?php endforeach; ?>
</div>
</div>
<?php
$script = <<< JS

$(document).ready(function(){

$('[data-toggle="tooltip"]').tooltip();
$( ".col-xs-6" ).hover(

  function() {
    $("img").removeClass( "selected" );
console.log('1')
  $(this).find("img").addClass( "selected" );
  $(".nkr", this).css( 'visibility', 'visible' );

  }, function() {
;

  $(".nkr[style='visibility: visible;'] .dropdown").removeClass( "open" );

$( ".edopt" ).attr( "aria-expanded", "false" );
  }
);
$(".delete").click(function(){
$.confirm({
    title: 'Delete action!',
    content: 'Delete picture?',
    confirm: function(){
        var selected = $('.selected').attr("id")
  console.log(selected)
$.get('/site/deletepic/'+selected,{},function(data){
    location.reload();

})
    },
    cancel: function(){

    }
});

})
 $(".change").click(function () {
        var selected = $(".selected").attr("id")
        $.get('/site/savepic/' + selected, {}, function (data) {
$.alert({
    title: 'Info!',
    content: 'Picture changed',
    confirm: function(){
    }
});
        })
    })
})

JS;
$this->registerJs($script);
$this->registerJsFile('@web/js/lightbox.js');

?>
<style>

    .edopt{
        width: 25px;
        height: 25px;
        margin: 5px;
        background-image: url('/images/edit.png');
        background-size: cover;
        background-color: #EFEFEF;
        float: right;
        border-radius: 5px;
        border: 1px solid #777777;

    }
    a.list-group-item:hover,
    button.list-group-item:hover,
    a.list-group-item:focus,
    button.list-group-item:focus {
        color: white;
        text-decoration: none;
        background-color:#00b3ee;
    }
    .nkr{
        position: absolute;
        top: 5px;
        right: 5px;
        cursor: pointer;
        width: 25px;
        height: 25px;
    }
    .list-opt{
        font-size: 80%;
        position: absolute;
        top: 26px;
        left: -138px;
        width: 150px;
        cursor: pointer;
    }
    .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus{
        background-color: #286090;
        color: white;

    }
    .selected {
    }
    .close {
        position: absolute;
        left:210px;
    }
</style
