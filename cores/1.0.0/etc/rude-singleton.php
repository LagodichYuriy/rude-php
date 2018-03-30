<?

namespace rude;

class singleton
{
	private $file;

	public function __construct($path)
	{
		$this->file = new file($path);
	}

	public function lock()
	{
		if (!$this->file->is_exist())
		{
			if (!$this->file->create())
			{
				return false;
			}
		}

		return $this->file->lock_read(true);
	}

	public function unlock()
	{
		$this->file->unlock();
	}

	public function __destruct()
	{
		static::unlock();

		$this->file->close();
	}
}