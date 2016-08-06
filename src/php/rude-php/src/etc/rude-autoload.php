<?

namespace rude;

require_once 'rude-globals.php';
require_once 'rude-filesystem.php';


# cli setup

if (!defined('RUDE_CLI'))
{
	define('RUDE_CLI', php_sapi_name() == 'cli');
}

if (!defined('RUDE_CLI_ARGUMENTS'))
{
	define('RUDE_CLI_ARGUMENTS', ini_get('register_argc_argv'));
}


# directories

define('RUDE_DIR',        realpath(__DIR__ . '/../../'));
define('RUDE_DIR_SRC',    RUDE_DIR . DIRECTORY_SEPARATOR . 'src');
define('RUDE_DIR_PARENT', realpath(RUDE_DIR . '/../'));

if (!defined('RUDE_DIR_WORKSPACE'))
{
	define('RUDE_DIR_WORKSPACE', RUDE_DIR . DIRECTORY_SEPARATOR . 'workspace');
}

if (!defined('RUDE_DIR_WORKSPACE_DATABASE'))
{
	define('RUDE_DIR_WORKSPACE_DATABASE', RUDE_DIR_WORKSPACE . DIRECTORY_SEPARATOR . 'database');
}


# autoload

$_source_list_core      = filesystem::search_files(RUDE_DIR_SRC,       'php');
$_source_list_workspace = filesystem::search_files(RUDE_DIR_WORKSPACE, 'php');

$_source_list = array_merge($_source_list_core, $_source_list_workspace);

function autoload($class_name)
{
	global $_source_list;

	if (!$_source_list)
	{
		return;
	}


	$class_name = str_replace(['_', '\\'], '-', $class_name);

	$source_file_expected = $class_name . '.php';

	foreach ($_source_list as $source_path)
	{
		$source_file = filesystem::file_basename($source_path);

		if ($source_file == $source_file_expected)
		{
			if (file_exists($source_path))
			{
				require_once $source_path;
			}

			return;
		}
	}
}

spl_autoload_register('rude\autoload');


# timezone

if (!defined('RUDE_TIMEZONE'))
{
	define('RUDE_TIMEZONE', 'UTC'); # UTC, Europe/Minsk, etc
}

date::set_timezone(RUDE_TIMEZONE); # set default timezone


# debug

if (defined('RUDE_DEBUG') and RUDE_DEBUG)
{
	error_reporting(-1); # show all errors/warnings/stricts

	set_error_handler('rude\exception::handler_error', -1); # custom error handler
}


# error types

define('RUDE_E_ERROR',      'ERROR');
define('RUDE_E_WARNING',    'WARNING');
define('RUDE_E_NOTICE',     'NOTICE');
define('RUDE_E_DEPRECATED', 'DEPRECATED');
define('RUDE_E_STRICT',     'STRICT');
define('RUDE_E_UNKNOWN',    'UNKNOWN');
define('RUDE_E_SUCCESS',    'OK');


# string charset

if (!defined('RUDE_STRING_ENCODING'))
{
	define('RUDE_STRING_ENCODING', 'UTF-8');
}


# time intervals in seconds; you can use this defines with cookies::set()

define('RUDE_TIME_SECOND', 1);             # 1 second
define('RUDE_TIME_MINUTE', 60);            # 1 minute = 60 seconds
define('RUDE_TIME_HOUR',   3600);          # 1 hour   = 3600 seconds
define('RUDE_TIME_DAY',    86400);         # 1 day    = 86400 seconds
define('RUDE_TIME_WEEK',   518400);        # 1 week   = 518400 seconds
define('RUDE_TIME_MONTH',  2592000);       # 1 month  = 2592000 seconds
define('RUDE_TIME_YEAR',   946080000);     # 1 year   = 946080000 seconds


# database

$_database = null;

if (!defined('RUDE_DATABASE_AUTO_CONNECTION'))
{
	define('RUDE_DATABASE_AUTO_CONNECTION', true);
}

if (RUDE_DATABASE_AUTO_CONNECTION and
	defined('RUDE_DATABASE_HOST') and
    defined('RUDE_DATABASE_USER') and
    defined('RUDE_DATABASE_PASS'))
{
	$host = RUDE_DATABASE_HOST;
	$user = RUDE_DATABASE_USER;
	$pass = RUDE_DATABASE_PASS;

	     if (defined('RUDE_DATABASE_PORT')) { $port = RUDE_DATABASE_PORT; }
	else                                    { $port = 3306;               }

	     if (defined('RUDE_DATABASE_NAME')) { $name = RUDE_DATABASE_NAME; }
	else                                    { $name = null;               }


	global $_database;

	$_database = new database($host, $user, $pass, $name, $port);
}