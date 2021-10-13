<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\interfaces\ResetPasswordFormInterface;
use yii\web\Request;
use yii\web\Session;

class ResetPasswordAction extends BaseAction
{
    public function run(
        Request                    $request,
        ResetPasswordFormInterface $model
    )
    {
        if ($model->load($request->post()) && $model->resetPassword()) {
            \Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
            return $this->goHome();
        }

        return $this->render('reset-password', compact('model'));
    }
}