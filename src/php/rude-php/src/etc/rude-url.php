<?

namespace rude;

class url
{
	public static function current($skip_protocol = false, $skip_domain = false)
	{
		if ($skip_domain !== false)
		{
			return $_SERVER['REQUEST_URI'];
		}


		$url = '';

		if ($skip_protocol === false)
		{
			$protocol = 'http';

			if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on')
			{
				$protocol .= 's';
			}

			$url = $protocol . '://';
		}

		if (isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT'] != '80')
		{
			$url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
		}
		else
		{
			$url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		}

		return $url;
	}

	/**
	 * @en Current host
	 * @ru Возвращает имя хоста, которому передан запрос
	 *
	 * $host = url::host(); # string(12) "rude-php.com"
	 *
	 * @param bool $http
	 *
	 * @return string
	 */
	public static function host($http = false)
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $host = $_SERVER['HTTP_X_FORWARDED_HOST'])
		{
			$elements = explode(',', $host);

			$host = trim(end($elements));
		}
		else
		{
			if (!$host = $_SERVER['HTTP_HOST'])
			{
				if (!$host = $_SERVER['SERVER_NAME'])
				{
					$host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
				}
			}
		}


		$host = preg_replace('/:\d+$/', '', $host); # remove port number from host

		if ($http !== false)
		{
			return 'http://' . trim($host);
		}

		return trim($host);
	}

	public static function change_host($url, $domain)
	{
		$url = parse_url($url);

		$url['host'] = $url['host'] . $domain;

		$url = url::unparse($url);

		return $url;
	}

	# `http://www.site.com` => `site.com`
	public static function clean($url)
	{
		# in case scheme relative URI is passed, e.g., //www.google.com/
		$url = trim($url, '/');

		# if scheme not included, prepend it
		if (!preg_match('#^http(s)?://#', $url))
		{
			$url = 'http://' . $url;
		}

		$url_parts = parse_url($url);

		return preg_replace('/^www\./', '', $url_parts['host']); # remove www
	}

	/**
	 * @en Parse a URL and return its components
	 * @ru Разбор ссылки на составляющие
	 *
	 * @param $url
	 *
	 * @return array
	 */
	public static function parse($url)
	{
		return parse_url($url);
	}

	/**
	 * @en Unparse components and return it's url (alias for http_build_url(), but you don't need pecl_http library for it)
	 * @ru Формирование ссылки из составляющих частей
	 *
	 * @param $parsed_url
	 *
	 * @return string
	 */
	public static function unparse($parsed_url)
	{
		$scheme   = isset($parsed_url['scheme'])   ?       $parsed_url['scheme'] . '://' : '';
		$host     = isset($parsed_url['host'])     ?       $parsed_url['host']           : '';
		$port     = isset($parsed_url['port'])     ? ':' . $parsed_url['port']           : '';
		$user     = isset($parsed_url['user'])     ?       $parsed_url['user']           : '';
		$pass     = isset($parsed_url['pass'])     ? ':' . $parsed_url['pass']           : '';
		$pass     = ($user || $pass)               ? "$pass@"                            : '';
		$path     = isset($parsed_url['path'])     ?       $parsed_url['path']           : '';
		$query    = isset($parsed_url['query'])    ? '?' . $parsed_url['query']          : '';
		$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment']       : '';

		return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
	}

	/**
	 * @en URL encoder
	 * @ru Зашифровать строку для передачи в формате URL'а
	 *
	 * @param string $string The string to be encoded
	 *
	 * $result = url::encode('http://site.com'); # string(23) "http%3A%2F%2Fsite.com"
	 *
	 * @return string
	 */
	public static function encode($string)
	{
		return urlencode($string);
	}

	/**
	 * @en URL decoder
	 * @ru Расшифровать строку из формата URL'а в привычный вид
	 *
	 * @param string $string The url to be decoded
	 *
	 * $result = url::encode('http%3A%2F%2Fsite.com'); # string(15) "http://site.com"
	 *
	 * @return string
	 */
	public static function decode($string)
	{
		return urldecode($string);
	}
}