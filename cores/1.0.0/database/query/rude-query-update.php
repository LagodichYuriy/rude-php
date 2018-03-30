<?

namespace rude;

class query_update extends query
{
	/**
	 * @param string        $table
	 * @param database|null $database
	 */
	public function __construct($table, $database = null)
	{
		     if ($database === null) { $this->database = database(); }
		else                         { $this->database = $database;  }

		static::_select_table($table);
		static::_select_from($table);
	}

	public function update($field, $value) { static::_update_value($field, $value); }

	public function where                 ($field, $value, $table = null) { static::_where                 ($table, $field, $value); }
	public function where_not             ($field, $value, $table = null) { static::_where_not             ($table, $field, $value); }
	public function where_greater         ($field, $value, $table = null) { static::_where_greater         ($table, $field, $value); }
	public function where_greater_or_equal($field, $value, $table = null) { static::_where_greater_or_equal($table, $field, $value); }
	public function where_less            ($field, $value, $table = null) { static::_where_less            ($table, $field, $value); }
	public function where_less_or_equal   ($field, $value, $table = null) { static::_where_less_or_equal   ($table, $field, $value); }

	public function where_null    ($field, $table = null) { static::_where_null    ($table, $field); }
	public function where_not_null($field, $table = null) { static::_where_not_null($table, $field); }

	public function order_asc ($field) { static::_order_asc ($field); }
	public function order_desc($field) { static::_order_desc($field); }

	public function limit ($limit)  { static::_limit($limit);   }
	public function offset($offset) { static::_offset($offset); }

	public function sql() { return static::sql_update(); }

	public function query() { return $this->database->query($this->sql()); }

	public function affected() { return $this->database->affected(); }
}