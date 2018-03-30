<?

namespace rude;

class session
{
	/**
	 * @en Session initialization
	 * @ru Инициализация сессии
	 *
	 * @return bool
	 */
	public static function start()
	{
		return session_start();
	}

	public static function get($key = null, $default = null)
	{
		if ($key === null)
		{
			return $_SESSION;
		}

		return get($key, $_SESSION, $default);
	}

	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public static function remove($key)
	{
		if (static::is_exist($key))
		{
			unset($_SESSION[$key]);
		}
	}

	public static function is_exist($key)
	{
		if (!isset($_SESSION))
		{
			return false;
		}

		return isset($_SESSION[$key]);
	}

	public static function is_equal($key, $value, $strict = true)
	{
		if (!static::is_exist($key))
		{
			return false;
		}

		if ($strict)
		{
			return $value === static::get($key);
		}

		return $value == static::get($key);
	}

	/**
	 * @en Session destruction
	 * @ru Завершение сессии
	 *
	 * @return bool
	 */
	public static function destroy()
	{
		$_SESSION = [];

		if (ini_get("session.use_cookies"))
		{
			$params = session_get_cookie_params();

			setcookie
			(
				session_name(),
				'',
				time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}

		return session_destroy();
	}
}
