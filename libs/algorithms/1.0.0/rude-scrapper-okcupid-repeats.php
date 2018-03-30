<?

namespace rude;

class scrapper_okcupid_repeats
{
	private $stream;

	/** @var scrapper_okcupid_repeat[] */
	private $repeats            = []; # [repeat_id => repeat]

	private $index_by_length    = []; # [length][repeat_id => repeat_id]
	private $index_by_substring = []; # [substring][offset => repeat_id]

	private $frequency = [];

	private $repeat_id = 1;

	private $length_min = 2;
	private $length_max = 10;

	private $frequency_min = 2;


	public function __construct($stream)
	{
		$this->stream = &$stream;
		$this->stream_length = strings::length($this->stream);
	}

	public function find()
	{
		$this->repeats = [];

		$this->index_by_length = [];

		for ($length = $this->length_min; $length <= $this->length_max; $length++)
		{
			$stream = $this->stream;

			for ($i = 0; $i < $length; $i++)
			{
				$substrings = static::split($stream, $length);

				foreach ($substrings as $index => $substring)
				{
					$repeat = new scrapper_okcupid_repeat();
					$repeat->id = static::id();
					$repeat->substring = $substring;
					$repeat->offset = $index * $length + $i;
					$repeat->length = $length;

					     if (isset($this->index_by_length[$length])) { $this->index_by_length[$length][$repeat->id] = $repeat->id;     }
					else                                             { $this->index_by_length[$length] = [$repeat->id => $repeat->id]; }

					     if (isset($this->index_by_substring[$substring])) { $this->index_by_substring[$substring][$repeat->offset] = $repeat->id;     }
					else                                                   { $this->index_by_substring[$substring] = [$repeat->offset => $repeat->id]; }

					$this->repeats[$repeat->id] = $repeat;
				}

				$stream = mb_substr($stream, 1);
			}
		}

		static::frequency_calc();
		static::frequency_filter();


		debug($this->repeats);
	}

	private function add()
	{
		$repeat = new scrapper_okcupid_repeat();

		$this->repeats[] = $repeat;
	}

	private function id()
	{
		return $this->repeat_id++;
	}

	public function frequency_filter()
	{
		foreach ($this->frequency as $substring => $frequency)
		{
			if ($frequency < $this->frequency_min)
			{
				unset($this->frequency[$substring]);
			}
		}

		arsort($this->frequency);
	}

	public static function analyze($strings, $repeats)
	{
		$max_profit = 0;

		foreach ($repeats as $length => $substrings)
		{
			foreach ($substrings as $substring => $frequency)
			{
				$max_profit = max($max_profit, $length * $frequency);
			}
		}
	}

	public function frequency_calc()
	{
		$this->frequency = [];

		foreach ($this->repeats as $repeat)
		{
			     if (isset($this->frequency[$repeat->substring])) { $this->frequency[$repeat->substring]++;   }
			else                                                  { $this->frequency[$repeat->substring] = 1; }
		}
	}

	public static function find_max_profit($repeats)
	{
		$max_profit = 0;

		foreach ($repeats as $length => $substrings)
		{
			foreach ($substrings as $substring => $frequency)
			{
				$max_profit = max($max_profit, $length * $frequency);
			}
		}

		return [$length, $substring, $max_profit];
	}

	public static function split($string, $length)
	{
		$substrings = strings::split($string, $length);

		if (strings::length(end($substrings)) != $length)
		{
			array_pop($substrings);
		}

		return $substrings;
	}

	public static function repeat($substring, $length, $offset, $frequency)
	{
		$repeat = new \stdClass();
		$repeat->id = static::repeat_id();
		$repeat->substring = $substring;
		$repeat->length = $length;
		$repeat->offset = $offset;
		$repeat->frequency = $frequency;

		return $repeat;
	}
}