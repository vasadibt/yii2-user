<?php

namespace vasadibt\user\commands\command;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\Module;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Class UserChangePasswordCommand
 * @package vasadibt\user\commands\command
 */
class UserChangePasswordCommand extends BaseAction
{
    /**
     * @param null $email
     * @param null $password
     * @return int
     */
    public function run(
        $email = null,
        $password = null
    )
    {
        $email = $email ?? $this->controller->prompt('Enter the email address:');
        $password = $password ?? $this->controller->prompt('Enter the password:');

        $user = $this->findUser($email);
        if ($user === null) {
            $this->controller->stdout('User is not found' . PHP_EOL, Console::FG_YELLOW);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user->setPassword($password);

        if (!$user->validate()) {
            $this->controller->stdout('Error occurred while changing password' . PHP_EOL, Console::FG_RED);
            foreach ($user->getErrors() as $errors) {
                foreach ($errors as $error) {
                    $this->controller->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user->save();
        $this->controller->stdout('Password has been changed' . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }
}