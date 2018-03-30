

namespace %PHP_NAMESPACE%\tables;

use %PHP_NAMESPACE%;

if (!defined('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%'))             { define('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%',             '%MYSQL_TABLE_NAME%');%ALIGN% }
if (!defined('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY')) { define('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY', '%MYSQL_PRIMARY_KEY%');%ALIGN% }

class %PHP_CLASS_NAME%
{
	%FOR_EACH_FIELD%
	public $%PHP_FIELD_LOWERCASE%%ALIGN% = null;
	%FOR_EACH_FIELD%

	# PHP fields
	private $__php =
	[
		%FOR_EACH_FIELD%
		'%PHP_FIELD_LOWERCASE%',
		%FOR_EACH_FIELD%
	];

	# MySQL fields
	private $__mysql =
	[
		%FOR_EACH_FIELD%
		'%MYSQL_FIELD%',
		%FOR_EACH_FIELD%
	];

	/** @var null|%PHP_CLASS_NAME_SKELETON% */
	private $__item = null;

	public function __construct($%PHP_PRIMARY_KEY% = null)
	{
		if ($%PHP_PRIMARY_KEY% !== null)
		{
			$this->%PHP_PRIMARY_KEY% = $%PHP_PRIMARY_KEY%;

			static::load($this->%PHP_PRIMARY_KEY%);
		}
	}

	public function load($%PHP_PRIMARY_KEY% = null)
	{
		if ($%PHP_PRIMARY_KEY% === null)
		{
			$%PHP_PRIMARY_KEY% = $this->%PHP_PRIMARY_KEY%;

			if ($%PHP_PRIMARY_KEY% === null)
			{
				return false;
			}
		}

		$q = new %PHP_NAMESPACE%\query_select(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->where(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY, $%PHP_PRIMARY_KEY%);
		$q->limit(1);
		$q->query();

		$item = $q->get_object();

		if ($item)
		{
			static::synchronize($item);
		}

		return $item == true;
	}

	public function save()
	{
		$q = new %PHP_NAMESPACE%\query_insert(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);

		$properties = %PHP_NAMESPACE%\items::remove_last(get_object_vars($this), 3); # ignore __php, __mysql and __item variables

		foreach ($properties as $field_php => $value)
		{
			$field_mysql = static::to_mysql($field_php);

			$q->add($field_mysql, $this->{$field_php});
		}

		$q->query();

		$item_id = $q->get_id();

		$this->%PHP_PRIMARY_KEY% = $item_id;


		$this->__item = static::skeleton();


		return $item_id;
	}

	public function update()
	{
		if (!static::is_changed())
		{
			return true; # nothing to update
		}

		$q = new %PHP_NAMESPACE%\query_update(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);

		foreach (get_object_vars($this->__item) as $field_php => $value)
		{
			if ($this->{$field_php} !== $value)
			{
				$field_mysql = static::to_mysql($field_php);

				$q->update($field_mysql, $this->{$field_php});
			}
		}

		$q->where(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY, $this->%PHP_PRIMARY_KEY%);
		$q->limit(1);
		$q->query();

		return $q->affected();
	}

	public function bind($items)
	{
		foreach (get_object_vars($this->__item) as $field_php => $value)
		{
			if (isset($items[$field_php]))
			{
				$this->$field_php = $items[$field_php];
			}
		}
	}

	public function delete()
	{
		$q = new %PHP_NAMESPACE%\query_delete(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->where(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY, $this->%PHP_PRIMARY_KEY%);
		$q->limit(1);
		$q->query();

		return $q->affected();
	}

	private function synchronize($item)
	{
		$this->__item = $item;

		if ($item === null)
		{
			return;
		}

		foreach (get_object_vars($item) as $field_mysql => $value)
		{
			$field_php = static::to_php($field_mysql);

			if ($field_php !== null)
			{
				$this->{$field_php} = $value;
			}
		}
	}

	private function is_changed()
	{
		if ($this->__item === null)
		{
			return true;
		}

		foreach (get_object_vars($this->__item) as $field_php => $value)
		{
			if ($this->{$field_php} !== $value)
			{
				return true;
			}
		}

		return false;
	}

	private function to_php($field_mysql)
	{
		$key = %PHP_NAMESPACE%\items::key($this->__mysql, $field_mysql);

		return get($key, $this->__mysql);
	}

	private function to_mysql($field_php)
	{
		$key = %PHP_NAMESPACE%\items::key($this->__php, $field_php);

		return get($key, $this->__mysql);
	}

	private function skeleton()
	{
		$skeleton = new %PHP_CLASS_NAME_SKELETON%();

		foreach (static::properties() as $field_php => $value)
		{
			$skeleton->{$field_php} = $this->{$field_php};
		}

		return $skeleton;
	}

	private function properties()
	{
		$properties = get_object_vars($this);

		foreach ($properties as $field => $value)
		{
			if (%PHP_NAMESPACE%\strings::starts_with($field, '__'))
			{
				unset($properties[$field]);
			}
		}

		return $properties;
	}
}