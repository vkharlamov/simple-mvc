<?php

namespace application\core\traits;

use application\models\Users;

trait UserValidator
{
    /**
     * @param array $params
     */
    public function validateUsername(array $params)
    {
        if (!isset($params['name']) || empty($params['name'])) {
            $this->addError(static::getClassNameShort(), 'name', 'Please enter User name.');
            return;
        }
        // Name must started from latin character first
        if (!preg_match('#^[a-zA-Z][a-zA-Z0-9_-]{' . Users::MIN_STRLEN_NAME . ',' . Users::MAX_STRLEN_NAME . '}$#', $params['name'])) {
            $this->addError(static::getClassNameShort(), 'name', 'Digits, chars, underscore, dash. No spaces. Min ' . Users::MIN_STRLEN_NAME . ', ' . 'Max ' . Users::MAX_STRLEN_NAME . ' symbols');
            return;
        }

        return;
    }

    /**
     * @param array $params
     */
    public function validatePassword(array $params)
    {
        if (!isset($params['password']) || empty($params['password'])) {
            $this->addError(static::getClassNameShort(), 'password', 'Please enter password.');
            return;
        }

        return;
    }
}
