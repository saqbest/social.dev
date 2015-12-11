<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\user */
/* @var $model frontend\models\SignupForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'last_name') ?>
    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'gender')
        ->radioList([
            'Female' => 'Female',
            'Male' => 'Male',
        ]); ?>
    <?= $form->field($model, 'country')->dropDownList(\yii\helpers\ArrayHelper::map(\frontend\models\Countries::find()->all(), 'country_name', 'country_name'), ['prompt' => 'Select Country']) ?>
    <p>Select your birthday date</p>
    <?= DatePicker::widget([
        'model' => $model,
        'attribute' => 'bd_date',
        //'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]); ?>
    <p></p>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
