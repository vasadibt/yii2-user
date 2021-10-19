<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\controllers\actions\BaseAction;
use vasadibt\user\interfaces\LoginFormInterface;
use vasadibt\user\interfaces\PasswordResetRequestFormInterface;
use Yii;
use yii\web\Request;
use yii\web\Session;

class LoginForgotAction extends BaseAction
{
    /**
     * @param Request $request
     * @param Session $session
     * @param LoginFormInterface $loginForm
     * @param PasswordResetRequestFormInterface $passwordResetRequestForm
     * @return string|\yii\web\Response
     */
    public function run(
        Request                           $request,
        Session                           $session,
        LoginFormInterface                $loginForm,
        PasswordResetRequestFormInterface $passwordResetRequestForm
    )
    {
        if ($request->isPost) {

            if ($loginForm->load($request->post())) {
                if ($loginForm->login()) {
                    return $this->goHome();
                } else {
                    $loginForm->resetPasswordFields();
                }
            } elseif ($passwordResetRequestForm->load($request->post())) {
                $passwordResetRequestForm->sendEmail();
                $session->setFlash('success', Yii::t('user', 'Check your email for further instructions.'));
                return $this->goHome();
            }

        }

        return $this->render('login-forgot-password', compact('loginForm', 'passwordResetRequestForm'));
    }
}