<?

namespace rude;

class libs
{
	/**
	 * @param $name    string
	 * @param $version string
	 *
	 * @return void
	 */
	public static function enable($name, $version = '1.0.0')
	{
		if (static::is_registered($name, $version))
		{
			return;
		}

		if (!static::is_safe($name) or !static::is_safe($version))
		{
			return;
		}

		$directory = static::directory($name, $version);

		autoload::scan($directory);
	}

	private static function directory($name, $version)
	{
		return filesystem::combine(RUDE_DIR_LIBS, $name, $version);
	}

	private static function is_registered($name, $version)
	{
		global $_libs_registered;

		return isset($_libs_registered[$name]) and $_libs_registered[$name] === $version;
	}

	private static function is_safe($string)
	{
		if (empty($string))
		{
			return false;
		}


		$alphabet = static::alphabet();

		foreach (strings::chars($string) as $char)
		{

			if (!isset($alphabet[$char]))
			{
				return false;
			}
		}

		return true;
	}

	private static function alphabet()
	{
		global $_libs_alphabet;

		if (!$_libs_alphabet)
		{
			$_libs_alphabet = [];

			$alphabet = english::alphabet();

			$chars = strings::chars($alphabet);

			$_libs_alphabet = array_flip($chars);
			$_libs_alphabet['.'] = true;
			$_libs_alphabet['-'] = true;
			$_libs_alphabet['_'] = true;
		}

		return $_libs_alphabet;
	}
}