<?

namespace rude;

class tokenizer
{
	private $code = null;

	private $file_name = null;

	private $tokens          = [];
	private $tokens_unparsed = [];

	public function __construct($code = null)
	{
		$this->code = $code;
	}

	public function set_code($code)
	{
		static::reset();

		$this->code = $code;
	}

	public function set_file($file_name)
	{
		static::reset();

		$this->file_name = $file_name;
	}

	public function tokens()
	{
		return $this->tokens;
	}

	private function reset()
	{
		$this->code      = null;
		$this->file_name = null;

		$this->tokens          = [];
		$this->tokens_unparsed = [];
	}

	private function code()
	{
		if ($this->code !== null)
		{
			return $this->code;
		}

		if ($this->file_name !== null)
		{
			return filesystem::read($this->file_name);
		}

		return null;
	}

	public function analyze()
	{
		$code = static::code();

		$this->tokens_unparsed = token_get_all($code);

		foreach ($this->tokens_unparsed as $index => $token)
		{
			if (is_array($token))
			{
				$this->tokens[] = static::parse_token($token);
			}
			else
			{
				$this->tokens[] = $token;
			}
		}

		return $this->tokens;
	}

	private function parse_token($token)
	{
		items::rename($token, 0, 'tag_id');

		$token['tag_name'] = token_name($token['tag_id']);
		$token['tag_shortname'] = strings::read_after($token['tag_name'], 'T_');
		$token['tag_shortname'] = strings::to_lowercase($token['tag_shortname']);

		items::rename($token, 1, 'value');
		items::rename($token, 2, 'line');

		return items::to_object($token);
	}

	public function methods($names_only = false)
	{
		$result = [];


		$tokens_size = count($this->tokens);

		for ($i = 0; $i < $tokens_size; $i++)
		{
			$token = $this->tokens[$i];

			if (!is_object($token))
			{
				continue;
			}

			if ($token->token_id == T_FUNCTION)
			{
				for ($j = $i; $j < $tokens_size; $j++)
				{
					$token_next = $this->tokens[$j];

					if ($token_next->token_id == T_STRING)
					{
						     if ($names_only === false) { $result[] = $token_next;      }
						else                            { $result[] = $token_next->tag; }

						break;
					}
				}
			}
		}


		return $result;
	}

	public function parse()
	{
		foreach ($this->tokens as $token)
		{
			if (is_object($token))
			{
				debug($token);
			}
			else
			{
				debug($token, true);
			}

			debug(PHP_EOL);
		}

		die;

		return $this->tokens;
	}

	public function get_token($property, $value = null)
	{
		foreach ($this->tokens as $token)
		{
			if (is_array($token))
			{
				if (get($property, $token) == $value)
				{
					return $token;
				}
			}
			else if ($token == $property)
			{
				return $value;
			}
		}

		return null;
	}

	public function get_namespace()
	{

	}

	public function get_line($number)
	{

	}
}