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

	public function get_object()      { return $this->database->get_object();      }
	public function get_object_list() { return $this->database->get_object_list(); }

	public function get_id() { return $this->database->insert_id(); }
}