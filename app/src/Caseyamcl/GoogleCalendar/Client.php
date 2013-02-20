<?php

namespace Caseyamcl\GoogleCalendar;

use \Guzzle\Http\Client as GuzzleClient;
use Guzzle\Common\GuzzleException;
use Guzzle\Http\Exception\BadResponseException;
use InvalidArgumentException;
use DateTime;

/**
 * Google Calendar Scraper
 */
class Client
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
    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    // --------------------------------------------------------------

    /**
     * Get a calendar events from a Google Calendar ID
     *
     * @param string $calendarId  User email address or Calendar ID
     * @param int    $limit       Limit number
     * @param string $timezone    Must be a valid timezone
     * @return EventList
     */
    public function getEvents($calendarId, $limit = 30, $timezone = 'America/New_York')
    {
        //Sanity check
        if ((int) $limit < 1) {
            throw new InvalidArgumentException("Limit must be an integer greater than or equal to one (1)");
        }

        //Build the URL
        $url = sprintf(
            'http://www.google.com/calendar/feeds/%s/public/full?alt=json&orderby=starttime&max-results=%s&singleevents=true&sortorder=ascending&futureevents=true&ctz=%s',
            $calendarId,
            (int) $limit,
            $timezone
        );

        //Do the request
        $req  = $this->guzzle->get($url);
        $resp = $req->send();
        //Google will send a 400 for invalid calendarId format or Timezone Format
        //Google will send a 404 or 403 for a non-existent calendat

        //Parse the JSON or fail
        if ( ! $caldata = json_decode($resp->getBody())) {
            throw new RuntimeException("Cannot parse JSON response from Google");
        }

        //Go through the events list and create an Event object for each
        $eventList = new EventList();

        foreach($caldata->feed->entry as $entry) {

            // 'Y-m-d\TH:i:sO' 'ISO8601'
            // 'Y-m-d\TH:i:sP' 'RFC3339 or W3C or ATOM'



            //Get WHEN and WHERE
            $when  = array_shift($entry->{'gd$when'});
            $where = count($entry->{'gd$where'} > 0) 
                ? array_shift($entry->{'gd$where'})->valueString 
                : null;

            //Parse the JSON entry
            $beginTime = $this->parseGoogleDateTime($when->startTime);
            $endTime   = $this->parseGoogleDateTime($when->endTime);
            $title     = $entry->title->{'$t'};

            //Create the event object
            $event = new Event($beginTime, $title, $endTime);
            if ($where) {
                $event->setLocation($where);
            }

            //Is All Day?  If only the date is specified, then yes
            if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $when->startTime)) {
                $event->setAllDay(true);
            }

            $eventList->addEvent($event);
            unset($event);
        }

        //Return events list
        return $eventList;
    }

    // --------------------------------------------------------------

    /**
     * Get a link for embedding Google Calendar rather than accessing its API
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
     * Parses a Google DateTime string and returns a PHP DateTime object
     *
     * Recognized formats:
     * - Full DateTime::ATOM, with microseconds:    2013-02-21T17:00:00.000-05:00
     * - Full DateTime::ATOM, without microseconds: 2013-02-21T17:00-05:00
     * - Date only:                                 2013-02-21
     *
     * @param string $dateTimeStr  E.g. 2013-02-21T17:00:00.000-05:00
     * @return DateTime
     */
    private function parseGoogleDateTime($dateTimeStr)
    {
        //If it has that weird microsecond thing attached, remove it
        if (preg_match("/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})(\.\d{3})(-\d{2}:\d{2})$/", $dateTimeStr, $matches)) {
            return DateTime::createFromFormat(DateTime::ATOM, $matches[1] . $matches[3]);
        }

        //If it is a standard ATOM/RFC date format, use that
        elseif (preg_match("/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}-\d{2}:\d{2}$/", $dateTimeStr)) {
            return DateTime::createFromFormat(DateTime::ATOM, $dateTimeStr);
        }

        //If it is Y-m-d only, use that
        elseif (preg_match("//", $dateTimeStr)) {
            return DateTime::createFromFormat('Y-m-d', $dateTimeStr);
        }

        //Made it here?  Throw exception.  Cannot parse.
        else {
            throw new InvalidArgumentException("Could not parse Google DateTime string");
        }
    }

}

/* EOF: GoogleCalendarScraper.php */