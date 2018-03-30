

namespace %PHP_NAMESPACE%\tables;

use %PHP_NAMESPACE%;

if (!defined('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%'))             { define('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%',             '%MYSQL_TABLE_NAME%');%ALIGN% }
if (!defined('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY')) { define('RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY', '%MYSQL_PRIMARY_KEY%');%ALIGN% }

class %PHP_CLASS_NAME%
{
	/**
	 * @param int|null $%PHP_PRIMARY_KEY%
	 * @param int|null $limit
	 * @param int|null $offset
	 *
	 * @return %PHP_CLASS_NAME_SKELETON%|%PHP_CLASS_NAME_SKELETON%[]
	 */
	public static function get($%PHP_PRIMARY_KEY% = null, $limit = null, $offset = null)
	{
		$q = new %PHP_NAMESPACE%\query_select(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->limit($limit);
		$q->offset($offset);

		if ($%PHP_PRIMARY_KEY% !== null)
		{
			$q->where(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY, $%PHP_PRIMARY_KEY%);
			$q->query();

			return $q->get_object();
		}

		$q->query();

		return $q->get_object_list();
	}

	/**
	 * @param int $n
	 *
	 * @return %PHP_CLASS_NAME_SKELETON%|%PHP_CLASS_NAME_SKELETON%[]
	 */
	public static function get_last($n = 1)
	{
		$q = new %PHP_NAMESPACE%\query_select(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->order_desc(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY);
		$q->limit($n);
		$q->query();

		if ((int) $n === 1)
		{
			return $q->get_object();
		}

		return $q->get_object_list();
	}

	/**
	 * @param int $n
	 *
	 * @return %PHP_CLASS_NAME_SKELETON%|%PHP_CLASS_NAME_SKELETON%[]
	 */
	public static function get_first($n = 1)
	{
		$q = new %PHP_NAMESPACE%\query_select(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->order_asc(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY);
		$q->limit($n);
		$q->query();

		if ((int) $n === 1)
		{
			return $q->get_object();
		}

		return $q->get_object_list();
	}

	public static function add(%PHP_FIELDS_EXCEPT_PRIMARY%)
	{
		$q = new %PHP_NAMESPACE%\query_insert(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);

		%FOR_EACH_FIELD_EXCEPT_PRIMARY_AUTO_INCREMENT%
		$q->add('%MYSQL_FIELD%', %ALIGN%$%PHP_FIELD_LOWERCASE%);
		%FOR_EACH_FIELD_EXCEPT_PRIMARY_AUTO_INCREMENT%

		$q->query();

		return $q->get_id();
	}

	public static function update($%PHP_PRIMARY_KEY%, %PHP_FIELDS_EXCEPT_PRIMARY%, $limit = null, $offset = null)
	{
		$q = new %PHP_NAMESPACE%\query_update(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);

		%FOR_EACH_FIELD_EXCEPT_PRIMARY_AUTO_INCREMENT%
		$q->update('%MYSQL_FIELD%', %ALIGN%$%PHP_FIELD_LOWERCASE%);
		%FOR_EACH_FIELD_EXCEPT_PRIMARY_AUTO_INCREMENT%

		$q->where(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY, $%PHP_PRIMARY_KEY%);
		$q->limit($limit);
		$q->offset($offset);
		$q->query();

		return $q->affected();
	}

	public static function is_exist($%PHP_PRIMARY_KEY%)
	{
		return static::get($%PHP_PRIMARY_KEY%) == true;
	}

	public static function remove($%PHP_PRIMARY_KEY%, $limit = null, $offset = null)
	{
		$q = new %PHP_NAMESPACE%\query_delete(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->where(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%_PRIMARY_KEY, $%PHP_PRIMARY_KEY%);
		$q->limit($limit);
		$q->offset($offset);
		$q->query();

		return $q->affected();
	}

	public static function count()
	{
		$database = database();
		$database->query('SELECT COUNT(*) AS count FROM ' . RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);

		return $database->get_object()->count;
	}

	%FOR_EACH_FIELD%
	/**
	 * @param      $%PHP_FIELD_LOWERCASE%
	 * @param bool $only_first
	 *
	 * @return %PHP_CLASS_NAME_SKELETON%|%PHP_CLASS_NAME_SKELETON%[]
	 */
	public static function get_by_%PHP_FIELD_LOWERCASE%($%PHP_FIELD_LOWERCASE%, $only_first = false)
	{
		$q = new %PHP_NAMESPACE%\query_select(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->where('%MYSQL_FIELD%', $%PHP_FIELD_LOWERCASE%);
		
		if ($only_first !== false)
		{
			$q->limit(1);
			$q->query();
	
			return $q->get_object();
		}
	
		$q->query();

		return $q->get_object_list();
	}

	%FOR_EACH_FIELD%

	%FOR_EACH_FIELD%
	public static function remove_by_%PHP_FIELD_LOWERCASE%($%PHP_FIELD_LOWERCASE%)
	{
		$q = new %PHP_NAMESPACE%\query_delete(RUDE_DATABASE_TABLE_%PHP_TABLE_NAME_UPPERCASE%);
		$q->where('%MYSQL_FIELD%', $%PHP_FIELD_LOWERCASE%);
		$q->query();

		return $q->affected();
	}

	%FOR_EACH_FIELD%

	%FOR_EACH_FIELD%
	public static function is_exist_%PHP_FIELD_LOWERCASE%%ALIGN%($%PHP_FIELD_LOWERCASE%) %ALIGN%{ return static::get_by_%PHP_FIELD_LOWERCASE%%ALIGN%($%PHP_FIELD_LOWERCASE%,%ALIGN% true) == true; }
	%FOR_EACH_FIELD%
}