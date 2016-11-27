<?php

/**
 * AST PHP framework.
 * A Simple Thing
 * a simple, light-weight, high-performance another PHP framework
 * Athor: Hua
 */
class DB
{
    protected $_dbHandle;
    protected $_result;
    protected $_table;

    public function connect($host, $user, $pass, $dbName)
    {
        try {
            $dsn = 'mysql:host='. $host .';dbname='. $dbName .';charset=utf8';
            $this->_dbHandle = new PDO($dsn, $user, $pass, array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
        } catch (PDOException $e)
        {
            die('错误：' . $e->getMessage());
        }
    }

    public function selectAll()
    {
        $sql = "SELECT * FROM `$this->_table`";
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->fetchAll();
    }

    public function select($id)
    {
        $sql = "SELECT * FROM `$this->_table` WHERE `id` = '$id'";
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->fetch();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `$this->_table` WHERE `id` = '$id'";
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->rowCount();
    }

    public function query($sql)
    {
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->rowCount();
    }

    public function add($data)
    {
        $data = $this->formatInsert($data);
        $sql = "INSERT INTO `$this->_table` $data";
        return $this->query($sql);
    }

    public function update($id, $data)
    {
        $data = $this->formatUpdate($data);
        $sql = "UPDATE `$this->_table` SET $data WHERE `id` = '$id'";
        return $this->query($sql);
    }

    //format insert data
    private function formatInsert(array $data)
    {
        $fields = $values = array();
        foreach ($data as $key => $value)
        {
            $fields[] = '`'.$key.'`';
            $values[] = '\''.$value.'\'';
        }

        $field = implode(',', $fields);
        $value = implode(',', $values);

        return "($field) VALUES ($value)";
    }

    //format update data
    private function formatUpdate($data)
    {
        $fields = array();
        foreach ($data as $key => $value)
        {
            $fields[] = "`$key`='$value'";
        }

        return implode(',', $fields);
    }
}