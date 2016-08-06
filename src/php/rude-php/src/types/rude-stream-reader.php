<?

namespace rude;

define('RUDE_TYPE_SIZE_BYTE',       1); # 1 byte
define('RUDE_TYPE_SIZE_INT32',      4); # 4 bytes
define('RUDE_TYPE_SIZE_INT16',      2); # 2 bytes
define('RUDE_TYPE_SIZE_INT8',       1); # 1 byte
define('RUDE_TYPE_SIZE_FLOAT',      4); # 4 bytes
define('RUDE_TYPE_SIZE_CHAR',       1); # 1 byte
define('RUDE_TYPE_SIZE_CHAR_ARRAY', 4); # 4 bytes

/**
 * @category: types
 */
class stream_reader
{
	private $stream = null;
	private $offset = 0;

	/**
	 * @en Stream-reader conscructor
	 * @ru Конструктор класса потокового чтения
	 *
	 * @param string $stream
	 * @param int $offset
	 */
	public function __construct(&$stream, $offset = 0)
	{
		$this->stream = $stream;
		$this->offset = $offset;
	}

	/**
	 * @en Read next bytes from the stream
	 * @ru Получить следующие байты из потока
	 *
	 * @param string $length
	 *
	 * @return string
	 */
	public function read($length)
	{
		$stream = stream::read($this->stream, $length, $this->offset);

		$this->offset_inc($length);

		return $stream;
	}

	/**
	 * @en Read the rest of the stream
	 * @ru Читает оставшиеся байты до конца потока
	 *
	 * @return string
	 */
	public function read_rest()
	{
		$length = strings::size($this->stream) - $this->offset;
		$string = stream::read($this->stream, $length, $this->offset);

		return $string;
	}

	/**
	 * @en Current stream's offset
	 * @ru Текущее смещение в потоке
	 *
	 * @return int
	 */
	public function offset()
	{
		return $this->offset;
	}

	/**
	 * @en Check if stream ends right now
	 * @ru Проверяет, закончился ли поток
	 *
	 *
	 * @return bool
	 */
	public function is_end()
	{
		if (strings::size($this->stream) == $this->offset - 1)
		{
			return true;
		}

		return false;
	}

	/**
	 * @en Check if the next character in the stream is equal to specified
	 * @ru Проверяет, находится ли указанный символ следующим символом в потоке
	 *
	 * @param $char
	 *
	 * @return bool
	 */
	public function is_next_char($char)
	{
		if ($char == stream::read($this->stream, RUDE_TYPE_SIZE_CHAR, $this->offset))
		{
			return true;
		}

		return false;
	}

	/**
	 * @en Check if the previous character in the stream is equal to specified
	 * @ru Проверяет, находится ли указанный символ предыдущим символом в потоке
	 *
	 * @param $char
	 *
	 * @return bool
	 */
	public function is_prev_char($char)
	{
		if ($char == stream::read($this->stream, RUDE_TYPE_SIZE_CHAR, $this->offset - 1))
		{
			return true;
		}

		return false;
	}

	/**
	 * @en Returns the position of the nearest int32 number
	 * @ru Возвращает позицию ближайшего числа с типом int32
	 *
	 * @param $int
	 *
	 * @return int
	 */
	public function find_int32($int)
	{
		while ($this->int32() != $int)
		{
		}

		return $this->offset;
	}

	/**
	 * @en Returns the position of the nearest uint32 number
	 * @ru Возвращает позицию ближайшего числа с типом uint32
	 *
	 * @param $uint
	 *
	 * @return int
	 */
	public function find_uint32($uint)
	{
		while ($this->uint32() != $uint)
		{
		}

		return $this->offset;
	}

	/**
	 * @en Read one byte from the stream
	 * @ru Десериализовать один байт из потока
	 *
	 * @return string
	 */
	public function byte()
	{
		return $this->read(RUDE_TYPE_SIZE_BYTE);
	}

	/**
	 * @en Deserialize following bytes as int32 number
	 * @ru Десериализовать int32 из потока
	 *
	 * @return int
	 */
	public function int32()
	{
		return (int) stream::to_int32($this->read(RUDE_TYPE_SIZE_INT32));
	}

