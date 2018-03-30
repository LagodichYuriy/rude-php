<?

namespace rude;

class csv
{
	private $path;
	private $mode;
	private $handle;
	private $delimiter;
	private $enclosure;
	private $escape_char;

	private $columns;
	private $columns_use_on_read = false;
	private $columns_use_on_save = false;

	public function __construct($file_path, $file_mode = 'r', $delimiter = ',', $enclosure = '"', $escape_char = "\\")
	{
		$this->path = $file_path;
		$this->mode = $file_mode;

		$this->delimiter = $delimiter;
		$this->enclosure = $enclosure;
		$this->escape_char = $escape_char;

		$this->handle = fopen($file_path, $this->mode);
	}

	public function columns($items, $use_on_read = true, $use_on_save = true)
	{
		$this->columns = $items;
		$this->columns_use_on_read = $use_on_read;
		$this->columns_use_on_save = $use_on_save;
	}

	public function __destruct()
	{
		static::close();
	}

	public function write_line($items = [])
	{
		return fputcsv($this->handle, $items, $this->delimiter, $this->enclosure, $this->escape_char);
	}

	public function write_lines($items)
	{
		foreach ($items as $item)
		{
			static::write_line($item);
		}
	}

	public function read_line()
	{
		$line = fgetcsv($this->handle, null, $this->delimiter, $this->enclosure, $this->escape_char);

		if ($line !== false and $this->columns !== null and $this->columns_use_on_read)
		{
			foreach ($this->columns as $key => $val)
			{
				items::rename($line, $key, $val);
			}
		}

		return $line;
	}

	public function read_lines($max_lines = INF)
	{
		$buffer = [];

		for ($i = 0; $i < $max_lines; $i++)
		{
			$line = static::read_line();

			if ($line === false)
			{
				break;
			}


			$buffer[] = $line;
		}

		return $buffer;
	}

	public function position()
	{
		return ftell($this->handle);
	}

	public function close()
	{
		if ($this->handle)
		{
			fclose($this->handle);

			$this->handle = null;
		}
	}
}