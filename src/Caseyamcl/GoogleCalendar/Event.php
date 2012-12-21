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
    private $beginTimestamp;

    /**
     * @var DateTime|null
     */
    private $endTimestamp;

    /**
     * @var DateTime  Derived from beginTimestamp
     */
    private $date;

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

    public function __construct(DateTime $beginTimestamp, $summary, DateTime $endTimestamp = null)
    {
        $this->setStartTime($beginTimestamp);
        $this->setSummary($summary);

        if ($endTimestamp) {
            $this->setEndTime($endTimestamp);
        }
    }

    // --------------------------------------------------------------

    public function __get($item)
    {
        switch ($item) {
            case 'beginTime':
                $item = 'beginTimestamp';
            break;
            case 'endTime':
                $item = 'endTimestamp';
            break;
        }

        return $this->$item;
    }

    // --------------------------------------------------------------

    public function __isset($item)
    {
        if (in_array($item, array('beginTime', 'endTime'))) {
            return true;
        }
        else {
            return (in_array($item, array_keys(get_object_vars($this))));    
        }

        
    }

    // --------------------------------------------------------------

    /**
     * Get frmatted time for short calendar
     *
     * e.g. (9am or 9:30am or 9:41am)
     *
     * @return string
     */
    public formattedTime()
    {
        //LEFT OFF HERE LEFT OFF HERE LEFT OFF HERE
        return null;
    }

    // --------------------------------------------------------------

    public function setStartTime(DateTime $dateTime)
    {
        $this->beginTimestamp = $dateTime;

        //Also set date..
        $this->date = DateTime::createFromFormat('Y M d', $dateTime->format('Y M d'));
    }

    // --------------------------------------------------------------

    public function setEndTime(DateTime $dateTime)
    {
        $this->endTimestamp;
    }

    // --------------------------------------------------------------

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    // --------------------------------------------------------------

    public function setLocation($location)
    {
        $this->location = $location;
    }

    // --------------------------------------------------------------

    public function setAllDay($allDay)
    {
        $this->allDay = (boolean) $allDay;
    }
}

/* EOF: Event.php */