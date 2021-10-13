<?php

namespace vasadibt\user\commands\command;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\Module;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Class UserDeleteCommand
 * @package vasadibt\user\commands\command
 */
class UserDeleteCommand extends BaseAction
{
    public function run($email = null)
    {
        if (!$this->controller->confirm('Are you sure? Deleted user can not be restored')) {
            return ExitCode::OK;
        }

        $email = $email ?? $this->controller->prompt('Enter the email address:');

        $user = $this->findUser($email);
        if ($user === null) {
            $this->controller->stdout('User is not found' . PHP_EOL, Console::FG_YELLOW);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        if (!$user->delete()) {
            $this->controller->stdout('Error occurred while deleting user' . PHP_EOL, Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->controller->stdout('User has been deleted' . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }
}