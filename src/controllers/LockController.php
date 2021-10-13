<?php

namespace vasadibt\user\controllers;

use vasadibt\user\controllers\actions\LockAction;
use vasadibt\user\controllers\actions\LockLoginAction;

class LockController extends BaseController
{
    /**
     * @var array[]
     */
    public $actions = [
        'login' => LockLoginAction::class,
        'down' => LockAction::class,
    ];
    /**
     * @var array[]
     */
    public $verbActions = [
        'down' => ['post'],
    ];

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }
}