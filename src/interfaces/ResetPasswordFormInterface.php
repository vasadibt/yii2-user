<?php

namespace vasadibt\user\interfaces;

/**
 * Interface ProfileFormInterface
 * @package vasadibt\user\interfaces
 */
interface ResetPasswordFormInterface extends FormModelInterface
{
    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword(): bool;
}