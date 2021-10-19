<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\interfaces\PasswordResetRequestFormInterface;
use Yii;
use yii\web\Request;
use yii\web\Session;

class PasswordResetRequestAction extends BaseAction
{
    /**
     * @param Request $request
     * @param PasswordResetRequestFormInterface $model
     * @return string|\yii\web\Response
     */
    public function run(
        Request                           $request,
        Session                           $session,
        PasswordResetRequestFormInterface $model
    )
    {
        if ($model->load($request->post())) {
            $model->sendEmail();
            $session->setFlash('success', Yii::t('user', 'Check your email for further instructions.'));
            return $this->goHome();
        }

        return $this->render('reset-request', compact('model'));
    }
}