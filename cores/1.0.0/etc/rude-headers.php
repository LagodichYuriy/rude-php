<?

namespace rude;

class headers
{
	public static function response($code)
	{
		$codes =
		[
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',

			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',

			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',

			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			418 => '418 I\'m a teapot',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			426 => 'Upgrade Required',

			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			506 => 'Variant Also Negotiates',
			507 => 'Insufficient Storage',
			509 => 'Bandwidth Limit Exceeded',
			510 => 'Not Extended'
		];

		header($_SERVER['SERVER_PROTOCOL'] . " $code $codes[$code]", true, $code);
	}

	# see also http://wiki.nginx.org/XSendfile
	public static function file($file_path, $file_name = false, $file_type = 'application/octet-stream', $die = true)
	{
		if ($file_name === false)
		{
			$file_name = basename($file_path);
		}

		if (file_exists($file_path))
		{
			@set_time_limit(0);

			header('Content-Description: File Transfer');
			header('Content-Type: ' . $file_type);
			header('Content-Disposition: attachment; filename="' . str_replace('"', "'", $file_name) . '"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file_path));

			readfile($file_path);
		}

		if ($die)
		{
			exit;
		}
	}


	/**
	 * @en HTTP 404 header answer (not found)
	 * @ru Заголовок HTTP с кодом ответа 404 (не найдено)
	 */
	public static function not_found()
	{
		header('HTTP/1.0 404 Not Found');
	}

	/**
	 * @en HTTP 403 header answer (forbidden)
	 * @ru Заголовок HTTP с кодом ответа 403 (доступ запрещён)
	 */
	public static function forbidden()
	{
		header('HTTP/1.0 403 Forbidden');
	}

	/**
	 * @en HTTP 410 header answer (gone)
	 * @ru Заголовок HTTP с кодом ответа 410 (документ удалён)
	 */
	public static function gone()
	{
		header('HTTP/1.1 410 Gone');
	}

	/**
	 * @en HTTP 418 header answer (I'm a teapot)
	 * @ru Заголовок HTTP с кодом ответа 418 (я чайник)
	 */
	public static function teapot()
	{
		header('HTTP/1.1 418 I\'m a teapot');
	}

	public static function unavailable($retry_after = 120)
	{
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
		header('Retry-After: ' . (int) $retry_after);

		exit;
	}

	public static function redirect($url, $replace = null, $code = null)
	{
		header("Location: $url", $replace, $code);

		exit;
	}

	public static function refresh($url = null)
	{
		if ($url === null)
		{
			$url = url::current();
		}

		static::redirect($url, true, 303);
	}

	public static function is_request_post()
	{
		if (!isset($_SERVER['REQUEST_METHOD']))
		{
			return false;
		}

		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public static function is_request_get()
	{
		if (!isset($_SERVER['REQUEST_METHOD']))
		{
			return false;
		}

		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}

	public static function accept_languages()
	{
		if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			return [];
		}

		$string = $_SERVER['HTTP_ACCEPT_LANGUAGE'];


		$langs = explode(',', $string);


		$result = [];

		foreach ($langs as $lang)
		{
			if (strings::contains($lang, ';'))
			{
				list($country, $priority) = explode(';', $lang);

				$result[$country] = $priority;
			}
		}

		return $result;
	}
}