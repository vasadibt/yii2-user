<?php

namespace vasadibt\user\controllers\actions;

use yii\web\User;

/**
 * Class LogoutAction
 * @package vasadibt\user\actions
 */
class LogoutAction extends BaseAction
{
    /**
     * @param User $user
     * @return \yii\web\Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run(
        User $user
    )
    {
        $user->logout();
        return $user->loginRequired();
    }
}