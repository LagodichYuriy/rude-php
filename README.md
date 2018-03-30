# rude-php

Lightweight standalone PHP framework.

Main features:
- Supports UTF-8 strings;
- Custom ORM with ActiveRecord and QueryBuilder patterns;
- HTTP(S) multithread scrapper
- console library with color support
- stream reader
- etc.

Framework core is located in the core/version/ directory. Web sites or programms should be located in the apps/ directory. Initial value for version is "1.0.0".

Dependencies:
- PHP 7.0 (with short_open_tag = On).
- Curl (for curl classes).
- mysqli.so extension (for database classeses).
- mbstring.so (for string classes).
