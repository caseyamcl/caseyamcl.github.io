<?php

namespace Caseyamcl\Controller;
use Caseyamcl\GoogleCalendar\Scraper;

/**
 * Calendar Controller
 */
class Calendar extends General
{
    /**
     * @var Caseyamcl\GoogleCalendar\Scraper
     */
    private $calendar;

    /**
     * @var string
     */
    private $calendarId;

    // --------------------------------------------------------------

    public function __construct(Scraper $calendar, $calendarId = 'caseyamcl@gmail.com')
    {
        $this->calendar   = $calendar;
        $this->calendarId = $calendarId;
    }

    // --------------------------------------------------------------

    public function init()
    {
        $this->addRoute('/calendar', 'getContent');
    }

    // --------------------------------------------------------------

    public function getContent()
    {
        try {
            $calObj = $this->calendar->getCalendar($this->calendarId);
        }
        catch (ClientErrorResponseException $err) {
            //404 usually means bad ID - Do something with it
        }
        catch (ClientErrorResponseException $err) {
            //400 means bad time-zone or other thing - Do something with it
        }
    }

    // --------------------------------------------------------------

    protected function parseCalendarHtml($responseBody)
    {
        //LEFT OFF HERE - Got a response; now need to process it
        
        //
        $title = QueryPath::withHTML($pageContent, 'div.date-section')->text();

    }
}

/* EOF: Calendar.php */