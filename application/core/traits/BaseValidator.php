<?php

namespace application\core\traits;

use application\models\Users;

trait BaseValidator
{
    public function validateEmail(array $params)
    {
        if (!isset($params['email']) && empty($params['email'])) {
            $this->addError(static::getClassNameShort(), 'email', 'Please enter e-mail.');
            return;
        }
        if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            $this->addError(static::getClassNameShort(), 'email', 'Invalid e-mail format.');
            return;
        }
        if (mb_strlen($params['email']) > Users::MAX_EMAIL_SIZE) {
            $this->addError(static::getClassNameShort(), 'email', 'Realy?');
            return;
        }

        return;
    }

    /**
     * @param string $str
     */
    public function sanitizeString(string $str)
    {
        $str = htmlspecialchars(trim($str));
        $str = $this->mysqlRealEscapeString($str);

        return $str;
    }

    /**
     * mysql_real_escape_string
     *
     * @param $value
     * @return mixed
     */
    public function mysqlRealEscapeString(string $str)
    {
        $search = ["\\", "\x00", "\n", "\r", "'", '"', "\x1a"];
        $replace = ["\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z"];

        return str_replace($search, $replace, $str);
    }

//    public function quote(string $str)
//    {
//        return mysql_real_escape_string($str);
//    }

    /**
     * Get class short name
     *
     * @return string
     * @throws \ReflectionException
     */
    public static function getClassNameShort()
    {
        return (new \ReflectionClass(get_called_class()))->getShortName();
    }
}
