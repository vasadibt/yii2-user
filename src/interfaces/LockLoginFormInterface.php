<?php

namespace vasadibt\user\interfaces;

/**
 * Interface LockLoginFormInterface
 * @package vasadibt\user\interfaces
 */
interface LockLoginFormInterface extends FormModelInterface, ResetPasswordInterface
{
    /**
     * @return bool
     */
    public function login(): bool;
}