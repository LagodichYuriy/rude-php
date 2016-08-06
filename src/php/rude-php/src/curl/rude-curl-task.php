<?

namespace rude;

class curl_task
{
	private $id = null;

	private $handle = null;

	private $url = null;


	public function __construct($url, $id = null)
	{
		$this->url = $url;

		if ($id === null)
		{
			$this->id = $this->url;
		}
		else
		{
			$this->id = $id;
		}


		$this->handle = curl_init($this->url);


		####################
		# default settings #
		####################

		return static::set_timeout_execution()  and
		       static::set_timeout_connection() and
		       static::set_encoding()           and
		       static::enable_redirects()       and
		       static::enable_return_transfer() and
		       static::disable_post()           and
		       static::disable_ssl_verification();
	}


	public function & get_handle() { return $this->handle; }
	public function   get_url()    { return $this->url;    }
	public function   get_id()     { return $this->id;     }

	public function set($key, $val)
	{
		return curl_setopt($this->handle, $key, $val);
	}

	public function set_timeout_execution ($timeout = 30)  { return static::set(CURLOPT_TIMEOUT,        $timeout);    }
	public function set_timeout_connection($timeout = 30)  { return static::set(CURLOPT_CONNECTTIMEOUT, $timeout);    }
	public function set_encoding          ($encoding = '') { return static::set(CURLOPT_ENCODING,       $encoding);   }
	public function set_user_agent        ($user_agent)    { return static::set(CURLOPT_USERAGENT,      $user_agent); }
	public function set_cookie_file_save  ($file_path)     { return static::set(CURLOPT_COOKIEJAR,      $file_path);  }
	public function set_cookie_file_load  ($file_path)     { return static::set(CURLOPT_COOKIEFILE,     $file_path);  }
	public function set_referer           ($referer)       { return static::set(CURLOPT_REFERER,        $referer);    }

	public function set_header($header)
	{
		if (is_string($header))
		{
			$header = [$header];
		}

		return static::set(CURLOPT_HTTPHEADER, $header);
	}

	public function set_post($post_fields)
	{
		return static::enable_post() and static::set(CURLOPT_POSTFIELDS, $post_fields);
	}
	
	public function set_json($post_fields)
	{
		$json = json_encode($post_fields);

		return static::enable_post() and static::set(CURLOPT_POSTFIELDS, $json) and
		                                 static::set(CURLOPT_HTTPHEADER,
		                                 [
		                                 	'Content-Type: application/json',
		                                 	'Content-Length: ' . strings::size($json)
										 ]);
	}
	
	public function set_put($data = '')
	{
		$file = tmpfile();

		fwrite($file, $data);

		fseek($file, 0);

		return static::enable_put() and static::set(CURLOPT_INFILE, $data) and static::set(CURLOPT_INFILESIZE, strings::size($data));
	}
	
	public function set_auth($login, $password)
	{
		return static::set(CURLOPT_USERPWD, "$login:$password");
	}

	public function set_proxy_http($proxy)
	{
		return static::set(CURLOPT_PROXYTYPE, CURLPROXY_HTTP) and
		       static::set(CURLOPT_PROXY, $proxy);
	}

	public function set_proxy_socks4($proxy)
	{
		return static::set(CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4) and
		       static::set(CURLOPT_PROXY, $proxy);
	}

	public function set_proxy_socks5($proxy)
	{
		return static::set(CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5) and
		       static::set(CURLOPT_PROXY, $proxy);
	}

	public function enable_delete()           { return static::set(CURLOPT_CUSTOMREQUEST, 'DELETE'); } # DELETE
	public function enable_patch()            { return static::set(CURLOPT_CUSTOMREQUEST, 'PATCH');  } # PATCH
	public function enable_put()              { return static::set(CURLOPT_PUT,            true);    } # PUT
	public function enable_post()             { return static::set(CURLOPT_POST,           true);    } # POST
	public function enable_redirects()        { return static::set(CURLOPT_FOLLOWLOCATION, true);    }
	public function enable_return_headers()   { return static::set(CURLOPT_HEADER,         true);    }
	public function enable_return_transfer()  { return static::set(CURLOPT_RETURNTRANSFER, true);    }
	public function enable_binary_transfer()  { return static::set(CURLOPT_BINARYTRANSFER, true);    }
	public function enable_ssl_verification() { return static::set(CURLOPT_SSL_VERIFYPEER, true);    }

	public function disable_delete()           { return static::set(CURLOPT_CUSTOMREQUEST, 'DELETE'); } # DELETE
	public function disable_patch()            { return static::set(CURLOPT_CUSTOMREQUEST, 'PATCH');  } # PATCH
	public function disable_put()              { return static::set(CURLOPT_PUT,            false);   } # PUT
	public function disable_post()             { return static::set(CURLOPT_POST,           false);   } # POST
	public function disable_redirects()        { return static::set(CURLOPT_FOLLOWLOCATION, false);   }
	public function disable_return_headers()   { return static::set(CURLOPT_HEADER,         false);   }
	public function disable_return_transfer()  { return static::set(CURLOPT_RETURNTRANSFER, false);   }
	public function disable_binary_transfer()  { return static::set(CURLOPT_BINARYTRANSFER, false);   }
	public function disable_ssl_verification() { return static::set(CURLOPT_SSL_VERIFYPEER, false);   }
}