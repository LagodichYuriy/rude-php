<?

namespace rude;

class timer
{
	private $start = 0.;
	private $end   = 0.;

	public function __construct($auto_start = true)
	{
		if ($auto_start === true)
		{
			$this->start();
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

	public function time_diff()
	{
		if (!$this->end)
		{
			$this->end();
		}

		return $this->end - $this->start;
	}

	public function start() { $this->start = $this->time_current(); }
	public function end()   { $this->end   = $this->time_current(); }

	public function reset()
	{
		$this->start = 0;
		$this->end   = 0;
	}
}

