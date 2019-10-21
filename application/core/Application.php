<?php

namespace application\core;

use application\core\factory\DataBaseFactory;
use Plasticbrain\FlashMessages\FlashMessages;

/**
 * Class Application
 *
 * @package application\core
 */
class Application
{
    public static $router;
    public static $db;
    public static $serviceLocator;
    public static $config;

    /**
     * Application constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        static::$serviceLocator = new ServiceLocator();
        self::$config = $config;
    }

    /**
     * @return $this
     */
    public function init()
    {
        set_exception_handler(['application\core\Application', 'handleException']);
        $this->bootstrap();

        return $this;
    }

    /**
     * Init application' dependencies
     */
    protected function bootstrap()
    {
        // self instance
        static::$serviceLocator->addInstance(get_called_class(), $this);
        // Request
        static::$serviceLocator->addClass(Request::class);
        // Router
        static::$serviceLocator->addClass(Router::class);
        static::$router = static::$serviceLocator->get(Router::class);
        // db
        static::$serviceLocator->addClass(DataBaseFactory::class, self::$config['db']);
        static::$db = static::$serviceLocator->get(DataBaseFactory::class);
        // Flash messages
        static::$serviceLocator->addClass(FlashMessages::class);
    }

    /**
     * @return object Instance of /application/core/Request
     */
    public static function getRequest()
    {
        return static::$serviceLocator->get(Request::class);
    }

    /**
     * Exception handler
     *
     * @param \application\core\Throwable $e
     */
    public static function handleException(\Throwable $exception)
    {

        // Temporary solution
        $flashMsg = Application::$serviceLocator->get(FlashMessages::class);
        $flashMsg->add('Internal Server Error..', $flashMsg::WARNING);
        static::$router->redirect('');

        // @TODO catch exception, include Logger, etc
        if ($exception instanceof \Error) {
            // ...
        }
    }

    /**
     * Check user authorized
     *
     * @return bool
     */
    public static function authorized()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }

        return false;
    }

    /**
     * Simple auth
     * User authorized with $_SESSION variable 'user_id'
     *
     * @param $userId
     */
    public function setAuthorized(int $userId = 0): bool
    {
        if ($userId) {
            return $_SESSION['user_id'] = $userId;
        }
        return false;
    }

    /**
     * User logout
     * Delete session vars or delete session file
     *
     * @param bool|void(for session_unset) $delete
     */
    public function logout($deleteFileSession = false)
    {
        if ($deleteFileSession) {
            return session_destroy();
        }
        if (session_status() == PHP_SESSION_ACTIVE) {
            return session_unset(); // clear session array not file session
        }
    }
}
