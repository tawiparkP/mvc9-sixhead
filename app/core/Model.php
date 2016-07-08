<?php
class Model extends Database{
	public function __construct(){
		parent::__construct(HOST_NAME,USER_NAME,PASSWORD,DB_NAME);		
	}
}
