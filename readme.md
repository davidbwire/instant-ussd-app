<p align="center"><img src="https://avatars1.githubusercontent.com/u/30041331?v=4&s=80"></p>

## About InstantUssd App

This project recommends a file structure for incorporating `bitmarshals/instant-ussd` composer package to your project.

InstantUssd Goals
-----------------

- Speed up USSD development
- Ease maintenance of USSD code

InstantUssd Features
--------------------

- Minimal coding (Provide USSD menus as config)
- Automatic screen to screen navigation
- Out of the box validation of user inputs
- Ready solutions for complex USSD flows involving going back and forth,
optional screens, looping set of screens,  jumping from screen to screen and 
resuming timed-out USSD sessions

Requirements
------------

PHP >=5.6

Installation
------------

### Via Git (clone)

First, clone the repository:

```bash
# git clone https://github.com/davidbwire/instant-ussd-app.git
$ cd path/to/install
# remove .git directory; you no longer need it
$ rm -Rvf .git
```

At this point, you need to use [Composer](https://getcomposer.org/) to install
dependencies.

```bash
$ php composer.phar install
# OR
$ composer install
```

### Via Composer

```bash
$ composer create-project davidbwire/instant-ussd-app
```

## Configuration

Import instant ussd tables from [database.sql](config/database.sql) file and then add database connection params to the  [config file](config/iussd.config.php).

## Documentation


1. [Configuration](https://github.com/bitmarshals/instant-ussd/wiki/Configuration)
1. [Looping Menus](https://github.com/bitmarshals/instant-ussd/wiki/Looping-Menus)
1. [Capturing Incoming Data](https://github.com/bitmarshals/instant-ussd/wiki/Capturing-Incoming-Data)


## License

InstantUssd App is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
