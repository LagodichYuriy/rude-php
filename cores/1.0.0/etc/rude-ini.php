<?

namespace rude;

class ini
{
	private $settings = [];

	public function parse_file($path_ini)
	{
		$data = filesystem::read($path_ini);

		static::parse_string($data);
	}

	public function parse_string($data)
	{
		$this->settings = [];


		$lines = strings::lines($data);

		foreach ($lines as $line)
		{
			$line = trim($line);

			$char = char::first($line);

			if ($char == '[' or $char == ';')
			{
				continue;
			}

			if (!strings::contains($line, '='))
			{
				continue;
			}


			$key = strings::read_until($line, '=');
			$val = strings::read_after($line, '=');

			$key = strings::trim($key);
			$val = strings::trim($val);

			$this->settings[$key] = $val;
		}
	}

	public function get($index = null)
	{
		if ($index !== null)
		{
			return get($index, $this->settings);
		}

		return $this->settings;
	}
}