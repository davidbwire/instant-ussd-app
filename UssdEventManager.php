<?php

namespace InstantUssd;

use InstantUssd\Listeners;
use Bitmarshals\InstantUssd\UssdEventManager as InstantUssdEventManager;
use Bitmarshals\InstantUssd\Listeners\UssdEventListener;

/**
 * Description of UssdEventManager
 * 
 * Aggregates USSD event listeners for the different USSD screens 
 *
 * @author David Bwire
 */
class UssdEventManager extends InstantUssdEventManager {

    public function __construct(array $ussdMenusConfig) {
        parent::__construct($ussdMenusConfig);

        // HOME PAGE example
        // example - attaching an event
        $this->attach('Bitmarshals\InstantUssd', 'home_instant_ussd', function($e) use ($ussdMenusConfig) {
            // instantiate your CUSTOM listener class
            $listener             = new Listeners\HomeInstantUssd($e, $ussdMenusConfig);
            $continueUssdHops     = true;
            $appendNavigationText = true;
            // TRIGGER IT
            return call_user_func([$listener, "onTrigger"], $continueUssdHops, $appendNavigationText);
        });

        // DEFAULT LISTENER - Helps with quick setup
        $defaultListener = function($e) use ($ussdMenusConfig) {
            $continueUssdHops     = true;
            $appendNavigationText = true;
            $listener             = new UssdEventListener($e, $ussdMenusConfig);
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
