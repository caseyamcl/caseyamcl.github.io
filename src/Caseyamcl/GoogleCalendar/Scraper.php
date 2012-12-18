<?php

namespace Caseyamcl\GoogleCalendar;

use \Guzzle\Http\Client;
use Guzzle\Common\GuzzleException;
use Guzzle\Http\Exception\BadResponseException;
use QueryPath;

class Scraper
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
        //Build the URL
        $url = sprintf(
            'https://www.google.com/calendar/htmlembed?src=%s&ctz=%s&mode=AGENDA',
            $calendarId, $timezone
        );  

        //Do the request
        $req  = $this->guzzle->get($url);
        $resp = $req->send();
        //Google will send a 400 for invalid calendarId format or Timezone Format
        //Google will send a 404 for a non-existent calendat


        //Build a querypath object from the response
        //LEFT OFF HERE LEFT OFF HERE - WORKING!!
        $dateSecs = QueryPath::withHTML((string) $resp->getBody(), 'div.date-section');

        foreach($dateSecs as $row) {
            $date = $row->find('div.date');
            var_dump($date->text());

            foreach($row->find('tr.event') as $event) {
                
                var_dump($event->text());

            }

        }

        die();

    }
}

/* EOF: GoogleCalendarScraper.php */