<?

namespace rude;

/**
 * @category types
 */
class objects
{
	public static function to_array($object)
	{
		if (is_array($object) || is_object($object))
		{
			$result = null;

			foreach ($object as $key => $value)
			{
				$result[$key] = static::to_array($value);
			}

			return $result;
		}

		return $object;
	}

	public static function get($object, $property)
	{
		return get($property, $object);
	}

	public static function properties($object)
	{
		return get_object_vars($object);
	}

	public static function has_property($object, $property)
	{
		return isset($object->{$property});
	}
}