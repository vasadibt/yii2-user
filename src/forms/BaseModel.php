<?php

namespace vasadibt\user\forms;

use vasadibt\user\interfaces\FormModelInterface;
use vasadibt\user\interfaces\UserFinderInterface;
use vasadibt\user\Module;
use yii\base\Model;

abstract class BaseModel extends Model implements FormModelInterface
{
    /**
     * @var Module
     */
    public Module $module;
    /**
     * @var UserFinderInterface
     */
    public UserFinderInterface $finder;

    /**
     * @param Module $module
     * @param array $config
     */
    public function __construct(
        Module              $module,
        UserFinderInterface $finder,
                            $config = []
    )
    {
        $this->module = $module;
        $this->finder = $finder;
        parent::__construct($config);
    }
}