<?php

namespace vasadibt\user\forms;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\interfaces\LoginFormInterface;
use Yii;

/**
 * Login form
 *
 * @property ExtendedIdentityInterface $user
 */
class Login extends BaseModel implements LoginFormInterface
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
    /**
     * @var ExtendedIdentityInterface
     */
    protected $_user = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'loginFailDetect'],
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['user', 'validateUser'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'E-mail address'),
            'password' => Yii::t('user', 'Password'),
        ];
    }

    /**
     * @param $attribute
     */
    public function loginFailDetect($attribute)
    {
        if ($this->hasErrors()) {
            return;
        }
        $this->module->loginFailDetect($this, $attribute, $this->email);
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateUser($attribute)
    {
        if ($this->hasErrors()) {
            return;
        }
        if (!($this->user instanceof ExtendedIdentityInterface)) {
            $this->addError($attribute, Yii::t('user', 'Incorrect login informations.'));
            return;
        }

        $this->module->userValidate($this->user, $this);
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if ($this->hasErrors()) {
            return;
        }

        if ($this->user === null || !$this->user->validatePassword($this->$attribute)) {
            $this->addError($attribute, Yii::t('user', 'Incorrect login informations.'));
        }
    }


    /**
     * Logs in a user using the provided e-mail address and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if (!$this->validate()) {
            $this->module->loginFailCounterUpdate($this->email);
            return false;
        }

        return Yii::$app->user->login($this->user, $this->module->rememberDuration);
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
                ->where(['email' => $this->email])
                ->userFilter($this)
                ->one();
        }

        return $this->_user;
    }

    /**
     *
     */
    public function resetPasswordFields(): void
    {
        $this->password = '';
    }
}
