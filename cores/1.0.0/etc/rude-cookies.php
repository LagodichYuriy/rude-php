<?

namespace rude;

class cookies
{
	public static function get($name = null)
	{
		if ($name === null)
		{
			return $_COOKIE;
		}

		return get($name, $_COOKIE);
	}

	public static function inc($name, $n = 1)
	{
		return static::set($name, static::get($name) + $n);
	}

	public static function dec($name, $n = 1)
	{
		return static::set($name, static::get($name) - $n);
	}

	public static function set($name, $value = 1, $expire = seconds_in_month, $path = '/', $domain = null, $secure = null, $httponly = null)
	{
		$_COOKIE[$name] = $value;

		return setcookie($name, $value, time() + $expire, $path, $domain, $secure, $httponly);
	}

	public static function delete($name = null, $path = '/')
	{
		if ($name === null)
		{
			foreach ($_COOKIE as $key => $val)
			{
				static::delete($key);
			}
		}


		unset($_COOKIE[$name]);

		return setcookie($name, '', time() - seconds_in_year, $path);
	}

	public static function is_exists($name, $value = false)
	{
		if ($value === false)
		{
			if (isset($_COOKIE[$name]))
			{
				return true;
			}

			return false;
		}

		if (static::get($name) == $value)
		{
			return true;
		}

		return false;
	}
}
