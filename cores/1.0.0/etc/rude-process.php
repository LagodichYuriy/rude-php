<?

namespace rude;

class process
{
	public static function leader()
	{
		return posix_setsid();
	}

	public static function is_exist($pid)
	{
		return posix_kill($pid, 0);
	}
}