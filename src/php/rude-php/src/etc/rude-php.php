<?

namespace rude;

class php
{
	public static function gid() { return getmygid(); }
	public static function pid() { return getmypid(); }
	public static function uid() { return getmyuid(); }

	public static function memory_usage     ($real_usage = true) { return memory_get_usage     ($real_usage); }
	public static function memory_usage_peak($real_usage = true) { return memory_get_peak_usage($real_usage); }

	public static function user()
	{
		return get_current_user();
	}

	public static function version()
	{
		return phpversion();
	}
}