<p align="center"><img src="https://avatars1.githubusercontent.com/u/30041331?v=4&s=80"></p>

## About InstantUssd App

InstantUssd is a USSD development package meant to provide you, the developer, with a set of tools to help you easily and quickly build your own USSD applications.

This project (InstantUssd App) recommends a file structure for incorporating InstantUssd package to your project.

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

Please see the [composer.json](composer.json) file.

Installation
------------

### Via Git (clone)

First, clone the repository:

```bash
# git clone https://github.com/davidbwire/instant-ussd-app.git InstantUssd
$ cd path/to/install
# remove .git directory; you no longer need it
$ rm -Rvf .git
```

At this point, you need to use [Composer](https://getcomposer.org/) to install
dependencies. Assuming you already have Composer:

```bash
$ php composer.phar install
# OR
$ composer install
```

Finally, import instant ussd tables from [database.sql](config/database.sql) file and then add database connection params to the  [config file](config/iussd.config.php).

## Documentation


1. [Configuration](https://github.com/davidbwire/instant-ussd/wiki/Configuration)
1. [Looping Menus](https://github.com/davidbwire/instant-ussd/wiki/Looping-Menus)
1. [Capturing Incoming Data](https://github.com/davidbwire/instant-ussd/wiki/Capturing-Incoming-Data)


## License

InstantUssd App is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT). However, it's dependent on <code>bitmarshals/instant-ussd</code> composer package which is still proprietary.
