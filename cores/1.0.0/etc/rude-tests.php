<?

namespace rude;

class tests
{
	private $namespace = null;
	private $class     = null;
	private $method    = null;
	private $arguments = null;

	private $log = [];

	public function set_namespace($namespace) { $this->namespace = $namespace; }
	public function set_class($class)         { $this->class     = $class;     }
	public function set_method($method)       { $this->method    = $method;    }
	public function set_arguments($arguments) { $this->arguments = $arguments; }
	
	public function get_namespace() { return $this->namespace;  }
	public function get_class()     { return $this->class;      }
	public function get_method()    { return $this->method;     }
	public function get_arguments() { return $this->arguments;  }

	public function get_log()       { return $this->log;        }

	public function get_classes_verified()
	{
		$result = [];

		foreach ($this->log as $class_name => $val)
		{
			$result[] = $class_name;
		}

		return $result;
	}

	public function get_methods_verified($class = null)
	{
		$result = [];

		foreach (static::get_log() as $class_name => $methods)
		{
			if ($class and $class != $class_name)
			{
				continue;
			}

			foreach ($methods as $method_name => $values)
			{
				$result[] = $method_name;
			}
		}

		return $result;
	}

	private function get_methods($class)
	{
		$class_name = static::get_namespace() . '-' . strings::replace($class, '_', '-') . '.php';


		$sources = sources();

		foreach ($sources as $source)
		{
			$source_name = filesystem::file_basename($source);

			if ($source_name != $class_name)
			{
				continue;
			}

			$tokenizer = new tokenizer();
			$tokenizer->set_file($source);
			$tokenizer->analyze();

			return $tokenizer->methods(true);
		}

		return [];
	}

	private function get_methods_unverified($class)
	{
		$methods = static::get_methods($class);

		$methods_verified = static::get_methods_verified($class);

		return items::diff($methods, $methods_verified);
	}

	public function __construct($namespace = 'rude')
	{
		static::set_namespace($namespace);
	}
	
	public function init()
	{
		static::strings();
		static::char();
		static::arrays();
	}

	public function info()
	{
		console::lines();
		console::write();
		console::write('Test system initialized', true);
		console::write();
		console::write('PHP: ' . php::version() . ' / ' . system::uname());
		console::write();


		foreach (static::get_classes_verified() as $class)
		{
			$methods_verified = static::get_methods_verified($class);

			if ($methods_verified)
			{
				console::lines();
				console::write('[' . $class . '] / verified (' . count($methods_verified) . '):', true);
				console::write();


				foreach ($methods_verified as $index => $method)
				{
					$entries = $this->log[$class][$method];

					foreach ($entries as $entry)
					{
						$arguments = items::first ($entry);
						$result    = items::second($entry);
						$expected  = items::third ($entry);

						$message = ($index + 1) . '. ';

						if ($result === $expected)
						{
							$message .= console_colors::format_green('[OK]', null, true);
						}
						else
						{
							$message .= console_colors::fg_red('[FAILED]', null, true);
						}

						$message .= ' ' . $this->namespace . '\\' . $class . '::' . console_colors::bold($method) . '(';

						foreach ($arguments as $argument)
						{
							$message .= static::to_string($argument) . ', ';
						}

						$message = char::remove_last($message, 2);

						$message .= ') ';

						if ($result === $expected)
						{
							$message .= console_colors::format_green(static::to_string($result) . ' === ' . static::to_string($expected));
						}
						else
						{
							$message .= console_colors::fg_red(static::to_string($result) . ' !== ' . static::to_string($expected));
						}

						console::write($message);
					}

					console::write();
				}
			}


			$methods_unverified = static::get_methods_unverified($class);

			if ($methods_unverified)
			{
				console::lines();
				console::write('[' . $class . '] / unverified (' . count($methods_unverified) . '):', true);
				console::write();


				foreach ($methods_unverified as $index => $method)
				{
					console::write(($index + 1) . '. ' . $method . '()');
				}
			}

			console::lines();
		}
	}

