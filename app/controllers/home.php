<?php

/**
 * 
 */
class Home extends Controller {
	
	function __construct($argument) {
	}
	
	public function index($name = ''){
		echo "In Home Index<br/>";
		$user = $this->model('User');
		$user->name = $name;
		$this->view('home',['name'=>$user->name]);
	} 
}
