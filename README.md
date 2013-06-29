Savvy
=====

Savvy is a MVP (MVC-like) framework for database-driven GUI applications
written in PHP. It is currently in an early stage of development and thus far
from being usable in a productive manner.

###Installation

1. Download core project and install dependencies (requires [Composer](http://getcomposer.org/)):<pre>
git clone https://github.com/poliander/savvy && cd savvy && composer.phar install
</pre>

2. Make "public/" your web server document root. (Note: there is an important
   ".htaccess" file in there!)

3. Create application.ini from example configuration:<pre>
cp config/application.ini.example config/application.ini
</pre>

4. Create an empty database (whatever Doctrine [supports](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connection-details)) and customize application.ini accordingly

5. Use "doctrine-update.sh" to update database schema, generate proxy classes etc.

6. Create user account with "savvy user:add username" and set password with "savvy user:passwd username password"

###Source code

* generate your own API documentation in "public/doc/api/" with "savvy-api.sh"
(requires [phpDocumentor 2](http://www.phpdoc.org/))  

* use [PHP_CodeSniffer](http://pear.php.net/package/PHP_CodeSniffer) to check
whether your code-style is compliant to [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

###Automated testing

Run "savvy-tests.sh" to perform automated unit tests using [PHPUnit](http://phpunit.de/manual/current/en/); this
requires both "php5-xdebug" and "php5-sqlite" to be installed. A non-persistent
in-memory database is used while unit tests are performed. Code coverage
reports will be created in "public/doc/tests/".

###License

Savvy is licensed under GPL v3; see LICENSE file for further informations.
