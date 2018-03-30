<?

namespace rude;

/**
 * @category types
 */
class items
{
	public static function get($array, $n, $default = null)
	{
		return get($n, $array, $default);
	}

	public static function nth($array, $n)
	{
		$i = 1;

		foreach ($array as $item)
		{
			if ($i == $n)
			{
				return $item;
			}

			$i++;
		}

		return null;
	}

	/**
	 * @en Get the first element of an array
	 * @ru Возвращает первый (первые) элементы массива
	 *
	 * @param array $array
	 * @param int   $n
	 *
	 * @param bool  $preserve_keys
	 *
	 * @param bool  $return_array_only
	 *
	 * @return mixed
	 */
	public static function first($array, $n = 1, $preserve_keys = true, $return_array_only = false)
	{
		$result = [];


		$i = 1;

		if ($preserve_keys)
		{
			foreach ($array as $index => $item)
			{
				if ($i++ > $n)
				{
					break;
				}

				$result[$index] = $item;
			}
		}
		else
		{
			foreach ($array as $item)
			{
				if ($i++ > $n)
				{
					break;
				}

				$result[] = $item;
			}
		}

		if ($n === 1 and !$return_array_only)
		{
			return array_shift($result);
		}

		return $result;
	}

	public static function second($array) { return static::nth($array, 2); }
	public static function third ($array) { return static::nth($array, 3); }

	/**
	 * @en Get the last element of an array
	 * @ru Возвращает последний (последние) элементы массива
	 *
	 * @param array $array
	 * @param int   $n
	 *
	 * @param bool  $preserve_keys
	 *
	 * @return mixed
	 */
	public static function last($array, $n = 1, $preserve_keys = true)
	{
		return static::first(array_reverse($array, $preserve_keys), $n, $preserve_keys);
	}

	public static function first_index($array) { return key(array_slice($array,  0, 1, true)); }
	public static function last_index($array)  { return key(array_slice($array, -1, 1, true)); }

	/**
	 * Shift an element off from the beginning of array
	 *
	 * @param      $array
	 * @param int  $n
	 * @param bool $return_array_only
	 * @param bool $preserve_keys
	 *
	 * @return array|mixed
	 */
	public static function shift(&$array, $n = 1, $preserve_keys = true, $return_array_only = false)
	{
		$result = [];

		reset($array);

		if ($preserve_keys)
		{
			$array_keys = array_keys($array);

			for ($i = 0; $i < $n and $array; $i++)
			{
				$result[array_shift($array_keys)] = array_shift($array);
			}
		}
		else
		{
			for ($i = 0; $i < $n and $array; $i++)
			{
				$result[] = array_shift($array);
			}
		}

		if ($n === 1 and !$return_array_only)
		{
			return items::first($result);
		}

		return $result;
	}

	public static function pop(&$array, $n = 1, $return_array_only = false)
	{
		if ($n === 1)
		{
			     if ($return_array_only === false) { return  array_pop($array) ; }
			else                                   { return [array_pop($array)]; }
		}


		$result = [];

		for ($i = 0; $i < $n and $array; $i++)
		{
			$result[] = array_pop($array);
		}

		return $result;
	}

	public static function push(&$array, $item)
	{
		return array_push($array, $item);
	}

	public static function unshift(&$array, $item)
	{
		return array_unshift($array, $item);
	}

	public static function contains($haystack, $needle, $any = false)
	{
		if (empty($haystack))
		{
			return false;
		}

		if (is_array($needle))
		{
			if ($any)
			{
				foreach ($needle as $item)
				{
					if (static::contains($haystack, $item))
					{
						return true;
					}
				}

				return false;
			}

			return !array_diff($needle, $haystack);
		}

		return in_array($needle, $haystack);
	}

	public static function exist($item, $property)
	{
		if (is_array($item))
		{
			return isset($item[$property]);
		}

		return isset($item->$property);
	}

