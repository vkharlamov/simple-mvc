<?php

namespace application\lib;

use \PDO;

/**
 * Class DbMysql
 *
 * @package application\lib
 */
class DbMysql implements DBInterface
{
    protected $dbObject;

    /**
     * DbMysql constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        try {
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            ];

            $this->dbObject = new PDO(
                'mysql:host=' . $config['host'] . ';dbname=' . $config['name'],
                $config['user'],
                $config['password'],
                $options
            );
        } catch (\PDOException $e) {
            echo 'Database connection can not be estabilished.' . $e->getMessage();
        }
    }

    /**
     * @inheritdoc
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->dbObject->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * @inheritdoc
     */
    public function rows($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @inheritdoc
     */
    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }

    /**
     * @return object
     */
    public function getDbObject(): \PDO
    {
        return $this->dbObject;
    }
}
