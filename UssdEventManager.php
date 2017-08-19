<?php

namespace InstantUssd;

use Bitmarshals\InstantUssd\UssdEvent;
use InstantUssd\Listeners;
use Bitmarshals\InstantUssd\UssdEventManager as InstantUssdEventManager;

/**
 * Description of UssdEventListener
 *
 * @author David Bwire
 */
class UssdEventManager extends InstantUssdEventManager {

    public function __construct(array $ussdMenusConfig) {
        parent::__construct($ussdMenusConfig);
        // HOME PAGE example
        // example - attaching an event
        $this->attach('Bitmarshals\InstantUssd', 'home_instant_ussd', function($e) use ($ussdMenusConfig) {
            $listener             = new Listeners\HomeInstantUssd($e, $ussdMenusConfig);
            $continueUssdHops     = true;
            $appendNavigationText = true;
            return call_user_func([$listener, "onTrigger"], $continueUssdHops, $appendNavigationText);
        });
        // Have a default listener for quick set up
        $defaultListener = function($e) use ($ussdMenusConfig) {
            $continueUssdHops     = true;
            $appendNavigationText = true;
            $listener             = new Listeners\UssdListener($e, $ussdMenusConfig);
            return call_user_func([$listener, "onTrigger"], $continueUssdHops, $appendNavigationText);
        };
        // REGISTER - SELF
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.self', $defaultListener);
        // REGISTER - CLIENT [Number of Clients]
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.client.count', $defaultListener);
        // REGISTER - CLIENT
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.client', $defaultListener);
        // MY ACCOUNT
        $this->attach("Bitmarshals\InstantUssd", 'iussd.my_account', $defaultListener);
        // EXIT STEP
        $this->attach("Bitmarshals\InstantUssd", 'iussd.final', $defaultListener);
    }

}
