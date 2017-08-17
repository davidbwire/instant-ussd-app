<?php

namespace InstantUssd;

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
            $menuConfig     = $ussdMenusConfig[$e->getName()];
            // we have data sent in
            // 1. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();
            // 2. Check if we should skip this listener
            if (!$e->containsIncomingData()) {
                $this->attachDynamicErrors($e, $menuConfig);
                // clear tracked menus
                $isValidResponse = $e->getParam('is_valid', true);
                if ($isValidResponse) {
                    // this method should only be called by home menus (menus beginning with home_*)
                    $this->clearMenuVisitHistory($e);
                }
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig, true, false);
            }
            // 3. Do your processing; save to db; etc
            // 4. Check if we should stop looping
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
        // REGISTER - SELF
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.self', function($e) use ($ussdMenusConfig) {

            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig     = $ussdMenusConfig[$e->getName()];
            // we have data sent in
            // 1. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            /* / 2. *             * *---------------- SKIPPABLE MENU EXAMPLE 
              $isSkippableMenu = $e->getInstantUssd()
              ->getSkippableUssdMenuMapper()
              ->isSkippable(['reference_id' => 1]);
              if ($isSkippableMenu) {
              // stop propagation so that it is not captured
              $e->stopPropagation(true);
              // return a pointer to the menu we should skip to
              // $shouldStopLooping should be called just before a call to determineNextMenu
              $shouldStopLooping = $e->getInstantUssd()->shouldStopLooping($menuConfig, $e);
              $ussdMenuItem = $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse, $shouldStopLooping);
              return $ussdMenuItem;
              }
              /* -------------------------------------------- */
            if (!$e->containsIncomingData()) {
                /* show this menu */
                $this->attachDynamicErrors($e, $menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            // 3. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
        // REGISTER - CLIENT [Number of Clients]
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.client.count', function($e) use ($ussdMenusConfig) {
            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig     = $ussdMenusConfig[$e->getName()];
            // we have data sent in
            // 1. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $this->attachDynamicErrors($e, $menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            $e->getInstantUssd()->getUssdLoopMapper()
                    ->initializeLoopingSession($menuConfig['target_loopset'], $e->getParam('session_id'), $latestResponse);
            // 3. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
        // REGISTER - CLIENT
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.client', function($e) use ($ussdMenusConfig) {
            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig     = $ussdMenusConfig[$e->getName()];
            // we have data sent in
            // 1. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $this->attachDynamicErrors($e, $menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            // 3. Do your processing; save to db; etc
            // 4. $shouldStopLooping should be called just before a call to determineNextMenu
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            $shouldStopLooping = $e->getInstantUssd()->shouldStopLooping($menuConfig, $e);
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse, $shouldStopLooping);
        });
        // MY ACCOUNT
        $this->attach("Bitmarshals\InstantUssd", 'iussd.my_account', function($e) use ($ussdMenusConfig) {
            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig     = $ussdMenusConfig[$e->getName()];
            // we have data sent in
            // 1. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $this->attachDynamicErrors($e, $menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            // 3. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });

        // EXIT STEP
        $this->attach("Bitmarshals\InstantUssd", 'iussd.final', function($e) use ($ussdMenusConfig) {
            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig     = $ussdMenusConfig[$e->getName()];
            // we have data sent in
            // 1. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $this->attachDynamicErrors($e, $menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig, false);
            }
            // 3. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
    }

}
