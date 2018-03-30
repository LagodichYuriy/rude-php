# rude-php

Lightweight standalone PHP framework.

Main features:
- Supports UTF-8 strings;
- Custom ORM with ActiveRecord and QueryBuilder patterns;
- HTTP(S) multithread scrapper;
- Console library with color support;
- Binary stream reader;
- PHPDoc with examples on two languages for main classes.

Framework core is located in the `cores/%CORE_VERSION%/` directory.
Web sites or programms should be located in the `apps/%APP_NAME%/%APP_VERSION%` directory. Initial value for version is `1.0.0`.

Dependencies:
- PHP 7.0 (with `short_open_tag = On`);
- curl.so extension (optional, for `curl` classes);
- mysqli.so extension (optional, for `database` classeses;
- mbstring.so extension (optional, for `string` and `char` classes).
