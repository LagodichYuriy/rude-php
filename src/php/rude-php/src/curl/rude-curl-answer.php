<?

namespace rude;

class curl_answer
{
	public $id      = null;
	public $url     = null;
	public $info    = null;
	public $content = null;

	public function __construct($id, $url, $info, $content)
	{
		$this->id = $id;
		$this->url = $url;
		$this->info = items::to_object($info);
		$this->content = $content;
	}
}