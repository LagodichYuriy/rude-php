<?

namespace rude;

class file_tmp extends file
{
	/** @var \SplTempFileObject */
	protected $file;

	/** @var int */
	private $memory_limit;

	/**
	 * Construct a new temporary file object
	 *
	 * [memory_limit] - the maximum amount of memory (in bytes, default is 2 MB) for the temporary file to use.
	 * If the temporary file exceeds this size, it will be moved to a file in the system's temp directory.
	 * If [memory_limit] is negative, only memory will be used.
	 * If [memory_limit] is zero, no memory will be used.
	 *
	 * Note that when the tmp file exceeds memory limitations and is written to the system temp directory,
	 * it is deleted upon completion of the script it was initially created in
	 *
	 * @param int $memory_limit
	 */
	public function __construct($memory_limit = 2048)
	{
		parent::__construct();

		$this->memory_limit = $memory_limit;
	}

	public function memory_limit() { return $this->memory_limit; }

	public function size() { return $this->file->ftell(); }
}