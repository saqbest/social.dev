<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\userSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
  <label> Input name or lastname <input type="text" class="form-control" name="userSearch[userFullName]" id="usersearch-userFullName"> </label>

<!--    --><?php // echo $form->field($model, 'first_name') ?>
    <?php  echo
    $form->field($model, 'gender')
        ->radioList([
            '1' => 'Female',
            '2' => 'Male',
            '' => 'Any'
        ]); ?>
<!--    --><?php // echo $form->field($model, 'last_name') ?>
<!--    --><?php //echo $form->field($model, 'bd_date') ?>
    <p>Age</p>
 <label>  From: <input type="text" style="width: 50px;display: inline;" class="form-control" name="userSearch[min_year]" id="min_year"></label><label>To:<input type="text" style="width: 50px;display: inline;" class="form-control" name="userSearch[max_year]" id="max_year"></label>

    <?= $form->field($model, 'country')->dropDownList(\yii\helpers\ArrayHelper::map(\frontend\models\Countries::find()->all(), 'country_name', 'country_name'), ['prompt' => 'Select Country']) ?>



    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
<!--        --><?//= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
