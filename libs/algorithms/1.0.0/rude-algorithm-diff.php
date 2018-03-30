<?

namespace rude;

class algorithm_diff
{
	private $stream;
	private $stream_size = 0;

	private $chars = [];

	private $diff = [];

	private $map = [];

	public function __construct(&$stream)
	{
		$this->stream = &$stream;
		$this->stream_size = strings::size($this->stream);

		static::unpack();


//		$this->chars_amount = count($this->chars);

		static::load_map();

//		debug($this->map); die;


//		static::calc();

		static::analyze();
	}

	private function unpack()
	{
		$this->chars = new \SplFixedArray($this->stream_size);

		for ($i = 0; $i < $this->stream_size; $i++)
		{
			$this->chars[$i] = ord($this->stream[$i]);
		}
	}

	private function load_map()
	{
		$this->map = [];
		$this->map += array_fill(      0,   3, 1);
		$this->map += array_fill(  2 + 1,   2, 2);
		$this->map += array_fill(  4 + 1,   4, 3);
		$this->map += array_fill(  8 + 1,   8, 4);
		$this->map += array_fill( 16 + 1,  16, 5);
		$this->map += array_fill( 32 + 1,  32, 6);
		$this->map += array_fill( 64 + 1,  64, 7);
		$this->map += array_fill(128 + 1, 128, 8);
		$this->map = \SplFixedArray::fromArray($this->map);
	}

	private function calc()
	{
		$this->diff = new \SplFixedArray($this->stream_size);
		$this->diff[0] = $this->chars[0];

		for ($i = 1; $i < $this->stream_size; $i++)
		{
			$this->diff[$i] = $this->chars[$i] - $this->chars[$i - 1];
		}

//		debug($this->diff);
	}

	# [look@me ~]$ calc 2^1 = 2
	# [look@me ~]$ calc 2^2 = 4
	# [look@me ~]$ calc 2^3 = 8
	# [look@me ~]$ calc 2^4 = 16
	# [look@me ~]$ calc 2^5 = 32
	# [look@me ~]$ calc 2^6 = 64
	# [look@me ~]$ calc 2^7 = 128
	# [look@me ~]$ calc 2^8 = 256

	private function analyze($min_length)
	{
		$sequences = [];

		$buffer = [];

		for ($i = 0; $i < $this->stream_size; $i++)
		{
			$bits = $this->map[$this->chars[$i]];

			for ($j = 1; $j <= $bits; $j++)
			{
				if (!isset($buffer[$j]))
				{
					$buffer[$j] = $i;
				}
				else
				{
//					$sequences->;
				}
			}

			for (; $j <= 8; $j++)
			{
				if (isset($buffer[$j]))
				{

				}
			}
		}

		if ($buffer)
		{
			foreach ($buffer as $bits => $offset)
			{
				$sequences[] = 0;
			}
		}
	}

	private function find_sequences($min_length = 4)
	{

	}
}