	/**
	 * @en Deserialize following bytes as int16 number
	 * @ru Десериализовать int16 из потока
	 *
	 * @return int
	 */
	public function int16()
	{
		return (int) stream::to_int16($this->read(RUDE_TYPE_SIZE_INT16));
	}

	/**
	 * @en Deserialize following bytes as int8 number
	 * @ru Десериализовать int8 из потока
	 *
	 * @return int
	 */
	public function int8()
	{
		return (int) stream::to_int8($this->read(RUDE_TYPE_SIZE_INT8));
	}

	/**
	 * @en Deserialize following bytes as uint32 number
	 * @ru Десериализовать uint32 из потока
	 *
	 * @return int
	 */
	public function uint32()
	{
		return $this->int32() & 0xFFFFFFFF;
	}

	/**
	 * @en Deserialize following bytes as uint16
	 * @ru Десериализовать uint16 из потока
	 *
	 * @return int
	 */
	public function uint16()
	{
		return $this->int16() & 0xFFFF;
	}

	/**
	 * @en Deserialize following bytes as uint16
	 * @ru Десериализовать uint16 из потока
	 *
	 * @return int
	 */
	public function uint8()
	{
		return $this->int8() & 0xFF;
	}

	/**
	 * @en Deserialize following bytes as char
	 * @ru Десериализовать char из потока
	 *
	 * @return string
	 */
	public function char()
	{
		return (string) $this->read(RUDE_TYPE_SIZE_CHAR);
	}

	/**
	 * @en Deserialize following bytes as uchar
	 * @ru Десериализовать uchar из потока
	 *
	 * @return string
	 */
	public function uchar()
	{
		return $this->char() & 0xFF;
	}

	/**
	 * @en Deserialize following bytes as char array
	 * @ru Десериализовать char array из потока
	 *
	 * @param int $char_array_length
	 *
	 * @return string
	 */
	public function char_array($char_array_length = RUDE_TYPE_SIZE_CHAR_ARRAY)
	{
		return (string) $this->read($char_array_length);
	}

	/**
	 * @en Deserialize following bytes as string
	 * @ru Десериализовать строку из потока
	 *
	 * @param string $terminate_char
	 *
	 * @return string
	 */
	public function string($terminate_char = "\0")
	{
		$string = null;

		$length = strings::size($this->stream);

		while ($this->stream[$this->offset] != $terminate_char and $this->offset < $length)
		{
			$string .= $this->stream[$this->offset];

			$this->offset_inc(RUDE_TYPE_SIZE_BYTE);
		}

		$this->offset_inc(RUDE_TYPE_SIZE_BYTE); # skip the "\0" character

		return $string;
	}

	/**
	 * @en Skip `n` bytes in a stream {alias = offset_inc}
	 * @ru Пропустить `n` байт потока
	 *
	 * @param int $length
	 */
	public function skip($length)
	{
		$this->offset_inc($length);
	}

	/**
	 * @en Set the stream offset value {alias = offset_set}
	 * @ru Установить значение текущего смещения в потоке
	 *
	 * @param int $length
	 */
	public function jump($length)
	{
		$this->offset_set($length);
	}

	/**
	 * @en Go back for `n` bytes in the stream {alias = offset_dec}
	 * @ru Переместиться назад на `n` байт в потоке
	 *
	 * @param int $length
	 */
	public function back($length)
	{
		$this->offset_dec($length);
	}

	/**
	 * @en Go forward for `n` bytes in the stream
	 * @ru Переместиться вперёд на `n` байт в потоке
	 *
	 * @param int $val
	 */
	private function offset_inc($val)
	{
		$this->offset += $val;
	}

	/**
	 * @en Go back for `n` bytes in the stream
	 * @ru Переместиться назад на `n` байт в потоке
	 *
	 * @param int $val
	 */
	private function offset_dec($val)
	{
		$this->offset -= $val;
	}

	/**
	 * @en Set the stream offset value
	 * @ru Установить значение текущего смещения в потоке
	 *
	 * @param int $val
	 */
	private function offset_set($val)
	{
		$this->offset = $val;
	}
}