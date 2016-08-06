<?

namespace rude;

class shell
{
	private $command = '';

	private $options = [];
	private $arguments = [];

	public function __construct($command)
	{
		static::command($command);
	}

	public function command($command)
	{
		$this->command = $command;
	}

	public function option($option, $argument = null)
	{
		$this->options[$option] = $argument;
	}

	public function argument($argument)
	{
		$this->arguments[] = $argument;
	}

	public function query()
	{
		$query = $this->command . ' ';

		foreach ($this->options as $option => $argument)
		{
			if ($argument === null)
			{
				$query .= $option . ' ';
			}
			else
			{
				$query .= $option . ' ' . static::escape($argument) . ' ';
			}
		}

		foreach ($this->arguments as $argument)
		{
			$query .= static::escape($argument) . ' ';
		}

		$query = char::remove_last($query);

		return $query;
	}

	public static function escape($var)
	{
		if (is_numeric($var))
		{
			return $var;
		}

		return escapeshellarg($var);
	}

	public function exec()
	{
		return shell_exec(static::query());
	}

	public function reset()
	{
		static::reset_command();
		static::reset_options();
		static::reset_arguments();
	}

	public function reset_command()   { $this->command   = ''; }
	public function reset_options()   { $this->options   = ''; }
	public function reset_arguments() { $this->arguments = ''; }

}