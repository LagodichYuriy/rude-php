<?

namespace rude;

class file_info
{
	public static function directory($path, $real = false) # '/path/to/file.txt' => '/path/to'
	{
		if ($real)
		{
			$path = realpath($path);
		}

		return pathinfo($path, PATHINFO_DIRNAME);
	}

	public static function extension($path) # '/path/to/file.txt' => 'txt'
	{
		return pathinfo($path, PATHINFO_EXTENSION);
	}

	public static function name($path, $include_extension = true) # '/path/to/file.txt' => 'file' or 'file_txt'
	{
		if ($include_extension)
		{
			return pathinfo($path, PATHINFO_BASENAME);
		}

		return pathinfo($path, PATHINFO_FILENAME);
	}
}