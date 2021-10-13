<?php

namespace vasadibt\user\commands\command;

use Carbon\Carbon;
use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\Module;
use Yii;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Class UserCreateCommand
 * @package vasadibt\user\commands\command
 */
class UserCreateCommand extends BaseAction
{
    public function run(
        $email = null,
        $password = null
    )
    {
        $email = $email ?? $this->controller->prompt('Enter the email address:');
        $password = $password ?? $this->controller->prompt('Enter the password:');

        /** @var ExtendedIdentityInterface $user */
        /** @var Module $module */
        $module = $this->controller->module;
        $user = new $module->userClass;
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setStatus(10);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if (!$user->validate()) {
            $this->controller->stdout('Please fix following errors:' . PHP_EOL, Console::FG_RED);
            foreach ($user->getErrors() as $errors) {
                foreach ($errors as $error) {
                    $this->controller->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user->save();
        $this->controller->stdout('User has been created!' . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }
}