<?php

/**
 * AST PHP framework.
 * A Simple Thing
 * a simple, light-weight, high-performance another PHP framework
 * Athor: Hua
 */
class Controller
{
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_template;

    public function __construct($model, $controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;

        $this->_model = new $model;
        $this->_template = new Template($controller, $action);
    }

    public function assign($name, $value)
    {
        $this->_template->assign($name, $value);
    }

    public function __destruct()
    {
        $this->_template->render();
    }
}
