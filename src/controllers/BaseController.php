<?php

namespace vasadibt\user\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

abstract class BaseController extends Controller
{
    /**
     * @var array[]
     */
    public $actions = [];
    /**
     * @var array[]
     */
    public $accessRules = [];
    /**
     * @var array[]
     */
    public $verbActions = [];

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => $this->accessRules(),
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbActions(),
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function accessRules()
    {
        return $this->accessRules;
    }

    /**
     * @return array[]
     */
    public function verbActions()
    {
        return $this->verbActions;
    }
}