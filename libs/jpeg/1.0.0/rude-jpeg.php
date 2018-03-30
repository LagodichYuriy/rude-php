<?

# IMPORTANT: `jpeginfo` required

namespace rude;

class jpeg
{
	public static function is_valid($path)
	{
		if (!filesystem::is_exist($path))
		{
			return false;
		}

		$path_escaped = escapeshellarg($path);

		$output = `jpeginfo -c $path_escaped`;

		if (strings::contains($output, '[ERROR]'))
		{
			return false;
		}

		return true;
	}
}