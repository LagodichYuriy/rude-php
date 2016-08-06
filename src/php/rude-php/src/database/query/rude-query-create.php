<?

namespace rude;

if (!defined('RUDE_TABLE_DEFAULT_CHARACTER_SET')) { define('RUDE_TABLE_DEFAULT_CHARACTER_SET', 'utf8');            }
if (!defined('RUDE_TABLE_DEFAULT_COLLATION'))     { define('RUDE_TABLE_DEFAULT_COLLATION',     'utf8_general_ci'); }
if (!defined('RUDE_TABLE_DEFAULT_ENGINE'))        { define('RUDE_TABLE_DEFAULT_ENGINE',        'InnoDB');          }
if (!defined('RUDE_TABLE_DEFAULT_CHARSET'))       { define('RUDE_TABLE_DEFAULT_CHARSET',       'utf8');            }

class query_create extends query
{
	public function __construct($table)
	{
		$this->database = database();

		static::add_table($table);

		static::engine(RUDE_TABLE_DEFAULT_ENGINE);

		static::default_charset(RUDE_TABLE_DEFAULT_CHARSET);
	}

	public function database($name)
	{
		static::add_option('CREATE DATABASE', $name);
	}

	public function index($field)
	{
		if (is_array($field))
		{
			foreach ($field as $item)
			{
				static::add_index($item, 'INDEX');
			}
		}
		else
		{
			static::add_index($field, 'INDEX');
		}
	}

	public function primary($field)
	{
		static::add_option('PRIMARY KEY', $field);
	}

	public function engine($name)
	{
		static::add_option('ENGINE', $name);
	}

	public function auto_increment($val)
	{
		static::add_option('AUTO_INCREMENT', $val);
	}

	public function comment($string)
	{
		static::add_option('COMMENT', $string);
	}

	public function default_charset($name)
	{
		static::add_option('DEFAULT CHARSET', $name);
	}

	public function int_tiny   ($field, $length = null, $null = false, $default = null, $auto_increment = false) { static::add_create($field, 'TINYINT',   $null, $default, $auto_increment, $length); }
	public function int_small  ($field, $length = null, $null = false, $default = null, $auto_increment = false) { static::add_create($field, 'SMALLINT',  $null, $default, $auto_increment, $length); }
	public function int_medium ($field, $length = null, $null = false, $default = null, $auto_increment = false) { static::add_create($field, 'MEDIUMINT', $null, $default, $auto_increment, $length); }
	public function int        ($field, $length = null, $null = false, $default = null, $auto_increment = false) { static::add_create($field, 'INT',       $null, $default, $auto_increment, $length); }
	public function integer    ($field, $length = null, $null = false, $default = null, $auto_increment = false) { static::add_create($field, 'INTEGER',   $null, $default, $auto_increment, $length); }
	public function int_big    ($field, $length = null, $null = false, $default = null, $auto_increment = false) { static::add_create($field, 'BIGINT',    $null, $default, $auto_increment, $length); }

	public function bit        ($field, $length = null, $null = false, $default = null)                   { static::add_create($field, 'BIT',       $null, $default, null, $length); }
	public function real       ($field, $length = null, $null = false, $default = null, $decimals = null) { static::add_create($field, 'REAL',      $null, $default, null, $length, $decimals); }
	public function double     ($field, $length = null, $null = false, $default = null, $decimals = null) { static::add_create($field, 'DOUBLE',    $null, $default, null, $length, $decimals); }
	public function float      ($field, $length = null, $null = false, $default = null, $decimals = null) { static::add_create($field, 'FLOAT',     $null, $default, null, $length, $decimals); }
	public function decimal    ($field, $length = null, $null = false, $default = null, $decimals = null) { static::add_create($field, 'DECIMAL',   $null, $default, null, $length, $decimals); }
	public function numeric    ($field, $length = null, $null = false, $default = null, $decimals = null) { static::add_create($field, 'NUMERIC',   $null, $default, null, $length, $decimals); }
	public function binary     ($field, $length = null, $null = false, $default = null)                   { static::add_create($field, 'BINARY',    $null, $default, null, $length); }
	public function varbinary  ($field, $length = null, $null = false, $default = null)                   { static::add_create($field, 'VARBINARY', $null, $default, null, $length); }

	public function date       ($field, $null = false, $default = null) { static::add_create($field, 'DATE',       $null, $default); }
	public function time       ($field, $null = false, $default = null) { static::add_create($field, 'TIME',       $null, $default); }
	public function timestamp  ($field, $null = false, $default = null) { static::add_create($field, 'TIMESTAMP',  $null, $default); }
	public function datetime   ($field, $null = false, $default = null) { static::add_create($field, 'DATETIME',   $null, $default); }
	public function year       ($field, $null = false, $default = null) { static::add_create($field, 'YEAR',       $null, $default); }
	public function blob_tiny  ($field, $null = false, $default = null) { static::add_create($field, 'TINYBLOB',   $null, $default); }
	public function blob       ($field, $null = false, $default = null) { static::add_create($field, 'BLOB',       $null, $default); }
	public function blob_medium($field, $null = false, $default = null) { static::add_create($field, 'MEDIUMBLOB', $null, $default); }
	public function blob_long  ($field, $null = false, $default = null) { static::add_create($field, 'LONGBLOB',   $null, $default); }
	public function json       ($field, $null = false, $default = null) { static::add_create($field, 'JSON',       $null, $default); }

	public function char       ($field, $length = null, $null = false, $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'CHAR',       $null, $default, null, $length, $charset_name, $collation_name, null); }
	public function varchar    ($field, $length = null, $null = false, $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'VARCHAR',    $null, $default, null, $length, $charset_name, $collation_name, null); }

	public function text_tiny  ($field, $null = false,                 $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'TINYTEXT',   $null, $default, null,    null, $charset_name, $collation_name, null); }
	public function text       ($field, $null = false,                 $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'TEXT',       $null, $default, null,    null, $charset_name, $collation_name, null); }
	public function text_medium($field, $null = false,                 $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'MEDIUMTEXT', $null, $default, null,    null, $charset_name, $collation_name, null); }
	public function text_long  ($field, $null = false,                 $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'LONGTEXT',   $null, $default, null,    null, $charset_name, $collation_name, null); }

	public function enum       ($field, $values, $null = false,        $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'ENUM',       $null, $default, null, null, $charset_name, $collation_name, $values); }
	public function set        ($field, $values, $null = false,        $default = null, $charset_name = RUDE_TABLE_DEFAULT_CHARACTER_SET, $collation_name = RUDE_TABLE_DEFAULT_COLLATION) { static::add_create($field, 'SET',        $null, $default, null, null, $charset_name, $collation_name, $values); }

	public function sql()
	{
		$sql  = static::sql_create_database();
		$sql .= static::sql_create_table();
		$sql .= static::sql_indexes();

		return $sql;
	}

	public function query() { return $this->database->query($this->sql()); }
}