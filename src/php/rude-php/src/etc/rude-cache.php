<?

namespace rude;

class cache
{
	private $cache = [];

	public function get($table, $column)
	{
		return $this->cache[$table][$column];
	}

	public function add($table, $column, $value, $force = false)
	{
		if ($force === true or !static::is_cached($table, $column))
		{
			$this->cache[$table][$column] = $value;
		}

		return $value;
	}

	public function is_cached($table, $column)
	{
		return isset($this->cache[$table][$column]);
	}

	public function reset()
	{
		$this->cache = [];
	}
}