<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\interfaces\LockLoginFormInterface;
use yii\web\Request;
use yii\web\User;

class LockLoginAction extends BaseAction
{
    /**
     * @param Request $request
     * @param LockLoginFormInterface $model
     * @return string|\yii\web\Response
     */
    public function run(
        Request                $request,
        LockLoginFormInterface $model
    )
    {
        $user = $model->user;
        if ($user === null) {
            return $this->goHome();
        }

        if ($model->load($request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->resetPasswordFields();

        return $this->render('login', compact('model', 'user'));
    }
}