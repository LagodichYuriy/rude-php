<?

namespace rude;

class query_insert extends query
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

	public function add($field, $value)
	{
		static::_insert_value($field, $value);
	}

	public function sql() { return static::sql_insert(); }

	public function query() { return $this->database->query(static::sql()); }

	public function get_object()                                              { return $this->database->get_object();                                }
	public function get_object_list($field_index = null, $field_value = null) { return $this->database->get_object_list($field_index, $field_value); }

	public function get_array()         { return $this->database->get_array();         }
	public function get_array_assoc()   { return $this->database->get_array_numeric(); }
	public function get_array_numeric() { return $this->database->get_array_numeric(); }

	public function get_array_list        ($field_index = null, $field_value = null) { return $this->database->get_array_list        ($field_index, $field_value); }
	public function get_array_list_assoc  ($field_index = null, $field_value = null) { return $this->database->get_array_list_assoc  ($field_index, $field_value); }
	public function get_array_list_numeric($field_index = null, $field_value = null) { return $this->database->get_array_list_numeric($field_index, $field_value); }

	public function get_id() { return $this->database->insert_id(); }
}