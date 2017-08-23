<p align="center"><img src="https://avatars1.githubusercontent.com/u/30041331?v=4&s=80"></p>

## InstantUssd App

This project recommends a file structure for incorporating [bitmarshals/instant-ussd](https://github.com/bitmarshals/instant-ussd) USSD library to your project.

It also provides the quickest way to boot up a fully functional USSD application within 3 minutes or less.

## Goals

- Speed up USSD development
- Ease maintenance of USSD code

## Features

- Minimal coding (provide USSD menus as config)
- Automatic screen to screen navigation
- Out of the box validation of user inputs
- Ready solutions for complex USSD flows involving going back and forth,
optional screens, looping set of screens,  jumping from screen to screen and 
resuming timed-out USSD sessions

Requirements
------------

PHP >=5.6

[bitmarshals/instant-ussd:0.1.*](https://github.com/bitmarshals/instant-ussd)

Installation
------------

### Via Download (easiest)

Download `instant-ussd-app.zip` or `instant-ussd-app.tar.gz` from the downloads section of our [latest release](https://github.com/davidbwire/instant-ussd-app/releases).

Extract the files 

```bash
$ tar -xvzf instant-ussd-app.tar.gz
# OR
$ unzip instant-ussd-app.zip
```

Configure database tables and connection (see below)

### Via Git (clone)

First, clone the repository:

```bash
$ git clone https://github.com/davidbwire/instant-ussd-app.git
```

At this point, you need to use [Composer](https://getcomposer.org/) to install
dependencies.

```bash
$ cd installation/path/used
$ php composer.phar install
```

Configure database tables and connection (see below)

### Via Composer

Run

```bash
$ composer create-project davidbwire/instant-ussd-app
```

Configure database tables and connection (see below)

## Configuration

Import instant ussd tables from [database.sql](config/database.sql) file and then add database connection params to the  [config file](config/iussd.config.php).

Finally, map incoming USSD requests to `http://your_domain_or_ip/callback.php`

## Documentation


1. [How It Works](https://github.com/bitmarshals/instant-ussd#usage)
1. [Configuration](https://github.com/bitmarshals/instant-ussd/wiki/Configuration)
1. [Capturing Incoming Data](https://github.com/bitmarshals/instant-ussd/wiki/Capturing-Incoming-Data)


## License

InstantUssd App is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
