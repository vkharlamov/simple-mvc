<?php

namespace application\core\traits;

use application\models\Task;

trait TaskValidator
{
    /**
     * @param array $params
     */
    public function validateDescription(array $params)
    {
        if (!isset($params['description']) || empty($params['description'])) {
            $this->addError(static::getClassNameShort(), 'description', 'Please enter a description');
            return;
        }
        if (mb_strlen($params['description']) < static::MIN_STRLEN_DESCRIPTION) {
            $this->addError(static::getClassNameShort(), 'description', 'Don\'t be so shortly ^)');
            return;
        }
        if (mb_strlen($params['description']) > static::MAX_STRLEN_DESCRIPTION) {
            $this->addError(static::getClassNameShort(), 'description', 'Max 255 symbols');
            return;
        }

        return;
    }

    /**
     * @param array $params
     */
    public function validateStatus(array $params)
    {
        if (!isset($params['status'])
            || empty($params['status'])
            || !in_array($params['status'], Task::$editableStatuses)
        ) {
            $this->addError(static::getClassNameShort(), 'status', 'field \'Status\' not set');
            return;
        }
    }
}
