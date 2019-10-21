<?php

namespace application\lib;

interface DBInterface
{
    /**
     * @param $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    public function query($sql, $params = []);

    /**
     * @param $sql
     * @param array $params
     * @return array
     */
    public function rows($sql, $params = []);

    /**
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public function column($sql, $params = []);

    /**
     * Get db driver instance
     * @return mixed
     */
    public function getDbObject();
}
