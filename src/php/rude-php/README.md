Rude-php
========
Here you can find most of the standard solutions, which are not available in PHP by default.



Installation
============

### A. Manual ###

```php
# just unpack archive and call this file it in any place of your code:

require_once '/rude-php/include.php';
```

### B. Composer ###
```php
# 1. Create file with name composer.json in the root directory (or just update "require" section if it' already exists):

{
    "require": {
        "rude/rude-php": "dev-master"
    }
}


# 2. Install package via `composer install` or `php composer.phar install`


# 3. Include autoloader:

require_once 'vendor/rude/rude-php/include.php';
```

Advanced configuration
======================
```php
#######################################################################################################################
# some classes (e.g. database) requires some configuration before using, just declare this before calling include.php #
#######################################################################################################################

# you can let PHP interpreter show all warnings, errors and stricts

define('RUDE_DEBUG', true);


# if you want to use database classes - you need to tell script connection settings

define('RUDE_DATABASE_USER', 'root');      # database user
define('RUDE_DATABASE_PASS', '1234');      # database user password
define('RUDE_DATABASE_HOST', 'localhost'); # database host address (you can also use direct ip declaration)
define('RUDE_DATABASE_NAME', 'database');  # database name


# also you can tell your current timezone if you want to get correct time results in code

define('RUDE_TIMEZONE', 'Europe/Minsk');


# you can use this defines with cookies::set()

define('RUDE_TIME_SECOND', 1);             # 1 second
define('RUDE_TIME_MINUTE', 60);            # 1 minute = 60 seconds
define('RUDE_TIME_HOUR',   3600);          # 1 hour   = 3600 seconds
define('RUDE_TIME_DAY',    86400);         # 1 day    = 86400 seconds
define('RUDE_TIME_MONTH',  2592000);       # 1 month  = 2592000 seconds
define('RUDE_TIME_YEAR',   946080000);     # 1 year   = 946080000 seconds
```

System requirements
===================
```
PHP 5.3+
short_open_tag=On
```
