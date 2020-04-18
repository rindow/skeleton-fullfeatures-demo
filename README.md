Rindow PHP Application Skeleton
===============================
This is a web application skeleton for Rindow PHP Application Framework.
You can make your new application with this skeleton.

The Rindow Framework is a PHP Application Framework that provides a modern programming and configuration model to all PHP programmers. Please see [The Rindow Framework](https://rindow.github.io).

Application Skeleton Types
--------------------------
You can choose skeleton type.
- Standard Application Skeleton.(*This skeleton*)
    - A typical web Application and command line application uses template engine and database to the Rindow Framework.
- Minimum Web Application Skeleton.([Get a mini app from here](https://github.com/rindow/skeleton-mini-webappl))
    - The minimal application skeleton contains nothing more than displaying a web page.

### Features of Standard Application Skeleton
You'll be able to choose the platform you want to use, as well as the features you will use most often.

- Inverse of control
    - Inverse of control programming is actually demonstrated by the application.
    - Flexible module exchange is possible by dependency injection and configuration injection.

- Annotation based configuration
    - The definition of Components, Controller, Transaction, Validation, Forms etc. is set to annotation base.

- Template Engine Manager
    - Includes separate samples for you to choose between Twig, Smarty and PHP. You can switch by setting.
    - We have already prepared Bootstrap, Foundation and Material Design Lite templates so you can start using them right away. This can also be switched by setting.

- Databases
    - Database can be used by switching between SQL Database (SQLite, MySQL, PostgreSQL), MongoDB and Google Cloud Datastore.
    - The access method from the application adopts the standard Repository method by declarative transaction. For the ORM method, another skeleton will be prepared.

- Access control
    - Includes sample user authentication and access control on web applications.
    - Although Rindow Framework access control is available for all applications, not just web-based, this sample demonstrates access control for web applications using forms authentication.

- REST Api application
    - A REST Api application sample is included with Vue.js.

- Command line application
    - A command line application sample is included for database maintenance.


Requirements
------------
This sample was created for PHP 7.2 and later.

However, Rindow Framework supports PHP 5.3.3 and later.
You can use the same features of Rindow Framework by rewriting only the sample code for PHP 5.x.

Installing
----------
### Using Composer(*recommend*)
If you do not have Composer, download it from http://getcomposer.org/ or
just run the following command:

```
$ php -r "readfile('https://getcomposer.org/installer');" | php
```

Then, generate a new project of the Application Skeleton with `create-project` command:

```
  php composer.phar create-project rindow/skeleton-fullfeatures-demo path/to/install
```

Composer will install Rindow Web Application Skeleton and components that depend on it under path/to/install directory.

### Download from Github
Also you can download it directly from github.

```
  $ git clone https://github.com/rindow/skeleton-fullfeatures-demo path/to/install
  $ cd path/to/install
  $ composer update
```

Setup and Run skeleton application
--------------------------
Setup database.
SQLite is set by default.
```
    $ cd path/to/install
    $ bin/myapp create-schema
```
or After finishing the database setup...
```
    $ bin/myapp create-schema -s | mysql -u username -p database_name
```
(Database settings are described in config/local/database.sql.php.disable.)

Setup users for protected pages.
```
    $ bin/myapp user-add -p password test@test.com
```

Run the application.
```
    $ php -S localhost:8000 -t public
```

The sample is now running. Access http://localhost:8000/ with a web browser.

Change settings
---------------
We have prepared a template of the changes so that you can easily change the settings. Please enable each setting under config/local/

The settings are compiled and saved. You must clear the cache after changing the settings. Script is prepared in the sample.
If you are using a memory cache such as APCu, also clear the memory cache.

If you change the version item in the module_manager section of webapp.config.php, the cache will be cleared automatically.

### Change pages theme
```
    $ mv config/local/theme.mdl.local.php.disable config/local/theme.mdl.local.php
    $ bin/cache-clear
```

### Change template engine
```
    $ mv config/local/template.smarty.local.php.disable config/local/template.smarty.local.php
    $ bin/cache-clear
```

### Change database platform
```
    $ mv config/local/database.mongodb.local.php.disable config/local/database.mongodb.local.php
    $ bin/cache-clear
```
