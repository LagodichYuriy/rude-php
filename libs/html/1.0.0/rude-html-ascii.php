<?

namespace rude;

class html_ascii
{
	private $chars = [];
	private $chars_alphabetic = [];

	private $chars_alphabetic_lowercase = [];
	private $chars_alphabetic_uppercase = [];

	private $chars_to_uppercase = [];
	private $chars_to_lowercase = [];

	public function __construct()
	{
		for ($i = 0; $i <= 127; $i++)
		{
			$char = chr($i);

			$this->chars             [$char] = $i;
			$this->chars_to_lowercase[$char] = strtolower($char);
			$this->chars_to_uppercase[$char] = strtoupper($char);
		}

		$this->chars_alphabetic_lowercase = range('a', 'z');
		$this->chars_alphabetic_uppercase = range('A', 'Z');

		$this->chars                      = array_flip($this->chars);
		$this->chars_alphabetic_lowercase = array_flip($this->chars_alphabetic_lowercase);
		$this->chars_alphabetic_uppercase = array_flip($this->chars_alphabetic_uppercase);

		$this->chars_alphabetic = $this->chars_alphabetic_lowercase + $this->chars_alphabetic_uppercase;
	}

	public function is_uppercase($char) { return isset($this->chars_alphabetic_uppercase[$char]); }
	public function is_lowercase($char) { return isset($this->chars_alphabetic_lowercase[$char]); }

	public function to_uppercase($char) { return $this->chars_to_uppercase[$char]; }
	public function to_lowercase($char) { return $this->chars_to_lowercase[$char]; }
}