<?php

namespace vasadibt\user\forms;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\interfaces\LockLoginFormInterface;
use vasadibt\user\Module;
use Yii;
use yii\db\ActiveRecord;

/**
 * Login form
 *
 * @property ExtendedIdentityInterface $user
 */
class LockLogin extends BaseModel implements LockLoginFormInterface
{
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
            ['password', 'loginFailDetect'],
            ['password', 'required'],
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
        $this->module->loginFailDetect($this, $attribute, $this->user->getEmail());
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
            $this->module->loginFailCounterUpdate($this->user->getEmail());
            return false;
        }

        Yii::$app->user->switchIdentity($this->user, $this->module->rememberDuration);

        return true;
    }

    /**
     * Finds user by [[email]]
     *
     * @return ExtendedIdentityInterface|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Yii::$app->session->get(Module::SESSION_KEY_LOCK);

            if($this->_user instanceof ActiveRecord){
                $this->_user->refresh();
            }
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
