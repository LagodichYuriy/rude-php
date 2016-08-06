<?

namespace rude;

class defines
{
	public static function get()
	{
		return get_defined_constants();
	}

	public static function starts_with($string, $case_sensitive = true)
	{
		$result = [];

		foreach (static::get() as $key => $val)
		{
			if (strings::starts_with($key, $string, $case_sensitive))
			{
				$result[$key] = $val;
			}
		}

		return $result;
	}

	public static function ends_with($string, $case_sensitive = true)
	{
		$result = [];

		foreach (static::get() as $key => $val)
		{
			if (strings::ends_with($key, $string, $case_sensitive))
			{
				$result[$key] = $val;
			}
		}

		return $result;
	}
}