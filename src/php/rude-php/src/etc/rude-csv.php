<?

namespace rude;

class csv
{
	public $items = [];

	public $delimiter = ',';

	public $text = '';

	public $file_path = null;

	public function __construct($items)
	{
		$this->items = $items;
	}

	public function load($file_path)
	{
		$this->file_path = $file_path;

		$this->items = array_map('str_getcsv', file($this->file_path));
	}

	public function save($file_path = null, $mode = 'w')
	{
		if ($file_path === null)
		{
			$this->file_path = filesystem::create_file_tmp();
		}
		else
		{
			$this->file_path = $file_path;
		}


		$file_pointer = fopen($this->file_path, $mode);

		foreach ($this->items as $items)
		{
			if (is_object($items))
			{
				$items = object::to_array($items);
			}

			fputcsv($file_pointer, $items);
		}

		$this->text = filesystem::read($this->file_path);

		return fclose($file_pointer);
	}

	public function append($file_path = null)
	{
		return static::save($file_path, 'a');
	}
}