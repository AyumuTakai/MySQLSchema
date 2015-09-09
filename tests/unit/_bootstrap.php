<?php
// Here you can initialize variables that will be available to your tests

spl_autoload_register(function($class){
	$project_path = __DIR__.'/../../';
	$path = $project_path.$class.'.php';
	if(file_exists($path)){
		require_once($path);
		return true;
	}else{
		return false;
	}
});