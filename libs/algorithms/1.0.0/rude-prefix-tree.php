<?

namespace rude;

class prefix_tree
{
	# structure for each tree node:
	# [freq, length, char_one => [freq, length, char_two => [...], char_three => [...]]]

	public $tree = [];

	public $frequency_min = 2;

	public function __construct($text = null, $chunk_size = 3)
	{
		if ($text !== null)
		{
			static::add_text($text, $chunk_size);
		}
	}

	public function add_text($text, $chunk_size = 3)
	{
		if ($chunk_size < 1)
		{
			$chunk_size = 1;
		}


		$chars = strings::chars($text);

		for ($i = 0; $i < $chunk_size; $i++)
		{
			$char_chunks = items::chunck($chars, $chunk_size, true);

			if (count(end($char_chunks)) != $chunk_size)
			{
				array_pop($char_chunks);
			}

			foreach ($char_chunks as $char_chunk)
			{
				static::add_chars($char_chunk);
			}

			array_shift($chars);
		}
	}

	public function add_word($string)
	{
		$chars = strings::chars($string);

		static::add_chars($chars);
	}

	public function add_chars($items)
	{
		$pointer = &$this->tree;


		$i = 1;

		foreach ($items as $item)
		{
			     if (isset($pointer[$item])) { $pointer[$item]['frequency']++; }
			else                             { $pointer[$item] = ['frequency' => 1, 'length' => $i]; }

			$pointer = &$pointer[$item];

			$i++;
		}
	}

	public function filter($frequency_min = null)
	{
		if ($frequency_min !== null)
		{
			$this->frequency_min = $frequency_min;
		}

		foreach ($this->tree as $char => $node)
		{
			foreach ($node as $key => $val)
			{
				static::filter_node($node);
			}
		}
	}

	private function filter_node($node)
	{
		foreach ($node as $key => $val)
		{
			switch ($key)
			{
				case 'frequency':
//					debug($node); die;

					if ($val < $this->frequency_min)
					{
						unset($node[$key]);

						return;
					}
					break;

				case 'length':
					break;

				default:
					static::filter_node($val);
			}
		}
	}

	public function debug()
	{
		foreach ($this->tree as $char => $node)
		{
			static::debug_node($node, $char);
		}

		static::debug_buffer();
	}

	private function debug_node($node, $substring)
	{
		foreach ($node as $key => $val)
		{
			switch ($key)
			{
				case 'frequency':
					static::debug_buffer($substring, $val);
					break;

				case 'length':
					break;

				default:
					static::debug_node($val, $substring . $key);
			}
		}
	}

	private function debug_buffer($substring = null, $frequency = null)
	{
		global $_debug_buffer;

		if ($substring !== null and $frequency !== null)
		{
			$chars = strings::chars($substring);

			foreach ($chars as $index => $char)
			{
				if (!ctype_print($char))
				{
					$chars[$index] = console::colorize('\\' . ord($char), false, 'magenta');
				}
			}

			$substring = items::implode($chars, '');


			$_debug_buffer[] = [$substring, $frequency];
		}
		else
		{
			$lines = [];

			$max_length = items::max_length($_debug_buffer, 0);

			foreach ($_debug_buffer as $items)
			{
				list($substring, $frequency) = $items;

				$lines[] = strings::pad_right("[$substring]:", $max_length + 3, ' ') . " $frequency";
			}


			$char_prev = null;

			foreach ($lines as $line)
			{
				$char = char::nth($line, 2);

				if ($char_prev !== $char)
				{
					$char_prev = $char;

					console::lines();
				}

				console::write($line);
			}
		}

		return $_debug_buffer;
	}
}