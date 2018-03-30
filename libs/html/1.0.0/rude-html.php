<?

namespace rude;

class html
{
	private $html;

	/** @var html_tokenizer */
	private $parser;

	/** @var html_node[] */
	private $nodes = [];

	public function __construct($html = null)
	{
		static::load($html);
	}

	public function load($html = null)
	{
		$this->html = $html;
	}

	public function load_file($path)
	{
		$file = new file($path, 'r');

		$this->html = $file->read();

		$file->close();
	}

	public function parse()
	{
		$this->parser = new html_tokenizer($this->html);
		$this->parser->parse();
	}

//	public function

	public function parse_word()
	{

	}

	public static function escape($string)
	{
		return htmlspecialchars($string);
	}

	public static function unescape($string)
	{
		return htmlspecialchars_decode($string);
	}
}