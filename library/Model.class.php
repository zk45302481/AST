<?php

/**
 * AST PHP framework.
 * A Simple Thing
 * a simple, light-weight, high-performance another PHP framework
 * Athor: Hua
 */
class Model extends DB
{
    protected $_model;

    public function __construct()
    {
        $this->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $this->_model = get_class($this);
        $this->_model = rtrim($this->_model, 'Model');
        $this->_table = strtolower($this->_model);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}