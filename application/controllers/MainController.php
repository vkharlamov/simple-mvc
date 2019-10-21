<?php

namespace application\controllers;

use application\core\Application;
use application\core\Controller;
use application\core\Model;
use application\models\Task;
use Plasticbrain\FlashMessages\FlashMessages;

/**
 * Class MainController
 *
 * @package application\controllers
 */
class MainController extends Controller
{
    /**
     * Task list
     */
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $flashMsg = Application::$serviceLocator->get(FlashMessages::class);
            if ($this->createTask()) {
                $flashMsg->add('Succsess. Task was created.', $flashMsg::SUCCESS);
            } else {
                $flashMsg->add('There is an error. Task not created.', $flashMsg::ERROR);
            }
        }
        /** Task grid */
        $model = new Task();
        $data = $model->getList(
            $this->requestParams['get']
        );

        $data = array_merge([
            'view' => $this->view,
            'errors' => Model::$errors,
            'authorized' => Application::authorized(), // just mean that is admin user
        ], $data);


        $this->view->layout = Application::authorized() ? 'admin' : $this->view->layout;

        return $this->view->render('Task List', $data);
    }

    /**
     * Creates task by guest
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
}
