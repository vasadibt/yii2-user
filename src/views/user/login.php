<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var ActiveForm $form */
/** @var \vasadibt\user\interfaces\LoginFormInterface $model */

$this->params['breadcrumbs'][] = ($this->title = Yii::t('user', 'Login'));

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

            <div style="color:#999;margin:1em 0">
                <?= Yii::t('user', 'If you forgot your password you can') ?> <?= Html::a(Yii::t('user', 'reset it'), ['/auth/forgot-password/reset-request']) ?>.
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
