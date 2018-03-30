<?

namespace rude;

class algorithm_sequences
{
	private $sequences = [];

	public function __construct(&$chars, $chars_amount = null)
	{
		parent::__construct($chars, $chars_amount);
	}

	public function & find($sequence_length = 8, $frequency_min = 4)
	{
		$this->sequences = [];


		$sequence = [];

		for ($i = 0; $i < $sequence_length; $i++)
		{
			$sequence[] = $this->chars[$i];
		}

		$sequence_string = implode(RUDE_COMPRESSOR_CHAR_IMPLODE, $sequence);

		$this->sequences[$sequence_string] = 1;


		for (; $i < $this->chars_amount; $i++)
		{
			array_shift($sequence);

			$sequence[] = $this->chars[$i];

			$sequence_string = implode(RUDE_COMPRESSOR_CHAR_IMPLODE, $sequence);

			     if (!isset($this->sequences[$sequence_string])) { $this->sequences[$sequence_string] = 1; }
			else                                                 { $this->sequences[$sequence_string]++;   }
		}


		foreach ($this->sequences as $sequence => $frequency)
		{
			if ($frequency < $frequency_min)
			{
				unset($this->sequences[$sequence]);
			}
		}

		arsort($this->sequences);


		return $this->sequences;
	}
}