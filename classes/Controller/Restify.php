<?php defined('SYSPATH') or die('No direct script access.');
/**
 * restify
 * 
 * @package		Request
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Controller_Restify extends Controller_REST
{
	/**
	 * restify/index
	 * 
	 * @access	public
	 * @return	void
	 */
	public function action_index()
	{
		$restify = Model::factory('restify');
		
		if ( ! $data['path'] = Kohana::$config->load('restify.media'))
			throw new Kohana_Exception('Media not configured. Specify path under `config/restify.php`');
		
		$data['referer'] 	= URL::site(Request::detect_uri());
		$data['useragent'] 	= $restify->get_useragent();
		$data['samples']	= Kohana::$config->load('restify.samples');
		$data['request']	= $this->request;
		
		$this->response->body(View::factory('restify/index', $data));
	}

	/**
	 * restify/create
	 * 
	 * @access	public
	 * @return	void
	 */
	public function action_create()
	{
		$restify = Model::factory('restify');
		
		$valid = Validation::factory($this->request->post())->labels($restify->labels());
		
		foreach ($restify->rules() as $field => $rules)
		{
			$valid->rules($field, $rules);
		}

		if ($valid->check())
		{
			$input = $valid->as_array() + array
			(
				'setting_referer'	=> URL::site(Request::detect_uri()),
				'setting_useragent'	=> $restify->get_useragent()
			);

			$data = NULL;

			if ($input['config_data_type'] == 'paired')
			{
				$data = $this->_combine_input('data', $input);
			}
			else if ($input['config_data_type'] == 'body' AND in_array($input['method'], array(Restify_Request::HTTP_POST, Restify_Request::HTTP_PUT)))
			{
				$data = $input['config_data_body'];
			}

			$request = Restify_Request::factory()
				->set_url($input['url'])
				->set_method($input['method'])
				->set_headers($this->_combine_input('header', $input))
				->set_data($data)
				->set_useragent($input['setting_useragent'])
				->set_referer($input['setting_referer']);
			
			$request->keep_cookies(TRUE);

			$response = $request->response();

			if ( ! $response->has_error())
			{
				$output = array
				(
					'http_code'		=> $response->get_http_code(),
					'content_type'	=> $response->get_content_type(),
					'headers'		=> HTML::chars(trim($response->get_headers())),
					'headers_out'	=> HTML::chars(trim($response->get_headers_out())),
					'cookies'		=> $this->_sanitize_cookies($response->get_cookies()),
					'content'		=> $response->get_content()
				);
			}
			else
			{
				$output = array('error' => $response->get_error());
			}
		}
		else
		{
			$output = array('error' => implode(', ', $valid->errors('validation')));
		}
			
		if (isset($output['error']))
		{
			$this->response->status(500);
		}
		
		$this->response->body(json_encode($output))->headers('content-type', 'application/json');
	}

	/**
	 * Get array
	 * 
	 * @todo	After urldecode, filter input through htmlspecialchars
	 * @access	protected
	 * @param	string
	 * @param	array
	 * @return	array
	 */
	protected function & _combine_input($prefix, & $input)
	{
		$return = array();
		
		$_key = $prefix . '_key';
		$_value = $prefix . '_value';
		
		if (isset($input[$_key]))
		{
			foreach ($input[$_key] as $index => $key)
			{
				if ($key != '' && $key = urldecode($key))
				{
					$return[$key] = (isset($input[$_value][$index])) 
						? urldecode($input[$_value][$index]) 
						: FALSE;
				}
			}
		}

		return $return;
	}
	
	/**
	 * Cleanse parsed cookie array
	 * 
	 * @access	protected
	 * @param	array
	 * @return	array
	 */
	protected function & _sanitize_cookies(array $rows)
	{
		foreach ($rows as & $row)
		{
			foreach ($row as $key => & $value)
			{
				$row[$key] = HTML::chars($value);
			}
		}
		
		return $rows;
	}
}
