<?

namespace rude;

class buffer_writer
{
	/** @var file */
	private $file;

	private $buffer          = '';
	private $buffer_size     = 0;
	private $buffer_size_max = 0;

	public function __construct($file_path, $file_mode = 'r+', $buffer_size = 1024)
	{
		$this->file = new file($file_path, $file_mode);

		$this->buffer_size_max = (int) $buffer_size;
	}

	public function & get()
	{
		return $this->buffer;
	}

	public function write($data, $length = null)
	{
		if ($length === null)
		{
			$length = strlen($data);
		}


		$this->buffer_size += $length;

		if ($this->buffer_size >= $this->buffer_size_max)
		{
			$this->file->write($data, $length);

			$this->buffer_size = $this->buffer_size_max;
		}
		else
		{
			$this->buffer .= $data;
		}
	}

	public function flush()
	{
		if ($this->buffer_size)
		{
			static::write($this->buffer, $this->buffer_size);
		}

		$this->buffer_size = 0;
	}
}