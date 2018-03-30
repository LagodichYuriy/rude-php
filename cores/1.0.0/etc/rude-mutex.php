<?

namespace rude;

class mutex
{
	/** @var file */
	private $file;

	public function __construct($file_path)
	{
		$this->file = new file($file_path, 'a+');
	}

	public function __destruct()
	{
		static::unlock();

		$this->file->close();
	}

	public function   lock() { return $this->file->lock_write(true); }
	public function unlock() { return $this->file->unlock(); }
}