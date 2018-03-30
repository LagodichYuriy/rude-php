<?

namespace rude;

class html_tokens
{
	/** @var html_tokens[] */
	protected $tokens;

	/** @var html_token */
	public $token;

	public function emit()
	{
		$this->tokens[] = $this->token;

		unset($this->token);
	}

	public function emit_eof() { }
	public function emit_comment() {  }

	public function create($type = null)
	{
		$this->token = new html_token($type);

		return $this->token;
	}

}