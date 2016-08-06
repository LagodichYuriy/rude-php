<?

namespace rude;


global $_foreground_colors;

$_foreground_colors['black']        = '0;30';
$_foreground_colors['dark gray']    = '1;30';
$_foreground_colors['blue']         = '0;34';
$_foreground_colors['light blue']   = '1;34';
$_foreground_colors['green']        = '0;32';
$_foreground_colors['light green']  = '1;32';
$_foreground_colors['cyan']         = '0;36';
$_foreground_colors['light cyan']   = '1;36';
$_foreground_colors['red']          = '0;31';
$_foreground_colors['light red']    = '1;31';
$_foreground_colors['purple']       = '0;35';
$_foreground_colors['light purple'] = '1;35';
$_foreground_colors['brown']        = '0;33';
$_foreground_colors['yellow']       = '1;33';
$_foreground_colors['light gray']   = '0;37';
$_foreground_colors['white']        = '1;37';


global $_background_colors;

$_background_colors['black']        = '40';
$_background_colors['red']          = '41';
$_background_colors['green']        = '42';
$_background_colors['yellow']       = '43';
$_background_colors['blue']         = '44';
$_background_colors['magenta']      = '45';
$_background_colors['cyan']         = '46';
$_background_colors['light gray']   = '47';


class console_colors
{
	public static function format($string, $foreground_color = null, $background_color = null, $bold = false)
	{
		$markers  = static::get_foreground_marker($foreground_color);
		$markers .= static::get_background_marker($background_color);


		if ($bold !== false)
		{
			$string = static::bold($string);
		}

		if ($foreground_color !== null or
		    $background_color !== null)
		{
			$string = $markers . $string . "\033[0m";
		}

		return $string;
	}

	public static function fg_black       ($string, $background_color = null, $bold = false) { return static::format($string, 'black',        $background_color, $bold); }
	public static function fg_dark_gray   ($string, $background_color = null, $bold = false) { return static::format($string, 'dark gray',    $background_color, $bold); }
	public static function fg_blue        ($string, $background_color = null, $bold = false) { return static::format($string, 'blue',         $background_color, $bold); }
	public static function fg_light_blue  ($string, $background_color = null, $bold = false) { return static::format($string, 'light blue',   $background_color, $bold); }
	public static function fg_green       ($string, $background_color = null, $bold = false) { return static::format($string, 'green',        $background_color, $bold); }
	public static function fg_light_green ($string, $background_color = null, $bold = false) { return static::format($string, 'light green',  $background_color, $bold); }
	public static function fg_cyan        ($string, $background_color = null, $bold = false) { return static::format($string, 'cyan',         $background_color, $bold); }
	public static function fg_light_cyan  ($string, $background_color = null, $bold = false) { return static::format($string, 'light cyan',   $background_color, $bold); }
	public static function fg_red         ($string, $background_color = null, $bold = false) { return static::format($string, 'red',          $background_color, $bold); }
	public static function fg_light_red   ($string, $background_color = null, $bold = false) { return static::format($string, 'light red',    $background_color, $bold); }
	public static function fg_purple      ($string, $background_color = null, $bold = false) { return static::format($string, 'purple',       $background_color, $bold); }
	public static function fg_light_purple($string, $background_color = null, $bold = false) { return static::format($string, 'light purple', $background_color, $bold); }
	public static function fg_brown       ($string, $background_color = null, $bold = false) { return static::format($string, 'brown',        $background_color, $bold); }
	public static function fg_yellow      ($string, $background_color = null, $bold = false) { return static::format($string, 'yellow',       $background_color, $bold); }
	public static function fg_light_gray  ($string, $background_color = null, $bold = false) { return static::format($string, 'light gray',   $background_color, $bold); }
	public static function fg_white       ($string, $background_color = null, $bold = false) { return static::format($string, 'white',        $background_color, $bold); }

	public static function bg_black     ($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'black',      $bold); }
	public static function bg_red       ($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'red',        $bold); }
	public static function bg_green     ($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'green',      $bold); }
	public static function bg_yellow    ($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'yellow',     $bold); }
	public static function bg_blue      ($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'blue',       $bold); }
	public static function bg_magenta   ($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'magenta',    $bold); }
	public static function bg_cyan      ($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'cyan',       $bold); }
	public static function bg_light_gray($string, $foreground_color = null, $bold = false) { return static::format($string, $foreground_color, 'light gray', $bold); }

	public static function bold($string)
	{
		return "\033[1m" . $string . "\033[0m";
	}

	public static function underline($string)
	{
		return "\033[4m" . $string . "\033[4m";
	}

	private static function get_foreground_marker($color_name)
	{
		if ($color_name !== null)
		{
			global $_foreground_colors;

			if (isset($_foreground_colors[$color_name]))
			{
				return "\033[" . $_foreground_colors[$color_name] . "m";
			}
		}

		return '';
	}

	private static function get_background_marker($color_name)
	{
		if ($color_name !== null)
		{
			global $_background_colors;

			if (isset($_background_colors[$color_name]))
			{
				return "\033[" . $_background_colors[$color_name] . "m";
			}
		}

		return '';
	}

	public static function get_foreground_colors()
	{
		global $_foreground_colors;

		return array_keys($_foreground_colors);
	}

	public static function get_background_colors()
	{
		global $_background_colors;

		return array_keys($_background_colors);
	}

	public static function get_error_color($error_type)
	{
		     if (exception::is_error     ($error_type)) { return 'red';       }
		else if (exception::is_warning   ($error_type)) { return 'light red'; }
		else if (exception::is_notice    ($error_type)) { return 'orange';    }
		else if (exception::is_deprecated($error_type)) { return 'brown';     }
		else if (exception::is_strict    ($error_type)) { return 'grey';      }
		else if (exception::is_success   ($error_type)) { return 'green';     }
		else                                            { return 'red';       }
	}
}