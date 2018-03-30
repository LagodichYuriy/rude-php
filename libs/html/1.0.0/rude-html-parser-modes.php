<?

namespace rude;

class html_parser_mode
{
	private $mode;
	
	private $modes = [];

	public function __construct()
	{
		$this->modes =
		[
			'initial',
			'before html',
			'before head',
			'in head',
			'in head noscript',
			'after head',
			'in body',
			'text',
			'in table',
			'in table text',
			'in caption',
			'in column group',
			'in table body',
			'in row',
			'in cell',
			'in select',
			'in select in table',
			'in template',
			'after body',
			'in frameset',
			'after frameset',
			'after after body',
			'after after frameset'
		];
	}

}