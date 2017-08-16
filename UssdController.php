<?php

namespace InstantUssd;

use Bitmarshals\InstantUssd\InstantUssd;
use Zend\Http\PhpEnvironment\Response;
use Bitmarshals\InstantUssd\UssdMenuItem;
use Bitmarshals\InstantUssd\UssdService;
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
        $ussdService       = $instantUssd->getUssdService();
        $eventManager      = $ussdService->getEventManager();

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

        // trim extraneous spaces
        $aTrimmedUssdValues = $ussdService
                ->trimArrayValues(explode('*', $ussdText));

        //------------------ check if we should exit early
        $isExitRequest = $ussdService->isExitRequest($aTrimmedUssdValues);
        if ($isExitRequest === true) {
            return $instantUssd->exitUssd([])
                            ->send();
        }

        //------------------ rid $aTrimmedUssdValues of extraneous values
        $aNonExtraneousUssdValues = $ussdService
                ->removeExtraneousValues($aTrimmedUssdValues);

        // ++----------------- PACKAGE/COMPACT USSD DATA
        $ussdData = $this->packageUssdData($ussdParams, $aTrimmedUssdValues, $aNonExtraneousUssdValues);

        // extract & attach latest reponse and first reponse
        $firstResponse               = $ussdService->getFirstResponse($aNonExtraneousUssdValues);
        $latestResponse              = $ussdService->getLatestResponse($aTrimmedUssdValues);
        $ussdData['latest_response'] = $latestResponse;
        $ussdData['first_response']  = $firstResponse;

        // check if user is starting
        $isEmptyString        = $ussdService
                ->isEmptyString($ussdText);
        // check if user requested home page
        $userRequestsHomePage = (count($aTrimmedUssdValues) &&
                (end($aTrimmedUssdValues) === UssdService::HOME_KEY));

        if ($isEmptyString || $userRequestsHomePage) {
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }

        // check if user is trying to go back
        $isGoBackRequest = $ussdService->isGoBackRequest($aTrimmedUssdValues);
        // should we go back?
        if ($isGoBackRequest === true) {
            $resultGoBak = $instantUssd->goBack($ussdData);
            if ($resultGoBak instanceof Response) {
                return $resultGoBak
                                ->send();
            }
            // Default - show home page if previous_menu missing
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }

        // ++--------- LATEST RESPONSE PROCESSING  -------- */
        // get last served menu_id from database
        $lastServedMenuId = $instantUssd->retrieveLastServedMenuId($ussdData);
        // check we got last_served_menu
        if (empty($lastServedMenuId)) {
            // @todo - error
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }

        // Get $lastServedMenuConfig. The config will used in validation trigger
        // Set $ussdData['menu_id'] to know the specific config to retreive
        $ussdData['menu_id']  = $lastServedMenuId;
        $lastServedMenuConfig = $instantUssd->retrieveMenuConfig($ussdData);
        // check we have $lastServedMenuConfig
        if (empty($lastServedMenuConfig)) {
            // @todo - error
            return $instantUssd->showHomePage($ussdData, 'home_instant_ussd')
                            ->send();
        }

        // ++----------- VALIDATE INCOMING DATA
        // validate incoming data
        $validator = new UssdValidator($lastServedMenuId, $lastServedMenuConfig);
        $isValid   = $validator->isValidResponse($ussdData);
        if (!$isValid) {
            // handle invalid data
            $nextMenuId = $lastServedMenuId;
            return $instantUssd->showNextMenuId($ussdData, $nextMenuId)
                            ->send();
        }

        // ++--------------- SEND VALID DATA FOR PROCESSING 
        // activate incoming data state
        $ussdData['is_incoming_data'] = true;
        $incomingCycleResults         = $eventManager->triggerUntil(function ($result) {
            // data was processed and we should expect a pointer to the next menu
            return ($result instanceof UssdMenuItem);
        }, $lastServedMenuId, $instantUssd, $ussdData);
        // check if we missed a pointer to the next screen
        if (!$incomingCycleResults->stopped()) {
            return $instantUssd->showError($ussdData, "Error. Next screen could not be found.")
                            ->send();
        }

        // try and render the pointer/next screen
        $ussdMenuItem              = $incomingCycleResults->last();
        $isResetToPreviousPosition = $ussdMenuItem->isResetToPreviousPosition();
        // retreive our next menu_id
        $nextMenuId                = $ussdMenuItem->getNextMenuId();
        // check if it's a parent node reset
        if ($isResetToPreviousPosition) {
            $instantUssd->getUssdMenusServedMapper()
                    ->resetMenuVisitHistoryToPreviousPosition($ussdParams['sessionId'], $nextMenuId);
        }
        return $instantUssd->showNextMenuId($ussdData, $nextMenuId)
                        ->send();
    }

    /**
     * 
     * @param array $ussdParams
     * @param array $aTrimmedUssdValues
     * @param array $aNonExtraneousUssdValues
     * @return array
     */
    protected function packageUssdData($ussdParams, $aTrimmedUssdValues, $aNonExtraneousUssdValues) {
        return [
            'phone_number' => $ussdParams['phoneNumber'],
            'session_id' => $ussdParams['sessionId'],
            'service_code' => $ussdParams ['serviceCode'],
            'text' => $ussdParams['text'],
            'a_values_trimmed' => $aTrimmedUssdValues,
            'a_values_non_extraneous' => $aNonExtraneousUssdValues
        ];
    }

}
