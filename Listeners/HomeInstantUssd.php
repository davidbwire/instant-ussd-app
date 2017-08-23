<?php

namespace InstantUssd\Listeners;

use Bitmarshals\InstantUssd\Listeners\UssdEventListener;

/**
 * Description of HomeInstantUssd
 *
 * @author David Bwire
 */
class HomeInstantUssd extends UssdEventListener {

    public function captureIncomingData() {

        $ussdEvent = $this->ussdEvent;

        // client's phone number
        $phoneNumber = $ussdEvent->getParam('phone_number');
        // session_id
        $sessionId = $ussdEvent->getParam('session_id');
        // service code
        $serviceCode = $ussdEvent->getParam('service_code');
        // incoming response
        $latestResponse = $this->latestResponse;
        // menu sending in data
        $lastServedMenu = $this->lastServedMenu;
        // configuration of menu sending in data
        $menuConfig = $this->menuConfig;
        // entire ussd_menus config
        $ussdMenusConfig = $this->ussdMenusConfig;
        // all responses sent in this session
        $allResponses = $ussdEvent->getNonExtraneousValues();
        // very first response
        $firstResponse = $ussdEvent->getFirstResponse();
        // your controller instance
        $yourController = $ussdEvent->getInitializer();
        // service locator
        $serviceLocator = $ussdEvent->getServiceLocator();
        // InstantUssd instance
        $instantUssd = $ussdEvent->getInstantUssd();
        // save to db; etc
        return;
    }

}
