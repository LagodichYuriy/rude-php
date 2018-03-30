<?

namespace rude;

class template_page
{
	protected $page = '';

	protected $title = '';

	protected $lang = 'en';

	public function html()
	{
		$class = $this->page;
		$class = htmlentities($class);
		$class = strings::replace($class, '_', '-');

		?><!DOCTYPE html>
		<html lang="<?= $this->lang ?>">
			<head>
				<title><?= $this->title ?></title>

				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

				<link rel="shortcut icon" href="<?= static::url() ?>/favicon.ico" type="image/x-icon">
				<link rel="icon" href="<?= static::url() ?>/favicon.ico" type="image/x-icon">

				<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">

				<? static::head() ?>
			</head>

			<body>
				<? static::header() ?>

				<div id="page-<?= $class ?>" class="container">
					<div id="content">
						<? static::content() ?>
					</div>
				</div>

				<? static::footer() ?>
			</body>
		</html>
		<?
	}

	public function head()
	{

	}

	public function header()
	{
		?>
		<div id="header"></div>
		<?
	}

	public function content()
	{
		# overridable method
	}

	public function footer()
	{
		?><div id="footer"></div><?
	}

	public static function js($path)
	{
		if (RUDE_APP_BUILD_ENABLED)
		{
			$path .= '?v=' . RUDE_APP_BUILD;
		}

		return '<script src="' . $path . '"></script>';
	}

	public static function css($path)
	{
		if (RUDE_APP_BUILD_ENABLED)
		{
			$path .= '?v=' . RUDE_APP_BUILD;
		}

		return '<link href="' . $path . '" rel="stylesheet" type="text/css">';
	}

	public function menu_item($page, $title, $icon)
	{
		?>
		<a class="item <? if (static::is($page)) { ?>active<? } ?>" href="<?= static::url($page) ?>">
			<i class="icon <?= $icon ?>"></i>

			<?= html::escape($title) ?>
		</a>
		<?
	}

	public function is($page)
	{
		return $page === get('page', $_REQUEST, 'homepage');
	}

	public static function url($params = [])
	{
		$url = RUDE_URL;

		if (isset($params['page']) and $params['page'] === 'homepage')
		{
			unset($params['page']);
		}

		$url = url::params($url, $params);

		return $url;
	}

	public static function url_current($params = [])
	{
		$url = url::current();
		$url = url::params($url, $params);

		return $url;
	}

	public function page($name)
	{
		$this->page = $name;
	}

	protected static function escape($string)
	{
		return htmlspecialchars($string);
	}

	protected static function unescape($string)
	{
		return htmlspecialchars_decode($string);
	}

	protected static function encode($data)
	{
		return base64_encode(json_encode($data)); # JSON + BASE64
	}

	protected static function decode($data)
	{
		return json_decode(base64_decode($data)); # BASE64 + JSON
	}

	protected static function modal_alert()
	{
		?>
		<div id="modal-alert" class="ui small modal">
			<div class="header">
				Alert
			</div>
			<div class="content">
				<p id="modal-alert-message"></p>
			</div>
			<div class="actions">
				<div class="ui positive right labeled icon button">
					ОК
					<i class="checkmark icon"></i>
				</div>
			</div>
		</div>
		<?
	}
}