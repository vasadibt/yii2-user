<?php

namespace vasadibt\user\interfaces;

/**
 * Interface ProfileFormInterface
 * @package vasadibt\user\interfaces
 */
interface PasswordResetRequestFormInterface extends FormModelInterface
{
    /**
     * @return bool
     */
    public function sendEmail(): bool;
}