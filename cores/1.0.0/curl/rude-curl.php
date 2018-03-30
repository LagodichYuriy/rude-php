<?

namespace rude;

class curl
{
	/** @var curl_task[] */
	private $tasks = null;

	private $handle = null;

	/** @var curl_answer[] */
	private $result = [];

	/** @var \stdClass */
	private $options;

	public function __construct($options = [])
	{
		$defaults =
		[
			'progress' => false,
			'threads_max' => null,
			'connection_attempts_max' => null,
			'connection_delay' => 0
		];

		$this->options = (object) ($options + $defaults);
	}

	public function options()
	{
		return (array) $this->options;
	}

	private function option($key, $val = null)
	{
		if ($val !== null)
		{
			$this->options->{$key} = $val;
		}

		return $this->options->{$key};
	}

	public function progress($val = null) { return static::option('progress',                $val); }
	public function attempts($val = null) { return static::option('connection_attempts_max', $val); }
	public function delay   ($val = null) { return static::option('connection_delay',        $val); }
	public function threads ($val = null) { return static::option('threads_max',             $val); }

	/**
	 * @param $task string|curl_task|string[]|curl_task[]
	 * @param $id = null|int|string
	 */
	public function add($task, $id = null)
	{
		if (is_array($task))
		{
			foreach ($task as $id => $item)
			{
				static::add($item, $id);
			}
		}
		else if (is_object($task))
		{
			if ($id !== null)
			{
				$task->id = $id;
			}

			static::add_task($task);
		}
		else
		{
			static::add_url($task, $id);
		}
	}

	/**
	 * @param $url string
	 * @param $id  = null|int|string
	 */
	public function add_url($url, $id = null)
	{
		$this->tasks[] = new curl_task($url, $id);
	}

	/** @param $task curl_task */
	public function add_task($task)
	{
		$this->tasks[] = $task;
	}

	public function tasks()
	{
		return $this->tasks;
	}

	public function tasks_count()
	{
		return count($this->tasks);
	}

	/**
	 * @return bool
	 */
	public function query()
	{
		$this->result = [];

		if (!$this->tasks)
		{
			return false;
		}


		$this->handle = curl_multi_init();


		if ($this->options->threads_max === null)
		{
			$this->options->threads_max = count($this->tasks);
		}

		foreach ($this->tasks as $task)
		{
			$task->connection_delay = $this->options->connection_delay;
		}


		$tasks_count = count($this->tasks);

		if ($this->options->progress)
		{
			console::progress(0, $tasks_count);
		}

		for (;;)
		{
			if (!$this->tasks)
			{
				break;
			}


			/** @var $tasks curl_task[] */
			$tasks = items::shift($this->tasks, $this->options->threads_max, true, true);

			foreach ($tasks as $index => $task)
			{
				if ($this->options->connection_delay)
				{
					$timestamp = microtime(true);

					if ($task->connection_timestemp + $this->options->connection_delay > $timestamp)
					{
						$diff = $task->connection_timestemp + $this->options->connection_delay - $timestamp;

						$microseconds = (int) convert::seconds_to_microseconds($diff);

						usleep($microseconds);
					}
				}

				$task->connection_timestemp = microtime(true);
				$task->connection_attempts++;

				$curl_handle = $task->get_handle();

				curl_multi_add_handle($this->handle, $curl_handle);
			}


			do
			{
				curl_multi_exec($this->handle, $running);

				curl_multi_select($this->handle);
			}
			while ($running > 0);


			foreach ($tasks as $index => $task)
			{
				$answer = new curl_answer($task);

				curl_multi_remove_handle($this->handle, $task->get_handle());



				if ($this->options->connection_attempts_max and $answer->http_code !== 200 and $task->connection_attempts < $this->options->connection_attempts_max)
				{
					static::add($task);
				}
				else
				{
					     if ($task->id !== null) { $this->result[$task->id] = $answer; }
					else                         { $this->result[]          = $answer; }

					$task->close();
				}
			}


			if ($this->options->progress)
			{
				console::progress($tasks_count - count($this->tasks), $tasks_count);
			}
		}

		curl_multi_close($this->handle);

		return true;
	}

	/**
	 * @param bool $only_valid
	 * @param bool $assoc
	 *
	 * @return curl_answer[]
	 */
	public function get_object_list($only_valid = false, $assoc = false)
	{
		if (!$only_valid)
		{
			return $this->result;
		}


		$result = [];

		foreach ($this->result as $task)
		{
			if ($task->http_code === 200)
			{
				     if ($assoc) { $result[$task->id] = $task; }
				else             { $result[]          = $task; }
			}
		}

		return $result;
	}

	/**
	 * @param bool $only_valid
	 *
	 * @return curl_answer
	 */
	public function get_object($only_valid = false)
	{
		$result = items::first($this->result);

		if ($only_valid and $result->http_code !== 200)
		{
			return null;
		}

		return $result;
	}

	/**
	 * @en CURL file_get_contents() equivalent with timeout settings
	 * @ru Эквивалент с использованием CURL для функции file_get_contents() с наличием таймаута
	 *
	 * $html = curl::file_get_contents('http://site.com', 3);
	 *
	 * @param string $url
	 * @param int $timeout
	 *
	 * @return mixed
	 */
	public static function file_get_contents($url, $timeout = 30)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER,    true);
		curl_setopt($ch, CURLOPT_HEADER,         0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL,            $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;
	}
}