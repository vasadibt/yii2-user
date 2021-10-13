<?php

namespace vasadibt\user\interfaces;

/**
 * Interface LoginFormInterface
 * @package vasadibt\user\interfaces
 */
interface LoginFormInterface extends FormModelInterface, ResetPasswordInterface
{
    /**
     * @return bool
     */
    public function login(): bool;
}