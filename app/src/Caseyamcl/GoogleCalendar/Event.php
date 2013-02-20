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
    private $allDay = false;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param DateTime $beginTimestamp  Mandatory beginning timestamp
     * @param string   $summary         Mandatory summary/title
     * @param DateTime $endTimeStamp    Optional end timestamp
     */
    public function __construct(DateTime $beginTimestamp, $summary, DateTime $endTimestamp = null)
    {
        $this->setStartTime($beginTimestamp);
        $this->setSummary($summary);

        if ($endTimestamp) {
            $this->setEndTime($endTimestamp);
        }
    }

    // --------------------------------------------------------------

    /**
     * Magic Method to get private properties
     *
     * @param string $item
     */
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

    /**
     * Magic method for determining if a class property is set
     *
     * Useful for using Events directly in Twig templates
     *
     * @param string $item  The property name
     */
    public function __isset($item)
    {
        if (in_array($item, array('beginTime', 'endTime'))) {
            return true;
        }
        else {
            return in_array($item, array_keys(get_object_vars($this)));
        }
    }

    // --------------------------------------------------------------

    /**
     * Get formatted time for friendlier time display
     *
     * e.g. (9am or 9:30am or 9:41am)
     *
     * @return string
     */
    public function formattedTime()
    {
        $hour = $this->beginTimestamp->format('g');
        $min  = $this->beginTimestamp->format('i');
        $ampm = $this->beginTimestamp->format('a');

        return ($min == 0)
            ? $hour . $ampm
            : $hour . ':' . $min . $ampm;
    }

    // --------------------------------------------------------------

    /**
     * Set Mandatory Start Time
     *
     * @param DateTime $dateTime
     */
    public function setStartTime(DateTime $dateTime)
    {
        $this->beginTimestamp = $dateTime;

        //Also set date..
        $this->date = DateTime::createFromFormat('Y M d', $dateTime->format('Y M d'));
    }

    // --------------------------------------------------------------

    /**
     * Set Optional End Time
     *
     * @param DateTime $dateTime
     */
    public function setEndTime(DateTime $dateTime)
    {
        $this->endTimestamp;
    }

    // --------------------------------------------------------------

    /**
     * Set Summary (title)
     *
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    // --------------------------------------------------------------

    /**
     * Set Optional Location
     *
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    // --------------------------------------------------------------

    /**
     * Set All Day Indicator
     *
     * @param boolean $allDay
     */
    public function setAllDay($allDay)
    {
        $this->allDay = (boolean) $allDay;
    }
}

/* EOF: Event.php */