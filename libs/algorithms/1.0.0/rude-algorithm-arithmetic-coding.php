<?

namespace rude;

//define('ALGORITHM_ARITHMETIC_CODING_END_OF_FLOAT', 257);

class algorithm_arithmetic_coding
{
	private $stream;
	private $stream_size;

	private $block_size;
	private $precision;
	private $epsilon;

//	private $output;

	private $chars = [];

	private $offset = 0;

	private $dictionary;
	private $dictionary_size;

	private $chances;
	private $ranges;

	public $output_bits = 0;

	/** @var file */
	private $file;

	public function __construct($block_size, $arbitrary_precision)
	{
		$this->block_size = $block_size;
		$this->precision = $arbitrary_precision;
		$this->epsilon = '0.' . str_repeat('0', $arbitrary_precision - 1) . '1';

		bcscale($this->precision);
	}

	public function load($stream, $stream_size = null)
	{
		$this->offset = 0;

		$this->stream = $stream;

		     if ($stream_size !== null) { $this->stream_size = $stream_size;        }
		else                            { $this->stream_size = count($this->chars); }

		$this->chars = unpack('C*', $this->stream);
		$this->chars = array_values($this->chars);

		$this->dictionary      = array_count_values($this->chars);
		$this->dictionary_size = array_sum($this->dictionary);

		arsort($this->dictionary);

		foreach ($this->dictionary as $char => $frequency)
		{
			$this->chances[$char] = bcdiv($frequency, $this->dictionary_size);
		}

		static::calc_ranges();
	}

	private function calc_ranges()
	{
		$this->ranges = [];

		$offset = 0;

		foreach ($this->chances as $index => $chance)
		{
			$range = [$offset, bcadd($offset, $chance)]; # $range = [$offset, $offset + $chance];

			$offset = bcadd($offset, $chance); # $offset += $chance;

			$this->ranges[$index] = $range;
		}
	}

	public function encode()
	{
		if ($this->offset >= $this->stream_size)
		{
			return null;
		}


		$limit = $this->offset + $this->block_size;

		if ($limit > $this->stream_size)
		{
			$limit = $this->stream_size;
		}


		$offset_start = $this->offset;

		for ($left = 0, $right = 1; $this->offset <= $limit; $this->offset++) # $diff = $right - $left;
		{
			     if ($this->offset !== $limit) { $char = $this->chars[$this->offset]; }
			else                               { $char = ALGORITHM_ARITHMETIC_CODING_END_OF_FLOAT; }

			$range = $this->ranges[$char];

			$diff = bcsub($right, $left);

			if (bccomp($this->epsilon, $diff) === 1)
			{
				exception::warning("lost precision: [$offset_start -> $limit] (current char: $this->offset)");

				debug($diff);
				debug($range);

				return false;
			}

			$right = bcadd($left, bcmul($diff, $range[1])); # $right = $left + $diff * $range[1];
			$left  = bcadd($left, bcmul($diff, $range[0])); # $left  = $left + $diff * $range[0];
		}

		$this->offset--;


		$number = bcdiv(bcadd($right, $left), 2); # ($right + $left) / 2;

		$number_packed = static::pack($number, $diff);
//

		# TODO: ADD SKIP FOR BAD PACKING THINGS


//		debug('PACKED!');

//		debug($number_packed, true);
//
		$number_unpacked = static::unpack($number_packed);
//
//		debug('UNPACKED!');

//		debug($number);
//		debug($number_packed);
//		debug($number_unpacked);
//
//		debug($number);


		return $number;
	}

	public function decode($encoded)
	{
		$decoded        = '';
		$decoded_length = 0;

		while ($decoded_length < $this->block_size)
		{
			foreach ($this->ranges as $char => $range)
			{
				if (bccomp($range[0], $encoded) <= 0 and bccomp($encoded, $range[1]) <= 0) # if ($range[0] <= $number and $number <= $range[1])
				{
					if ($char === ALGORITHM_ARITHMETIC_CODING_END_OF_FLOAT)
					{
						break;
					}

					$decoded .= chr($char);
					$encoded = bcdiv(bcsub($encoded, $range[0]), bcsub($range[1], $range[0])); # $number = ($number - $range[0]) / ($range[1] - $range[0]);

					break;
				}
			}

			$decoded_length++;
		}

		return $decoded;
	}

	public function pack($encoded, $diff)
	{
		$bin = '';

		for ($divider = 1; $divider > $diff;)
		{
			$divider = bcdiv($divider, 2);

			if (bccomp($encoded, $divider) >= 0)
			{
				$bin .= '1';

				$encoded = bcsub($encoded, $divider);
			}
			else
			{
				$bin .= '0';
			}
		}

		return $bin;
	}

	public function unpack($packed)
	{
		for ($divider = 1, $decoded = 0, $bits_size = strlen($packed), $i = 0; $i < $bits_size; $i++)
		{
			$divider = bcdiv($divider, 2);

			if ($packed[$i])
			{
				$decoded = bcadd($decoded, $divider);
			}
		}

		return $decoded;
	}

	public function size_dictionary()
	{
		debug($this->dictionary);
		debug(count($this->dictionary));

		$max = max($this->dictionary);

		debug($max);

		for ($i = 1; $i <= 16; $i++)
		{
			$number = pow(2, $i);

			if ($number > $max)
			{
				break;
			}
		}

		debug($i);
	}

	public function size_body()
	{
		return round($this->output_bits / 8);
	}

	public function save($file_path)
	{
		$this->file = new file($file_path, 'w');

		static::save_dictionary();
	}

	private function save_header()
	{
		$marker = chr(7) . chr(4) . chr(9);
	}

	private function save_dictionary()
	{
		foreach ($this->dictionary as $char => $frequency)
		{

		}

		debug($this->dictionary);
	}
}