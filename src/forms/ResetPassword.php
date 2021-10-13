<?php

namespace vasadibt\user\forms;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\interfaces\ResetPasswordFormInterface;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;


/**
 * Password reset form
 *
 * @property ExtendedIdentityInterface $user
 */
class ResetPassword extends BaseModel implements ResetPasswordFormInterface
{
    /**
     * @var string
     */
    public $newPassword;
    /**
     * @var string
     */
    public $retypePassword;
    /**
     * @var array|mixed|ActiveRecordInterface|null
     */
    public $_user;

    /**
     * {@inheritDoc}
     * @throws NotFoundHttpException
     */
    public function init()
    {
        if ($this->user === null) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['newPassword', 'retypePassword'], 'required'],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
            ['newPassword', 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'newPassword' => Yii::t('user', 'New password'),
            'retypePassword' => Yii::t('user', 'Repeat new password'),
        ];
    }

    /**
     * Finds user by [[email]]
     *
     * @return ExtendedIdentityInterface|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $token = Yii::$app->request->get('token');

            if (!$this->module->userClass::isPasswordResetTokenValid($token)) {
                $this->_user = null;
            } else {
                $this->_user = $this->finder::find()
                    ->andWhere(['password_reset_token' => $token])
                    ->userFilter($this)
                    ->activeUserFilter($this)
                    ->one();
            }
        }

        return $this->_user;
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user->setPassword($this->newPassword);
        $this->user->setPasswordResetToken(null);
        return $this->user->save(false);
    }
}
