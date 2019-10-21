<?php

namespace application\models;

use application\core\Application;
use application\core\helper\ToolsHelper;
use application\core\Model;
use application\models\Users;
use application\core\traits\BaseValidator;
use application\core\traits\TaskValidator;
use application\core\traits\UserValidator;

/**
 * Class Task
 *
 * @package application\models
 */
class Task extends Model
{
    use BaseValidator;
    use TaskValidator;
    use UserValidator;

    const STATUS_NEW = 'new';
    const STATUS_DONE = 'done';
    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';
    const TASKS_PER_PAGE = 3;

    const MIN_STRLEN_DESCRIPTION = 5;
    const MAX_STRLEN_DESCRIPTION = 500;

    /** @var array Pagination. all allowed request fields */
    protected $safeFields = ['user', 'email', 'order', 'direction', 'page', 'status'];

    /** @var array Pagination. request fields for 'order by' clause */
    protected $safeOrderFields = ['user', 'email'];

    /** @var array Pagination. request fields for a 'where' clause */
    protected $whereSafeFields = [
        'status' => [self::STATUS_NEW, self::STATUS_DONE],
    ];

    /** @var array status allowed to edit */
    public static $editableStatuses = [self::STATUS_NEW, self::STATUS_DONE];

    /** @var array rules to update by admin */
    protected $adminUpdateFields = [
        'status',
        'description',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * Get task list for current page
     *
     * @param $params
     * @return mixed
     */
    public function getList(array $params)
    {
        $params = $this->sanitize($params);
        $page = isset($params['page']) ? $params['page'] : 1;
        $page = $page <= 0 ? 1 : $page;

        $where = $this->setWhereCondition($params);
        $orderBy = $this->setOrderBy($params);
        $limit = self::TASKS_PER_PAGE;

        $result = $this->paginate(
            self::tableName(),
            Users::tableName(),
            $where,
            $orderBy,
            $limit,
            $page - 1 // offset starts from 0
        );

        return $result;
    }

    /**
     * Sanitize request params
     *
     * @param array $params
     * @return array An array of $params allowed with $this->safeFields
     */
    protected function sanitize(array $params)
    {
        if (empty($params)) {
            return $params;
        }

        $result = [];
        $validFieldNames = array_intersect(array_keys($params), $this->safeFields);
        foreach ($validFieldNames as $field) {
            $result[$field] = $params[$field];
        }

        return $result;
    }

    /**
     * Set order by
     *
     * @param $params array
     * @return array [fieldName => order by direction]
     */
    protected function setOrderBy(array $params)
    {
        // Default order by user name
        $orderBy = [Users::tableName() . '.name' => self::ORDER_ASC];

        if (empty($params)) {
            return $orderBy;
        }

        if (isset($params['order'])) {
            $direction = self::ORDER_ASC;

            if (isset($params['direction'])) {
                $direction = in_array($params['direction'], [self::ORDER_ASC, self::ORDER_DESC])
                    ? $params['direction']
                    : $direction;
            }
            $orderBy = [$params['order'] => $direction];
        }

        return $orderBy;
    }

    /**
     * Set order by condition for status field
     *
     * @param $params array
     * @return array [filedName => value]
     */
    protected function setWhereCondition(array $params)
    {
        $where = [];
        if (empty($params)) {
            return $where;
        }
        if (isset($params['status'])) {
            $statusValue = $params['status'];
            $status = in_array($statusValue, $this->whereSafeFields['status'])
                ? ['status' => $statusValue]
                : ['status' => self::STATUS_NEW];

            $where = array_merge($where, $status);
        }

        return $where;
    }

    /**
     * @param array $params ['user_id', 'description', 'status']
     *
     * @return bool|integer false if not created or id of created task
     */
    public function create(array $params)
    {
        /** Set default task status if Guest creates the task */
        if (!Application::authorized() || !isset($params['status'])) {
            $params['status'] = Task::STATUS_NEW;
        }

        $this->validateDescription($params);
        $this->validateUsername($params);
        $this->validateEmail($params);

        if ($this->hasError()) {
            return false;
        }
        /** Let's create */
        $params['description'] = $this->sanitizeString($params['description']);
        try {
            $userModel = new Users();
            $userId = $userModel->getUserByEmail($params['email']);

            /** Secure check. Guest can't create task with admin credentials.*/
            if ($userId
                && $userModel->isRoleAdmin($userId)
                && !Application::authorized()
            ) {
                // Silently notice to Guest with error creating task.
                return false;
            }

            /** Create user if not exists */
            if (!$userId) {
                $userId = $userModel->create([
                    'email' => $params['email'],
                    'name' => $params['name'],
                ]);
            }
            if (!$userId) {
                return false;
            }
            /** Create Task */
            $pdo = $this->db->getDbObject();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("
                INSERT INTO " . self::tableName() . " (`user_id`, `description`, `status`, `description_hash`) " . " 
                VALUES (:user_id, :description, :status, :description_hash)"
            );
            $stmt->execute([
                'user_id' => $userId,
                'description' => $params['description'],
                'status' => $params['status'],
                'description_hash' => ToolsHelper::getHashFromString($params['description']),
            ]);
            $id = $pdo->lastInsertId();
            $pdo->commit();

            return $id;

        } catch (\Exception $e) {
            $pdo->rollBack();

            return false;
        }
    }

    /**
     * @param array $params
     * @param $editorId
     * @return bool
     */
    public function update(array $params, $editorId)
    {
        $this->validateStatus($params);
        $this->validateDescription($params);
        $params['description'] = $this->sanitizeString($params['description']);
        if (!$this->hasError()) {
            try {
                $setStmt = '';
                $values = [];
                /** @var $this ->adminUpdateFields allowed to update by admin */
                foreach ($this->adminUpdateFields as $field) {
                    $setStmt .= "$field = :$field,";
                    $values[$field] = $params[$field];
                }
                /** Check for updated description*/
                $currHash = $this->getDescriptionHash($params['id']);
                $newHash = ToolsHelper::getHashFromString($params['description']);
                if (false === hash_equals($currHash, $newHash)) {
                    $setStmt .= "`description_hash` = :description_hash,";
                    $setStmt .= "`edit_by` = :edit_by,";

                    $values['description_hash'] = $newHash;
                    $values['edit_by'] = $editorId;

                    $setStmt = rtrim($setStmt, ",");
                    $values = array_merge($values, ['id' => (int)$params['id']]);
                    $stmt = "UPDATE " . self::tableName() . " SET {$setStmt} WHERE id = :id";
                    $this->db->query($stmt, $values);
                }

                return true;
            } catch (\Exception $e) {
                // Logger placed here
                return false;
            }
        }
    }

    /**
     * @param int $taskId
     * @return mixed
     * @throws \Exception
     */
    protected function getDescriptionHash(int $taskId): string
    {
        if ($taskId) {
            $stmt = "
                SELECT description_hash FROM " . self::tableName() . "
                WHERE id = :id 
            ";
            if (!empty($hash = $this->db->column($stmt, ['id' => $taskId]))) {
                return $hash;
            }
        }
        throw new \Exception('Task not found. id = ' . $taskId);
    }

    /**
     * @param string $primaryTable
     * @param string $linkedTable Reference table to get user data
     * @param array $whereAndParams
     * @param array $orderBy
     * @param int $limitCount
     * @param int $pageNumber init with 0 value i.e first page
     * @return array
     */
    public function paginate(
        $primaryTable,
        $linkedTable,
        $whereAndParams = [],
        $orderBy = [],
        $limitCount = self::TASKS_PER_PAGE,
        $pageNumber = 0
    ) {
        $where = '';
        if (!empty($whereAndParams)) {
            $where = ' WHERE ';
            foreach ($whereAndParams as $param => $value) {
                $where .= " $param = :$param AND";
            }
            $where .= ' 1';
        }
        $order = '';
        if ($orderBy) {
            $order = " ORDER BY " . key($orderBy) . ' ' . reset($orderBy);
        }

        $limit = '';
        $offset = $pageNumber * $limitCount;

        if (!empty($limitCount)) {
            $limit = " LIMIT $offset, $limitCount";
        }

        $totalCount = $this->db->query("SELECT count(id) FROM $primaryTable" . $where, $whereAndParams)->fetchColumn();

        $gridData = $this->db->rows("
            SELECT $primaryTable.id,
                   $primaryTable.description,
                   $primaryTable.status,
                   $primaryTable.edit_by,
                   $linkedTable.name,
                   $linkedTable.email
            FROM $primaryTable
              INNER JOIN " . $linkedTable .
            " ON $linkedTable.id = $primaryTable.user_id
            " . $where . $order . $limit, $whereAndParams
        );

        return [
            'totalCount' => $totalCount,
            'pagesCount' => ceil($totalCount / $limitCount),
            'data' => $gridData,
        ];
    }
}
