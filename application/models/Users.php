<?php

namespace application\models;

use application\core\Model;
use application\core\Application;
use application\core\traits\BaseValidator;
use application\core\traits\UserValidator;

/**
 * Class Users
 *
 * @package application\models
 */
class Users extends Model
{
    use BaseValidator;
    use UserValidator;

    const ROLE_ADMIN = 'admin';
    const MIN_STRLEN_NAME = 3;
    const MAX_STRLEN_NAME = 16;
    const MAX_EMAIL_SIZE = 255;

    public static function tableName()
    {
        return 'users';
    }

    /**
     * Creates user.
     *
     * @param array $params
     * @return int id of created row
     */
    public function create(array $params)
    {
        try {
            $pdo = $this->db->getDbObject();
            $fields = implode(",", array_keys($params));

            $pdo->beginTransaction();
            $stmt = $pdo->prepare("
                INSERT INTO " . self::tableName() . " ($fields) " . " 
                VALUES (:email, :name)"
            );

            $stmt->execute($params);
            $id = $pdo->lastInsertId();
            $pdo->commit();

            return $id;

        } catch (\Exception $e) {
            $pdo->rollBack();

            return false;
        }
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserByEmail(string $email)
    {
        $userId = $this->db->column("
            SELECT id FROM " . Users::tableName() . "
            WHERE email = :email    
            ", ['email' => $email]
        );

        return $userId;
    }

    /**
     * Check for admin role.
     * Entity with not empty password field means admin user
     *
     * @param int $userId
     * @return bool
     */
    public static function isRoleAdmin(int $userId): bool
    {
        if ($userId) {
            $stmt = "
                SELECT count(id) FROM " . self::tableName() . "
                WHERE id = :id 
                AND password IS NOT NULL";

            return (bool)Application::$db->column($stmt, ['id' => $userId]);
        }

        return false;
    }

    /**
     * User log in
     *
     * @param array $data [password, name]
     * @return bool|integer userId or false if none
     */
    public function login(array $data = [])
    {
        $this->validatePassword($data);
        $this->validateUsername($data);
        if ($this->hasError()) {
            return false;
        }
        $password = $this->mysqlRealEscapeString($data['password']);
        $password = $this->getPasswordHash($password);
        $userId = $this->db->column("
              SELECT id FROM users 
              WHERE name = :name
              AND password = :password", ['password' => $password, 'name' => $data['name']]);

        return $userId;
    }

    /**
     * Password hash
     *
     * @param string $str
     * @return string
     */
    private function getPasswordHash(string $str): string
    {
        $str = $str . Application::$config['salt'];
        $str = hash('sha256', $str);
        return $str;
    }
}
