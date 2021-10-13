<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var ActiveForm $form */
/** @var \vasadibt\user\interfaces\LockLoginFormInterface $model */

$this->params['breadcrumbs'][] = ($this->title = Yii::t('user', 'Login'));

?>
<div class="lock-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('user', 'Please fill out the following fields to login:')?></p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'lock-login-form']); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::a(Yii::t('user', 'Change account'), ['/auth/user/login'], ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
