<?

namespace rude;

class visitor
{
	/**
	 * @en Shows current visitor's ip
	 * @ru Получение текущего ip пользователя
	 *
	 * $ip = visitor::ip(); # string(9) "86.92.16.32"
	 *
	 * @return string
	 */
	public static function ip()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		}

		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		return $_SERVER['REMOTE_ADDR'];
	}

	public static function language()
	{
		$languages = headers::accept_languages();

		foreach ($languages as $country => $priority)
		{
			return static::format($country);
		}

		return '';
	}

	public static function languages()
	{
		$languages = headers::accept_languages();


		$result = [];

		foreach ($languages as $country => $priority)
		{
			$country_formatted = static::format($country);

			$result[$country_formatted] = $country_formatted;
		}

		$result = array_unique($result);


		return $result;
	}

	private static function format($string)
	{
		if (strings::contains($string, '-'))
		{
			$string = strings::read_until($string, '-');
		}

		return strings::to_lowercase($string);
	}

	public static function is_language($lang)
	{
		if (static::language() == static::format($lang))
		{
			return true;
		}

		return false;
	}


	#####################################################################################
	# http://www.iana.org/assignments/language-subtag-registry/language-subtag-registry #
	#####################################################################################

	public static function is_englishman() { return static::is_language('en'); }
	public static function is_spanish()    { return static::is_language('es'); }
	public static function is_chinese()    { return static::is_language('zh'); }
	public static function is_russian()    { return static::is_language('ru'); }


	public static function referer()
	{
		return get('HTTP_REFERER', $_SERVER);
	}

	public static function is_referer($url, $case_sensitive = false)
	{
		return strings::contains(static::referer(), $url, $case_sensitive);
	}

	public static function user_agent() { return $_SERVER['HTTP_USER_AGENT']; }

	public static function is_android()       { return strings::contains(static::user_agent(), 'android',       false); }
	public static function is_windows_phone() { return strings::contains(static::user_agent(), 'windows phone', false); }
	public static function is_iphone()        { return strings::contains(static::user_agent(), 'iphone',        false); }
	public static function is_ipad()          { return strings::contains(static::user_agent(), 'ipad',          false); }
	public static function is_ipod()          { return strings::contains(static::user_agent(), 'ipod',          false); }

	public static function is_phone()
	{
		return static::is_android()       or
		       static::is_windows_phone() or
		       static::is_iphone()        or
		       static::is_ipad()          or
		       static::is_ipod();
	}
}