<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var ActiveForm $form */
/** @var \yii\base\Model $model */

$this->params['breadcrumbs'][] = ($this->title = Yii::t('user', 'Request password reset'));

?>
<div class="forgot-password-reset-request">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('user', 'Please fill out your email. A link to reset password will be sent there.')?></p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-request-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::a(Yii::t('user', 'Back to login'), ['/auth/user/login'], ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton(Yii::t('user', 'Send'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
