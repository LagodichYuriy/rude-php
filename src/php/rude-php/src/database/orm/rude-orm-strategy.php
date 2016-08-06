<?

namespace rude;

class orm_strategy
{
	public $name         = null;
	public $name_escaped = null;
	public $singularize  = false;
	public $pluralize    = false;
	public $prefix       = null;
	public $postfix      = null;
	public $template     = null;

	public function __construct($name)
	{
		$this->name         = $name;
		$this->name_escaped = static::escape($name);

		static::load();
	}
	
	private function load()
	{
		$this->template = '<?' . filesystem::read(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'template-' . $this->name_escaped . '.php');
	}
	
	private function escape($string, $search = [' ', '_'], $replace = '-')
	{
		return strings::replace($string, $search, $replace);
	}

	public function class_name($table_name)
	{
		$class_name = $table_name;

		     if ($this->singularize) { $class_name = english::singularize($class_name); }
		else if ($this->pluralize)   { $class_name = english::pluralize  ($class_name); }

		if ($this->prefix  !== null) { $class_name = $this->prefix . $class_name;  }
		if ($this->postfix !== null) { $class_name = $class_name . $this->postfix; }

		return $class_name;
	}
}