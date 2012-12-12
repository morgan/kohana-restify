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
	 * Whether or not to parse data
	 * 
	 * @access	public
	 * @return	void
	 */
	protected $_raw_data = FALSE;

	/**
	 * Setup
	 * 
	 * @access	public
	 * @return	void
	 */
	public function before()
	{
		$this->_raw_data = ($this->request->param('raw')) ? TRUE : FALSE;

		parent::before();
	}

	/**
	 * restify/test/index
	 * 
	 * @access	public
	 * @return	void
	 */
	public function action_index()
	{
		$data = NULL;

		if ($this->_raw_data)
		{
			$data = http_build_query($this->request->query());
		}
		else
		{
			$data = $this->request->query();
		}

		$this->_render(array
		(
			'method'	=> Restify_Request::HTTP_GET,
			'data'		=> $data
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
			'data'		=> ($this->_raw_data) ? $this->request->body() : $this->request->post()
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
		$data = array();
		
		if ($this->_raw_data)
		{
			$data = $this->request->body();
		}
		else
		{
			parse_str($this->request->body(), $data);
		}
		
		$this->_render(array
		(
			'method'	=> Restify_Request::HTTP_PUT,
			'data'		=> $data
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
		$data = NULL;

		if ($this->_raw_data)
		{
			$data = http_build_query($this->request->query());
		}
		else
		{
			$data = $this->request->query();
		}

		$this->_render(array
		(
			'method'	=> Restify_Request::HTTP_DELETE,
			'data'		=> $data
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
