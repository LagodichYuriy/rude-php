<?

namespace rude;

class directory
{
	public static function current()
	{
		return getcwd();
	}

	public static function change($directory)
	{
		return chdir($directory);
	}

	/**
	 * @en Create the directory
	 * @ru Создание директории
	 *
	 * @param string $path
	 * @param int $mode
	 * @param bool $recursive
	 *
	 * @return bool
	 */
	public static function create($path, $mode = 0755, $recursive = false)
	{
		return mkdir($path, $mode, $recursive);
	}

	public static function is_exist($path)
	{
		if (is_dir($path) and file_exists($path))
		{
			return true;
		}

		return false;
	}

	public static function space_used($path) { return disk_total_space($path); }
	public static function space_free($path) { return disk_free_space($path); }
}