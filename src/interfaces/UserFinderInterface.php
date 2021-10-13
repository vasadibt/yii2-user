<?php

namespace vasadibt\user\interfaces;

use vasadibt\user\Module;
use yii\base\Model;
use yii\db\Connection;

interface UserFinderInterface
{
    /**
     * @return UserFinderInterface
     */
    public static function find();

    /**
     * @param callable|array $where
     * @return UserFinderInterface
     */
    public function where($where);

    /**
     * @param callable|array $where
     * @return UserFinderInterface
     */
    public function andWhere($where);

    /**
     * @param Model $form
     * @return UserFinderInterface
     */
    public function userFilter(Model $form);

    /**
     * @param Model $form
     * @return UserFinderInterface
     */
    public function activeUserFilter(Model $form);

    /**
     * @param null $db
     * @return array|\yii\db\ActiveRecordInterface|null|ExtendedIdentityInterface
     */
    public function one($db = null);

    /**
     * @param null $db
     * @return array|\yii\db\ActiveRecordInterface|null|ExtendedIdentityInterface[]
     */
    public function all($db = null);
}