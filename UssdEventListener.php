<?php

use Bitmarshals\InstantUssd\UssdEventListener as InstantUssdEventListener;
use Bitmarshals\InstantUssd\UssdEvent;

/**
 * Description of UssdEventListener
 *
 * @author David Bwire
 */
class UssdEventListener extends InstantUssdEventListener {

    public function __construct(array $ussdMenusConfig) {
        parent::__construct($ussdMenusConfig);
        // HOME PAGE example
        // example - attaching an event
        $this->attach('Bitmarshals\InstantUssd', 'home_instant_ussd', function($e) use ($ussdMenusConfig) {
            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig = $ussdMenusConfig[$e->getName()];
            if (!$e->containsIncomingData()) {
                $this->attachDynamicErrors($e, $menuConfig);
                // clear tracked menus
                $isValidResponse = $e->getParam('is_valid', true);
                if ($isValidResponse) {
                    $this->clearMenuVisitHistory($e);
                }
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig, true, false);
            }
            // we have data sent in
            // 1. Get latest response & save it; as it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();
            // 2. Determine next menu using latest response
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
    }

}
