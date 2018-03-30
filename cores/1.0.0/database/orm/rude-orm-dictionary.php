<?

namespace rude;

class orm_dictionary
{
	private $dictionary = [];

	private $columns = [];
	private $columns_except_primary_auto_increment = [];

	public function add($key, $val, $mysql = true, $php = false, $php_uppercase = false, $do_not_escape = false)
	{
		if ($mysql !== false)
		{
			$this->dictionary["%MYSQL_{$key}%"] = $val;
		}

		if ($do_not_escape !== true)
		{
			$val = static::escape($val);
		}

		if ($php           !== false) { $this->dictionary["%PHP_{$key}%"] = $val; }
		if ($php_uppercase !== false) { $this->dictionary["%PHP_{$key}_UPPERCASE%"] = strings::to_uppercase($val); }
	}

	public function sort()
	{
		ksort($this->dictionary);
	}

	public function get($key = null)
	{
		if ($key !== null)
		{
			if (!strings::starts_with($key, '%'))
			{
				$key = "%$key%";
			}

			return $this->dictionary[$key];
		}

		static::sort();

		return $this->dictionary;
	}

	public function is_exists($key)
	{
		return isset($this->dictionary[$key]);
	}

	public function set_columns                              ($columns) { $this->columns                               = $columns; }
	public function set_columns_except_primary_auto_increment($columns) { $this->columns_except_primary_auto_increment = $columns; }

	public function add_namespace($namespace) { static::add('NAMESPACE', $namespace, false, true); }

	public function add_table_name($table_name) { static::add('TABLE_NAME', $table_name, true, true, true); }

	public function add_class_name($class_name)
	{
		$class_name = strings::to_uppercase($class_name);
		$class_name = strings::replace($class_name, [' ', '-'], '_');

		static::add('CLASS_NAME', $class_name, true, true, true);
	}

	public function add_class_name_alias($class_name, $strategy_name)
	{
		$class_name = strings::to_uppercase($class_name);
		$class_name = strings::replace($class_name, [' ', '-'], '_');

		$strategy_name = strings::to_uppercase($strategy_name);
		$strategy_name = strings::replace($strategy_name, [' ', '-'], '_');

		static::add("CLASS_NAME_$strategy_name", $class_name, true, true, true);
	}

	public function add_primary($val) { static::add('PRIMARY_KEY', $val, true, true, true); }

	public function add_fields               ($val) { static::add('FIELDS',                static::escape_fields($val), false, true, true, true); }
	public function add_fields_except_primary($val) { static::add('FIELDS_EXCEPT_PRIMARY', static::escape_fields($val), false, true, true, true); }

	public static function escape_fields($fields)
	{
		$fields = static::escape($fields);
		$fields = items::append_before($fields, '$');
		$fields = items::append_after($fields, ' = null');
		$fields = items::implode($fields, ', ');

		return $fields;
	}

	public static function escape($var, $to_lowercase = true, $replace = '_', $search = [' ', '-'])
	{
		if (is_array($var))
		{
			foreach ($var as $index => $item)
			{
				$var[$index] = static::escape($item);
			}

			return $var;
		}

		if ($to_lowercase)
		{
			$var = strings::to_lowercase($var);
		}

		return strings::replace($var, $search, $replace);
	}

	public function replace($template)
	{
		foreach ($this->dictionary as $key => $val)
		{
			$template = strings::replace($template, $key, $val);
		}

		$template = static::replace_loops($template, $this->columns_except_primary_auto_increment, '%FOR_EACH_FIELD_EXCEPT_PRIMARY_AUTO_INCREMENT%');
		$template = static::replace_loops($template, $this->columns,                               '%FOR_EACH_FIELD%');

		$template = static::replace_aligns($template);

		return $template;
	}

	private function replace_loops($template, $columns, $marker = '%FOR_EACH_FIELD%')
	{
		if (!$columns or !strings::contains($template, $marker))
		{
			return $template;
		}

		do
		{
			$loop = strings::read_between($template, $marker, $marker);

			$loop_parts = [];

			foreach ($columns as $column)
			{
				$loop_part = strings::replace($loop, '%PHP_FIELD_LOWERCASE%', static::escape($column->field));
				$loop_part = strings::replace($loop_part, '%MYSQL_FIELD%', $column->field);
				$loop_part = strings::replace($loop_part, '%MYSQL_FIELD_ESCAPED_QUOTE%', strings::replace($column->field, "'", "\\'"));

				$loop_part = strings::trim_left($loop_part, "\n");
				$loop_part = strings::trim_right($loop_part, [" ", "\t", "\0", "\x0B"]);

				$loop_parts[] = $loop_part;
			}

			$loop_parts = items::implode($loop_parts, '');
			$loop_parts = strings::trim($loop_parts);

			$template = strings::replace_between($template, $marker, $marker, $loop_parts, true, true);
		}
		while ($loop);

		return $template;
	}

	private function replace_aligns($templates)
	{
		$lines = strings::lines($templates);

		$buffer = [];

		foreach ($lines as $line_index => $line)
		{
			if (strings::contains($line, '%ALIGN%'))
			{
				$buffer[$line_index] = $line;
			}
			else if ($buffer)
			{
				$max_length = 0;

				foreach ($buffer as $buffer_line)
				{
					$substring        = strings::read_until($buffer_line, '%ALIGN%');
					$substring_length = strings::length($substring);

					$max_length = max($max_length, $substring_length);
				}


				$buffer_replaced = [];

				foreach ($buffer as $buffer_index => $buffer_line)
				{
					$substring        = strings::read_until($buffer_line, '%ALIGN%');
					$substring_length = strings::length($substring);

					$replace = strings::repeat(' ', $max_length - $substring_length);

					$buffer_replaced[$buffer_index] = strings::replace($buffer_line, '%ALIGN%', $replace);
				}

				foreach ($buffer_replaced as $buffer_replaced_index => $buffer_replaced_line)
				{
					$lines[$buffer_replaced_index] = $buffer_replaced_line;
				}

				$buffer = [];
			}
		}

		$templates = strings::implode($lines, PHP_EOL);

		return $templates;
	}
}