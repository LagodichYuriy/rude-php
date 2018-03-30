<?

namespace rude;

class console_arguments
{
	public static function count()
	{
		if (isset($argc))
		{
			return $argc;
		}

		if (isset($_SERVER['argc']))
		{
			return $_SERVER['argc'];
		}

		return 0;
	}

	public static function is_exist($argument, $value = null, $value_strict = false)
	{
		$arguments = static::get();

		if (!isset($arguments[$argument]))
		{
			return false;
		}

		if ($value === null)
		{
			return true;
		}

			 if ($value_strict) { return $arguments[$argument] === $value; }
		else                    { return $arguments[$argument]  == $value; }
	}

	public static function get($name = null)
	{
		global $_console_arguments;

		if ($_console_arguments !== null)
		{
			if ($name !== null)
			{
				return get($name, $_console_arguments);
			}

			return $_console_arguments;
		}


		if (!RUDE_CLI_ARGUMENTS)
		{
			return []; # do not call `exception` here to prevent endless loop
		}


		$arguments = static::get_raw();

		if (!$arguments)
		{
			return [];
		}


		array_shift($arguments); # skip php script name

		if (!$arguments)
		{
			return [];
		}



		$arguments_amount = count($arguments);

		for ($i = 0; $i < $arguments_amount; $i++)
		{
			$argument       = $arguments[$i];
			$argument_clean = static::clean($argument);

			if (isset($arguments[$i + 1]) and strings::starts_with($argument, '-'))
			{
				$argument_next = $arguments[$i + 1];

				if (!strings::starts_with($argument_next, '-'))
				{
					$_console_arguments[$argument_clean] = static::clean($argument_next);

					$i++;

					continue;
				}
			}

			$_console_arguments[$argument_clean] = $argument_clean;
		}

		if ($name !== null)
		{
			return get($name, $_console_arguments);
		}

		return $_console_arguments;
	}

	public static function get_raw()
	{
		global $_console_arguments_raw;

		if ($_console_arguments_raw !== null)
		{
			return $_console_arguments_raw;
		}

		if (isset($_SERVER['argv']))
		{
			$_console_arguments_raw = $_SERVER['argv'];
		}

		if (isset($argv))
		{
			$_console_arguments_raw = $argv;
		}

		return $_console_arguments_raw;
	}

	private static function clean($string)
	{
		return strings::trim_left($string, '-');
	}
}