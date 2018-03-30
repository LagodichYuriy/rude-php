<?

namespace rude;

class timer
{
	public $start = 0.;
	public $end   = 0.;

	public function __construct($auto_start = true)
	{
		if ($auto_start)
		{
			static::init();
		}
	}

	public function time_current()
	{
		return microtime(true);
	}

	/**
	 * @min PHP 5.4
	 *
	 * @return mixed
	 */
	public function time_execution()
	{
		return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
	}

	public function time_diff($precision = 4)
	{
		if (!$this->end)
		{
			$this->end();
		}


		$diff = round($this->end - $this->start, $precision);

		return number_format($diff, $precision);
	}

	public function init()
	{
		static::reset();

		$this->start = $this->time_current();
	}

	public function end()
	{
		$this->end = $this->time_current();
	}

	public function reset()
	{
		$this->start = 0;
		$this->end   = 0;
	}

	public static function start($key = null)
	{
		/** @var $_timers timer[] */
		global $_timers;

		if ($key === null)
		{
			do
			{
				$key = strings::rand(6);
			}
			while (isset($_timers[$key]));
		}

		$_timers[$key] = new timer(false);
		$_timers[$key]->start = microtime(true);
	}

	public static function finish($message = null, $key = null, $precision = 6)
	{
		$time_finish = microtime(true);


		/** @var $_timers timer[] */
		global $_timers;

		if ($key === null)
		{
			$key = items::key_last($_timers);
		}

		$timer = $_timers[$key];
		$timer->end = $time_finish;

		$diff = $timer->time_diff($precision);

		if ($message !== null)
		{
			if (console::is_exist())
			{
				$diff_colored = console::colorize($diff, true);

				console::write("$diff_colored seconds: {$message}");
			}
			else
			{
				debug("$diff seconds: {$message}");
			}
		}

		unset($_timers[$key]);

		return $diff;
	}
}

