<?php

namespace application\core;

use application\core\Application;
use application\core\Request;

/**
 * Class Model
 * Parent class for models
 *
 * @package application\core
 */
abstract class Model
{
    /**  @var array accumulates model errors  [modelName => [attr => message]] */
    public static $errors = [];

    /** @var instance current db class driver */
    protected $db = null;

    /**
     * Model constructor
     */
    public function __construct()
    {
        $this->db = Application::$db;
    }

    /**
     * Get db table name
     *
     * @return string
     */
    abstract public static function tableName();

    /**
     * Set model error messages
     *
     * @param $model
     * @param $attribute
     * @param $message
     */
    public function addError($model, $attribute, $message)
    {
        if (array_key_exists($model, static::$errors)) {
            static::$errors[$model] = array_merge(static::$errors[$model], [$attribute => $message]);
        } else {
            static::$errors[$model] = [$attribute => $message];
        }
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty(static::$errors);
    }
}
