<?php
/**
 * AST PHP framework.
 * A Simple Thing
 * a simple, light-weight, high-performance another PHP framework
 * Athor: Hua
 */
class Core
{

    public function init()
    {
        spl_autoload_register(array($this, 'loadClass'));
        $this->setReporting();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->route();
    }

    public function route()
    {
        $controller = 'Index';
        $action = 'index';

        if (!empty($_GET['url'])) {
            $url = $_GET['url'];
            $urlArray = explode('/', $url);

            //获取控制器
            $controller = empty($urlArray) ? 'Index' : ucfirst($urlArray[0]);

            //获取动作
            array_shift($urlArray);
            $action = empty($urlArray) ? 'index' : $urlArray[0];

            //获取URL参数
            array_shift($urlArray);
            $queryString = empty($urlArray) ? array() : $urlArray;
        }

        $queryString = empty($queryString) ? array() : $queryString;
        $controllerName = $controller;
        $controller = ucfirst($controller);
        $model = $controller.'Model';
        $controller .= 'Controller';
        $dispatch = new $controller($model, $controllerName, $action);

        if (method_exists($controller, $action))
        {
            call_user_func_array(array($dispatch, $action), $queryString);
        }
    }

    //如果是开发环境，显示错误
    private function setReporting()
    {
        if (DEBUG == true)
        {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }
        else
        {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', ROOT . '/tmp/logs/' . 'error.log');
        }
    }

    //移除魔术引号
    private function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
        return $value;
    }

    private function removeMagicQuotes()
    {
        if ( get_magic_quotes_gpc() )
        {
            $_GET       = $this->stripSlashesDeep($_GET);
            $_POST      = $this->stripSlashesDeep($_POST);
            $_COOKIE    = $this->stripSlashesDeep($_COOKIE);
        }
    }

    //检测“注册全局变量”配置，如果打开，则移除
    private function unregisterGlobals()
    {
        if (ini_get('register_globals'))
        {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value)
            {
                foreach ($GLOBALS[$value] as $key => $val)
                {
                    if ($val === $GLOBALS[$key]) unset($GLOBALS[$key]);
                }
            }
        }
    }

    //按需自动加载类
    private function loadClass($className)
    {
        $libClass = ROOT . '/library/' . $className . '.class.php';
        $controller = ROOT . '/application/controllers/' . $className . '.class.php';
        $model = ROOT . '/application/models/' . $className . '.class.php';

        if (file_exists($libClass))
        {
            require_once ($libClass);
        }
        elseif (file_exists($controller))
        {
            require_once ($controller);
        }
        elseif (file_exists($model))
        {
            require_once ($model);
        }
        else
        {
            die('错误：' . $className . '不存在！');
        }
    }


}

