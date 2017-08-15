<p align="center"><img src="https://avatars1.githubusercontent.com/u/30041331?v=4&s=80"></p>

## About InstantUssd App

InstantUssd is USSD development library distributed as <code>bitmarshals/instant-ussd</code> composer package. This project recommends a file structure for incorporating <code>bitmarshals/instant-ussd</code> package to your code.

Requirements
------------

Please see the [composer.json](composer.json) file.

Installation
------------

### Via Git (clone)

First, clone the repository to the approriate folder within your project:

```bash
# git clone https://github.com/bitmarshals/instant-ussd-app.git InstantUssd
$ cd path/to/install
# remove .git directory; you no longer need it
$ rm -Rvf .git
```

At this point, you need to use [Composer](https://getcomposer.org/) to install
dependencies. Assuming you already have Composer:

```bash
$ cd path/to/project/root
# install bitmarshals/instant-ussd composer package via the command
# php composer.phar require bitmarshals/instant-ussd:dev-master
# OR
$ composer require bitmarshals/instant-ussd:dev-master
```

Finally, import instant ussd tables from [database.sql](config/database.sql) file.

<b>Note</b> You may opt to use your own namespace instead of <code>namespace InstantUssd;</code> provided for [UssdEventListener.php](UssdEventListener.php), [UssdValidator.php](UssdValidator.php) and [UssdController.php](UssdController.php). If you do, remember to reference <code>UssdEventListener.php</code> & <code>UssdValidator.php</code> correctly from the [config file](config/iussd.config.php#L5) and [UssdController.php](UssdController.php#L9) respectively.

## License

InstantUssdApp is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT). However, it's dependent on <code>bitmarshals/instant-ussd</code> composer package which is still proprietary.
