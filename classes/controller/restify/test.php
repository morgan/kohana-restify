<?php defined('SYSPATH') or die('No direct script access.');
/**
 * restify/test
 * 
 * @package		Request
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Controller_Restify_Test extends Controller_REST
{
	/**
	 * restify/test/index
	 * 
	 * @access	public
	 * @return	void
	 */
	public function action_index()
	{
		$this->_render(array
		(
			'method'	=> Restify_Request::HTTP_GET,
			'data'		=> $this->request->query()
		));
	}

	/**
	 * restify/test/create
	 * 
	 * @access	public
	 * @return	void
	 */
	public function action_create()
	{
		$this->_render(array
		(
			'method'	=> Restify_Request::HTTP_POST,
			'data'		=> $this->request->post()
		));
	}

	/**
	 * restify/test/update
	 * 
	 * @access	public
	 * @return	void
	 */
	public function action_update()
	{
		$post = array();
		
		parse_str($this->request->body(), $post);
		
		$this->request->post($post);
		
		$this->_render(array
		(
			'method'	=> Restify_Request::HTTP_PUT,
			'data'		=> $this->request->post()
		));
	}

	/**
	 * restify/test/delete
	 * 
	 * @access	public
	 * @return	void
	 */
	public function action_delete()
	{
		$this->_render(array
		(
			'method'	=> Restify_Request::HTTP_DELETE,
			'data'		=> $this->request->query()
		));
	}
	
	/**
	 * Render Response
	 * 
	 * @access	protected
	 * @return	void
	 */
	public function _render($data)
	{
		$this->response->body(json_encode($data))->headers('content-type', 'application/json');		
	}
}