<?php defined('SYSPATH') or die('No direct script access.');

if (trim(Request::detect_uri(), '/') == 'restify/test')
{
	Route::set('restify/test', '<directory>(/<controller>)')
		->defaults(array
		(
			'directory' 	=> 'restify', 
			'controller' 	=> 'test'
		));
}