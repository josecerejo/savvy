Savvy
=====

Installation
------------

1. Download core project and install dependencies:<pre>
git clone https://github.com/poliander/savvy && cd savvy && composer.phar install
</pre>

2. Create application.ini from example configuration:<pre>
cp config/application.ini.example config/application.ini
</pre>

3. Make "public/" your document root; notice: there is an important ".htaccess"
   file in there

4. Add "bin/" to your environment PATH variable

5. Create an empty MySQL database and customize application.ini accordingly

6. Use "doctrine-update.sh" to update database

Documentation
-------------

A new installation has an empty "public/doc/api/" directory; you may want to
create an API documentation on your own using "savvy-api.sh", which requires
[phpDocumentor 2](http://www.phpdoc.org/) to be installed on your system.  

Same thing with "savvy-tests.sh", which uses [PHPUnit](http://phpunit.de/manual/current/en/)
with Xdebug to create code coverage reports in "public/doc/tests/".  