	public function arrays()
	{
		static::set_class(__FUNCTION__);


		static::set_method('get');

		static::verify([['a', 'b', 'c'], 1], 'a');
		static::verify([['a', 'b', 'c'], 2], 'b');
		static::verify([['a', 'b', 'c'], 3], 'c');
		static::verify([['a', 'b', 'c'], 4], null);
		static::verify([[],              4], null);
		static::verify([[0 => 'a', 2 => 'b', 4 => 'c'], 3], 'b');


		static::set_method('nth');

		static::verify([['a', 'b', 'c'], 1], 'a');
		static::verify([['a', 'b', 'c'], 2], 'b');
		static::verify([['a', 'b', 'c'], 3], 'c');
		static::verify([['a', 'b', 'c'], 4], null);
		static::verify([[],              4], null);

		static::verify([[  0 => 'a',   2 => 'b',          4 => 'c'], 3], 'c');
		static::verify([['a' => 'a', 'b' => 'b',        'c' => 'c'], 3], 'c');
		static::verify([[  0 => 'a',   2 => ['b', 'c'],   4 => 'c'], 3], 'c');


		static::set_method('first');

		static::verify([['a', 'b', 'c']],              'a');
		static::verify([['b', 'c', 'd']],              'b');
		static::verify([[['a', 'b'], 'c', 'd']], ['a', 'b']);
		static::verify([[]], null);

		static::verify([['a', 'b', 'c'],        2], ['a', 'b']);
		static::verify([['b', 'c', 'd'],        2], ['b', 'c']);
		static::verify([[['a', 'b'], 'c', 'd'], 2], [['a', 'b'], 'c']);
		static::verify([[],                     2], null);


		static::set_method('second');

		static::verify([['a', 'b', 'c']],        'b');
		static::verify([['b', 'c', 'd']],        'c');
		static::verify([[['a', 'b'], 'c', 'd']], 'c');
		static::verify([[]], null);


		static::set_method('third');

		static::verify([['a', 'b', 'c']],        'c');
		static::verify([['b', 'c', 'd']],        'd');
		static::verify([[['a', 'b'], 'c', 'd']], 'd');
		static::verify([[]], null);


		static::set_method('last');

		static::verify([['a', 'b', 'c']],        'c');
		static::verify([['b', 'c', 'd']],        'd');
		static::verify([[['a', 'b'], 'c', 'd']], 'd');
		static::verify([[]], null);

		static::verify([['a', 'b', 'c'],        2], ['b', 'c']);
		static::verify([['b', 'c', 'd'],        2], ['c', 'd']);
		static::verify([[['a', 'b'], 'c', 'd'], 2], ['c', 'd']);
		static::verify([[],                     2], null);


//		static::set_method('shift');
//
//		static::verify([['a', 'b', 'c']],        ['b', 'c']);
//		static::verify([['b', 'c', 'd']],        ['c', 'd']);
//		static::verify([[['a', 'b'], 'c', 'd']], [['a', 'b'], 'c', 'd']);
//		static::verify([[]], null);
//
//		static::verify([['a', 'b', 'c'],        2], ['a', 'b']);
//		static::verify([['b', 'c', 'd'],        2], ['b', 'c']);
//		static::verify([[['a', 'b'], 'c', 'd'], 2], [['a', 'b'], 'c']);
//		static::verify([[],                     2], null);
	}

