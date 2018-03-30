<?

namespace rude;

class algorithm_repeats extends algorithms
{
	private $repeats = [];

	public function __construct(&$chars, $chars_amount = null)
	{
		parent::__construct($chars, $chars_amount);
	}

	public function & find($min_length = 4)
	{
		$this->repeats = [];


		$i_max = $this->chars_amount - 1;

		for ($i = 0; $i < $i_max; $i++)
		{
			$char      = $this->chars[$i];
			$char_next = $this->chars[$i + 1];

			if ($char === $char_next)
			{
				for ($j = $i + 2; $j < $i_max; $j++)
				{
					if ($this->chars[$j] !== $char)
					{
						break;
					}
				}


				$length = $j - $i - 1;

				if ($length >= $min_length)
				{
					$this->repeats[] = [$char, $length, $i];
				}

				$i += $length;
			}
		}
	}
}