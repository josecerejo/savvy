Savvy
=====

Savvy is a MVP framework for database-driven GUI applications written in PHP.
It is currently in an early stage of development and far from being usable in
a productive manner.

###Installation

1. Download core project and install dependencies (requires [Composer](http://getcomposer.org/)):<pre>
git clone https://github.com/poliander/savvy && cd savvy && composer.phar install
</pre>

2. Make "public/" your web server document root. (Note: there is an important
   ".htaccess" file in there!)

3. For convenience, add "bin/" to your environment's PATH variable

4. Create application.ini from example configuration:<pre>
cp config/application.ini.example config/application.ini
</pre>

5. Create an empty database (whatever Doctrine [supports](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connection-details))
and customize application.ini accordingly

6. Use "doctrine-update.sh" to update database schema, generate proxy classes etc.

###Source documentation

* generate your own API documentation in "public/doc/api/" with "savvy-api.sh"
(requires [phpDocumentor 2](http://www.phpdoc.org/))  

* use [PHP_CodeSniffer](http://pear.php.net/package/PHP_CodeSniffer) to check
whether your code-style is compliant to [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

###Automated testing

Run "savvy-tests.sh" to perform automated unit tests using [PHPUnit](http://phpunit.de/manual/current/en/); this
requires installing "php5-xdebug" and "php5-sqlite". A non-persistent in-memory database is used while unit tests
are performed. Code coverage reports will be created in "public/doc/tests/".

###License

Savvy is licensed under GPL v3; see LICENSE file for further informations.
