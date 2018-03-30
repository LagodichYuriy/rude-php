<?

namespace rude;

const second = 1;
const byte   = 1;

const seconds_in_nanosecond  = 0.000000001;   # 1 nanosecond  = 10^-9 seconds
const seconds_in_microsecond = 0.000001;      # 1 microsecond = 10^-6 seconds
const seconds_in_millisecond = 0.001;         # 1 millisecond = 10^-3 seconds
const seconds_in_minute      = 60;            # 1 minute      = 60 seconds
const seconds_in_hour        = 3600;          # 1 hour        = 60*60 seconds
const seconds_in_day         = 86400;         # 1 day         = 60*60*24 seconds
const seconds_in_week        = 604800;        # 1 week        = 60*60*24*7 seconds
const seconds_in_month       = 2592000;       # 1 month       = 60*60*24*30 seconds
const seconds_in_month_28    = 2419200;       # 1 month       = 60*60*24*28 seconds
const seconds_in_month_29    = 2505600;       # 1 month       = 60*60*24*29 seconds
const seconds_in_month_30    = 2592000;       # 1 month       = 60*60*24*30 seconds
const seconds_in_month_31    = 2678400;       # 1 month       = 60*60*24*31 seconds
const seconds_in_year        = 31536000;      # 1 year        = 60*60*24*365 seconds
const seconds_in_year_leap   = 31622400;      # 1 year        = 60*60*24*366 seconds

const bytes_in_kilobyte      = 1024;          # 1 kilobyte    = 1024 bytes
const bytes_in_megabyte      = 1048576;       # 1 megabyte    = 1048576 bytes
const bytes_in_gigabyte      = 1073741824;    # 1 gigabyte    = 1073741824 bytes
const bytes_in_terabyte      = 1099511627776; # 1 terabyte    = 1099511627776 bytes


require_once RUDE_DIR_CORE . DIRECTORY_SEPARATOR . 'filesystem' . DIRECTORY_SEPARATOR . 'rude-filesystem.php';
require_once RUDE_DIR_CORE . DIRECTORY_SEPARATOR . 'etc'        . DIRECTORY_SEPARATOR . 'rude-convert.php';
require_once RUDE_DIR_CORE . DIRECTORY_SEPARATOR . 'etc'        . DIRECTORY_SEPARATOR . 'rude-globals.php';
require_once RUDE_DIR_CORE . DIRECTORY_SEPARATOR . 'types'      . DIRECTORY_SEPARATOR . 'rude-strings.php';


autoload::init();


class autoload
{
	public static function add($file_paths)
	{
		if (!$file_paths)
		{
			return;
		}


		global $_autoload_paths;

		foreach ($file_paths as $file_path)
		{
			if (static::is_valid($file_path))
			{
				$class_name = static::class_name($file_path);

				if (isset($_autoload_paths[$class_name]))
				{
					new \exception("class name `$class_name` ($file_path) has been already defined in $_autoload_paths[$class_name]");
				}

				$_autoload_paths[$class_name] = $file_path;
			}
		}
	}

	public static function count()
	{
		return count(static::get());
	}

	/**
	 * @return array
	 */
	public static function & get()
	{
		global $_autoload_paths;

		if ($_autoload_paths === null)
		{
			$_autoload_paths = [];
		}

		return $_autoload_paths;
	}

	public static function is_valid($file_path)
	{
		$basename = basename($file_path);

		if (strings::starts_with($basename, 'rude-') and strings::ends_with($basename, '.php'))
		{
			return true;
		}

		return false;
	}

	public static function class_name($file_path)
	{
		$basename = basename($file_path, '.php');
		$basename = strings::replace_first($basename, '-', '\\');
		$basename = strings::replace      ($basename, '-', '_');

		return $basename;
	}

	public static function search($dir)
	{
		return filesystem::search_files($dir, 'php');
	}

	public static function scan($dir)
	{
		$files = static::search($dir);

		static::add($files);
	}

	public static function init()
	{
		if (!defined('RUDE_CLI'))           { define('RUDE_CLI',           php_sapi_name() == 'cli'); }
		if (!defined('RUDE_CLI_ARGUMENTS')) { define('RUDE_CLI_ARGUMENTS', ini_get('register_argc_argv')); }
		if (!defined('RUDE_AJAX'))          { define('RUDE_AJAX',          !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'); };

		if (!defined('RUDE_HASH_ALGORITHM_DEFAULT'))
		{
			define('RUDE_HASH_ALGORITHM_DEFAULT', 'sha512');
		}

		if (!defined('RUDE_DIR_WORKSPACE'))
		{
			define('RUDE_DIR_WORKSPACE', RUDE_APP_DIR . DIRECTORY_SEPARATOR . 'workspace');
		}


		# string encoding

		if (!defined('RUDE_STRING_ENCODING'))
		{
			define('RUDE_STRING_ENCODING', 'UTF-8');
		}

		mb_internal_encoding(RUDE_STRING_ENCODING);


		# sources

		static::scan(RUDE_DIR_CORE);

		if (defined('RUDE_DIR_WORKSPACE'))
		{
			static::scan(RUDE_DIR_WORKSPACE);
		}


		# autoload method

		spl_autoload_register(__NAMESPACE__ . '\autoload::autoload');


		# timezone

		if (!defined('RUDE_TIMEZONE')) { define('RUDE_TIMEZONE', 'UTC'); } # UTC, Europe/Minsk, etc

		date::set_timezone(RUDE_TIMEZONE); # set default timezone


		# debug

		if (defined('RUDE_DEBUG') and RUDE_DEBUG)
		{
			error_reporting(-1); # show all errors/warnings/stricts

			set_error_handler(__NAMESPACE__ . '\exception::handler_error', -1); # custom error handler
		}


		# database

		if (!defined('MYSQLI_TRANS_START_READ_WRITE')) { define('MYSQLI_TRANS_START_READ_WRITE', 2); }
		if (!defined('MYSQLI_TRANS_START_READ_ONLY'))  { define('MYSQLI_TRANS_START_READ_ONLY',  4); }

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



		# change working dir if code was launched in a bad manner (`php dir/index.php` instead of `cd dir && php index.php`)

		if (RUDE_CLI)
		{
			$arguments = console_arguments::get_raw();
			$argument = reset($arguments);

			if (strings::contains($argument, DIRECTORY_SEPARATOR))
			{
				if (!chdir(__DIR__))
				{
					trigger_error('unable to change working PHP directory', E_USER_WARNING);
				}
			}
		}

	}

	public static function autoload($class_name)
	{
		global $_autoload_paths;

		if ($_autoload_paths === null)
		{
			return;
		}

		if (isset($_autoload_paths[$class_name]))
		{
			require_once $_autoload_paths[$class_name];
		}
	}
}