<?php

namespace vasadibt\user;

use vasadibt\user\finders\UserFinder;
use vasadibt\user\forms\LockLogin;
use vasadibt\user\forms\Login;
use vasadibt\user\forms\PasswordResetRequest;
use vasadibt\user\forms\ResetPassword;
use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\interfaces\LockLoginFormInterface;
use vasadibt\user\interfaces\LoginFormInterface;
use vasadibt\user\interfaces\PasswordResetRequestFormInterface;
use vasadibt\user\interfaces\ResetPasswordFormInterface;
use vasadibt\user\interfaces\UserFinderInterface;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\console\Application as ConsoleApplication;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\Connection;
use yii\di\Container;
use yii\web\Application as WebApplication;

/**
 * Class Module
 * @package vasadibt\user
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    const SESSION_KEY_LOCK = 'locked-user';
    const SESSION_KEY_LOGIN_FAIL = 'login_fail';

    const IP = 'ip';
    const EMAIL = 'email';
    const IP_AND_EMAIL = 'ip_and_email';

    /**
     * @var ExtendedIdentityInterface
     */
    public $userClass;
    /**
     * @var array
     */
    protected $definitions = [
        LoginFormInterface::class => Login::class,
        LockLoginFormInterface::class => LockLogin::class,
        PasswordResetRequestFormInterface::class => PasswordResetRequest::class,
        ResetPasswordFormInterface::class => ResetPassword::class,
        UserFinderInterface::class => UserFinder::class,
    ];
    /**
     * @var callable Bootstrap call module custom register parts
     *
     * ```php
     * function (Container $container, array $definitions, Module $module) {
     *     // register definitions to container
     * }
     * ```
     *
     * If this property is not set, then simple container set definitions
     */
    public $register;
    /**
     * @var callable|array
     */
    public $userFilter;
    /**
     * @var callable|array
     */
    public $activeUserFilter;
    /**
     * @var callable
     */
    public $userValidate;
    /**
     * @var string[]
     */
    public $resetPasswordEmailTemplates = [
        'html' => 'passwordResetToken-html',
        'text' => 'passwordResetToken-text',
    ];
    /**
     * @var string[] email => name
     */
    public $resetPasswordEmailFrom = [];
    /**
     * @var string
     */
    public $resetPasswordEmailSubject;
    /**
     * @var int
     */
    public $rememberDuration = 86400;
    /**
     * @var bool
     */
    public $autoSetLoginUrl = true;
    /**
     * @var bool
     */
    public $autoSetLayout = true;
    /**
     * @var string
     */
    public $guestLayout = 'guest';
    /**
     * @var string
     */
    public $authLayout = 'main';

    public $loginFailDetect = true;
    public $loginFailType = self::IP_AND_EMAIL;
    public $loginFailMax = 3;

    /**
     * Initializes the module.
     */
    public function init()
    {
        if ($this->controllerNamespace === null) {
            $subNamespace = Yii::$app instanceof ConsoleApplication ? 'commands' : 'controllers';
            $this->controllerNamespace = __NAMESPACE__ . '\\' . $subNamespace;
        }

        parent::init();

        if ($this->userClass === null) {
            if (Yii::$app->get('user', false) !== null) {
                $this->userClass = Yii::$app->user->identityClass;
            } else {
                throw new InvalidConfigException("The 'userClass' option is required.");
            }
        }

        if ($this->resetPasswordEmailSubject === null) {
            $this->resetPasswordEmailSubject = Yii::t('user', '{appName} :: Password reset', ['appName' => Yii::$app->name]);
        } else {
            $this->resetPasswordEmailSubject = strtr($this->resetPasswordEmailSubject, ['{appName}' => Yii::$app->name]);
        }
    }

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $this->register($app, Yii::$container);

        if ($app instanceof WebApplication) {

            if ($this->autoSetLoginUrl) {
                $app->user->loginUrl = [$this->id . '/user/login'];
            }

            if ($this->autoSetLayout) {
                $app->layout = $app->user->isGuest ? $this->guestLayout : $this->authLayout;
            }
        }
    }

    /**
     * @param \yii\base\Application $app
     * @param Container $container
     */
    protected function register($app, $container)
    {
        $definitions = array_merge(
            [
                get_class($app->db) => $app->db,
                get_class($this) => $this,
            ],
            $this->definitions
        );

        if (is_callable($this->register)) {
            call_user_func($this->register, $container, $definitions, $this);
        } else {
            $container->setDefinitions($definitions);
        }
    }

    /**
     * @param Model $form
     * @param string $attribute
     * @param string $email
     */
    public function loginFailDetect(
        Model $form,
              $attribute,
              $email
    )
    {
        if (!$this->loginFailDetect) {
            return;
        }
        $email = strtolower($email);

        $cache = Yii::$app->cache;
        $bannedIp = false;
        $bannedEmail = false;

        if (in_array($this->loginFailType, [static::IP, static::IP_AND_EMAIL])) {
            if ($ipFail = $cache->get([static::SESSION_KEY_LOGIN_FAIL, Yii::$app->request->userIP])) {
                $bannedIp = $ipFail > $this->loginFailMax;
            }
        }

        if (in_array($this->loginFailType, [static::EMAIL, static::IP_AND_EMAIL])) {
            if ($emailFail = $cache->get([static::SESSION_KEY_LOGIN_FAIL, $email])) {
                $bannedEmail = $emailFail > $this->loginFailMax;
            }
        }

        if ($bannedIp || $bannedEmail) {
            $form->addError($attribute, Yii::t('user', 'Too many incorrect login attempts.'));
        }
    }

    public function loginFailCounterUpdate($email)
    {
        if (!$this->loginFailDetect) {
            return;
        }
        $email = strtolower($email);

        $cache = Yii::$app->cache;
        $ipKey = [static::SESSION_KEY_LOGIN_FAIL, Yii::$app->request->userIP];
        $emailKey = [static::SESSION_KEY_LOGIN_FAIL, $email];

        if (in_array($this->loginFailType, [static::IP, static::IP_AND_EMAIL])) {
            $cache->set($ipKey, intval($cache->get($ipKey)) + 1);
        }

        if (in_array($this->loginFailType, [static::EMAIL, static::IP_AND_EMAIL])) {
            $cache->set($emailKey, intval($cache->get($emailKey)) + 1);
        }
    }

    /**
     * @param ExtendedIdentityInterface $user
     * @param Model $form
     */
    public function userValidate(ExtendedIdentityInterface $user, Model $form)
    {
        if ($this->userValidate) {
            call_user_func($this->userValidate, $user, $form, $this);
        }
    }
}