<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Restify Model
 *
 * @package		Request
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Model_Restify extends Model 
{
	/**
	 * Validation rules
	 * 
	 * @access	public
	 * @return	array
	 */
	public function rules()
	{
		return array
		(
			'url' => array
			(
				array('not_empty'),
				array('url')
			),
			'method' => array
			(
				array('not_empty'),
				array('in_array', array(':value', $this->http_methods()))
			),
			'setting_referer' => array
			(
				array('url')
			),
			'setting_useragent' => array
			(
				array('max_length', array(':value', 255))
			),
			'setting_html' => array
			(
				array('digit')
			)
		);
	}

	/**
	 * Validation labels
	 * 
	 * @access	public
	 * @return	array
	 */
	public function labels()
	{
		return array
		(
			'url' 				=> 'URL', 
			'method' 			=> 'Method', 
			'setting_referer' 	=> 'Referer', 
			'setting_useragent' => 'User Agent',
			'setting_html'		=> 'Render HTML'
		);
	}

	/**
	 * HTTP request methods
	 * 
	 * @access	public
	 * @return	array
	 */
	public function http_methods()
	{
		return array
		(
			Restify_Request::HTTP_GET, 
			Restify_Request::HTTP_POST, 
			Restify_Request::HTTP_PUT, 
			Restify_Request::HTTP_DELETE
		);
	} 
	
	/**
	 * Get Default User Agent
	 * 
	 * @access	public
	 * @return	string
	 */
	public function get_useragent()
	{
		return (isset($_SERVER['HTTP_USER_AGENT'])) 
			? $_SERVER['HTTP_USER_AGENT'] 
			: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:5.0) Gecko/20100101 Firefox/5.0';
	}
}
