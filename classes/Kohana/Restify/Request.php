<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Request
 * 
 * @package		Request
 * @category	Base
 * @author		Micheal Morgan <micheal@morgan.ly>
 * @copyright	(c) 2011-2012 Micheal Morgan
 * @license		MIT
 */
class Kohana_Restify_Request 
{
	/**
	 * HTTP GET
	 * 
	 * @var		string
	 */	
	const HTTP_GET		= 'GET';
	
	/**
	 * HTTP POST
	 * 
	 * @var		string
	 */		
	const HTTP_POST		= 'POST';
	
	/**
	 * HTTP PUT
	 * 
	 * @var		string
	 */		
	const HTTP_PUT		= 'PUT';
	
	/**
	 * HTTP DELETE
	 * 
	 * @var		string
	 */		
	const HTTP_DELETE	= 'DELETE';
	
	/**
	 * Factory Pattern
	 * 
	 * @static
	 * @access	public
	 * @return	Restify_Request
	 */
	public static function factory()
	{
		return new Restify_Request;
	}
	
	/**
	 * Request URL
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_url;
	
	/**
	 * Request Method
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_method;
	
	/**
	 * Headers
	 * 
	 * @access	protected
	 * @var		string
	 */
	protected $_headers = array();
	
	/**
	 * Referer
	 * 
	 * @access	protected
	 * @var		string|NULL
	 */
	protected $_referer;
	
	/**
	 * Useragent
	 * 
	 * @access	protected
	 * @var		string|NULL
	 */
	protected $_useragent;
	
	/**
	 * Collect cookies?
	 * 
	 * @access	protected
	 * @var		bool
	 */
	protected $_keep_cookies = FALSE;
	
	/**
	 * Key/Value data
	 * 
	 * @access	protected
	 * @var		mixed	array|string
	 */
	protected $_data = array();
	
	/**
	 * Max redirects
	 * 
	 * @access	protected
	 * @var		array
	 */
	protected $_max_redirects = 5;
	
	/**
	 * Get and set cookie flag
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function keep_cookies($set = NULL)
	{
		if (is_bool($set))
		{
			$this->_keep_cookies = $set;
		}
		
		return $this->_keep_cookies;
	}
	
	/**
	 * Set URL
	 * 
	 * @access	public
	 * @param	string
	 * @return	$this
	 */
	public function set_url($value)
	{
		$this->_url = $value;
		
		return $this;
	}
	
	/**
	 * Set referer
	 * 
	 * @access	public
	 * @param	string
	 * @return	$this
	 */
	public function set_referer($value)
	{
		$this->_referer = $value;
		
		return $this;
	}
	
	/**
	 * Add header
	 * 
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	$this
	 */
	public function add_header($key, $value)
	{
		$this->_headers[$key] = $value;
		
		return $this;
	}
	
	/**
	 * Set headers
	 * 
	 * @access	public
	 * @param	array
	 * @return	$this
	 */
	public function set_headers(array $headers)
	{
		foreach ($headers as $key => $value)
		{
			$this->add_header($key, $value);
		}
		
		return $this;
	}
	
	/**
	 * Add data
	 * 
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	$this
	 */
	public function add_data($key, $value)
	{
		// If not array, clear previously set data
		if ( ! is_array($this->_data))
		{
			$this->_data = array();
		}

		$this->_data[$key] = $value;
		
		return $this;
	}
	
	/**
	 * Set data
	 * 
	 * @access	public
	 * @param	array
	 * @return	$this
	 */
	public function set_data($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				$this->add_data($key, $value);
			}
		}
		else
		{
			$this->_data = $data;
		}
		
		return $this;
	}
	
	/**
	 * Set method
	 * 
	 * @access	public
	 * @param	string
	 * @return	$this
	 */
	public function set_method($value)
	{
		$this->_method = $value;
		
		return $this;
	}
	
	/**
	 * Set useragent
	 * 
	 * @access	public
	 * @param	string
	 * @return	$this
	 */
	public function set_useragent($value)
	{
		$this->_useragent = $value;
		
		return $this;
	}
	
	/**
	 * Response
	 * 
	 * @access	public
	 * @return	$this
	 */
	public function response()
	{
		$response = new Restify_Response;
		
		$handler = curl_init();

		if ($this->_data)
		{
			// Determine how to use data array, either within body or query string
			if (in_array($this->_method, array(self::HTTP_POST, self::HTTP_PUT)))
			{
				$data = (is_array($this->_data)) ? http_build_query($this->_data) : $this->_data;

				curl_setopt($handler, CURLOPT_POST, 1); 
				curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
			} 
			else if (is_array($this->_data))
			{
				$this->_url .= '?' . http_build_query($this->_data);
			}
		}

		curl_setopt($handler, CURLOPT_URL, $this->_url);

		curl_setopt($handler, CURLOPT_USERAGENT, $this->_useragent);
		curl_setopt($handler, CURLOPT_REFERER, $this->_referer);

		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($handler, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($handler, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($handler, CURLOPT_MAXREDIRS, $this->_max_redirects);

		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $this->_method);
		curl_setopt($handler, CURLOPT_HEADERFUNCTION, array($response, 'callback_header'));
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handler, CURLOPT_TIMEOUT, 10);
		curl_setopt($handler, CURLINFO_HEADER_OUT, TRUE);

		if ( ! empty($this->_headers))
		{
			curl_setopt($handler, CURLOPT_HTTPHEADER, $this->_get_formatted_headers());
		}

		return $response->process($handler, $this);
	}
	
	/**
	 * Get Formatted Headers
	 * 
	 * @access	protected
	 * @return	array
	 */
	public function _get_formatted_headers($formatted = array())
	{
		foreach ($this->_headers as $key => $value)
		{
			$formatted[] = $key . ': ' . $value;
		}

		$formatted[] = 'Expect:';

		return $formatted;
	}
}
