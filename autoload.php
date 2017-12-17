<?php

// This file is strictly used for PHPUnit test resolution of class names.

spl_autoload_register(function ($class_name) {

	$f = str_replace("SLDB","src",str_replace("\\","/",$class_name)) . '.php';

	//This check just removes a ton of warnings on the output.
	if( is_file( $f ) ){

    	include $f;
    	
	}

});