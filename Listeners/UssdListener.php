<?php

namespace InstantUssd\Listeners;

use Bitmarshals\InstantUssd\UssdEvent;
use Bitmarshals\InstantUssd\UssdResponseGenerator;
use Bitmarshals\InstantUssd\UssdMenuItem;
use Bitmarshals\InstantUssd\Response;

/**
 * Description of UssdListener
 *
 * @author David Bwire
 */
class UssdListener {

    /**
     *
     * @var array 
     */
    protected $menuConfig;

    /**
     *
     * @var array 
     */
    protected $ussdMenusConfig;

    /**
     *
     * @var string 
     */
    protected $latestResponse;

    /**
     *
     * @var string 
     */
    protected $lastServedMenu;

    /**
     *
     * @var UssdEvent 
     */
    protected $ussdEvent;

    /**
     *
     * @var UssdResponseGenerator 
     */
    protected $ussdResponseGenerator;

    /**
     * 
     * @param UssdEvent $e
     * @param array $ussdMenusConfig
     */
    public function __construct(UssdEvent $e, array $ussdMenusConfig) {
        $this->ussdEvent             = $e;
        $this->ussdMenusConfig       = $ussdMenusConfig;
        $this->menuConfig            = $ussdMenusConfig[$e->getName()];
        $this->latestResponse        = $e->getLatestResponse();
        $this->lastServedMenu        = $e->getName();
        $this->ussdResponseGenerator = new UssdResponseGenerator();
    }

    /**
     * 
     * @param boolean $continueUssdHops
     * @param boolean $appendNavigationText
     * @return UssdMenuItem|Response
     */
    public function onTrigger($continueUssdHops = true, $appendNavigationText = true) {
        $e = $this->ussdEvent;
        // Override isSkippableScreen method, if we're dealing with a skippable menu
        if ($this->isSkippableScreen()) {
            // stop event propagation so that navigation history is not captured
            $this->ussdEvent->stopPropagation(true);
            $alternativeScreen = $this->getAlternativeScreen();
            if ($alternativeScreen instanceof UssdMenuItem) {
                // return $alternativeScreen thus skipping default screen
                return $alternativeScreen;
            } else {
                // return default screen
                return $this->nextMenu();
            }
        }
        if (!$e->containsIncomingData()) {
            $e->attachDynamicErrors($this->menuConfig);
            $isValidResponse = $e->getParam('is_valid', true);
            $isHomeMenu      = (substr($this->lastServedMenu, 0, 5) == 'home_');
            if ($isValidResponse && $isHomeMenu) {
                // this method should only be called by home menus (menus beginning with home_*)
                $e->getInstantUssd()->clearMenuVisitHistory($e);
            }
            return $this->showScreen($continueUssdHops, $appendNavigationText);
        }
        // Override method & do your processing; save to db; etc
        $this->captureIncomingData();
        // return UssdMenuItem with pointer to the next screen
        return $this->nextMenu();
    }

    /**
     * 
     * @param boolean $continueUssdHops
     * @param boolean $appendNavigationText
     * @return Response
     */
    protected function showScreen($continueUssdHops = true, $appendNavigationText = true) {
        return $this->ussdResponseGenerator
                        ->composeAndRenderUssdMenu($this->menuConfig, $continueUssdHops, $appendNavigationText);
    }

    /**
     * 
     * @return UssdMenuItem
     */
    protected function nextMenu() {
        $e           = $this->ussdEvent;
        // check if we should stop looping
        $stopLooping = $e->getInstantUssd()
                ->shouldStopLooping($this->menuConfig, $e);
        return $this->ussdResponseGenerator
                        ->determineNextMenu($this->lastServedMenu, $this->menuConfig, $this->latestResponse, $stopLooping);
    }

    /**
     * Override this method and add your business logic
     * 
     *  @return void
     */
    protected function captureIncomingData() {
        
    }

    /**
     * Override this method to add logic to check if a screen is skippable
     * 
     * @return boolean
     */
    protected function isSkippableScreen() {
        /* $isSkippableMenu = $this->ussdEvent->getInstantUssd()
          ->getSkippableUssdMenuMapper()
          ->isSkippable(['col_1' => $col1Val,
          'col_2' => $col2Val, 'col_n' => $colNVal], $tableToCheck); */
        return (bool) $this->ussdEvent->getParam('is_skippable', false);
    }

    /**
     * Override this method to manage optional screens/pages
     * 
     * @return UssdMenuItem
     */
    protected function getAlternativeScreen() {
        // set the screen we should show
        $alternativeScreen         = "_exit_";
        // are we going back to an already displayed screen?
        $isResetToPreviousPosition = false;

        $ussdMenuItem = new UssdMenuItem($alternativeScreen);
        $ussdMenuItem->setIsResetToPreviousPosition($isResetToPreviousPosition);
        return $ussdMenuItem;
    }

}
