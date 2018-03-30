<?

namespace rude;

class cache
{
	public static function get($key)
	{
		global $_cache;

		return $_cache[$key];
	}

	public static function add($key, $val)
	{
		global $_cache;

		$_cache[$key] = $val;

		return $val;
	}

	public static function is_exist($key)
	{
		global $_cache;

		return isset($_cache[$key]);
	}

	public static function remove($key)
	{
		global $_cache;

		unset($_cache[$key]);
	}

	public static function reset()
	{
		global $_cache;

		$_cache = [];
	}
}