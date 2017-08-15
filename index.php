<?php
// This is an optional file; use to run quick tests
require_once './vendor/autoload.php';

use InstantUssd\UssdController;

$ussdController = new UssdController();
return $ussdController->ussd();

