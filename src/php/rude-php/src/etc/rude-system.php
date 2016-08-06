<?

namespace rude;

class system
{
	/**
	 * @en Byte order system checker, returns `true` on little endian systems and `false` on big endian systems
	 * @ru Проверка порядка байтов, возвращает `true` на системах с порядком `от младшего к старшему (little endian)` и `false` на системах `от старшего к младшему (big endian)`
	 *
	 * $result = sysinfo::is_little_endian();
	 *
	 * @return bool
	 */
	public static function is_little_endian()
	{
		$int = 0x00FF;

		return $int === current(unpack('v', pack('S', $int)));
	}

	public static function is_big_endian()
	{
		return !system::is_little_endian();
	}

	public static function uname($mode = null)
	{
		return php_uname($mode);
	}

	public static function hostname()
	{
		return static::uname('n');
	}

	public static function processor()
	{
		return static::uname('m');
	}

	public static function os()
	{
		return php_uname('s');
	}


	# OS definition: https://en.wikipedia.org/wiki/Uname#Table_of_standard_uname_output

	public static function is_os_linux()   { return strings::starts_with(PHP_OS, ['linux', 'gnu'],  false); }
	public static function is_os_windows() { return strings::starts_with(PHP_OS, ['win', 'cygwin'], false); }
	public static function is_os_mac()     { return strings::starts_with(PHP_OS, 'darwin',          false); }
	public static function is_os_freebsd() { return strings::starts_with(PHP_OS, 'freebsd',         false); }
}