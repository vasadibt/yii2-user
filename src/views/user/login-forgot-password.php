<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var ActiveForm $form */
/** @var \vasadibt\user\interfaces\LoginFormInterface $model */

$this->params['breadcrumbs'][] = ($this->title = Yii::t('user', 'Login'));

$js = <<<JS
setTimeout(function() {
    $('.card').removeClass('card-hidden');
    $('.logo-container').removeClass('hidden');
}, 700);

$('.forgot-pass-btn').click(function (){
    $('#login-form').slideUp(300);
    $('#forgot-password-form').slideDown(300);
});

$('.back-to-login').click(function () {
    $("#login-form").slideDown(300);
    $("#forgot-password-form").slideUp(300);
});

JS;
$this->registerJs($js);

?>
<div class="user-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p></p>
    <p><?= Yii::t('user', 'Please fill out the following fields to login:') ?></p>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= Html::button(Yii::t('materialdashboard', 'Forgot your password?'), [
                'class' => 'btn btn-info forgot-pass-btn',
                'tabindex' => '-1',
            ]) ?>
            <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary float-right']) ?>
            <?php ActiveForm::end(); ?>


            <?php $form = ActiveForm::begin(['id' => 'forgot-password-form', 'options' => ['style' => 'display: none;']]); ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= Html::button(Yii::t('materialdashboard', 'Back to login'), ['class' => 'btn btn-warning back-to-login']) ?>
            <?= Html::submitButton(Yii::t('user', 'Send'), ['class' => 'btn btn-primary float-right']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
