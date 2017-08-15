<?php

namespace InstantUssd;

use Bitmarshals\InstantUssd\UssdValidator as InstantUssdValidator;

/**
 * Description of UssdValidator
 *
 * @author David Bwire
 */
class UssdValidator extends InstantUssdValidator {

    /**
     * 
     * @param array $ussdData
     * @return boolean
     */
    public function isValidResponse(array &$ussdData) {

        // extract $latestResponse and run your own custom validations
        $latestResponse     = $ussdData['latest_response'];
        // check if the latest response is within valid range
        $isWithinValidRange = parent::isWithinValidRange($latestResponse, $ussdData);
        if (!$isWithinValidRange) {
            return false;
        }

        // Run a custom validator depending on menu_id
        switch ($this->lastServedMenuId) {
            case "enter_full_name":
                $isValid = $this->fullNameValidation($latestResponse, $ussdData);
                break;
            /* -- add your own case statements here an validate -- */
            default:
            //    You may also set custom error
            //    eg $ussdData['error_message'] = "Incorrect password.";
        }
        // set is_valid status to prevent menu from being tracked/saved
        $ussdData['is_valid'] = $isValid;
        return $isValid;
    }

}
