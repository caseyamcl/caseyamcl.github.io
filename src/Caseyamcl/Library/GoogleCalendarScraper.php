<?php

namespace Caseyamcl\Library;

use \Guzzle\Http\Client;
use Guzzle\Common\GuzzleException;
use Guzzle\Http\Exception\BadResponseException;
use QueryPath;

class GoogleCalendarScraper
{
    /**
     * @var Guzzle\Http\Client
     */
    private $guzzle;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Guzzle\Http\Client
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    // --------------------------------------------------------------

    /**
     * Get a calendar from a URL
     *
     * @param string $calendarId  User email address or Calendar ID
     * @param string $timezone    Must be a valid timezone
     * @return ____
     */
    public function getCalendar($calendarId, $timezone = 'America/New_York')
    {
        $url = sprintf(
            'https://www.google.com/calendar/htmlembed?src=%s&ctz=%s&mode=AGENDA',
            $calendarId, $timezone
        );  

        //LEFT OFF HERE -- Catch a 404 ERROR or a 400 Error with Guzzle
        //Google will send a 400 for invalid calendarId format or Timezone Format
        //Google will send a 404 for a non-existent calendat
    }
}

/* EOF: GoogleCalendarScraper.php */