	/**
	 * @en Pick one or more random entries out of an array
	 * @ru Выбирает указанное количество случайных элементов из массива
	 *
	 * @param array $array
	 * @param int
	 *
	 * @return mixed
	 */
	public static function rand($array, $n = 1)
	{
		if ($n == 1)
		{
			$array[array_rand($array, $n)];
		}


		$result = [];

		foreach (array_rand($array, $n) as $key)
		{
			$result[] = $array[$key];
		}

		return $result;
	}

	public static function swap(&$array, $a, $b)
	{
		if (is_numeric($a)) { $a = $a - 1; }
		if (is_numeric($b)) { $b = $b - 1; }

		$temp      = $array[$a];
		$array[$a] = $array[$b];
		$array[$b] = $temp;

		return $array;
	}

	/**
	 * @en Same as trim(), just for all elements of the array
	 * @ru Тоже самое, что и trim(), только для всех элементов в массиве
	 *
	 * @param array $array
	 * @param array $chars
	 *
	 * @return array
	 */
	public static function trim($array, $chars = [" ", "\t", "\n", "\r", "\0", "\x0B"])
	{
		foreach ($array as $index => $item)
		{
			$array[$index] = strings::trim($item, $chars);
		}

		return $array;
	}

	/**
	 * @en Count all occurrences of specified arrays inside array
	 * @ru Возвращает количество копий определённых элементов в массиве
	 *
	 * $haystack = array('a', 'b', 'f', 'r', 'b', 'v', 'r', 'b', 't', 'a');
	 * $needle = array('a', 'b');
	 *
	 * $count = array_count($needle, $haystack); # int(2)
	 *
	 * @param array $needle
	 * @param array $haystack
	 *
	 * @return int
	 */
	public static function count($needle, $haystack)
	{
		$count = INF;

		$array = array_count_values($haystack);

		foreach ($needle as $item)
		{
			if (!isset($array[$item]))
			{
				return 0;
			}

			$count = min($count, $array[$item]);
		}

		return (int) $count;
	}

	public static function sum($array, $property = null)
	{
		$result = 0;

		foreach ($array as $item)
		{
			if ($property === null)
			{
				$result += (float) $item;
			}
			else
			{
				$result += (float) get($property, $item);
			}
		}

		return $result;
	}

	public static function remove_first($array, $n = 1, $preserve_keys = false)
	{
		if ($n <= 0)
		{
			return $array;
		}

		return array_slice($array, $n, null, $preserve_keys);
	}

	public static function remove_last($array, $n = 1, $preserve_keys = false)
	{
		$array_size = count($array);

		if ($n >= $array_size)
		{
			return [];
		}

		return array_slice($array, null, count($array) - $n, $preserve_keys);
	}

	/**
	 * @en Erase specified items inside array
	 * @ru Убирает полные копии указанных элементов из массива
	 *
	 * $haystack = array('a', 'b', 'f', 'r', 'b', 'v', 'r', 'b', 't', 'a');
	 * $needle = array('a', 'b');
	 *
	 * $result = arrays::remove_pairs($needle, $haystack); # array('f', 'r', 'v', 'r', 'b', 't');
	 *
	 * @param array $needle
	 * @param array $haystack
	 * @param int $count
	 *
	 * @return mixed
	 */
	public static function remove_pairs($needle, $haystack, $count = null)
	{
		if ($count === null)
		{
			$count = static::count($needle, $haystack);
		}

		if (!$count)
		{
			return $haystack;
		}


		$result = $haystack;

		foreach ($needle as $item)
		{
			$index = array_keys($result, $item);

			for ($i = 0; $i < $count; $i++)
			{
				unset($result[$index[$i]]);
			}
		}

		return $result;
	}

	public static function remove_empty($array)
	{
		if (!$array)
		{
			return $array;
		}

		return array_filter($array);
	}

	public static function key_first($array)
	{
		reset($array);

		return key($array);
	}

	public static function key_last($array)
	{
		end($array);

		return key($array);
	}

