<?php

namespace vasadibt\user\commands\command;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\Module;
use yii\base\Action;

abstract class BaseAction extends Action
{
    /**
     * @param string $email
     * @return array|ExtendedIdentityInterface|\yii\db\ActiveRecordInterface|null
     */
    public function findUser($email)
    {
        /** @var ExtendedIdentityInterface|null $user */
        /** @var Module $module */
        $module = $this->controller->module;

        $user = $module->userClass::find()
            ->where(['email' => $email])
            ->one();
        return $user;
    }

}