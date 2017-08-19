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
            // 2. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();
            // 3. Check if we should skip this listener
            if (!$e->containsIncomingData()) {
                $e->attachDynamicErrors($menuConfig);
                // clear tracked menus
                $isValidResponse = $e->getParam('is_valid', true);
                if ($isValidResponse) {
                    // this method should only be called by home menus (menus beginning with home_*)
                    $e->getInstantUssd()->clearMenuVisitHistory($e);
                }
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig, true, false);
            }
            // 4. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
        // REGISTER - SELF
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.self', function($e) use ($ussdMenusConfig) {

            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig        = $ussdMenusConfig[$e->getName()];
            // 1. $shouldStopLooping should be called before a call to determineNextMenu
            // $shouldStopLooping = $e->getInstantUssd()->shouldStopLooping($menuConfig, $e);
            // we have data sent in
            // 2. Get latest response; it should be valid
            $latestResponse    = $e->getLatestResponse();
            $lastServedMenu    = $e->getName();

            /* / 3. *             * *---------------- SKIPPABLE MENU EXAMPLE 
              $isSkippableMenu = $e->getInstantUssd()
              ->getSkippableUssdMenuMapper()
              ->isSkippable(['col_1' => $col1Val, 'col_2' => $col2Val, 'col_n' => $colNVal], $tableToCheck);
              if ($isSkippableMenu) {
              // stop propagation so that it is not captured
              $e->stopPropagation(true);
              // return a pointer to the menu we should skip to
              $ussdMenuItem = $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse, $shouldStopLooping);
              return $ussdMenuItem;
              }
              /* -------------------------------------------- */
            if (!$e->containsIncomingData()) {
                /* show this menu */
                $e->attachDynamicErrors($menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            // 4. Do your processing; save to db; etc
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
            // 2. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $e->attachDynamicErrors($menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            $e->getInstantUssd()->getUssdLoopMapper()
                    ->initializeLoopingSession($menuConfig['target_loopset'], $e->getParam('session_id'), $latestResponse);
            // 4. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
        // REGISTER - CLIENT
        $this->attach("Bitmarshals\InstantUssd", 'iussd.register.client', function($e) use ($ussdMenusConfig) {
            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig        = $ussdMenusConfig[$e->getName()];
            // 1.
            $shouldStopLooping = $e->getInstantUssd()->shouldStopLooping($menuConfig, $e);
            // we have data sent in
            // 2. Get latest response; it should be valid
            $latestResponse    = $e->getLatestResponse();
            $lastServedMenu    = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $e->attachDynamicErrors($menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            // 4. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse, $shouldStopLooping);
        });
        // MY ACCOUNT
        $this->attach("Bitmarshals\InstantUssd", 'iussd.my_account', function($e) use ($ussdMenusConfig) {
            if (!$e instanceof UssdEvent) {
                return false;
            }
            $menuConfig     = $ussdMenusConfig[$e->getName()];
            // we have data sent in
            // 2. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $e->attachDynamicErrors($menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig);
            }
            // 4. Do your processing; save to db; etc
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
            // 2. Get latest response; it should be valid
            $latestResponse = $e->getLatestResponse();
            $lastServedMenu = $e->getName();

            if (!$e->containsIncomingData()) {
                /* show this menu */
                $e->attachDynamicErrors($menuConfig);
                return $this->ussdResponseGenerator->composeAndRenderUssdMenu($menuConfig, false);
            }
            // 4. Do your processing; save to db; etc
            // 5. Determine next menu using $lastServedMenu, $menuConfig and $latestResponse
            return $this->ussdResponseGenerator->determineNextMenu($lastServedMenu, $menuConfig, $latestResponse);
        });
    }

}
