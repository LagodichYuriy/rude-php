<?

namespace rude;

/**
 * @category database
 */
class database
{
	/** @var \mysqli  */
	private $mysqli = null;

	/** @var \mysqli_result  */
	private $result = null;

	private $name = null; # current database name

	private $columns = null; # columns cache


	public function __construct($host, $user, $pass, $name = null, $port = 3306, $charset = 'utf8', $die_on_error = true)
	{
		if ($die_on_error)
		{
			$this->mysqli = new \mysqli($host, $user, $pass, $name, (int) $port);

			if ($this->mysqli->connect_error)
			{
				exception::error('Connect Error (' . static::error_code() . '). ' . strings::to_capitalcase(static::error()));
			}

			if (!$this->mysqli->set_charset($charset))
			{
				exception::error("Error loading character set ($charset): {$this->mysqli->error}");
			}
		}
		else
		{
			$this->mysqli = @new \mysqli($host, $user, $pass, $name, (int) $port);

			$this->mysqli->set_charset($charset);
		}

		$this->name = $name;
	}

	public function create($database_name)
	{
		return static::query('CREATE DATABASE IF NOT EXISTS `' . static::escape($database_name) . '`');
	}

	public function select($database_name)
	{
		$result = $this->mysqli->select_db($database_name);

		if ($result)
		{
			$this->name = $database_name;
		}
		else
		{
			exception::error("Unable to select database with name `$database_name`");
		}

		return $result;
	}

	public function error_code() { return mysqli_connect_errno(); }
	public function error()      { return mysqli_connect_error(); }

	public function charset() { return $this->mysqli->character_set_name(); }

	/**
	 * @en Execute SQL query. WARNING: do not forget to escape SQL queries via escape() method if you don't use query() classes
	 * @ru Выполнение построенного SQL запроса. ВАЖНО: не забывайте экранировать SQL запросы с помощью метода escape() если вы генерируете SQL запрос без помощи семейства классов query()
	 *
	 * $database = new database($host, $user, $pass, $name);
	 * $database->query('SELECT * FROM users WHERE 1 = 1');
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	public function query($string)
	{
		$this->result = $this->mysqli->query($string);

		if ($this->result === false)
		{
			exception::error(strings::replace($this->mysqli->error, PHP_EOL, ' ') . ':' . PHP_EOL . PHP_EOL . $string);
		}

		return $this->result;
	}

	public function escape($var)
	{
		return $this->mysqli->real_escape_string($var);
	}

	public function affected()
	{
		return $this->mysqli->affected_rows;
	}

	public function insert_id()
	{
		return $this->mysqli->insert_id;
	}

	public function databases()
	{
		return static::query('SHOW DATABASES');
	}

	public function tables()
	{
		$this->query('SHOW TABLES');

		$result = [];

		foreach ($this->get_object_list() as $object)
		{
			foreach ($object as $table)
			{
				$result[] = $table;

				break;
			}
		}

		return $result;
	}

	public function columns($table)
	{
		if (isset($this->columns[$this->name][$table])) # search results in cache
		{
			return $this->columns[$this->name][$table];
		}


		$result = $this->query('SHOW COLUMNS FROM `' . $this->escape($table) . '`');

		if (!isset($this->columns[$this->name]))
		{
			$this->columns[$this->name] = null;
		}

		while ($column = $result->fetch_row())
		{
			items::rename($column, 0, 'field');   # [0] - field
			items::rename($column, 1, 'type');    # [1] - type
			items::rename($column, 2, 'null');    # [2] - null
			items::rename($column, 3, 'key');     # [3] - key
			items::rename($column, 4, 'default'); # [4] - default
			items::rename($column, 5, 'extra');   # [5] - extra

			$this->columns[$this->name][$table][] = items::to_object($column);
		}

		return $this->columns[$this->name][$table];
	}

	/**
	 * @param $table
	 * @param $field
	 *
	 * @return \stdClass|null
	 */
	public function column($table, $field)
	{
		foreach (static::columns($table) as $column)
		{
			if ($column->field == $field)
			{
				return $column;
			}
		}

		return null;
	}

	public function fields($table)
	{
		$columns = $this->columns($table);

		return items::select($columns, 'field');
	}

	/**
	 * @en Get query result as an object list
	 * @ru Получить ответ из базы данных в виде массива объектов
	 *
	 * $database = new database(); # do not forget to declare defines before calling this class:
	 *                             # > RUDE_DATABASE_USER
	 *                             # > RUDE_DATABASE_PASS
	 *                             # > RUDE_DATABASE_HOST
	 *                             # > RUDE_DATABASE_NAME
	 *
	 * $database->query('SELECT * FROM users WHERE 1 = 1');
	 *
	 * $result = $database->get_object_list(); # Array
	 *                                         # (
	 *                                         #     [0] => stdClass Object
	 *                                         #     (
	 *                                         #         [id] => 1
	 *                                         #         [username] => root
	 *                                         #         [hash] => e7ee2c83e86af973196fde64e1ab7178
	 *                                         #         [salt] => Jb9JbfrQoBRs7p0mNu3i7NSsC9AhuaCz
	 *                                         #         [role_id] => 1
	 *                                         #     )
	 *                                         #
	 *                                         #     [1] => stdClass Object
	 *                                         #     (
	 *                                         #         [id] => 2
	 *                                         #         [username] => manager
	 *                                         #         [hash] => 0991b08461e5ce0ee0a038c104b5a6d3
	 *                                         #         [salt] => nLpUwvzSpD5yoqXtqjvhtqwhJUsMFh8P
	 *                                         #         [role_id] => 2
	 *                                         #     )
	 *                                         # )
	 *
	 * @return mixed
	 */
	public function get_object_list()
	{
		$object_list = [];

		while ($object = $this->result->fetch_object())
		{
			$object_list[] = $object;
		}

		return $object_list;
	}

	/**
	 * @en Get element from query result as an object
	 * @ru Получить первую запись из ответа базы данных в виде объекта
	 *
	 * $database = new database(); # do not forget to declare defines before calling this class:
	 *                             # > RUDE_DATABASE_USER
	 *                             # > RUDE_DATABASE_PASS
	 *                             # > RUDE_DATABASE_HOST
	 *                             # > RUDE_DATABASE_NAME
	 *
	 * $database->query('SELECT * FROM users WHERE 1 = 1');
	 *
	 * $result = $database->get_object(); # stdClass Object
	 *                                    # (
	 *                                    #     [id] => 2
	 *                                    #     [username] => manager
	 *                                    #     [hash] => 0991b08461e5ce0ee0a038c104b5a6d3
	 *                                    #     [salt] => nLpUwvzSpD5yoqXtqjvhtqwhJUsMFh8P
	 *                                    #     [role_id] => 2
	 *                                    # )
	 *
	 * @return mixed
	 */
	public function get_object()
	{
		if ($object = $this->result->fetch_object())
		{
			return $object;
		}

		return null;
	}

	public function transaction($read_write = false, $savepoint_name = null)
	{
		if (!defined('MYSQLI_TRANS_START_READ_WRITE')) { define('MYSQLI_TRANS_START_READ_WRITE', 2); }
		if (!defined('MYSQLI_TRANS_START_READ_ONLY'))  { define('MYSQLI_TRANS_START_READ_ONLY',  4); }

		     if ($read_write) { $flags = MYSQLI_TRANS_START_READ_WRITE; }
		else                  { $flags = MYSQLI_TRANS_START_READ_ONLY;  }

		$this->mysqli->begin_transaction($flags, $savepoint_name);
	}

	public function commit()
	{
		$this->mysqli->commit();
	}
}
