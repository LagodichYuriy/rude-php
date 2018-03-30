

namespace %PHP_NAMESPACE%\tables;

class %PHP_CLASS_NAME%
{
	%FOR_EACH_FIELD%
	public $%PHP_FIELD_LOWERCASE%%ALIGN% = null;
	%FOR_EACH_FIELD%
}