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

	public static function get($key)
	{
		return get($key, $_SESSION);
	}

	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public static function remove($key)
	{
		if (static::is_exists($key))
		{
			unset ($_SESSION[$key]);
		}
	}

	public static function is_exists($key, $value = null)
	{
		if (!isset($_SESSION))
		{
			return false;
		}

		if ($value === null)
		{
			     if (get($key, $_SESSION) === null) { return false; }
			else                                    { return true;  }
		}

		if (session::is_exists($key) and get($key, $_SESSION) == $value)
		{
			return true;
		}

		return false;
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
