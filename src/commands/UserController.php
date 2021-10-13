<?php

namespace vasadibt\user\commands;

use vasadibt\user\commands\command\UserCreateCommand;
use vasadibt\user\commands\command\UserDeleteCommand;
use yii\console\Controller;

/**
 * Class UserController
 * @package vasadibt\user\commands
 */
class UserController extends Controller
{
    public $actions = [
        'create' => ['class' => UserCreateCommand::class],
        'delete' => ['class' => UserDeleteCommand::class],
        'change-password' => ['class' => UserDeleteCommand::class],
    ];

    /**
     * @return array|string[]
     */
    public function actions()
    {
        return $this->actions;
    }
}