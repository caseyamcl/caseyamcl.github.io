<?php

namespace Caseyamcl\Controller;
use Caseyamcl\GoogleCalendar\Scraper;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Calendar Controller
 */
class Calendar extends ControllerAbstract
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

    protected function init()
    {
        $this->addRoute('calendar', 'index');
    }

    // --------------------------------------------------------------

    public function index()
    {
        try {
            $calEvents = $this->calendar->getEvents($this->calendarId);
        }
        catch (BadResponseException $err) {
            return $this->abort(500, "Error retrieving Google Calendar");
        }

        $data = array(
            'events'        => $calEvents,
            'calendar_link' => $this->calendar->getLink($this->calendarId)
        );

        return $this->render('pages/calendar', $data);
    }
}

/* EOF: Calendar.php */