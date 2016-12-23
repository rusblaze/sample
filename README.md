&Frankly demo api
========================

Assignment B realization ([Assignment][1]).

Requirements
------------

  * PHP 7.0 or higher;
  * PDO-Mysql PHP extension enabled;
  * and the [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html).

Installation
------------
Download and install the demo application using Git and Composer:
```sh
$ git clone https://github.com/rusblaze/sample
$ cd sample/
$ composer install
```

Usage
-----
It is better to configure some virtual host configuration,
but basically application could be run using embedded server (just cun ```./bin/console server:run``` command).

Swagger configuration is available via http(s)://*YOUR.HOST*/_swagger/swagger.json.
Swagger configuration is available in development mode ONLY.

Not mentioned in assignment but realized functionality
--------------
* Session management (http(s)://*SWAGGER-UI.HOST*/#!/session)
    * Login
    * Logout
    * Receiving current session
* Roles
    * Implemented basic roles hierarchy
    * Implemented mechanism of "default" role

@TODO list
--------------
* Users management
    * Need more description on update-list data validating
    * Need to describe the case of user self-deleting
    * Need to describe session management of deleted users
* Users roles management
    * Need to describe the case of roles deleting
    * Need to describe the roles hierarchy (if needed)
    * Need to implement some lind of API for role management (may be)
* Authorization
    * Need to implement some kind of access rights management for new (or deleted) roles
* Logging
    * Need to implement user's activity logging 

Used bundles
--------------

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle
  * [**SensioFrameworkExtraBundle**][2] - Adds several enhancements, including
    template and routing annotation capability
  * [**DoctrineBundle**][3] - Adds support for the Doctrine ORM
  * [**TwigBundle**][4] - Adds support for the Twig templating engine
  * [**SecurityBundle**][5] - Adds security by integrating Symfony's security
    component
  * [**SwiftmailerBundle**][6] - Adds support for Swiftmailer, a library for
    sending emails
  * [**MonologBundle**][7] - Adds support for Monolog, a logging library
  * [**FOSRestBundle**][9] - Provides several tools to assist in building REST applications
  * [**DoctrineMigrationsBundle**][10] - The database migrations feature is an extension
    of the database abstraction layer and offers you the ability to
    programmatically deploy new versions of your database schema in a safe,
    easy and standardized way.
  * [**JMSSerializerBundle**][11] - JMSSerializerBundle allows you to serialize
    your data into a requested output format such as JSON, XML, or YAML, and vice versa.
  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar
  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions
  * [**SensioGeneratorBundle**][8] (in dev/test env) - Adds code generation
    capabilities
  * **DebugBundle** (in dev/test env) - Adds Debug and VarDumper component
    integration
  * [**SwaggerBundle**][12] (in dev env) - Provides integration of swagger-php in Symfony

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  assignments.pdf
[2]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[3]:  https://symfony.com/doc/3.2/doctrine.html
[4]:  https://symfony.com/doc/3.2/templating.html
[5]:  https://symfony.com/doc/3.2/security.html
[6]:  https://symfony.com/doc/3.2/email.html
[7]:  https://symfony.com/doc/3.2/logging.html
[8]:  https://symfony.com/doc/current/bundles/SensioGeneratorBundle/index.html
[9]:  http://symfony.com/doc/current/bundles/FOSRestBundle/index.html
[10]: http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
[11]: http://jmsyst.com/bundles/JMSSerializerBundle
[12]: https://github.com/TimeIncOSS/swagger-bundle
