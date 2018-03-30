<?

/**
 * @en Getting an item from an array. Designed for usage with $_REQUEST by default
 * @ru Получение элемента из именнованного массива. Предполагается преимущественное использование с массивом $_REQUEST по умолчанию
 *
 * # $_REQUEST parse (default usage)
 *
 * debug($_REQUEST);    # Array
 *                      # (
 *                      #     [user] => Sandy
 *                      #     [host] => localhost
 *                      #     [pass] => XHIIGygSsr
 *                      # )
 *
 * $user = get('user'); # string(5) "Sandy"
 * $host = get('host'); # string(9) "localhost"
 * $pass = get('pass'); # string(10) "XHIIGygSsr"
 *
 *
 * # array parse (advanced usage)
 *
 * $array = array('username' => 'Sandy', 'password' => "XHIIGygSsr");
 *
 * $username = get('username', $array); # string(5) "Sandy"
 * $password = get('password', $array); # string(10) "XHIIGygSsr"
 *
 *
 * # you can also specify a default value for return if the specified array element not found
 *
 * $undefined = get('undefined', $array);              # NULL
 * $undefined = get('undefined', $array, 1);           # int(1)
 * $undefined = get('undefined', $array, false);       # bool(false)
 * $undefined = get('undefined', $array, "not found"); # string(9) "not found"
 *
 * @param mixed $what
 * @param mixed $src
 * @param mixed $default
 *
 * @param null|int
 *
 * @return mixed
 */
function get($what, $src = null, $default = null)
{
	if ($src === null)
	{
		$src = $_REQUEST;
	}

	if (is_object($src))
	{
		if (isset($src->$what))
		{
			return $src->$what;
		}
	}
	else if (isset($src[$what]))
	{
		return $src[$what];
	}

	return $default;
}

function get_int   ($what, $src = null, $default = 0)     { return (int)    get($what, $src, $default); }
function get_float ($what, $src = null, $default = 0.0)   { return (float)  get($what, $src, $default); }
function get_bool  ($what, $src = null, $default = false) { return (bool)   get($what, $src, $default); }
function get_string($what, $src = null, $default = '')    { return (string) get($what, $src, $default); }


/**
 * @en Human-readable variable dumper
 * @ru Человеко-понятный вывод информации о переменной
 *
 * $int = 12345;
 *
 * debug($int);                           # 12345             # print_r alias
 * debug($int, true);                     # int(12345)        # var_dump alias
 * debug($int, false, 'number');          # [number]: 12345   # print_r + title
 *
 *
 * $array = array('AAA', 'BBB', 'CCC');
 *
 * debug($array);                         # Array
 *                                        # (
 *                                        #     [0] => AAA
 *                                        #     [1] => BBB
 *                                        #     [2] => CCC
 *                                        # )
 *
 *
 * $object = new \stdClass();
 * $object->int = $int;
 * $object->array = $array;
 *
 * debug($object);                        # stdClass Object
 *                                        # (
 *                                        #     [int] => 12345
 *                                        #     [array] => Array
 *                                        #         (
 *                                        #             [0] => AAA
 *                                        #             [1] => BBB
 *                                        #             [2] => CCC
 *                                        #         )
 *                                        # )
 *
 * @param mixed $var
 * @param bool $var_dump
 * @param string $title
 */
function debug($var, $var_dump = false, $title = null)
{
	if (defined('RUDE_DEBUG') and !RUDE_DEBUG)
	{
		return;
	}

	if (get('ajax') or (defined('RUDE_CLI') and RUDE_CLI) or (defined('RUDE_AJAX') and RUDE_AJAX))
	{
		if ($title !== null)
		{
			echo '[' . $title . ']: ';
		}

		     if ($var_dump) { var_dump($var);              }
		else                { print_r($var); echo PHP_EOL; }
	}
	else
	{
		?><pre style="text-align: left!important;"><? if ($title !== null) { ?><b>[<?= $title ?>]:</b> <? } if ($var_dump) { var_dump($var); } else { print_r($var); } ?></pre><?
	}
}


/**
 * @return rude\database|null
 */
function & database()
{
	global $_database;

	return $_database;
}