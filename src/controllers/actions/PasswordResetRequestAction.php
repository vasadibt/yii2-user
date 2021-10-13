<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\interfaces\PasswordResetRequestFormInterface;
use Yii;
use yii\web\Request;

class PasswordResetRequestAction extends BaseAction
{
    /**
     * @param Request $request
     * @param PasswordResetRequestFormInterface $model
     * @return string|\yii\web\Response
     */
    public function run(
        Request                           $request,
        PasswordResetRequestFormInterface $model
    )
    {
        if ($model->load($request->post()) && $model->sendEmail()) {
            return $this->goHome();
        }

        return $this->render('reset-request', compact('model'));
    }
}