<?php

namespace InstantUssd;

use Bitmarshals\InstantUssd\InstantUssd;
use Zend\Http\PhpEnvironment\Response;
use InstantUssd\UssdValidator;

/**
 * Description of UssdController
 *
 * @author David Bwire
 */
class UssdController {

    /**
     * All incoming USSD requests hit this endpoint
     * 
     * @return string
     */
    public function ussd() {

        // if using a framework extract instant_ussd config from
        // the framework's recommended config file
        $config            = require_once './config/iussd.config.php';
        $instantUssdConfig = $config['instant_ussd'];
        $instantUssd       = new InstantUssd($instantUssdConfig, $this);

        // extract as per framework or use global $_POST
        /* $_POST      = array(
          'text' => '',
          'sessionId' => 'ATUid_a8192b768f275360854a446632ddc08a',
          'phoneNumber' => '+254712688559',
          'serviceCode' => '*483*1#',
          ); */

        // use framework utils to extract data safely
        $ussdParams = $_POST;
        $ussdText   = $ussdParams['text'];

        // package incoming data in a format instant-ussd understands
        $ussdService = $instantUssd->getUssdService($ussdText);
        $ussdData    = $ussdService->packageUssdData($ussdParams);

        // Should we EXIT early?
        $isExitRequest = $ussdService->isExitRequest();
        if ($isExitRequest === true) {
            return $instantUssd->exitUssd([])
                            ->send();
        }
        // Should we SHOW HOME Page?
        $isInitialRequest     = $ussdService
                ->isEmptyString($ussdText);
        $userRequestsHomePage = $ussdService->isExplicitHomepageRequest();
        if ($isInitialRequest || $userRequestsHomePage) {
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }
        // Should we GO BACK?
        $isGoBackRequest = $ussdService->isGoBackRequest();
        if ($isGoBackRequest === true) {
            $resultGoBak = $instantUssd->goBack($ussdData);
            if ($resultGoBak instanceof Response) {
                return $resultGoBak->send();
            }
            // fallback to home page if previous menu missing
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }
        // We are ready to PROCESS LATEST DATA from user's device
        //        
        // get last served menu_id from database
        $lastServedMenuId = $instantUssd->retrieveLastServedMenuId($ussdData);
        // check we got last_served_menu
        if (empty($lastServedMenuId)) {
            // fallback to home page
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }
        // Get $lastServedMenuConfig. The config will used in validation trigger
        // Set $ussdData['menu_id'] to know the specific config to retreive
        $ussdData['menu_id']  = $lastServedMenuId;
        $lastServedMenuConfig = $instantUssd->retrieveMenuConfig($ussdData);
        // check we have $lastServedMenuConfig
        if (empty($lastServedMenuConfig)) {
            // fallback to home page
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }
        // VALIDATE incoming data
        $validator = new UssdValidator($lastServedMenuId, $lastServedMenuConfig);
        $isValid   = $validator->isValidResponse($ussdData);
        if (!$isValid) {
            // handle invalid data
            $nextMenuId = $lastServedMenuId;
            // essentially we're re-rendering the menu with error message
            return $instantUssd->showNextMenuId($ussdData, $nextMenuId)
                            ->send();
        }
        // send valid data FOR PROCESSING. Save to db, etc
        // this step should give us a pointer to the next screen
        $nextMenuId = $instantUssd->processIncomingData($lastServedMenuId, $ussdData);
        if (empty($nextMenuId)) {
            // we couldn't find the next screen
            return $instantUssd->showError($ussdData, "Error. Next screen could not be found.")
                            ->send();
        }
        // we have the next screen; SHOW NEXT SCREEN
        return $instantUssd->showNextMenuId($ussdData, $nextMenuId)
                        ->send();
    }

}
