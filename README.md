# rude-php

A lightweight standalone PHP framework.

Main features:
- UTF-8 PHP strings support;
- Custom ORM with ActiveRecord and QueryBuilder patterns;
- HTTP(S) multithread scrapper;
- Console library with color support;
- Binary stream reader;
- PHPDoc with samples on two languages for main core classes.

The framework core is located in the `cores/%CORE_VERSION%/` directory.
Web application or CLI programs should be placed in the `apps/%APP_NAME%/%APP_VERSION%` directory. Initial `%APP_VERSION%` value is `1.0.0`.

Requirements:
- PHP 7.0 (with `short_open_tag = On`);
- curl.so extension (optional, for `curl` classes);
- mysqli.so extension (optional, for `database` classes);
- mbstring.so extension (optional, for `string` and `char` classes).
