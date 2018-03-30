<?

namespace rude;

/**
 * @category database
 */
class database
{
	/** @var \mysqli  */
	private $mysqli;

	private $host;
	private $user;
	private $pass;
	private $name;
	private $port;

	private $charset;

	private $die_on_error;

	/** @var \mysqli_result  */
	private $result;

	private $tables;  # tables cache
	private $columns; # columns cache

	public $transaction;

	public function __construct($host, $user, $pass, $name = null, $port = 3306, $charset = 'utf8', $die_on_error = true)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->name = $name;
		$this->port = $port;

		$this->charset = $charset;

		$this->die_on_error = $die_on_error;


		static::connect($this->host, $this->user, $this->pass, $this->name, $this->port, $this->charset, $this->die_on_error);
	}

	public function connect($host, $user, $pass, $name = null, $port = 3306, $charset = 'utf8', $die_on_error = true)
	{
		     if ($die_on_error) { $this->mysqli =  new \mysqli($host, $user, $pass, $name, (int) $port); }
		else                    { $this->mysqli = @new \mysqli($host, $user, $pass, $name, (int) $port); }

		if ($this->mysqli->connect_error and $die_on_error)
		{
			exception::error('Connect Error (' . static::error_code() . '). ' . strings::to_capitalcase(static::error()));
		}
		else if (!$this->mysqli->set_charset($charset) and $die_on_error)
		{
			exception::error("Error loading character set ($charset): {$this->mysqli->error}");
		}
	}

	public function reconnect()
	{
		static::connect($this->host, $this->user, $this->pass, $this->name, $this->port, $this->charset, $this->die_on_error);
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

	public function transaction($write = false, $name = null)
	{
		     if ($write) { $flags = MYSQLI_TRANS_START_READ_WRITE; }
		else             { $flags = MYSQLI_TRANS_START_READ_ONLY;  }

		$this->mysqli->begin_transaction($flags, $name);
	}

	public function autocommit($bool)         { return $this->mysqli->autocommit($bool);         }
	public function commit()                  { return $this->mysqli->commit();                  }
	public function rollback()                { return $this->mysqli->rollback();                }
	public function savepoint($name)          { return $this->mysqli->savepoint($name);          }
	public function savepoint_release($name)  { return $this->mysqli->release_savepoint($name);  }

	/**
	 * @en Execute SQL query. WARNING: do not forget to escape SQL queries via escape() method if you don't use query() classes
	 * @ru Выполнение построенного SQL запроса. ВАЖНО: не забывайте экранировать SQL запросы с помощью метода escape() если вы генерируете SQL запрос без помощи семейства классов query()
	 *
	 * $database = new database($host, $user, $pass, $name);
	 * $database->query('SELECT * FROM users WHERE 1 = 1');
	 *
	 * @param $string
	 *
	 * @return \mysqli_result
	 */
	public function query($string)
	{
		if (!static::ping())
		{
			exception::notice('connection lost, trying to reconnect');

			static::connect($this->host, $this->user, $this->pass, $this->name, $this->port, $this->charset, $this->die_on_error);
		}

		$this->result = $this->mysqli->query($string);

		if ($this->result === false)
		{
			exception::error(strings::replace($this->mysqli->error, PHP_EOL, ' ') . ':' . PHP_EOL . PHP_EOL . $string);
		}

		return $this->result;
	}


	/** @return \mysqli_result */
	public function result()
	{
		return $this->result;
	}

	public function ping()
	{
		return @$this->mysqli->ping();
	}

	public function escape($var)
	{
		if (is_array($var))
		{
			$result = [];

			foreach ($var as $index => $item)
			{
				$result[$index] = static::escape($item);
			}

			return $result;
		}

		return $this->mysqli->real_escape_string($var);
	}

	public function table_rows($table)
	{
		$this->query("SELECT COUNT(*) AS `count` FROM `$table`");

		return $this->get_object()->count;
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
		if ($this->tables !== null)
		{
			return $this->tables;
		}


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

		$this->tables = $result;

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

			$this->columns[$this->name][$table][] = (object) $column;
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

		return items::select_unique($columns, 'field');
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
	 * @param null|string $field_index
	 * @param null|string $field_value
	 *
	 * @return mixed
	 */
	public function get_object_list($field_index = null, $field_value = null)
	{
		$object_list = [];

		while ($object = $this->result->fetch_object())
		{
			     if ($field_value === null) { $value = $object;               }
			else                            { $value = $object->$field_value; }


			     if ($field_index === null) { $object_list[]                      = $value; }
			else                            { $object_list[$object->$field_index] = $value; }
		}

		return $object_list;
	}

	public function get_array_list_fixed($mode = MYSQLI_ASSOC)
	{
		$count = $this->result->num_rows;

		$array_list = new \SplFixedArray($count);

		for ($i = 0; $i < $count; $i++)
		{
			$array_list[$i] = $this->result->fetch_array($mode);
		}

		return $array_list;
	}

	public function get_array_list($field_index = null, $field_value = null, $mode = MYSQLI_ASSOC)
	{
		$array_list = [];

		while ($array = $this->result->fetch_array($mode))
		{
			     if ($field_value === null) { $value = $array;               }
			else                            { $value = $array[$field_value]; }

			     if ($field_index === null) { $array_list[]                     = $value; }
			else                            { $array_list[$array[$field_index]] = $value; }
		}

		return $array_list;
	}

	public function get_array_list_assoc  ($field_index = null, $field_value = null) { return static::get_array_list($field_index, $field_value, MYSQLI_ASSOC); }
	public function get_array_list_numeric($field_index = null, $field_value = null) { return static::get_array_list($field_index, $field_value, MYSQLI_NUM);   }


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

	public function get_array($mode = MYSQLI_ASSOC)
	{
		if ($array = $this->result->fetch_array($mode))
		{
			return $array;
		}

		return [];
	}

	public function get_array_assoc  () { return static::get_array(MYSQLI_ASSOC); }
	public function get_array_numeric() { return static::get_array(MYSQLI_NUM);   }

	/**
	 * @param string|null $property
	 *
	 * @return string|int|float|bool|null
	 */
	public function get_value($property = null)
	{
		if ($array = $this->result->fetch_array(MYSQLI_ASSOC))
		{
			return get($property, $array);
		}

		return null;
	}

	public function truncate($table)
	{
		if (static::has_table($table))
		{
			return $this->query("TRUNCATE $table");
		}

		exception::warning("table `$table` hasn't been found");

		return null;
	}

	public function time()
	{
		$this->query('SELECT NOW() AS time');

		return $this->get_object()->time;
	}

	public function timestamp()
	{
		$this->query('SELECT UNIX_TIMESTAMP() AS timestamp');

		return $this->get_object()->timestamp;
	}

	private function has_table($table)
	{
		return items::contains(static::tables(), $table);
	}

	public function lock($table, $read = true, $write = true, $alias_read = null, $alias_write = null)
	{
		if (static::has_table($table))
		{
			$locks = [];

			if ($read and $write and $alias_read === null and $alias_write === null)
			{
				$alias_read = $table . '_read';
			}

			if ($read)
			{
				     if ($alias_read !== null) { $locks[] = "`$table` AS `$alias_read` READ"; }
				else                           { $locks[] = "`$table` READ"; }
			}

			if ($write)
			{
				     if ($alias_write !== null) { $locks[] = "`$table` AS `$alias_write` WRITE"; }
				else                            { $locks[] = "`$table` WRITE"; }
			}

			$q = 'LOCK TABLES ' . implode(', ', $locks);

			return $this->query($q);
		}

		exception::warning("table `$table` hasn't been found");

		return null;
	}

	public function unlock()
	{
		return $this->query('UNLOCK TABLES');
	}
}
