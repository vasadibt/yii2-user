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
    public $_user = false;

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

        if (!($this->user instanceof ExtendedIdentityInterface)) {
            return false;
        }

        $this->refreshPasswordResetToken($this->user);

        return $this->sendResetTokenEmail();
    }

    public function refreshPasswordResetToken(ExtendedIdentityInterface $user)
    {
        if (!$user->isPasswordResetTokenValid($user->getPasswordResetToken())) {
            $user->generatePasswordResetToken();
            $user->save();
        }
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
     * @return bool
     */
    protected function sendResetTokenEmail()
    {
        $user = $this->user;
        $message = Yii::$app->mailer->compose($this->module->resetPasswordEmailTemplates, compact('user'));
        $message->setTo($user->getEmail());
        $message->setSubject($this->module->resetPasswordEmailSubject);
        return $message->send();
    }
}
