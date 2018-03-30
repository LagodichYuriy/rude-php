<?

namespace rude;

class algorithm_byte_pair
{
	private $chars        = [];
	private $chars_amount = 0;

	public function __construct(&$chars, $chars_amount = null)
	{
		$this->chars = $chars;

		if ($chars_amount === null)
		{
			$this->chars_amount = count($chars);
		}

		$this->chars_amount = $chars_amount;
	}

	public function analyze()
	{

	}


}