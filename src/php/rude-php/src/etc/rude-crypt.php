<?

namespace rude;

class crypt
{
	public static function password($password, $salt = null, $salt_length = 32)
	{
		if ($salt === null)
		{
			$salt = static::salt($salt_length);
		}

		$hash = md5($password . $salt);

		return array($hash, $salt);
	}

	public static function salt($length = 32, $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		return strings::rand($length, $alphabet);
	}

	public static function is_valid($password, $hash, $salt)
	{
		list($tmp_hash, $tmp_salt) = static::password($password, $salt);

		if ($tmp_hash === $hash and $tmp_salt === $salt)
		{
			return true;
		}

		return false;
	}
}