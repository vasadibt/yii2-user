<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var ActiveForm $form */
/** @var \yii\base\Model $model */

$this->params['breadcrumbs'][] = ($this->title = Yii::t('user', 'Reset password'));

?>
<div class="forgot-password-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('user', 'Please choose your new password:')?></p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'newPassword')->passwordInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'retypePassword')->passwordInput() ?>

            <div class="form-group">
                <?= Html::a(Yii::t('user', 'Back to login'), ['/auth/user/login'], ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
