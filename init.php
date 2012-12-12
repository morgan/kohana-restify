<?php defined('SYSPATH') or die('No direct script access.');

if (in_array(trim(Request::detect_uri(), '/'), array('restify/test', 'restify/test/raw')))
{
	Route::set('restify/test', '<directory>(/<controller>(/<raw>))')
		->defaults(array
		(
			'directory' 	=> 'restify', 
			'controller' 	=> 'test'
		));
}
