<?php

namespace vasadibt\user\finders;

use vasadibt\user\interfaces\ExtendedIdentityInterface;
use vasadibt\user\interfaces\UserFinderInterface;
use vasadibt\user\Module;
use Yii;
use yii\base\BaseObject;
use yii\base\Model;
use yii\db\ActiveQueryInterface;
use yii\db\Connection;

class UserFinder extends BaseObject implements UserFinderInterface
{
    public Connection $db;
    public Module $module;
    public ActiveQueryInterface $query;

    /**
     * @return UserFinderInterface
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function find()
    {
        /** @var UserFinderInterface $finder */
        $finder = Yii::createObject(static::class);
        return $finder;
    }

    /**
     * @param Connection $db
     * @param Module $module
     * @param array $config
     */
    public function __construct(Connection $db, Module $module, $config = [])
    {
        $this->db = $db;
        $this->module = $module;
        $this->query = $this->module->userClass::find();
        parent::__construct($config);
    }

    /**
     * @param $filter
     * @return $this
     */
    public function where($filter)
    {
        if (is_callable($filter)) {
            call_user_func($filter, $this->query, $this);
        } else {
            $this->query->where($filter);
        }
        return $this;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function andWhere($filter)
    {
        if (is_callable($filter)) {
            call_user_func($filter, $this->query, $this);
        } else {
            $this->query->andWhere($filter);
        }
        return $this;
    }

    /**
     * @param Model $form
     * @return UserFinder
     */
    public function userFilter(Model $form)
    {
        if ($filter = $this->module->userFilter) {
            if (is_callable($filter)) {
                call_user_func($filter, $this->query, $form, $this);
            } else {
                $this->query->andWhere($filter);
            }
        }
        return $this;
    }

    /**
     * @param Model $form
     * @return UserFinder
     */
    public function activeUserFilter(Model $form)
    {
        if ($filter = $this->module->activeUserFilter) {
            if (is_callable($filter)) {
                call_user_func($filter, $this->query, $form, $this);
            } else {
                $this->query->andWhere($filter);
            }
        }
        return $this;
    }

    /**
     * @param null $db
     * @return array|\yii\db\ActiveRecordInterface|null|ExtendedIdentityInterface
     */
    public function one($db = null)
    {
        return $this->query->one($db ?? $this->db);
    }

    /**
     * @param null $db
     * @return array|\yii\db\ActiveRecordInterface|null|ExtendedIdentityInterface[]
     */
    public function all($db = null)
    {
        return $this->query->one($db ?? $this->db);
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return $this|mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->query, $method)) {
            $this->query->{$method}(...$parameters);
            return $this;
        }

        return parent::__call($method, $parameters);
    }

}