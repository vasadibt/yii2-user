<?php

namespace vasadibt\user\interfaces;

use yii\db\ActiveRecordInterface;
use yii\web\IdentityInterface;

/**
 * Interface ExtendedIdentityInterface
 * @package vasadibt\user\interfaces
 */
interface ExtendedIdentityInterface extends IdentityInterface, ActiveRecordInterface, ModelInterface
{
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password);

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password);

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey();

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken();

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken();

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken();

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token);

    /**
     * Return the user email address
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set the user email address
     */
    public function setEmail($email);

    /**
     * Return the user status
     *
     * @return int
     */
    public function getStatus();

    /**
     * Set the user status
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getPasswordResetToken();

    /**
     * @param $passwordResetToken
     * @return void
     */
    public function setPasswordResetToken($passwordResetToken);
}