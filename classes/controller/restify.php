<?php defined('SYSPATH') or die('No direct script access.');
/**
 * restify
 * 
 * @package		Request
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011 Micheal Morgan
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
		
		if ( ! $data['path'] = Kohana::config('restify.media'))
			throw new Kohana_Exception('Media not configured. Specify path under `config/restify.php`');
		
		$data['referer'] 	= URL::site(Request::detect_uri());		
		$data['useragent'] 	= $restify->get_useragent();
		
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
				'setting_useragent'	=> $restify->get_useragent(),
				'setting_cookies'	=> FALSE
			);
			
			$request = Restify_Request::factory()
				->set_url($input['url'])
				->set_method($input['method'])		
				->set_headers($this->_combine_input('header', $input))
				->set_data($this->_combine_input('data', $input))					
				->set_useragent($input['setting_useragent'])
				->set_referer($input['setting_referer']);
				
			if ($input['setting_cookies'])
			{
				$request->keep_cookies(TRUE);
			}

		    $response = $request->response();

		    if ( ! $response->has_error())
		    {
			    $output = array
				(
					'http_code'		=> $response->get_http_code(),
					'content_type'	=> $response->get_content_type(),				
					'headers'		=> HTML::chars(trim($response->get_headers())),
					'headers_out'	=> HTML::chars(trim($response->get_headers_out())),
					'cookies'		=> HTML::chars($response->get_cookies()),
					'content'		=> HTML::chars($response->get_content())
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
				if ($key != '' && $key = urlencode($key))
				{
					$return[$key] = (isset($input[$_value][$index])) ? urlencode($input[$_value][$index]) : FALSE;
				}
			}
		}

		return $return;
	}	
}