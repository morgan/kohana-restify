<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Response
 * 
 * @package		Request
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Kohana_Restify_Response 
{
	/**
	 * Factory Pattern
	 * 
	 * @static
	 * @access	public
	 * @return	Restify_Response
	 */
	public static function factory()
	{
		return new Restify_Response;
	}
	
	/**
	 * HTTP Code
	 * 
	 * @access	protected
	 * @var		int
	 */
	protected $_http_code;
	
	/**
	 * In Headers
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_headers;
	
	/**
	 * Out Headers
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_headers_out;
	
	/**
	 * Cookie jar
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $_cookies = array();
	
	/**
	 * Content type
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_content_type;
	
	/**
	 * Content
	 * 
	 * @access	protected
	 * @var		string|NULL
	 */
	protected $_content;

	/**
	 * Error
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_error;
	
	/**
	 * Get HTTP Code
	 * 
	 * @access	protected
	 * @return	int
	 */
	public function get_http_code()
	{
		return $this->_http_code;
	}
	
	/**
	 * Get headers
	 * 
	 * @access	protected
	 * @return	string
	 */
	public function get_headers()
	{
		return $this->_headers;
	}
	
	/**
	 * Get headers out
	 * 
	 * @access	protected
	 * @return	string
	 */
	public function get_headers_out()
	{
		return $this->_headers_out;
	}
	
	/**
	 * Get content type
	 * 
	 * @access	protected
	 * @return	string
	 */
	public function get_content_type()
	{
		return $this->_content_type;
	}
	
	/**
	 * Get content
	 * 
	 * @access	protected
	 * @return	string
	 */
	public function & get_content()
	{
		return $this->_content;
	}
	
	/**
	 * Get cookies
	 * 
	 * @access	protected
	 * @return	string
	 */
	public function get_cookies()
	{
		return $this->_cookies;
	}
	
	/**
	 * Has Error
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function has_error()
	{
		return (bool) $this->_error;
	}
	
	/**
	 * Get Error
	 * 
	 * @access	public
	 * @return	string|NULL
	 */
	public function get_error()
	{
		return $this->_error;
	}
	
	/**
	 * Process
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function process($handler, Restify_Request $request)
	{
		if ($request->keep_cookies())
		{
			$temp = tempnam(sys_get_temp_dir(), 'cookie');

			curl_setopt($handler, CURLOPT_COOKIEJAR, $temp); 
			curl_setopt($handler, CURLOPT_COOKIEFILE, $temp);
			curl_setopt($handler, CURLOPT_COOKIESESSION, TRUE);
		}
		
		$this->_content = curl_exec($handler);
		
		if ($this->_content === FALSE)
		{
			$this->_error = curl_error($handler);
		}
		
		$this->_http_code 		= curl_getinfo($handler, CURLINFO_HTTP_CODE);
		$this->_headers_out		= curl_getinfo($handler, CURLINFO_HEADER_OUT);
		$this->_content_type	= curl_getinfo($handler, CURLINFO_CONTENT_TYPE);

		curl_close($handler);
		
		if ($request->keep_cookies())
		{
			$this->_cookies = $this->_parse_cookie($temp);
			
			unlink($temp);
		}

		return $this;
	}
	
	/**
	 * Parse cookie
	 * 
	 * @access	protected
	 * @param	resource
	 * @return	array
	 */
	public function _parse_cookie($file)
	{
		$response = array();
		
		$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		foreach ($lines as $line)
		{
			if ($line[0] == '#')
				continue;

			$response[] = explode("\t", $line);
		}
		
		return $response;
	}
	
	/**
	 * Header Callback
	 * 
	 * @access	public
	 * @param	resource
	 * @param	string
	 * @return	int
	 */
	public function callback_header($handle, $header)
	{
		$this->_headers .= $header;
		
		return strlen($header);
	}
}
