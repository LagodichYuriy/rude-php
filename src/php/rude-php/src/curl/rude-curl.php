<?

namespace rude;

class curl
{
	/** @var curl_task[] */
	private $tasks = null;

	private $handle = null;


	public function __construct()
	{
		$this->handle = curl_multi_init();
	}

	public function __destruct()
	{
		curl_multi_close($this->handle);
	}

	public function add_url($task, $id = null)
	{
		$this->tasks[] = new curl_task($task, $id);
	}

	public function add_task($task)
	{
		$this->tasks[] = $task;
	}

	/**
	 * @param $max_threads
	 *
	 * @return curl_answer[]
	 */
	public function query($max_threads = null)
	{
		if (!$this->tasks)
		{
			return null;
		}

		if ($max_threads === null)
		{
			$max_threads = count($this->tasks);
		}


		$result = null;

		for (;;)
		{
			$tasks = null; /** @var curl_task[] $tasks */


			################
			# Gather tasks #
			################

			for ($i = 0; $i < $max_threads; $i++)
			{
				$tasks[] = array_shift($this->tasks);

				if (!$this->tasks)
				{
					break;
				}
			}


			################################
			# Connect tasks with multicurl #
			################################

			foreach ($tasks as &$task)
			{
				$curl_handle = $task->get_handle();

				curl_multi_add_handle($this->handle, $curl_handle);
			}


			#################
			# Parse content #
			#################

			do
			{
				curl_multi_exec($this->handle, $running);

				curl_multi_select($this->handle);
			}
			while ($running > 0);


			#######################
			# Collect parsed info #
			#######################

			foreach ($tasks as &$task)
			{
				$curl_handle = $task->get_handle();


				$answer = new curl_answer($task->get_id(), $task->get_url(), curl_getinfo($curl_handle), curl_multi_getcontent($curl_handle));


				if ($task->get_id())
				{
					$result[$task->get_id()] = $answer;
				}
				else
				{
					$result[] = $answer;
				}


				curl_multi_remove_handle($this->handle, $curl_handle);
			}


			#####################################
			# Break it if there's no more tasks #
			#####################################

			if (!$this->tasks)
			{
				break;
			}
		}

		return $result;
	}

	/**
	 * @return curl_answer
	 */
	public function query_first()
	{
		return items::first(static::query());
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