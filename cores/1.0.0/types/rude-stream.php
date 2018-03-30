<?

namespace rude;

# https://stackoverflow.com/a/12430799
#
# x86/x64 is always little-endian, but PHP could technically run on big-endian machines ... somewhere.
# It is best to get into the habit of being explicit and precise to avoid having code suddenly and mysteriously "stop working" later on.

/**
 * @category types
 */
class stream
{
	protected $stream;

	protected $stream_size   = 0;
	protected $stream_offset = 0;

	public function __construct(&$stream = null, $stream_size = null)
	{
		$this->stream = $stream;

		if ($stream !== null)
		{
			     if ($stream_size !== null) { $this->stream_size = $stream_size;    }
			else                            { $this->stream_size = strlen($stream); }
		}
	}

	public function & get()
	{
		return $this->stream;
	}

	public function read($length = null)
	{
		$data = substr($this->stream, $this->stream_offset, $length);

		     if ($length !== null) { static::offset_skip($length); }
		else                       { static::offset_tail();        }

		if ($this->stream_offset > $this->stream_size)
		{
			exception::warning('out of range');

			return null;
		}

		return $data;
	}

	public function write($stream, $length = null)
	{
		$this->stream .= $stream;

		if ($length === null)
		{
			$length = strlen($length);
		}

		$this->stream_size += $length;
	}

	public function size()
	{
		return $this->stream_size;
	}

	public function offset($offset = null)
	{
		if ($offset !== null)
		{
			$this->stream_offset = $offset;
		}

		return $offset;
	}

	public function offset_skip($offset) { static::offset($this->stream_offset + $offset); }
	public function offset_head()        { static::offset(0);                       }
	public function offset_tail()        { static::offset($this->stream_size);      }

	public function read_int8  ($units = 1, $array_force = false) { return static::unpack('c*', $units, 1, $array_force); }
	public function read_int16 ($units = 1, $array_force = false) { return static::unpack('s*', $units, 2, $array_force); }
	public function read_int32 ($units = 1, $array_force = false) { return static::unpack('l*', $units, 4, $array_force); }
	public function read_int64 ($units = 1, $array_force = false) { return static::unpack('q*', $units, 8, $array_force); }

	public function read_uint8 ($units = 1, $array_force = false) { return static::unpack('C*', $units, 1, $array_force); }
	public function read_uint16($units = 1, $array_force = false) { return static::unpack('v*', $units, 2, $array_force); }
	public function read_uint32($units = 1, $array_force = false) { return static::unpack('V*', $units, 4, $array_force); }
	public function read_uint64($units = 1, $array_force = false) { return static::unpack('P*', $units, 8, $array_force); }

	public function write_int8  ($data, $units = 1) { static::pack('c*', $data, $units, 1); }
	public function write_int16 ($data, $units = 1) { static::pack('s*', $data, $units, 2); }
	public function write_int32 ($data, $units = 1) { static::pack('l*', $data, $units, 4); }
	public function write_int64 ($data, $units = 1) { static::pack('q*', $data, $units, 8); }

	public function write_uint8 ($data, $units = 1) { static::pack('C*', $data, $units, 1); }
	public function write_uint16($data, $units = 1) { static::pack('v*', $data, $units, 2); }
	public function write_uint32($data, $units = 1) { static::pack('V*', $data, $units, 4); }
	public function write_uint64($data, $units = 1) { static::pack('P*', $data, $units, 8); }

	protected function unpack($format, $units = 1, $unit_size, $array_force)
	{
		$length = (int) ($units * $unit_size);

		$data = unpack($format, static::read($length));

		if ($array_force === false and $units === 1)
		{
			return $data[1];
		}

		return $data;
	}

	protected function pack($format, $data, $units, $unit_size)
	{
		$stream = pack($format, $data);

		$length = (int) ($units * $unit_size);

		static::write($stream, $length);
	}
}