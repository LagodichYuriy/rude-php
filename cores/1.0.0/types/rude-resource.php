<?

namespace rude;

class resource
{
	public static function path($resource)
	{
		return stream_get_meta_data($resource)['uri'];
	}
}