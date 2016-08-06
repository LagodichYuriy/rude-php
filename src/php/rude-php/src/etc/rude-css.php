<?

namespace rude;

class css
{
	public static function compress($data)
	{
		$data = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $data);

		$data = strings::replace($data, ["\r\n", "\r", "\n", "\t", '  '], '');
		$data = strings::replace($data, '{ ', '{');
		$data = strings::replace($data, ' }', '}');
		$data = strings::replace($data, '; ', ';');
		$data = strings::replace($data, ', ', ',');
		$data = strings::replace($data, ' {', '{');
		$data = strings::replace($data, '} ', '}');
		$data = strings::replace($data, ': ', ':');
		$data = strings::replace($data, ' ,', ',');
		$data = strings::replace($data, ' ;', ';');

		return $data;
	}
}