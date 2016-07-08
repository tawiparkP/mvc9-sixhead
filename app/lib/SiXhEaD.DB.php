<?php
/*
SiXhEaD Database - Database
Copyright (c) 2013, Chairath Soonthornwiphat (sixhead.com). All rights reserved.
Version 1.0

> MySQLi
*/
class Database
{
    public $connection = false;
    public $debug = false;
	public $db_host = 'sd';
		
    public function __construct($db_host, $db_user, $db_pass, $db_name)
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
    }
    
    public function connect()
    {
    	echo $this->db_host;
        $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if ($this->connection->connect_error) {
            //trigger_error('Database connection failed: '  . $this->connection->connect_error, E_USER_ERROR);
            $this->setDebug('Database connection failed: ' . $this->connection->connect_error);
            exit;
        }
        //$this->connection->query('SET time_zone = '+7:00'');
        $this->connection->query('SET NAMES UTF8');
        $this->connection->query('SET character_set_results=UTF8');
    }
    
    public function disconnect()
    {
        $this->connection->close();
    }
    
    public function query($sql)
    {
        $result = $this->connection->query($sql);
        if (!$result) {
            if ($this->debug) {
                $this->setDebug($sql);
            } else {
                $this->setDebug('SQL error');
            }
            exit;
        }
        return $result;
    }
    
    public function execute($sql)
    {
        $result = $this->connection->query($sql);
        return $result;
    }
    
    protected function setDebug($str)
    {
        echo "<pre>";
        print_r($str);
        echo "</pre>";
    }
}
