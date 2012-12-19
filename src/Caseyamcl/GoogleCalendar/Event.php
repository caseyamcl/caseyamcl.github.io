<?php

namespace Caseyamcl\GoogleCalendar;
use DateTime;

/**
 * Google Calendar Event
 */
class Event
{
    /**
     * @var DateTime
     */
    private $startTime;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $location;

    /**
     * @var boolean
     */
    private $allDay;

    // --------------------------------------------------------------

    public function __construct(DateTime $startTime, $summary)
    {
        $this->setStartTime($startTime);
        $this->setSummary($summary);
    }

    // --------------------------------------------------------------

    public function setStartTime(DateTime $dateTime)
    {
        $this->startTime = $dateTime;
    }

    // --------------------------------------------------------------

    public function getStartTime()
    {
        return $This->startTime;
    }

    // --------------------------------------------------------------

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    // --------------------------------------------------------------

    public function getSummary()
    {
        return $this->summary;
    }

    // --------------------------------------------------------------

    public function setLocation($location)
    {
        $this->location = $location;
    }

    // --------------------------------------------------------------

    public function getLocation()
    {
        return $this->location;
    }

    // --------------------------------------------------------------

    public function setAllDay($allDay)
    {
        $this->allDay = (boolean) $allDay;
    }

    // --------------------------------------------------------------

    public function getAllDay()
    {
        return $this->allDay;
    }
}

/* EOF: Event.php */