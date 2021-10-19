<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\interfaces\ResetPasswordFormInterface;
use Yii;
use yii\web\Request;
use yii\web\Session;

class ResetPasswordAction extends BaseAction
{
    /**
     * @param Request $request
     * @param Session $session
     * @param ResetPasswordFormInterface $model
     * @return string|\yii\web\Response
     */
    public function run(
        Request                    $request,
        Session                    $session,
        ResetPasswordFormInterface $model
    )
    {
        if ($model->load($request->post()) && $model->resetPassword()) {
            $session->setFlash('success', Yii::t('user', 'You have successfully set the new password.'));
            return $this->goHome();
        }

        return $this->render('reset-password', compact('model'));
    }
}