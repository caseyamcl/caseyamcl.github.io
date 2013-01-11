<?php

namespace Caseyamcl\GoogleCalendar;

use \Guzzle\Http\Client;
use Guzzle\Common\GuzzleException;
use Guzzle\Http\Exception\BadResponseException;
use QueryPath;
use DateTime;

/**
 * Google Calendar Scraper
 */
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
     * Get a calendar events from a Google Calendar ID
     *
     * @param string $calendarId  User email address or Calendar ID
     * @param string $timezone    Must be a valid timezone
     * @return EventList
     */
    public function getEvents($calendarId, $timezone = 'America/New_York')
    {
        //Build the URL
        $url = sprintf(
            'https://www.google.com/calendar/htmlembed?src=%s&ctz=%s&mode=AGENDA',
            $calendarId, 
            $timezone
        );  

        //Do the request
        $req  = $this->guzzle->get($url);
        $resp = $req->send();
        //Google will send a 400 for invalid calendarId format or Timezone Format
        //Google will send a 404 for a non-existent calendat

        //Build a querypath object from the response
        $dateSecs = QueryPath::withHTML((string) $resp->getBody(), 'div.date-section');

        //Setup Events
        $eventList = new EventList();

        //Populate Events Array
        foreach($dateSecs as $row) {
            $date = $row->find('div.date')->text();

            foreach($row->find('tr.event') as $event) {

                //Time/Summary
                $time    = $event->find('td.event-time')->text();
                $summary = $event->find('span.event-summary')->text();

                //Time and Date
                if ($time) {
                    $dateTime = $this->buildDateTime($date, $time);
                    $allDay   = false;
                }
                else {
                    $dateTime = $this->buildDateTime($date, '12am');
                    $allDay   = true;
                }

                //Build Object
                $eventObj = new Event($dateTime, $summary);
                $eventObj->setAllDay($allDay);
                $eventList->addEvent($eventObj);
                unset($eventObj);
            }

        }

        //Return events list
        return $eventList;
    }

    // --------------------------------------------------------------

    /**
     * Get a calendar link for a Google Calendar
     *
     * @param string $calendarId  User email address or Calendar ID
     * @param string $timezone    Must be a valid timezone
     * @return EventList
     */
    public function getLink($calendarId, $timezone = 'America/New_York')
    {
        return (sprintf(
            'https://www.google.com/calendar/embed?src=%s&ctz=%s',
            $calendarId,
            $timezone
        ));
    }

    // --------------------------------------------------------------

    /**
     * Build Date/Time from Date and Time as gathered from getCalendar()
     *
     * @param string $date
     * @param string $time
     * @return \DateTime
     */
    private function buildDateTime($date, $time)
    {
        //Time and AM/PM
        $fixtime = substr($time, 0, -2);
        $ampm = substr($time, -2);

        //Fix time
        if (strpos($fixtime, ':') === false) {
            $fixtime .= ':00';
        }

        $dateTime = $date . ', ' . $fixtime . ' ' . strtolower($ampm);
        return DateTime::createFromFormat('D M j, Y, H:i a', $dateTime);
    }


}

/* EOF: GoogleCalendarScraper.php */