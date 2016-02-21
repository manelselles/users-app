# Users app

A project to show different ways of configuring users management and security with Symfony3 framework


## Install

### Requirements

PHP 5.6+, SQLite and Ant

### Composer

Run `composer install` to install project dependencies.

### Doctrine

Run `ant doctrine` to generate sqlite database.

### Server

This project uses built-in server for development. Run `bin/console server:run` to start the server.


## Tests

Run `ant test` and it will execute phpspec, phpunit and behat suites.