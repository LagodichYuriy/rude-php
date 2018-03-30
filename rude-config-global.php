<?php


define('RUDE_TIMEZONE', 'Europe/Minsk');

define('RUDE_LOCALHOST_HOSTNAME', 'me');



if (!defined('RUDE_APP_DIR'))
{
	die ("fatal error: RUDE_APP_DIR contant has not been defined\n");
}

if (!ini_get('short_open_tag'))
{
	# this framework is written with short open tags (<? and <?=) so it's important to set short_open_tag = On in your php.ini

	die("fatal error: `short_open_tag` must be enabled in your php.ini (short_open_tag = On)\n");
}

if (!defined('RUDE_VERSION_CORE'))
{
	define('RUDE_VERSION_CORE', '1.0.0');
}


$_app_parts = explode(DIRECTORY_SEPARATOR, RUDE_APP_DIR);

define('RUDE_APP_VERSION', array_pop($_app_parts));
define('RUDE_APP_NAME',    array_pop($_app_parts));

define('RUDE_IS_LOCALHOST', php_uname('n') === RUDE_LOCALHOST_HOSTNAME);
define('RUDE_IS_PRODUCTION', !RUDE_IS_LOCALHOST);

     if (RUDE_IS_PRODUCTION and defined('RUDE_URL_PRODUCTION')) { define('RUDE_URL', RUDE_URL_PRODUCTION); }
else if (RUDE_IS_LOCALHOST  and defined('RUDE_URL_LOCALHOST'))  { define('RUDE_URL', RUDE_URL_LOCALHOST);  }
else                                                            { define('RUDE_URL', null); }


if (!defined('RUDE_DEBUG') and !RUDE_IS_PRODUCTION)
{
	define('RUDE_DEBUG', true);
}


if (RUDE_IS_PRODUCTION)
{
	if (defined('RUDE_DATABASE_HOST_PRODUCTION') and !defined('RUDE_DATABASE_HOST')) { define('RUDE_DATABASE_HOST', RUDE_DATABASE_HOST_PRODUCTION); }
	if (defined('RUDE_DATABASE_USER_PRODUCTION') and !defined('RUDE_DATABASE_USER')) { define('RUDE_DATABASE_USER', RUDE_DATABASE_USER_PRODUCTION); }
	if (defined('RUDE_DATABASE_PASS_PRODUCTION') and !defined('RUDE_DATABASE_PASS')) { define('RUDE_DATABASE_PASS', RUDE_DATABASE_PASS_PRODUCTION); }
	if (defined('RUDE_DATABASE_PORT_PRODUCTION') and !defined('RUDE_DATABASE_PORT')) { define('RUDE_DATABASE_PORT', RUDE_DATABASE_PORT_PRODUCTION); }
}
else if (RUDE_IS_LOCALHOST)
{
	if (defined('RUDE_DATABASE_HOST_LOCALHOST') and !defined('RUDE_DATABASE_HOST')) { define('RUDE_DATABASE_HOST', RUDE_DATABASE_HOST_LOCALHOST); }
	if (defined('RUDE_DATABASE_USER_LOCALHOST') and !defined('RUDE_DATABASE_USER')) { define('RUDE_DATABASE_USER', RUDE_DATABASE_USER_LOCALHOST); }
	if (defined('RUDE_DATABASE_PASS_LOCALHOST') and !defined('RUDE_DATABASE_PASS')) { define('RUDE_DATABASE_PASS', RUDE_DATABASE_PASS_LOCALHOST); }
	if (defined('RUDE_DATABASE_PORT_LOCALHOST') and !defined('RUDE_DATABASE_PORT')) { define('RUDE_DATABASE_PORT', RUDE_DATABASE_PORT_LOCALHOST); }
}


$_app_build = 1;

if (!defined('RUDE_APP_BUILD_ENABLED'))
{
	define('RUDE_APP_BUILD_ENABLED', false);
}

if (RUDE_APP_BUILD_ENABLED)
{
	define('RUDE_FILE_BUILD', RUDE_APP_DIR . DIRECTORY_SEPARATOR . 'rude-build.txt');

	if (!file_exists(RUDE_FILE_BUILD))
	{
		touch(RUDE_FILE_BUILD);
	}

	if (is_file(RUDE_FILE_BUILD))
	{
		$_app_build = (int) file_get_contents(RUDE_FILE_BUILD);

		if (RUDE_IS_LOCALHOST)
		{
			file_put_contents(RUDE_FILE_BUILD, ++$_app_build);
		}
	}
}

define('RUDE_APP_BUILD', $_app_build);


define('RUDE_DIR_ROOT',  __DIR__);
define('RUDE_DIR_APPS',  RUDE_DIR_ROOT . DIRECTORY_SEPARATOR . 'apps');
define('RUDE_DIR_CORES', RUDE_DIR_ROOT . DIRECTORY_SEPARATOR . 'cores');
define('RUDE_DIR_LIBS',  RUDE_DIR_ROOT . DIRECTORY_SEPARATOR . 'libs');

define('RUDE_DIR_CORE',  RUDE_DIR_CORES . DIRECTORY_SEPARATOR . RUDE_VERSION_CORE);

require_once RUDE_DIR_CORE . DIRECTORY_SEPARATOR . 'rude-autoload.php';