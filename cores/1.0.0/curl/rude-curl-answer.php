<?

namespace rude;

class curl_answer
{
	public $id        = null;
	public $url       = null;
	public $info      = null;
	public $content   = null;
	public $http_code = null;
	public $attempts  = null;

	/**
	 * curl_answer constructor.
	 *
	 * @param $task curl_task
	 */
	public function __construct($task)
	{
		$this->id = $task->id;
		$this->url = $task->url;

		$curl_handle = $task->get_handle();

		$this->content = curl_multi_getcontent($curl_handle);
		$this->info = static::info($curl_handle);
		$this->http_code = (int) get('CURLINFO_HTTP_CODE', $this->info);
		$this->attempts = $task->connection_attempts;
	}

	/**
	 * @param $curl_handle \resource
	 *
	 * @return array
	 */
	private static function info(&$curl_handle)
	{
		global $_curl_info_constants;

		if ($_curl_info_constants === null)
		{
			$contants = get_defined_constants();

			foreach ($contants as $key => $value)
			{
				if (strings::starts_with($key, 'CURLINFO'))
				{
					$_curl_info_constants[$key] = $value;
				}
			}
		}

		$info = [];

		foreach ($_curl_info_constants as $key => $value)
		{
			$info[$key] = curl_getinfo($curl_handle, $value);
		}

		return $info;
	}
}