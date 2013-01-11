<?php

namespace Caseyamcl\GoogleCalendar;
use IteratorAggregate, ArrayObject;
use DateTime;

class EventList implements IteratorAggregate
{
    /**
     * @var array
     */
    private $events;

    /**
     * @var array
     */
    private $eventsByDate;

    /**
     * @var int
     */
    private $pos = 0;

    // --------------------------------------------------------------

    /**
     * Add events and ensure everything is in order
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
        $this->events = $this->fixOrder($this->events);

        $date = $event->date->format('U');
        $this->eventsByDate[$date][] = $event;

        $this->eventsByDate[$date] = $this->fixOrder($this->eventsByDate[$date]);
    }

    // --------------------------------------------------------------

    /**
     * @return array  Keys are UNIX timestamp for date, values are events
     */
    public function getByDate()
    {
        return $this->eventsByDate;
    }

    // --------------------------------------------------------------

    public function getIterator()
    {
        return new ArrayObject($this->events);
    }

    // --------------------------------------------------------------

    private function fixOrder(array $items)
    {
        usort($items, function($a, $b) {
            $aTime = $a->beginTime->format('U');
            $bTime = $b->beginTime->format('U');

            if ($aTime == $bTime) {
                return 0;
            }
            else {
                return ($aTime < $bTime) ? -1 : 1;
            }
        });

        return $items;
    }
}

/* EOF: EventList.php */