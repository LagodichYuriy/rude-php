<?

namespace rude;

class file
{
	/** @var \SplFileObject */
	protected $file;

	protected $file_path;
	protected $file_mode;

	public function __construct($path = null, $mode = null)
	{
		if ($path !== null and $mode !== null)
		{
			static::open($path, $mode);
		}
	}

	public function open($path, $mode)
	{
		$this->file_path = $path;
		$this->file_mode = $mode;

		$this->file = new \SplFileObject($path, $mode);
	}

	public  function close() { $this->file = null; }
	private function reset() { $this->file = null; $this->file_path = null; $this->file_mode = null; }

	public function reopen($mode = null)
	{
		if ($mode === null)
		{
			$mode = $this->file_mode;
		}

		static::close();

		static::open($this->file_path, $mode);

		static::reset();
	}

	public function create($time_modified = null, $time_accesed = null)
	{
		return touch(static::path(), $time_modified, $time_accesed);
	}

	public function remove()
	{
		static::close();

		$status = unlink($this->file_path);

		static::reset();

		return $status;
	}

	public function move($path)
	{
		$status = rename($this->file_path, $path);

		static::reset();

		return $status;
	}

	public function rename($name)
	{
		static::move(static::directory() . DIRECTORY_SEPARATOR . $name);
	}

	public function path($real = false)
	{
		if ($real)
		{
			return $this->file->getRealPath();
		}

		return $this->file_path;
	}

	public function name($include_extension = true)
	{
		return file_info::name(static::path(), $include_extension);
	}

	public function directory($real = false)
	{
		return file_info::directory(static::path($real));
	}

	public function extension()
	{
		return file_info::extension(static::path());
	}

	public function size() { return $this->file->getSize(); }
	public function type() { return $this->file->getType(); } # file, link or dir

	public function owner      ($owner       = null) { if ($owner       === null) { return $this->file->getOwner(); } return chown(static::path(), $owner);       }
	public function group      ($group       = null) { if ($group       === null) { return $this->file->getGroup(); } return chgrp(static::path(), $group);       }
	public function permissions($permissions = null) { if ($permissions === null) { return $this->file->getPerms(); } return chmod(static::path(), $permissions); }

	public function inode() { return $this->file->getInode(); }

	public function time_access() { return $this->file->getATime(); } # atime - last accessed time, it gets updated when you open a file but also when a file is used for other operations like grep, sort, cat, head, tail and so on
	public function time_modify() { return $this->file->getMTime(); } # mtime - the last modified time, when you changed the content of the file
	public function time_change() { return $this->file->getCTime(); } # ctime - change time, the last time the inode for that file has been changed (e.g. you changed permissions, or renamed the file)

	public function resize($size) { return $this->file->ftruncate($size); }

	public function truncate($size = 0) { return static::resize($size); }

	public function flush() { return $this->file->fflush(); }

	public function stat() { return $this->file->fstat(); }

	public function lock_read ($non_blocking = false) { if ($non_blocking) { return $this->file->flock(LOCK_SH | LOCK_NB); } return $this->file->flock(LOCK_SH); }
	public function lock_write($non_blocking = false) { if ($non_blocking) { return $this->file->flock(LOCK_EX | LOCK_NB); } return $this->file->flock(LOCK_EX); }

	public function unlock() { return $this->file->flock(LOCK_UN); }

	public function write($string, $length = null)
	{
		if ($length !== null)
		{
			return $this->file->fwrite($string, $length);
		}

		return $this->file->fwrite($string);
	}

	public function write_line($string, $length = null) { return $this->file->fwrite($string . PHP_EOL, $length); }
	public function write_line_csv($items)              { return $this->file->fputcsv($items); }

	public function read($length = null)           { return $this->file->fread($length); }
	public function read_char()                    { return $this->file->fgetc(); }
	public function read_line()                    { return $this->file->fgets(); }
	public function read_line_csv()                { return $this->file->fgetcsv(); }

	public function offset() { return $this->file->ftell(); }
	public function offset_reset() { $this->file->rewind(); }

	public function enable_csv()              { static::flags($this->flags() | \SplFileObject::READ_CSV);      }
	public function enable_read_ahead()       { static::flags($this->flags() | \SplFileObject::READ_AHEAD);    }
	public function enable_skip_newline()     { static::flags($this->flags() | \SplFileObject::DROP_NEW_LINE); }
	public function enable_skip_empty_lines() { static::flags($this->flags() | \SplFileObject::SKIP_EMPTY);    }

	public function flags($flags = null) { if ($flags === null) { return $this->file->getFlags(); } $this->file->setFlags($flags); return true; }

	public function csv_delimeter($value = null) { if ($value === null) { return static::csv_settings()[0]; } return static::csv_settings($value); }
	public function csv_enclosure($value = null) { if ($value === null) { return static::csv_settings()[1]; } return static::csv_settings(null, $value); }
	public function csv_escape   ($value = null) { if ($value === null) { return static::csv_settings()[2]; } return static::csv_settings(null, null, $value); }

	public function csv_settings($delimiter = null, $enclosure = null, $escape = null)
	{
		$settings = $this->file->getCsvControl();

		if ($delimiter === null and $enclosure === null and $escape === null)
		{
			return $settings;
		}

		if ($delimiter !== null) { $settings[0] = $delimiter; }
		if ($enclosure !== null) { $settings[1] = $enclosure; }
		if ($escape    !== null) { $settings[2] = $escape;    }

		$this->file->setCsvControl($settings[0], $settings[1], $settings[2]);

		return true;
	}

	public function link_stat() { return lstat(static::path()); }

	public function link_target() { return $this->file->getLinkTarget(); }

	public function link_owner($owner) { return lchown(static::path(), $owner); }
	public function link_group($group) { return lchgrp(static::path(), $group); }

	public function link_time_access() { return static::link_stat()['atime']; } # atime - last accessed time, it gets updated when you open a file but also when a file is used for other operations like grep, sort, cat, head, tail and so on
	public function link_time_modify() { return static::link_stat()['mtime']; } # mtime - the last modified time, when you changed the content of the file
	public function link_time_change() { return static::link_stat()['ctime']; } # ctime - change time, the last time the inode for that file has been changed (e.g. you changed permissions, or renamed the file)

	public function is_end()        { return $this->file->eof(); }

	public function is_exist()      { return file_exists(static::path()); }

	public function is_file()       { return $this->file->isFile();       }
	public function is_directory()  { return $this->file->isDir();        }
	public function is_link()       { return $this->file->isLink();       }

	public function is_readable()   { return $this->file->isReadable();   }
	public function is_writable()   { return $this->file->isWritable();   }
	public function is_executable() { return $this->file->isExecutable(); }
}