	public static function key_nth($array, $n)
	{
		$i = 1;

		foreach ($array as $index => $item)
		{
			if ($i == $n)
			{
				return $index;
			}

			$i++;
		}

		return null;
	}

//	public static function key($items, $value, $strict = false)
//	{
//		foreach ($items as $key => $val)
//		{
//			if ($strict !== false)
//			{
//				if ($val === $value) { return $key; }
//			}
//			else
//			{
//				if ($val ==  $value) { return $key; }
//			}
//		}
//
//		return null;
//	}

	public static function keys($items)
	{
		return array_keys($items);
	}

	/**
	 * @en Generating all permutations of a given array
	 * @ru Получение всех возможны вариантов перестановок элементов массива
	 *
	 * $array = array('AAA', 'BBB', 'CCC');
	 *
	 * $result = arrays::permutation($array); # Array
	 *                                        # (
	 *                                        #     [0] => Array
	 *                                        #     (
	 *                                        #         [0] => AAA
	 *                                        #         [1] => BBB
	 *                                        #         [2] => CCC
	 *                                        #     )
	 *                                        #
	 *                                        #     [1] => Array
	 *                                        #     (
	 *                                        #         [0] => AAA
	 *                                        #         [1] => CCC
	 *                                        #         [2] => BBB
	 *                                        #     )
	 *                                        #
	 *                                        #     [2] => Array
	 *                                        #     (
	 *                                        #         [0] => BBB
	 *                                        #         [1] => CCC
	 *                                        #         [2] => AAA
	 *                                        #     )
	 *                                        #
	 *                                        #     [3] => Array
	 *                                        #     (
	 *                                        #         [0] => BBB
	 *                                        #         [1] => AAA
	 *                                        #         [2] => CCC
	 *                                        #     )
	 *                                        #
	 *                                        #     [4] => Array
	 *                                        #     (
	 *                                        #         [0] => CCC
	 *                                        #         [1] => AAA
	 *                                        #         [2] => BBB
	 *                                        #     )
	 *                                        #
	 *                                        #     [5] => Array
	 *                                        #     (
	 *                                        #         [0] => CCC
	 *                                        #         [1] => BBB
	 *                                        #         [2] => AAA
	 *                                        #     )
	 *                                        # )
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function permutation($array)
	{
		$results = array();

		if (count($array) == 1)
		{
			$results[] = $array;
		}
		else
		{
			for ($i = 0; $i < count($array); $i++)
			{
				$first = array_shift($array);

				$subresults = static::permutation($array);

				array_push($array, $first);

				foreach ($subresults as $subresult)
				{
					$results[] = array_merge(array($first), $subresult);
				}
			}
		}

		return $results;
	}

	public static function to_object($array, $escape_keys = false)
	{
		if ($array === null)
		{
			return [];
		}

		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$array[$key] = static::to_object($value, $escape_keys);
			}
		}

		$object = (object) $array;

		if ($escape_keys)
		{
			$search = ['-'];

			foreach ($object as $key => $val)
			{
				if (strings::contains($key, $search))
				{
					$key_escaped = strings::replace($key, $search, '_');

					$object->$key_escaped = $object->$key;

					unset($object->$key);
				}
			}
		}

		return $object;
	}

	public static function max_length($items, $property = null)
	{
		$max_length = 0;

		foreach ($items as $item)
		{
			if ($property !== null)
			{
				$item = get($property, $item);
			}

			$max_length = max($max_length, strings::length($item));
		}

		return $max_length;
	}

	public static function min_length($items, $property = null)
	{
		$min_length = INF;

		foreach ($items as $item)
		{
			if ($property !== null)
			{
				$item = get($property, $item);
			}

			$min_length = min($min_length, strings::length($item));
		}

		if ($min_length === INF)
		{
			return 0;
		}

		return $min_length;
	}

	public static function max($items, $property = null)
	{
		if ($property !== null)
		{
			$items = static::select($items, $property);
		}

		return max($items);
	}

	public static function min($items, $property = null)
	{
		if ($property !== null)
		{
			$items = static::select($items, $property);
		}

		return min($items);
	}

	public static function max_int($items) { return static::extremum($items,  true, true); }
	public static function min_int($items) { return static::extremum($items, false, true); }

	public static function max_float($items) { return static::extremum($items,  true, false); }
	public static function min_float($items) { return static::extremum($items, false, false); }

	private static function extremum($items, $max = true, $int = true)
	{
		     if ($max) { $result = -INF; }
		else           { $result =  INF; }

		foreach ($items as $item)
		{
			     if ($int) { $value = filter_var($item, FILTER_SANITIZE_NUMBER_INT); }
			else           { $value = filter_var($item, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); }

			if ($value !== false)
			{
				if (is_numeric($value))
				{
					     if ($int) { $value =   (int) $value; }
					else           { $value = (float) $value; }

					     if ($max) { $result = max($result, $value); }
					else           { $result = min($result, $value); }
				}
			}
		}

		if ($result === INF or $result === -INF)
		{
			return null;
		}

		return $result;
	}

	public static function add($items, $number) { foreach ($items as $index => $item) { $items[$index] = $item + $number; } return $items; }
	public static function sub($items, $number) { foreach ($items as $index => $item) { $items[$index] = $item - $number; } return $items; }
	public static function div($items, $number) { foreach ($items as $index => $item) { $items[$index] = $item / $number; } return $items; }
	public static function mul($items, $number) { foreach ($items as $index => $item) { $items[$index] = $item * $number; } return $items; }

	public static function rename(&$item, $old, $new)
	{
		$item[$new] = $item[$old];

		unset($item[$old]);
	}

	public static function reverse($items, $preserve_keys = false)
	{
		return array_reverse($items, $preserve_keys);
	}

	public static function reverse_keys($items)
	{
		return array_reverse(array_reverse($items, true), false);
	}

	public static function flip($items)
	{
		return array_flip($items);
	}

	public static function select($items, $property)
	{
		if (is_object($items) and $items instanceof \Traversable)
		{
			$result = [];

			foreach ($items as $item)
			{
				if (isset($item->{$property}))
				{
					$result[] = $item->{$property};
				}
			}

			return $result;
		}

		return array_column($items, $property);
	}

	public static function select_unique($items, $property)
	{
		if (is_object($items) and $items instanceof \Traversable)
		{
			$result = [];

			foreach ($items as $item)
			{
				if (isset($item->{$property}))
				{
					$result[$item->{$property}] = $item->{$property};
				}
			}

			return $result;
		}

		return array_column($items, $property, $property);
	}

	public static function assoc($items, $property = null)
	{
		$result = [];

		if ($property === null)
		{
			foreach ($items as $item)
			{
				$result[$item] = $item;
			}
		}
		else
		{
			foreach ($items as $item)
			{
				$result[get($property, $item)] = $item;
			}
		}

		return $result;
	}

	public static function where(&$items, $property, $value, $strict = false, $unset = false)
	{
		$result = [];

		foreach ($items as $index => $item)
		{
			$val = get($property, $item, null);

			     if ($strict) { $is_equal = $val === $value; }
			else              { $is_equal = $val ==  $value; }

			if ($is_equal)
			{
				$result[] = $item;

				if ($unset)
				{
					unset($items[$index]);
				}
			}
		}

		return $result;
	}

	public static function unset_where(&$items, $property, $value, $strict = false)
	{
		foreach ($items as $index => $item)
		{
			     if ($strict) { $is_equal = $item[$property] === $value; }
			else              { $is_equal = $item[$property]  == $value; }

			if ($is_equal)
			{
				unset($items[$index]);
			}
		}
	}

	public static function remove($items, $remove)
	{
		return array_diff($items, $remove);
	}

	public static function remove_keys($items, $keys)
	{
		if (is_string($keys))
		{
			$keys = [$keys];
		}

		foreach ($items as $index => $item)
		{
			$is_object = is_object($item);

			if ($is_object)
			{
				foreach (objects::properties($item) as $property => $val)
				{
					if (items::contains($keys, $property))
					{
						unset($items[$index]->{$property});
					}
				}
			}
			else
			{
				foreach (items::keys($item) as $key => $val)
				{
					if (items::contains($keys, $key))
					{
						unset($items[$index][$key]);
					}
				}
			}
		}

		return $items;
	}

	public static function remove_keys_except($items, $keys)
	{
		if (is_string($keys))
		{
			$keys = [$keys];
		}

		foreach ($items as $index => $item)
		{
			$is_object = is_object($item);

			if ($is_object)
			{
				foreach (objects::properties($item) as $property => $val)
				{
					if (!items::contains($keys, $property))
					{
						unset($items[$index]->{$property});
					}
				}
			}
			else
			{
				foreach (items::keys($item) as $key => $val)
				{
					if (!items::contains($keys, $key))
					{
						unset($items[$index][$key]);
					}
				}
			}
		}

		return $items;
	}

	public static function diff($a, $b)
	{
		return array_diff($a, $b);
	}

	public static function common($a, $b)
	{
		return array_intersect($a, $b);
	}

	public static function implode($items, $glue = ', ')
	{
		return implode($glue, $items);
	}

	public static function explode($items, $delimiter = ', ', $limit = null)
	{
		if ($limit === null)
		{
			return explode($delimiter, $items);
		}

		return explode($delimiter, $items, $limit);
	}

	public static function chunck($items, $size, $preserve_keys = true)
	{
		if ($preserve_keys)
		{
			return array_chunk($items, $size, true);
		}

		return array_chunk($items, $size);
	}

	public static function group($items, $field, $subfield = null)
	{
		$result = [];

		foreach ($items as $item)
		{
			$value = get($field, $item);

			if (!isset($result[$value]))
			{
				$result[$value] = [];
			}

			     if ($subfield === null) { $result[$value][]                      = $item; }
			else                         { $result[$value][get($subfield, $item)] = $item; }
		}

		return $result;
	}

	public static function append($items, $string, $append_after = true, $append_before = false, $skip_last = false)
	{
		     if ($skip_last) { $last_index = static::last_index($items); }
		else                 { $last_index = null;                       }

		foreach ($items as $index => $item)
		{
			if ($skip_last and $last_index == $index)
			{
				break;
			}

			if ($append_before) { $items[$index] = $string . $items[$index]; }
			if ($append_after)  { $items[$index] = $items[$index] . $string; }
		}

		return $items;
	}

	public static function append_before($items, $string, $skip_last = false) { return static::append($items, $string, false,  true, $skip_last); }
	public static function append_after ($items, $string, $skip_last = false) { return static::append($items, $string,  true, false, $skip_last); }
	public static function append_both  ($items, $string, $skip_last = false) { return static::append($items, $string,  true,  true, $skip_last); }

	public static function pad($items, $property = null, $pad_length = null, $pad_string = ' ', $pad_direction = STR_PAD_RIGHT)
	{
		if ($pad_length === null)
		{
			$pad_length = static::max_length($items, $property);
		}

		foreach ($items as $index => $item)
		{
			if ($property !== null)
			{
				if (is_object($item))
				{
					$items[$index]->$property = strings::pad($item->$property, $pad_length, $pad_string, $pad_direction);
				}
				else
				{
					$items[$index][$property] = strings::pad($item[$property], $pad_length, $pad_string, $pad_direction);
				}
			}
			else
			{
				$items[$index] = strings::pad($item, $pad_length, $pad_string, $pad_direction);
			}
		}

		return $items;
	}

	public static function pad_left ($items, $property = null, $pad_length = null, $pad_string = ' ') { return static::pad($items, $property, $pad_length, $pad_string, STR_PAD_LEFT);  }
	public static function pad_right($items, $property = null, $pad_length = null, $pad_string = ' ') { return static::pad($items, $property, $pad_length, $pad_string, STR_PAD_RIGHT); }
	public static function pad_both ($items, $property = null, $pad_length = null, $pad_string = ' ') { return static::pad($items, $property, $pad_length, $pad_string, STR_PAD_BOTH);  }
}
