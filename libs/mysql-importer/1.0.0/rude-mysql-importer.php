<?

namespace rude;

class mysql_importer
{
	private $table = null;

	private $csv = null;
	private $csv_path = null;
	private $csv_size_max = null;
	private $csv_delimiter = null;

	public function __construct($table, $csv_path = null, $csv_size_max = null, $csv_delimiter = ',')
	{
		$this->table = $table;

		$this->csv_delimiter = $csv_delimiter;


		if ($csv_path === null)
		{
			$this->csv_path = tempnam(sys_get_temp_dir(), $table . '.csv');

			$this->csv = new csv($this->csv_path, 'w', $csv_delimiter);
		}
		else
		{
			$this->csv_path = $csv_path;

			$this->csv = new csv($this->csv_path, 'c', $csv_delimiter);
		}


		if ($csv_size_max === null)
		{
			if (!defined('RUDE_DATABASE_MAX_ALLOWED_PACKET'))
			{
				define('RUDE_DATABASE_MAX_ALLOWED_PACKET', static::max_allowed_packet());
			}

			$this->csv_size_max = (int) (RUDE_DATABASE_MAX_ALLOWED_PACKET - static::header_size() - convert::kilobytes_to_bytes(1));
		}
		else
		{
			$this->csv_size_max = (int) $csv_size_max;
		}

		if ($this->csv_size_max < convert::kilobytes_to_bytes(1))
		{
			exception::error("csv mysql file for import: $this->csv_size_max < RUDE_SIZE_KILOBYTE");
		}
	}

	private function header_size()
	{
		$database = database();

		$header = items::implode($database->fields($this->table), $this->csv_delimiter);

		return strings::size($header);
	}

	public function add($items)
	{
		$this->csv->write_line($items);

		foreach ($items as $index => $item)
		{
			unset($items[$index]);
		}
	}

	public function query()
	{
		$file_paths = static::split_file($this->csv_path);

		foreach ($file_paths as $index => $file_path)
		{
//			debug($file_path);

			static::query_file($file_path);
		}
	}

	public function split_file($file_path, $file_size_max = null)
	{
		if ($file_size_max === null)
		{
			$file_size_max = $this->csv_size_max;
		}

		if (filesystem::size($file_path) <= $file_size_max)
		{
			return [$file_path];
		}


		$file_dir  = filesystem::file_dir     ($file_path);
		$file_name = filesystem::file_basename($file_path);

		$php_dir = directory::current();

		directory::change($file_dir);


		$file_name_tmp = "$file_name.tmp.";

		`split $file_name -C $file_size_max -d $file_name_tmp`;

		directory::change($php_dir);


		$result = [];

		foreach (filesystem::search_files($file_dir) as $index => $path)
		{
			if (strings::contains($path, $file_name_tmp))
			{
				$result[] = $path;
			}
		}

		sort($result);

		return $result;
	}

	private function query_file($file_path)
	{
		# https://stackoverflow.com/a/15002729

		$database = database();
		$database->query
		("
			LOAD DATA LOCAL INFILE '{$file_path}' IGNORE
			INTO TABLE {$this->table}
			FIELDS TERMINATED BY '$this->csv_delimiter'
			ENCLOSED BY '\"'
			LINES TERMINATED BY '\n'
			(" . implode(', ', $database->fields($this->table)) . ")
		");
	}

	public static function max_allowed_packet()
	{
		$database = database();
		$database->query('SELECT @@global.max_allowed_packet as `value`');

		return $database->get_object()->value;
	}
}
