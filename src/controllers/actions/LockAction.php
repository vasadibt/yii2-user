<?php

namespace vasadibt\user\controllers\actions;

use vasadibt\user\Module;
use Yii;
use yii\web\Session;
use yii\web\User;

/**
 * Class LockAction
 * @package vasadibt\user\actions
 */
class LockAction extends BaseAction
{
    /**
     * @param User $user
     * @param Session $session
     * @return \yii\web\Response
     */
    public function run(
        User    $user,
        Session $session
    )
    {
        $identity = $user->identity;
        $user->logout();
        $session->set(Module::SESSION_KEY_LOCK, $identity);
        return $this->redirect(['login']);
    }
}