	public function strings()
	{
		static::set_class(__FUNCTION__);


		static::set_method('find');

		static::verify(['string πράδειγμα string πράδειγμα', 'string'],           0);
		static::verify(['string πράδειγμα string πράδειγμα', 'string', 0],        0);
		static::verify(['string πράδειγμα string πράδειγμα', 'string', 5],        17);
		static::verify(['string πράδειγμα STRING πράδειγμα', 'STRING', 0, false], 0);
		static::verify(['string πράδειγμα STRING πράδειγμα', 'STRING', 0,  true], 17);


		static::set_method('find_last');

		static::verify(['string πράδειγμα string πράδειγμα', 'string'],           17);
		static::verify(['string πράδειγμα string πράδειγμα', 'πράδειγμα'],        24);
		static::verify(['string πράδειγμα string πράδειγμα', 'ABCD'],             false);
		static::verify(['STRING πράδειγμα STRING πράδειγμα', 'string', 0,  true], false);
		static::verify(['STRING πράδειγμα STRING πράδειγμα', 'string', 0, false], 17);


		static::set_method('find_nth');

		static::verify(['string πράδειγμα string πράδειγμα', 'string',    1],          0);
		static::verify(['string πράδειγμα string πράδειγμα', 'string',    2],         17);
		static::verify(['string πράδειγμα string πράδειγμα', 'πράδειγμα', 1],          7);
		static::verify(['string πράδειγμα string πράδειγμα', 'πράδειγμα', 2],         24);
		static::verify(['STRING ΠΡΆΔΕΙΓΜΑ string πράδειγμα', 'string',    1, null,  true], 17);
		static::verify(['STRING ΠΡΆΔΕΙΓΜΑ string πράδειγμα', 'πράδειγμα', 1, null,  true], 24);
		static::verify(['STRING ΠΡΆΔΕΙΓΜΑ string πράδειγμα', 'string',    1, null, false],  0);
		static::verify(['STRING ΠΡΆΔΕΙΓΜΑ string πράδειγμα', 'πράδειγμα', 1, null, false],  7);


		static::set_method('find_first');

		static::verify(['string πράδειγμα string πράδειγμα', 'string'],        0);
		static::verify(['string πράδειγμα string πράδειγμα', 'πράδειγμα'],     7);
		static::verify(['string πράδειγμα string πράδειγμα', 'ABCD'],      false);


		static::set_method('find_second');

		static::verify(['string πράδειγμα string πράδειγμα', 'string'],       17);
		static::verify(['string πράδειγμα string πράδειγμα', 'πράδειγμα'],    24);
		static::verify(['string πράδειγμα string πράδειγμα', 'ABCD'],      false);
		static::verify(['string πράδειγμα',                  'string'],    false);
		static::verify(['string string string string',       'string'],        7);

		static::set_method('find_third');

		static::verify(['string πράδειγμα string πράδειγμα string πράδειγμα', 'string'],       34);
		static::verify(['string πράδειγμα string πράδειγμα string πράδειγμα', 'πράδειγμα'],    41);
		static::verify(['string πράδειγμα string πράδειγμα',                  'ABCD'],      false);
		static::verify(['string πράδειγμα',                                   'string'],    false);


		static::set_method('size');

		static::verify([''],          0);
		static::verify(['ABCDEFEGH'], 9);
		static::verify(['πράδειγμα'], 18);


		static::set_method('length');

		static::verify(['string'],    6);
		static::verify(['πράδειγμα'], 9);
		static::verify([''],          0);


		static::set_method('count');

		static::verify(['string',                     '1234'],      0);
		static::verify(['string',                     'str'],       1);
		static::verify(['string πράδειγμα πράδειγμα', 'πράδειγμα'], 2);


		static::set_method('count_lines');

		static::verify([''],                        1);
		static::verify(['one' . PHP_EOL . 'two'],   2);
		static::verify(['one' . PHP_EOL . PHP_EOL], 3);


		static::set_method('replace');

		static::verify(['string string πράδειγμα πράδειγμα',        '',  ''], 'string string πράδειγμα πράδειγμα');
		static::verify(['string string πράδειγμα πράδειγμα', 'string ',  ''], 'πράδειγμα πράδειγμα');
		static::verify(['string string πράδειγμα πράδειγμα',       'i', 'o'], 'strong strong πράδειγμα πράδειγμα');


		static::set_method('replace_first');

		static::verify(['string string πράδειγμα πράδειγμα',        '',  ''], 'string string πράδειγμα πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', 'string ',  ''], 'πράδειγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα',       'i', 'o'], 'strong πράδειγμα string πράδειγμα');


		static::set_method('replace_last');

		static::verify(['string πράδειγμα string πράδειγμα',        '',  ''], 'string πράδειγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', 'string ',  ''], 'string πράδειγμα πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα',       'i', 'o'], 'string πράδειγμα strong πράδειγμα');


		static::set_method('read');

		static::verify(['string πράδειγμα string πράδειγμα'],           'string πράδειγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', null, 0],  'string πράδειγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', null, 0],  'string πράδειγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', null, 0],  'string πράδειγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', null, 12], 'ιγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα',   12, 0],  'string πράδε');
		static::verify(['string πράδειγμα string πράδειγμα',   12, 2],  'ring πράδειγ');
		static::verify(['string πράδειγμα string πράδειγμα',   12, 99], '');
		static::verify(['string πράδειγμα string πράδειγμα',   0],      '');


		static::set_method('read_after');

		static::verify(['string πράδειγμα string πράδειγμα',                                  ''],                  '');
		static::verify(['string πράδειγμα string πράδειγμα',                  'string πράδειγμα'], ' string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', 'string πράδειγμα string πράδειγμα'],                  '');


		static::set_method('read_between');

		static::verify(['string πράδειγμα string πράδειγμα', 'string πράδειγμα', 'πράδειγμα'],        ' string ');
		static::verify(['string πράδειγμα string πράδειγμα', 'string πράδειγμα', 'πράδειγμα',  true], 'string πράδειγμα string πράδειγμα');
		static::verify(['string πράδειγμα string πράδειγμα', 'string',           'πράδειγμα', false], ' ');
		static::verify(['string πράδειγμα string πράδειγμα', 'πράδειγμα',        'πράδειγμα', false], ' string ');
		static::verify(['string πράδειγμα string πράδειγμα', 'str',                      ' ', false], 'ing');
		static::verify(['string πράδειγμα string πράδειγμα', 'str',                      ' ',  true], 'string ');
	}

	public function char()
	{
		static::set_class(__FUNCTION__);


		static::set_method('count_unique');

		static::verify([''],          0);
		static::verify(['AABCDEFFG'], 7);
		static::verify(['πράδειγμα'], 9);


		static::set_method('unique');

		static::verify([''],          []);
		static::verify(['AABCDEFFG'], ['A', 'B', 'C', 'D', 'E', 'F', 'G']);
		static::verify(['πράδειγμα'], ['π', 'ρ', 'ά', 'δ', 'ε', 'ι', 'γ', 'μ', 'α']);


		static::set_method('nth');

		static::verify(['string πράδειγμα',   0], null);
		static::verify(['string πράδειγμα',   1], 's');
		static::verify(['string πράδειγμα',   2], 't');
		static::verify(['string πράδειγμα',   8], 'π');
		static::verify(['string πράδειγμα', 999], null);


		static::set_method('count');

		static::verify(['strin'],     5);
		static::verify(['πράδειγμα'], 9);
		static::verify([''],          0);


		static::set_method('rand');

		static::verify(['ssssss', 1], 's');
		static::verify(['ssssss', 2], 'ss');
		static::verify(['ππππππ', 1], 'π');
		static::verify(['ππππππ', 2], 'ππ');


		static::set_method('first');

		static::verify(['string πράδειγμα'],    's');
		static::verify(['πράδειγμα string'],    'π');
		static::verify(['string πράδειγμα', 2], 'st');
		static::verify(['πράδειγμα string', 2], 'πρ');


		static::set_method('last');

		static::verify(['string πράδειγμα'],    'α');
		static::verify(['πράδειγμα string'],    'g');
		static::verify(['string πράδειγμα', 2], 'μα');
		static::verify(['πράδειγμα string', 2], 'ng');


		static::set_method('is_null');

		static::verify(['string πράδειγμα'], false);
		static::verify([''],                 false);
		static::verify(["\0"],               true);


		static::set_method('remove_first');

		static::verify([''],                      '');
		static::verify(['string πράδειγμα'],      'tring πράδειγμα');
		static::verify(['πράδειγμα string'],      'ράδειγμα string');
		static::verify(['string πράδειγμα',   2], 'ring πράδειγμα');
		static::verify(['πράδειγμα string',   2], 'άδειγμα string');
		static::verify(['πράδειγμα string', 999], '');


		static::set_method('remove_last');

		static::verify([''],                      '');
		static::verify(['string πράδειγμα'],      'string πράδειγμ');
		static::verify(['πράδειγμα string'],      'πράδειγμα strin');
		static::verify(['string πράδειγμα',   2], 'string πράδειγ');
		static::verify(['πράδειγμα string',   2], 'πράδειγμα stri');
		static::verify(['πράδειγμα string', 999], '');
	}

	private function verify($arguments, $expected)
	{
		$call = static::caller();

		if (is_callable($call))
		{
			$result = call_user_func_array($call, $arguments);
		}
		else
		{
			$result = null;
		}

		static::log($this->class, $this->method, $arguments, $result, $expected);
	}

	private function log($class, $function, $arguments, $result = null, $expected = null)
	{
		if (!isset($this->log[$class]))
		{
			$this->log[$class] = [];
		}

		if (!isset($this->log[$class][$function]))
		{
			$this->log[$class][$function] = [];
		}

		$this->log[$class][$function][] = [$arguments, $result, $expected];
	}

	private function caller($arguments = null)
	{
		$call = static::get_namespace() . '\\' . static::get_class() . '::' . static::get_method();

		if (is_array($arguments))
		{
			foreach ($arguments as $key => $argument)
			{
				$arguments[$key] = static::to_string($argument);
			}

			$call .= '(' . implode(', ', $arguments) . ')';
		}

		return $call;
	}

	public static function to_string($var)
	{
		if (is_string($var))
		{
			$var = str_replace(PHP_EOL, '\r', $var);

			return "'" . $var . "'";
		}
		else if (is_bool($var))
		{
			if ($var) { return 'TRUE';  }
			else           { return 'FALSE'; }
		}
		else if (is_null($var))
		{
			return 'NULL';
		}
		elseif (is_array($var))
		{
			$result = '[';

			if ($var)
			{
				foreach ($var as $item)
				{
					$result .= static::to_string($item) . ', ';
				}

				$result = char::remove_last($result, 2);
			}

			$result .= ']';

			return $result;
		}
		else
		{
			return $var;
		}
	}
}