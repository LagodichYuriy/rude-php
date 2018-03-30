<?

namespace rude;

class query
{
	/** @var database */
	protected $database = null;

	/** @var \stdClass */
	protected $table = null;

	protected $create_fields = [];
	protected $create_indexes = [];
	protected $create_options = [];

	/** @var \stdClass[] */
	protected $select_from = [];
	protected $select_fields = [];

	protected $insert_values = [];

	protected $update_values = [];

	protected $where = [];

	protected $limit  = null;
	protected $offset = null;

	protected $orders = [];


	private function supported_indexes()          { return ['INDEX', 'UNIQUE', 'FULLTEXT', 'SPATIAL']; }
	private function supported_where_conditions() { return ['=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'IS NULL', 'IS NOT NULL']; }
	private function supported_order_directions() { return ['ASC', 'DESC']; }


	# SELECT

	protected function _select_table($table_name)
	{
		$table = new \stdClass();
		$table->name         = $table_name;
		$table->name_escaped = static::escape($table_name);

		$this->table = $table;

		static::_select_from($table_name);
	}

	protected function _select_from($table_name)
	{
		if (static::in_array($this->select_from, $table_name, 'name'))
		{
			return;
		}

		$table = new \stdClass();
		$table->name         = $table_name;
		$table->name_escaped = static::escape($table_name);

		$this->select_from[] = $table;
	}

	protected function _select_field($field_name)
	{
		if (is_array($field_name))
		{
			foreach ($field_name as $item)
			{
				static::_select_field($item);
			}

			return;
		}

		if (isset($this->select_fields[$field_name]))
		{
			return;
		}


		static::verify_field($field_name);

		$field = new \stdClass();
		$field->name         = $field_name;
		$field->name_escaped = static::escape($field_name);

		$this->select_fields[$field->name] = $field;
	}


	# INSERT

	protected function _insert_value($field_name, $value)
	{
		static::verify_field($field_name);

		$obj = new \stdClass();
		$obj->name  = $field_name;
		$obj->value = $value;

		$this->insert_values[] = $obj;
	}


	# UPDATE

	protected function _update_value($field_name, $value)
	{
		static::verify_field($field_name);

		$field = new \stdClass();
		$field->name          = $field_name;
		$field->name_escaped  = static::escape($field_name);
		$field->value         = $value;

		$this->update_values[] = $field;
	}


	# CONDITIONS

	protected function _where($table_name, $field_name, $value = null, $type = '=')
	{
		if (is_array($value))
		{
			if ($type ==  '=') { $type = 'IN';     }
			if ($type == '!=') { $type = 'NOT IN'; }
		}

		if ($value === null)
		{
			$value = 'NULL';
		}


		static::verify_field($field_name);

		if (!items::contains(static::supported_where_conditions(), $type))
		{
			exception::error("Wrong WHERE condition: `$type` (" . items::implode(static::supported_where_conditions()) . " expected)");
		}

		if ($table_name === null)
		{
			$table_name = $this->table->name_escaped;
		}

		$field = new \stdClass();
		$field->table         = $table_name;
		$field->name          = $field_name;
		$field->name_escaped  = static::escape($field_name);
		$field->value         = $value;
		$field->type          = $type;

		$this->where[] = $field;
	}

	protected function _where_not             ($table_name, $field_name, $value) { static::verify_field($field_name); static::_where($table_name, $field_name, $value, '!=');     }
	protected function _where_in              ($table_name, $field_name, $value) { static::verify_field($field_name); static::_where($table_name, $field_name, $value, 'IN');     }
	protected function _where_not_in          ($table_name, $field_name, $value) { static::verify_field($field_name); static::_where($table_name, $field_name, $value, 'NOT IN'); }
	protected function _where_greater         ($table_name, $field_name, $value) { static::verify_field($field_name); static::_where($table_name, $field_name, $value, '>');      }
	protected function _where_greater_or_equal($table_name, $field_name, $value) { static::verify_field($field_name); static::_where($table_name, $field_name, $value, '>=');     }
	protected function _where_less            ($table_name, $field_name, $value) { static::verify_field($field_name); static::_where($table_name, $field_name, $value, '<');      }
	protected function _where_less_or_equal   ($table_name, $field_name, $value) { static::verify_field($field_name); static::_where($table_name, $field_name, $value, '<=');     }

	protected function _where_null    ($table_name, $field_name) { static::verify_field($field_name); static::_where($table_name, $field_name, $value = null, 'IS NULL');     }
	protected function _where_not_null($table_name, $field_name) { static::verify_field($field_name); static::_where($table_name, $field_name, $value = null, 'IS NOT NULL'); }

	protected function _limit($limit)
	{
		if ($limit !== null)
		{
			$this->limit = (int) $limit;
		}
	}

	protected function _offset($offset)
	{
		if ($offset !== null)
		{
			$this->offset = (int) $offset;
		}
	}


	# ORDER BY

	protected function _order($field_name, $direction)
	{
		static::verify_field($field_name);

		if (!items::contains(static::supported_order_directions(), $direction))
		{
			exception::error("Wrong ORDER BY direction: `$direction` (" . items::implode(static::supported_order_directions()) . " expected)");
		}

		$field = new \stdClass();
		$field->name         = $field_name;
		$field->name_escaped = static::escape($field_name);
		$field->direction    = $direction;

		$this->orders[] = $field;
	}

	protected function _order_asc ($field) { static::_order($field, 'ASC');  }
	protected function _order_desc($field) { static::_order($field, 'DESC'); }


	# CREATE

	protected function _create_field($name, $type, $null = null, $default = null, $auto_increment = null, $length = null, $decimals = null, $charset_name = null, $collation_name = null, $values = null)
	{
		$field = new \stdClass();
		$field->name                   = $name;
		$field->name_escaped           = static::escape($name);
		$field->type                   = $type;
		$field->type_escaped           = static::escape($type);;
		$field->null                   = $null;
		$field->default                = $default;
		$field->default_escaped        = static::escape($default);
		$field->auto_increment         = $auto_increment;
		$field->length                 = $length;
		$field->decimals               = $decimals;
		$field->charset_name           = $charset_name;
		$field->charset_name_escaped   = static::escape($charset_name);
		$field->collation_name         = $collation_name;
		$field->collation_name_escaped = static::escape($collation_name);
		$field->values                 = $values;

		$this->create_fields[] = $field;
	}

	protected function _create_index($field, $index_type = 'INDEX')
	{
		if (!items::contains(static::supported_indexes(), $index_type))
		{
			exception::error("Unknown index type: `$index_type` (" . items::implode(static::supported_indexes()) . " expected)");
		}

		$obj = new \stdClass();
		$obj->field         = $field;
		$obj->field_escaped = static::escape($field);
		$obj->type          = $index_type;

		$this->create_indexes[] = $obj;
	}

	protected function _create_index_unique($field)   { static::_create_index($field, 'UNIQUE');   }
	protected function _create_index_fulltext($field) { static::_create_index($field, 'FULLTEXT'); }
	protected function _create_index_spatial($field)  { static::_create_index($field, 'SPATIAL');  }

	protected function _create_option($key, $val)
	{
		$obj = new \stdClass();
		$obj->key         = $key;
		$obj->key_escaped = static::escape($key);
		$obj->val         = $val;
		$obj->val_escaped = static::escape($val);

		$this->create_options[] = $obj;
	}

	protected function sql_select()
	{
		$template = static::get_template('select');

		$template = static::_sql_fields($template); # [required] fields
		$template = static::_sql_from  ($template); # [required] from
		$template = static::_sql_where ($template); # [optional] where
		$template = static::_sql_order ($template); # [optional] order
		$template = static::_sql_limit ($template); # [optional] limit and offset

		return strings::replace($template, PHP_EOL . PHP_EOL, PHP_EOL);
	}

	protected function sql_delete()
	{
		$template = static::get_template('delete');

		$template = static::_sql_from  ($template); # [required] from
		$template = static::_sql_where ($template); # [optional] where
		$template = static::_sql_order ($template); # [optional] order
		$template = static::_sql_limit ($template); # [optional] limit and offset

		return static::format($template);
	}

	protected function sql_update()
	{
		$template = static::get_template('update');

		$template = static::_sql_update($template); # [required] from
		$template = static::_sql_set   ($template); # [required] fields
		$template = static::_sql_where ($template); # [optional] where
		$template = static::_sql_order ($template); # [optional] order
		$template = static::_sql_limit ($template); # [optional] limit and offset

		return static::format($template);
	}

	protected function _sql_fields($template)
	{
		if (!$this->select_fields)
		{
			$fields = '*';
		}
		else
		{
			$delimiter_fields = static::get_delimiter($template, 'SELECT', '%FIELDS%');

			$fields = static::combine_items($this->select_fields, $delimiter_fields, 'name_escaped');
		}

		return strings::replace($template, '%FIELDS%', $fields);
	}

	protected function _sql_from($template)
	{
		$delimiter_from = static::get_delimiter($template, 'FROM', '%FROM%');

		$from = static::combine_items($this->select_from, $delimiter_from, 'name_escaped');

		return strings::replace($template, '%FROM%', $from);
	}

	protected function _sql_update($template)
	{
		$delimiter_from = static::get_delimiter($template, 'UPDATE', '%TABLE%');

		$from = static::combine_items($this->select_from, $delimiter_from, 'name_escaped');

		return strings::replace($template, '%TABLE%', $from);
	}

	protected function _sql_set($template)
	{
		$delimiter_set = static::get_delimiter($template, 'SET', '%FIELDS%');

		$set = [];

		foreach ($this->update_values as $field)
		{
			$column = $this->database->column($this->table->name, $field->name);

			$value = static::to($field->value, $column->type);

			$set[] = "`$field->name_escaped` = $value";
		}

		$set = items::implode($set, ',' . $delimiter_set);

		return strings::replace($template, '%FIELDS%', $set);
	}

	protected function _sql_where($template)
	{
		if (!$this->where)
		{
			return strings::replace($template, '%WHERE%', '1 = 1');
		}


		$delimiter_where = static::get_delimiter($template, 'WHERE',  '%WHERE%');

		$where_list = '';

		foreach ($this->where as $where)
		{
			$column = $this->database->column($where->table, $where->name);

			$value = static::to($where->value, $column->type);


			switch ($where->type)
			{
				case     'IN':
				case 'NOT IN':
					$where_list .= PHP_EOL . "AND{$delimiter_where}`$where->name_escaped` $where->type($value)";
					break;

				case 'IS NULL':
				case 'IS NOT NULL':
					$where_list .= PHP_EOL . "AND{$delimiter_where}`$where->name_escaped` $where->type";
					break;

				default:
					$where_list .= PHP_EOL . "AND{$delimiter_where}`$where->name_escaped` $where->type $value";
			}

			if ($where->type ==     'IN' or
			    $where->type == 'NOT IN')
			{

			}
			else if ($where->type == '')
			{

			}
			else
			{

			}
		}

		$where_list = strings::remove_first($where_list, PHP_EOL . 'AND' . $delimiter_where);

		return strings::replace($template, '%WHERE%', $where_list);
	}

	protected function _sql_order($template)
	{
		$delimiter_order = static::get_delimiter($template, 'ORDER BY',  '%ORDER%');


		if (!$this->orders)
		{
			return strings::remove($template, 'ORDER BY' . $delimiter_order . '%ORDER%');
		}


		$orders = [];

		foreach ($this->orders as $order)
		{
			$orders[] = "`$order->name_escaped` $order->direction";
		}

		$orders = items::implode($orders, ',' . $delimiter_order);

		return $template = strings::replace($template, '%ORDER%', $orders);
	}

	protected function _sql_limit($template)
	{
		if (!$this->limit and $this->offset)
		{
			$this->limit = PHP_INT_MAX;
		}

		if (!$this->offset)
		{
			$delimiter_offset = static::get_delimiter($template, 'OFFSET', '%OFFSET%');
			$delimiter_limit  = static::get_delimiter($template, 'LIMIT',  '%LIMIT%');

			$template = strings::remove($template, 'OFFSET' . $delimiter_offset . '%OFFSET%');

			     if (!$this->limit) { return strings::remove ($template, 'LIMIT'  . $delimiter_limit . '%LIMIT%'); }
			else                    { return strings::replace($template, '%LIMIT%', (int) $this->limit); }
		}


		$template = strings::replace($template, '%OFFSET%', (int) $this->offset);

		return strings::replace($template, '%LIMIT%', (int) $this->limit);
	}

	public function sql_insert()
	{
		$template = static::get_template('insert');

		$template = static::_sql_insert($template); # [required] table
		$template = static::_sql_values($template); # [required] values

		return strings::replace($template, PHP_EOL . PHP_EOL, PHP_EOL);
	}

	public function _sql_insert($template)
	{
		$template = strings::replace($template, '%TABLE%', "`{$this->table->name_escaped}`");

		$delimiter_fields = strings::read_between($template, '(', '%FIELDS%');

		$insert = static::combine_items(static::columns(), $delimiter_fields, 'field');

		return strings::replace($template, '%FIELDS%', $insert);
	}

	public function _sql_values($template)
	{
		$delimiter_values = strings::read_between($template, 'VALUES', '%VALUES%', true, true, null);
		$delimiter_values = strings::read_between($delimiter_values, '(', '%VALUES%');


		$values = [];

		foreach (static::columns() as $column)
		{
			$insert = null; /** @var $insert mixed */

			foreach ($this->insert_values as $insert_value)
			{
				if ($insert_value->name == $column->field)
				{
					$insert = $insert_value;

					break;
				}
			}

			if ($insert !== null)
			{
				$values[] = static::to($insert->value, $column->type);
			}
			else
			{
				if ($column->default !== null)
				{
					if ($column->default != 'CURRENT_TIMESTAMP')
					{
						$values[] = static::to($column->default, $column->type);
					}
					else
					{
						$values[] = 'NULL';
					}
				}
				else
				{
					$values[] = 'NULL';
				}
			}
		}

		$values = items::implode($values, ',' . $delimiter_values);

		return strings::replace($template, '%VALUES%', $values);
	}


	# ETC

	public function affected()
	{
		return $this->database->affected();
	}

	private function verify_field($field, $table_name = null)
	{
		if (is_array($field))
		{
			foreach ($field as $field_name)
			{
				static::verify_field($field_name);
			}

			return;
		}

		if ($table_name === null)
		{
			$table_name = $this->table->name;
		}

		if (!items::contains($this->database->fields($table_name), $field))
		{
			exception::error("Field `$field` hasn't been found in `$table_name`");
		}
	}

	private function in_array($array, $search_item, $search_index = null)
	{
		if (!$array)
		{
			return false;
		}

		if ($search_index !== null)
		{
			$array = items::select($array, $search_index);
		}

		return items::contains($array, $search_item);
	}

	public function escape($var)
	{
		if ($this->database === null)
		{
			exception::error('Database connection is not established');
		}

		return $this->database->escape($var);
	}

	protected function get_template($name)
	{
		if (cache::is_exist("template_$name"))
		{
			return cache::get("template_$name");
		}


		$path = filesystem::combine(__DIR__, 'templates', "template-query-$name.php");

		if (!filesystem::is_exist($path))
		{
			exception::warning("File `$path` was not found");

			return '';
		}

		$data = filesystem::read($path);

		cache::add("template_$name", $data);

		return $data;
	}

	private function get_delimiter($template, $read_from, $read_to)
	{
		return strings::read_between($template, $read_from, $read_to);
	}

	private function combine_items($items, $delimiter, $property = null, $char_escape = '`', $char_delimiter = ',')
	{
		if ($property !== null)
		{
			$items = items::select($items, $property);
		}

		$items = items::append_both($items, $char_escape);

		return items::implode($items, $char_delimiter . $delimiter);
	}

	private function columns($table_name = null)
	{
		if ($table_name === null)
		{
			$table_name = $this->table->name;
		}

		return $this->database->columns($table_name);
	}

	private function format($sql)
	{
		return strings::replace($sql, PHP_EOL . PHP_EOL, PHP_EOL);
	}


	# TYPES

	private function to($var, $type)
	{
		if ($type == 'IN'     or
			$type == 'NOT IN' or
			is_array($var))
		{
			     if (static::is_number($type) or static::is_enum_numbers($type)) { return static::to_enum_numbers($var); }
			else if (static::is_string($type) or static::is_enum_strings($type)) { return static::to_enum_strings($var); }
			else
			{
				exception::error("Unsupported enum field type: `$type`");
			}
		}
		else if (static::is_string   ($type)) { return static::to_string   ($var); }
		else if (static::is_number   ($type)) { return static::to_number   ($var); }
		else if (static::is_float    ($type)) { return static::to_float    ($var); }
		else if (static::is_boolean  ($type)) { return static::to_boolean  ($var); }
		else if (static::is_time     ($type)) { return static::to_time     ($var); }
		else if (static::is_date     ($type)) { return static::to_date     ($var); }
		else if (static::is_timestamp($type)) { return static::to_timestamp($var); }
		else if (static::is_datetime ($type)) { return static::to_datetime ($var); }
		else if (static::is_enum     ($type))
		{
			     if (static::is_enum_strings($type)) { return static::to_string($var); }
			else if (static::is_enum_numbers($type)) { return static::to_number($var); }
		}
		else
		{
			exception::error("Unsupported field type: `$type`");
		}

		return null;
	}

	private function to_number($var) { return   (int) $var; }
	private function to_float ($var) { return (float) $var; }

	private function to_string($var)
	{
		return "'" . static::escape((string) $var) . "'";
	}

	private function to_boolean($var)
	{
		if ($var)
		{
			return 'TRUE';
		}

		return 'FALSE';
	}

	private function to_datetime($string)
	{
		$timestamp = strtotime($string);

		if (!$timestamp or $timestamp < 1)
		{
			return "'0000-00-00 00:00:00'";
		}

		return "'" . date('Y-m-d H:i:s', $timestamp) . "'";
	}

	private function to_time($string)
	{
		$timestamp = strtotime($string);

		if (!$timestamp or $timestamp < 1)
		{
			return "'00:00:00'";
		}

		return "'" . date('H:i:s', $timestamp) . "'";
	}

	private function to_date($string)
	{
		$timestamp = strtotime($string);

		if (!$timestamp or $timestamp < 1)
		{
			return "'0000-00-00'";
		}

		return "'" . date('Y-m-d', $timestamp) . "'";
	}

	private function to_timestamp($var)
	{
		$date = date_create($var);

		if (!$date)
		{
			return "''";
		}

		return "'" . date_format($date, 'Y-m-d H:i:s') . "'";
	}

	private function to_enum_numbers(array $var_list)
	{
		$result = [];

		foreach ($var_list as $var)
		{
			$result[] = static::to_number($var);
		}

		return items::implode($result, ', ');
	}

	private function to_enum_strings(array $var_list)
	{
		$result = [];

		foreach ($var_list as $var)
		{
			$result[] = static::to_string($var);
		}

		return items::implode($result, ', ');
	}


	######################
	# validation section #
	######################

	protected function in_table($table, $field)
	{
		foreach (static::columns($table) as $column)
		{
			if ($column->field == $field)
			{
				return true;
			}
		}

		return false;
	}

	# TODO: move verification to column object ($column->type)

	protected function is_string($field_type)    { return strings::starts_with($field_type, ['varchar', 'text', 'char', 'tinytext', 'mediumtext', 'longtext']); }
	protected function is_number($field_type)    { return strings::starts_with($field_type, ['int', 'tinyint', 'smallint', 'mediumint', 'bigint']); }

	protected function is_boolean($field_type)   { return strings::starts_with($field_type, 'bit');                          }
	protected function is_float($field_type)     { return strings::starts_with($field_type, ['float', 'double', 'decimal']); }
	protected function is_datetime($field_type)  { return strings::starts_with($field_type, 'datetime');                     }
	protected function is_timestamp($field_type) { return strings::starts_with($field_type, 'timestamp');                    }
	protected function is_date($field_type)      { return strings::starts_with($field_type, 'date') and
	                                                     !strings::starts_with($field_type, 'datetime');                     }
	protected function is_time($field_type)      { return strings::starts_with($field_type, 'time') and
	                                                     !strings::starts_with($field_type, 'timestamp');                    }

	private function is_enum($field_type)         { return strings::starts_with($field_type, 'enum');                               }
	private function is_enum_strings($field_type) { return strings::starts_with($field_type, "enum('");                             }
	private function is_enum_numbers($field_type) { return static::is_enum($field_type) and !static::is_enum_strings($field_type);  }
}