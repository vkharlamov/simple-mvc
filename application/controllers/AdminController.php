<?php

namespace application\controllers;

use application\core\Application;
use application\core\Controller;
use application\core\helper\ToolsHelper;
use application\core\Model;
use application\models\Task;
use application\models\Users;
use Plasticbrain\FlashMessages\FlashMessages;

/**
 * Class AdminController
 *
 * @package application\controllers
 */
class AdminController extends Controller
{
    /**
     * AdminController constructor.
     *
     * @param $route
     * @param array $requestParams
     */
    function __construct($route, array $requestParams = [])
    {
        parent::__construct($route, $requestParams);
        $this->view->layout = 'admin';
    }

    /**
     * Login
     */
    public function loginAction()
    {
        if (Application::authorized()) {
            return Application::$router->redirect('main/index');
        }

        if ($this->request->isPost()) {
            $data = $this->requestParams['post'];
            if (!empty($userId = (new Users())->login($data))) {
                Application::$serviceLocator->get(Application::class)->setAuthorized($userId);
                Application::$router->redirect('main/index');
            } else {
                $flashMsg = Application::$serviceLocator->get(FlashMessages::class);
                $flashMsg->add('User not found.', $flashMsg::ERROR);
            }
        }

        $this->view->render('Sign up', [
            'errors' => Model::$errors,
            'authorized' => Application::authorized(), // just mean that is admin user
        ]);
    }

    /**
     * Create task
     *
     * @return bool|integer
     */
    protected function createTask()
    {
        if (!empty($post = $this->requestParams['post'])) {
            $taskId = (new Task())->create($post);
            return $taskId;
        }

        return false;
    }

    /**
     * Log out user admin
     */
    public function logoutAction()
    {
        Application::$serviceLocator->get(Application::class)->logout();

        return Application::$router->redirect('admin/login');
    }

    /**
     * Edit task
     */
    public function editAction()
    {
        if (empty($editorId = Application::authorized())) {
            return Application::$router->redirect('admin/login');
        }

        if ($this->request->isPost()) {
            $flashMsg = Application::$serviceLocator->get(FlashMessages::class);
            if ((new Task())->update($this->requestParams['post'], $editorId)) {
                $flashMsg->add('Success. Task was updated.', $flashMsg::SUCCESS);
            } else {
                $flashMsg->add('Fail. There is an error occurs during update. Task not updated.', $flashMsg::ERROR);
            }
        }
        /** Get task by id */
        if (!empty($taskId = $this->requestParams['get']['id'])) {
            $stmt = "
                SELECT id, description, status FROM " . Task::tableName() . "
                WHERE id = :id 
                ";
            $data = Application::$db->rows($stmt, ['id' => $taskId]);
            if (empty($data)) {
                Application::$serviceLocator->get(FlashMessages::class)
                    ->add("Task with id: {$taskId} not found.");

                return Application::$router->redirect('main/index');
            }

            return $this->view->render('Task Manger', [
                    'data' => array_pop($data),
                    'statuses' => Task::$editableStatuses,
                    'errors' => Model::$errors,
                ]
            );
        }
    }
}
