<?php

namespace vasadibt\user\forms;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\interfaces\PasswordResetRequestFormInterface;
use Yii;

/**
 * Password reset request form
 *
 * @property ExtendedIdentityInterface $user
 */
class PasswordResetRequest extends BaseModel implements PasswordResetRequestFormInterface
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var ExtendedIdentityInterface
     */
    public $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'E-mail address'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->user instanceof ExtendedIdentityInterface) {
            $this->sendResetTokenEmail($this->user, $this->getPasswordResetToken($this->user));
        }

        return true;
    }

    /**
     * @param ExtendedIdentityInterface $user
     * @return string
     */
    public function getPasswordResetToken(ExtendedIdentityInterface $user)
    {
        if ($user->isPasswordResetTokenValid($passwordResetToken = $user->getPasswordResetToken())) {
            return $passwordResetToken;
        }

        $user->setPasswordResetToken($passwordResetToken = $user->generatePasswordResetToken());
        $user->save();

        return $passwordResetToken;
    }

    /**
     * Finds user by [[email]]
     *
     * @return ExtendedIdentityInterface|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = $this->finder::find()
                ->andWhere(['email' => $this->email])
                ->userFilter($this)
                ->activeUserFilter($this)
                ->one();
        }

        return $this->_user;
    }

    /**
     * @param ExtendedIdentityInterface $user
     * @param $passwordResetToken
     * @return bool
     */
    protected function sendResetTokenEmail(ExtendedIdentityInterface $user, $passwordResetToken)
    {
        $message = Yii::$app->mailer->compose($this->module->resetPasswordEmailTemplates, compact('user', 'passwordResetToken'));

        if ($this->module->resetPasswordEmailFrom) {
            $message->setFrom($this->module->resetPasswordEmailFrom);
        }

        $message->setTo($user->getEmail());
        $message->setSubject(sprintf($this->module->resetPasswordEmailSubject, ['{appName}' => Yii::$app->name]));

        return $message->send();
    }
}
