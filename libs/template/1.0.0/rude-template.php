<?

namespace rude;

class template
{
	public static function init()
	{
		session::start();


		$page = get('page', $_GET, 'homepage');

		if ($page)
		{
			if (static::is_exist($page))
			{
				$class = __NAMESPACE__ . '\page_' . $page;

				$template = new $class; /** @var $template template_page */
				$template->page($page);
				$template->html();
			}
			else
			{
				static::error(404);
			}
		}
	}

	public static function is_exist($page, $template_starts = 'rude-page-', $template_ends = '.php')
	{
		$sources = autoload::get();

		foreach ($sources as $source)
		{
			if (strings::contains($source, $template_starts))
			{
				$class = strings::read_between($source, $template_starts, $template_ends);
				$class = strings::replace($class, '-', '_');

				if ($class == $page)
				{
					return true;
				}
			}
		}

		return false;
	}

	public static function error($code)
	{
		switch ($code)
		{
			case 403: $title = 'Access Denied';  break;
			case 404: $title = 'Page not found'; break;

			default:
				$title = '';
		}

		$template = new page_error($code, $title);
		$template->html();

		die;
	}
}