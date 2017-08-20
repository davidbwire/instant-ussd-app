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

        $latestResponse = $this->latestResponse;
        // save to db; etc
        return;
    }

}
