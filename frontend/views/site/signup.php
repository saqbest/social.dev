<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\jui\DatePicker;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="container"style="background-color: ;border-radius: 10px;margin: 0 auto">
    <h1><?= Html::encode($this->title) ?> or <a  href="/site/login">Login</a></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>


            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>
            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'repeat_password')->passwordInput() ?>
            <?= $form->field($model, 'gender')
                ->radioList([
                    '1' => 'Female',
                    '2' => 'Male',
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
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>