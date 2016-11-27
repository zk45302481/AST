<?php

/**
 * AST PHP framework.
 * A Simple Thing
 * a simple, light-weight, high-performance another PHP framework
 * Athor: Hua
 */
class Template
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    public function __construct($controller, $action)
    {
        $this->_controller = strtolower($controller);
        $this->_action = $action;
    }

    function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function render()
    {
        extract($this->variables);

        $controllerHeader = ROOT . '/application/views/'. $this->_controller .'/header.php';
        $controllerFooter = ROOT .'/application/views/'. $this->_controller .'/footer.php';
        $defaultHeader = ROOT . '/application/views/header.php';
        $defaultFooter = ROOT .'/application/views/footer.php';

        if (file_exists($controllerHeader))
        {
            include ($controllerHeader);
        }
        else
        {
            include ($defaultHeader);
        }

        include (ROOT . '/application/views/'. $this->_controller .'/'. $this->_action .'.php');

        if (file_exists($controllerFooter))
        {
            include ($controllerFooter);
        }
        else
        {
            include ($defaultFooter);
        }
    }
}