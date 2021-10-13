<?php

namespace vasadibt\user\controllers;

use vasadibt\user\controllers\actions\LoginAction;
use vasadibt\user\controllers\actions\LogoutAction;

class UserController extends BaseController
{
    /**
     * @var array[]
     */
    public $actions = [
        'login' => LoginAction::class,
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