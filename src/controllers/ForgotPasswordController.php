<?php

namespace vasadibt\user\controllers;

use vasadibt\user\controllers\actions\PasswordResetRequestAction;
use vasadibt\user\controllers\actions\ResetPasswordAction;

class ForgotPasswordController extends BaseController
{
    /**
     * @var array[]
     */
    public $actions = [
        'reset-request' => PasswordResetRequestAction::class,
        'reset-password' => ResetPasswordAction::class,
    ];
    /**
     * @var array
     */
    public $accessRules = [
        ['roles' => ['?'], 'allow' => true],
    ];
}