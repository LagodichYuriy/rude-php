<?

namespace rude;

class anticaptcha
{
	/** @var curl */
	protected $curl;

	protected $api = 'http://2captcha.com';
	protected $key;

	protected $errors = [];
	protected $errors_log = false;

	public function __construct($key = null, $errors_log = false)
	{
		     if ($key !== null)                { $this->key = $key;              }
		else if (defined('RUDE_2CAPTCHA_KEY')) { $this->key = RUDE_2CAPTCHA_KEY; }
		else
		{
			exception::warning('undefined access key');
		}

		$this->curl = new curl();
		$this->curl->attempts(3);

		$this->errors_log = $errors_log;
	}

	public function error($message = null, $type = 'error')
	{
		if ($message !== null)
		{
			if ($this->errors_log)
			{
				if (!isset($this->errors[$type]))
				{
					$this->errors[$type] = [];
				}

				$this->errors[$type][] = $message;
			}
		}

		return false;
	}

	public function errors($type = null)
	{
		if ($type === null)
		{
			return $this->errors;
		}

		return get($type, $this->errors);
	}

	public function solve($base64)
	{
		return static::query
		([
			'page'     => 'in.php',
			'method'   => 'base64',
			'language' => 2,
			'json'     => 1,
			'body'     => $base64
		]);
	}

	protected function query($options)
	{
		# https://2captcha.com/2captcha-api

		$url = static::url($options);

		$this->curl->add($url);
		$this->curl->query();

		$answer = $this->curl->get_object(true);

		if (!$answer)
		{
			return static::error("unable to connect to anticaptcha service: $url");
		}


		$response = json_decode($answer->content);

		if (!$response or $response->status !== 1)
		{
			return static::error("invalid response: [$url] $answer->content");
		}

		$url = static::url
		([
			'page' => 'res.php',
			'action' => 'get',
			'json' => 1,
			'id' => $response->request
		]);

		for ($i = 0; $i < 12; $i++)
		{
			sleep(5);

			$this->curl->add($url);
			$this->curl->query();

			$answer = $this->curl->get_object(true);

			if (!$answer)
			{
				return static::error("unable to connect to anticaptcha service: $url");
			}


			$response = json_decode($answer->content);

			if (!$response)
			{
				return static::error("invalid response: [$url] $answer->content");
			}

			if ($response->status === 1)
			{
				return $response->request;
			}
			else
			{
				static::error("response: $answer->content", 'warning');
			}
		}

		return static::error("unable to solve the captcha (timeout): $url");
	}

	protected function url($options, $page = null)
	{
		$options['key'] = $this->key;

		if ($page === null)
		{
			$page = $options['page'];
		}

		unset($options['page']);


		$url = url::parse($this->api);
		$url['path'] = "/$page";
		$url['query'] = http_build_query($options);

		return url::unparse($url);
	}
}