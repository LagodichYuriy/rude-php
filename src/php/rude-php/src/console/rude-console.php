<?

namespace rude;

class console
{
	public static function is_exists()
	{
		return php_sapi_name() == 'cli';
	}

	public static function read($prompt = null)
	{
		if (system::is_os_windows())
		{
			if ($prompt !== null)
			{
				echo $prompt;
			}

			return stream_get_line(STDIN, 1024, PHP_EOL);
		}

		return readline($prompt);
	}

	public static function argument($argument, $strict = false)
	{
		$arguments = static::arguments();

		foreach ($arguments as $key => $val)
		{
			if (static::is_equal($argument, $key, $strict))
			{
				return $val;
			}
		}

		return null;
	}

	public static function arguments()
	{
		if (!RUDE_CLI_ARGUMENTS)
		{
			return []; # do not call `exception` here to prevent endless loop
		}


		$arguments = [];

		if (isset($_SERVER['argv']))
		{
			$arguments = $_SERVER['argv'];
		}

		if (isset($argv))
		{
			$arguments = $argv;
		}


		$result = [];


		$skip = [];

		foreach ($arguments as $index => $argument)
		{
			if (strings::starts_with($argument, '-'))
			{
				$skip[] = $index + 1;

				$result[strings::read_after($argument, '-')] = $arguments[$index + 1];
			}
		}

		return $result;
	}

	public static function arguments_count()
	{
		if (isset($argc))
		{
			return $argc;
		}

		if (isset($_SERVER['argc']))
		{
			$_SERVER['argc'];
		}

		return 0;
	}

	private function is_equal($a, $b, $strict = false)
	{
		if ($strict === false)
		{
			$a = strings::trim_left($a, '-');
			$b = strings::trim_left($b, '-');
		}

		return $a == $b;
	}

	public static function is_argument_exist($argument, $strict = false)
	{
		$arguments = static::arguments();
		
		if ($strict === false)
		{
			$argument = [$argument, '-' . $argument, '--' . $argument];
		}

		return items::contains($arguments, $argument);
	}

	public static function write($var = '', $bold = false, $replace = false)
	{
		if ($bold !== false)
		{
			$var = console_colors::bold($var);
		}

		if ($replace !== false)
		{
			echo $var . "\r";
		}
		else
		{
			echo $var . PHP_EOL;
		}

		return true;
	}

	public static function lines($char = '-')
	{
		console::write(strings::repeat($char, static::cols()));
	}

	public static function cols()
	{
		if (system::is_os_windows())
		{
			return 80;
		}

		foreach (strings::lines(`resize`) as $line)
		{
			if (strings::contains($line, 'COLUMNS'))
			{
				return (int) strings::read_after($line, '=');
			}
		}

		return 0;
	}

	public static function rows()
	{
		if (system::is_os_windows())
		{
			return 24;
		}

		foreach (strings::lines(`resize`) as $line)
		{
			if (strings::contains($line, 'LINES'))
			{
				return (int) strings::read_after($line, '=');
			}
		}

		return 0;
	}

	public static function is_exists_command($command)
	{
		$shell = new shell('which' . $command);

		if ($shell->exec())
		{
			return true;
		}

		return false;
	}

	public static function log($string, $error_type = RUDE_E_WARNING)
	{
		$error_color = console_colors::get_error_color($error_type);

		$status = "[$error_type]";
		$status = console_colors::format($status, $error_color, null, true);

		return static::write("$status $string");
	}

	public static function clear()
	{
		array_map(create_function('$a', 'print chr($a);'), [27, 91, 72, 27, 91, 50, 74]);
	}

	/**
	 *
	 * for ($i = 0, $total = 10; $i <= $total; $i++)
	 * {
	 *     console::progress($i, $total);
	 *
	 *     sleep(1);
	 * }
	 *
	 * @param        $current
	 * @param        $total
	 * @param int    $cells
	 * @param string $cell
	 */
	public static function progress($current, $total, $cells = 10, $cell = '#')
	{
		$total_length = strings::length($total);

		$current = strings::pad_left($current, $total_length, '0');
		$total   = strings::pad_left($total,   $total_length, '0');

		$percent = round($current / $total * 100, 2);
		$percent = strings::pad_left($percent, 5);

		$passed = floating::to_upper($percent / (100 / $cells));

		$progress = strings::repeat($cell, $passed);
		$progress = strings::pad_right($progress, $cells);


		static::write("($current/$total) [$progress] $percent%", false, true);

		if ($current == $total)
		{
			static::write();
		}
	}
}