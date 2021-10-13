<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\interfaces\LoginFormInterface;
use vasadibt\user\Module;
use yii\web\Request;
use yii\web\User;

/**
 * Class LoginAction
 * @package vasadibt\user\actions
 */
class LoginAction extends BaseAction
{
    /**
     * @param Request $request
     * @param LoginFormInterface $model
     * @return string|\yii\web\Response
     */
    public function run(
        Request            $request,
        LoginFormInterface $model
    )
    {
        if ($model->load($request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->resetPasswordFields();

        return $this->render('login', compact('model'));
    }
}