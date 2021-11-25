<?php

namespace vasadibt\user\controllers\scenarios;

use vasadibt\user\controllers\actions\LoginForgotAction;
use vasadibt\user\controllers\actions\LogoutAction;
use vasadibt\user\controllers\BaseController;

class UserForgotPasswordController extends BaseController
{
    /**
     * @var array[]
     */
    public $actions = [
        'login' => LoginForgotAction::class,
        'logout' => LogoutAction::class,
    ];
    /**
     * @var array
     */
    public $accessRules = [
        ['actions' => ['login'], 'allow' => true],
        ['actions' => ['logout'], 'roles' => ['@'], 'allow' => true],
    ];
    /**
     * @var array[]
     */
    public $verbActions = [
        'logout' => ['post'],
    ];
}