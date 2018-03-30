<?

namespace rude;

class orm
{
	/** @var database */
	private $database = null;

	/** @var orm_strategy[] */
	protected $strategies = [];

	/**
	 * database_classes constructor.
	 *
	 * @param database            $database
	 * @param orm_strategy[]|null $strategies
	 */
	public function __construct($database = null, $strategies = null)
	{
		     if ($database !== null) { $this->database = $database;  }
		else                         { $this->database = database(); }

		     if ($strategies === null) { static::load_strategies();    }
		else                           { $this->strategies = $strategies; }
	}

	private function load_strategies()
	{
		# active record

		$strategy = new orm_strategy('active record');
		$strategy->singularize = true;

		$this->strategies[] = $strategy;


		# query builder

		$strategy = new orm_strategy('query builder');
		$strategy->pluralize = true;

		$this->strategies[] = $strategy;


		# skeleton

		$strategy = new orm_strategy('skeleton');
		$strategy->singularize = true;
		$strategy->postfix = '-skeleton';

		$this->strategies[] = $strategy;
	}

	private function tables()        { return $this->database->tables();        }
	private function fields($table)  { return $this->database->fields($table);  }
	private function columns($table) { return $this->database->columns($table); }

	public function create_all()
	{
		foreach (static::tables() as $table)
		{
			static::create($table);
		}
	}

	public function create($table)
	{
		foreach ($this->strategies as $index => $strategy)
		{
			# 0. prepare class name

			$class_name = $strategy->class_name($table);


			# 1. add table name and class name to the dictionary

			$dictionary = new orm_dictionary();
			$dictionary->add_namespace(__NAMESPACE__);
			$dictionary->add_table_name($table);
			$dictionary->add_class_name($class_name);

			foreach ($this->strategies as $index_alias => $strategy_alias)
			{
				if ($index == $index_alias)
				{
					continue;
				}

				$dictionary->add_class_name_alias($strategy_alias->class_name($table), $strategy_alias->name);
			}


			# 2. find all columns in the table

			$columns = static::columns($table);

			if (!$columns)
			{
				continue;
			}

			$dictionary->set_columns($columns);


			# 3. select names of the columns

			$fields = items::select($columns, 'field');


			# 4. find primary columns

			$column_primary = static::primary($table);

			$dictionary->add_primary($column_primary->field);


			# 5. filter primary column with auto increment ability from other columns

			$columns_except_primary_auto_increment = [];

			foreach ($columns as $column)
			{
				if ($column->field == $column_primary->field and $column->extra == 'auto_increment')
				{
					continue;
				}

				$columns_except_primary_auto_increment[] = $column;
			}

			$fields_except_primary = items::select($columns_except_primary_auto_increment, 'field');

			$dictionary->set_columns_except_primary_auto_increment($columns_except_primary_auto_increment);


			# 6. prepare fields

			$dictionary->add_fields($fields);


			# 7. prepare fields except primary

			$dictionary->add_fields_except_primary($fields_except_primary);


			# 8. replace template variables

			$template = $dictionary->replace($strategy->template);


			# 9. save it

			static::save(static::escape($class_name), $table, $template);
		}
	}

	public function create_starts_with($string, $case_sensitive = true)
	{
		foreach ($this->database->tables() as $table)
		{
			if (strings::starts_with($table, $string, $case_sensitive))
			{
				static::create($table);
			}
		}
	}

	public function create_ends_with($string, $case_sensitive = true)
	{
		foreach ($this->database->tables() as $table)
		{
			if (strings::ends_with($table, $string, $case_sensitive))
			{
				static::create($table);
			}
		}
	}

	public function create_contains($string, $case_sensitive = true, $offset = null)
	{
		foreach ($this->database->tables() as $table)
		{
			if (strings::contains($table, $string, $case_sensitive, $offset))
			{
				static::create($table);
			}
		}
	}

	private static function save($file_name, $subdirectory, $template)
	{
		$directory = RUDE_DIR_WORKSPACE_DATABASE . DIRECTORY_SEPARATOR . $subdirectory;

		if (!filesystem::is_exist($directory))
		{
			filesystem::create_directory($directory);
		}

		$file_path = filesystem::combine($directory, "rude-tables-$file_name.php");

		return filesystem::rewrite($file_path, $template);
	}

	private function escape($string, $search = [' ', '_'], $replace = '-')
	{
		return strings::replace($string, $search, $replace);
	}

	private function primary($table)
	{
		$columns = static::columns($table);

		foreach ($columns as $column)
		{
			if ($column->key == 'PRI')
			{
				return $column;
			}
		}

		return items::first($columns);
	}
}