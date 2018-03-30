<?

namespace rude;

class console
{
	public static function is_exist()
	{
		return RUDE_CLI;
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

	public static function write($var = '', $bold = false, $replace = false, $newline = PHP_EOL)
	{
		if ($bold)
		{
			$var = static::bold($var);
		}

		     if ($replace) { echo $var . "\r";     }
		else               { echo $var . $newline; }

		return true;
	}

	public static function bold($string)
	{
		return static::colorize($string, true);
	}

	public static function colorize($string, $bold = false, $foreground_color = null, $background_color = null)
	{
		##################################################
		# http://ascii-table.com/ansi-escape-sequences.php
		# ------------------------------------------------
		# 0     All attributes off
		# 1     Bold on
		# 4     Underscore (on monochrome display adapter only)
		# 5     Blink on
		# 7     Reverse video on
		# 8     Concealed on
		# ------------------
		# Foreground colors:
		#
		# 30    Black
		# 31    Red
		# 32    Green
		# 33    Yellow
		# 34    Blue
		# 35    Magenta
		# 36    Cyan
		# 37    White
		# ------------------
		# Background colors:
		#
		# 40    Black
		# 41    Red
		# 42    Green
		# 43    Yellow
		# 44    Blue
		# 45    Magenta
		# 46    Cyan
		# 47    White

		global $_colors_foreground;

		if ($_colors_foreground === null)
		{
			$_colors_foreground =
			[
				'underline' => 4,
				'black'     => 30,
				'red'       => 31,
				'green'     => 32,
				'yellow'    => 33,
				'blue'      => 34,
				'magenta'   => 35,
				'cyan'      => 36,
				'white'     => 37
			];
		}


		global $_colors_background;

		if ($_colors_background === null)
		{
			$_colors_background =
			[
				'black'   => 40,
				'red'     => 41,
				'green'   => 42,
				'yellow'  => 43,
				'blue'    => 44,
				'magenta' => 45,
				'cyan'    => 46,
				'white'   => 47
			];
		}


		$values = [];

		if ($bold)
		{
			$values[] = 1;
		}

		if ($foreground_color) { $values[] = $_colors_foreground[$foreground_color]; }
		if ($background_color) { $values[] = $_colors_background[$background_color]; }


		$values = implode(';', $values);

		return "\033[" . $values . "m" . $string . "\033[0m"; # \0333[0m = "all attributes off"
	}

	public static function lines($char = '-')
	{
		console::write(strings::repeat($char, static::cols()));
	}

	public static function cols($default = 80, $refresh = false)
	{
		if (!$refresh and cache::is_exist(__METHOD__))
		{
			return cache::get(__METHOD__);
		}

		if (system::is_os_windows())
		{
			return $default;
		}


		$content = `which resize`;

		preg_match('/COLUMNS=([0-9]+)/', $content, $matches);

		return cache::add(__METHOD__, get(1, $matches, $default));
	}

	public static function rows($default = 24, $refresh = false)
	{
		if (!$refresh and cache::is_exist(__METHOD__))
		{
			return cache::get(__METHOD__);
		}

		if (system::is_os_windows())
		{
			return $default;
		}


		$content = `which resize`;

		preg_match('/LINES=([0-9]+)/', $content, $matches);

		return cache::add(__METHOD__, get(1, $matches, $default));
	}

	public static function is_exist_command($command)
	{
		$shell = new shell("which $command");

		if ($shell->exec())
		{
			return true;
		}

		return false;
	}

	public static function log($string, $error_type)
	{
		$error_color = static::get_error_color($error_type);

		$status = static::colorize("[$error_type]", true, $error_color);

		return static::write("$status $string");
	}

	public static function get_error_color($error_type)
	{
		     if (exception::is_error     ($error_type)) { return 'red';    }
		else if (exception::is_warning   ($error_type)) { return 'red';    }
		else if (exception::is_notice    ($error_type)) { return 'yellow'; }
		else if (exception::is_deprecated($error_type)) { return 'yellow'; }
		else if (exception::is_strict    ($error_type)) { return 'yellow'; }
		else if (exception::is_success   ($error_type)) { return 'green';  }
		else                                            { return 'red';    }
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
	 * @param             $current
	 * @param             $total
	 * @param null|string $label_current
	 * @param null|string $label_total
	 * @param null|string $postfix
	 * @param int    $cells
	 * @param string $cell
	 */
	public static function progress($current, $total, $label_current = null, $label_total = null, $postfix = null, $cells = 10, $cell = '#')
	{
		if ($current > $total)
		{
			return;
		}

		$total_length = strings::length($total);

		$current = strings::pad_left($current, $total_length, '0');
		$total   = strings::pad_left($total,   $total_length, '0');

		     if ($total) { $percent = round($current / $total * 100, 2); }
		else             { $percent = 0;                                 }

		$percent = number_format($percent, 2);
		$percent = strings::pad_left($percent, 6);

		$passed = floating::to_upper($percent / (100 / $cells));

		$progress = strings::repeat($cell, $passed);
		$progress = strings::pad_right($progress, $cells);

		if ($label_current !== null) { $label_current = ' ' . $label_current; }
		if ($label_total   !== null) { $label_total   = ' ' . $label_total;   }
		if ($postfix       !== null) { $postfix       = ' ' . $postfix;       }

		$message = "($current$label_current/$total$label_total) [$progress] $percent%$postfix";
		$message = strings::pad_right($message, static::cols(), ' ');

		static::write($message, false, true);

		if ($current == $total)
		{
			static::write();
		}
	}
}