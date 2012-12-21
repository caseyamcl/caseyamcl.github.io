<?php

namespace Caseyamcl\Controller;
use Caseyamcl\GoogleCalendar\Scraper;
use Guzzle\Http\Exception\BadResponseException;

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

    protected function loadRoutes()
    {
        $this->addRoute('/calendar', 'index');
    }

    // --------------------------------------------------------------

    public function index()
    {
        try {
            $calObj = $this->calendar->getCalendar($this->calendarId);
        }
        catch (BadResponseException $err) {
            $this->abort(500, "Error retrieving Google Calendar");
        }

        return $this->loadPage('/calendar', array('calendar' => $calObj), 'calendar');
    }
}

/* EOF: Calendar.php */