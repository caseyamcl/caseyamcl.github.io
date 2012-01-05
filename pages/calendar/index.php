<div class="big-calendar-container sixteen columns">
	<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showCalendars=0&amp;showTz=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=caseyamcl%40gmail.com&amp;color=%232952A3&amp;ctz=America%2FNew_York" style=" border-width:0 " width="940" height="600" frameborder="0" scrolling="no"></iframe>
</div>



<div class="medium-calendar-container sixteen columns">
	<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showCalendars=0&amp;showTz=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=caseyamcl%40gmail.com&amp;color=%232952A3&amp;ctz=America%2FNew_York" style=" border-width:0 " width="755" height="600" frameborder="0" scrolling="no"></iframe>
</div>



<div class="little-calendar-container sixteen columns">
	
	<?php 
	
		require_once($page_path . 'ical/SG_iCal.php');
		$ical = @new SG_iCalReader('https://www.google.com/calendar/ical/caseyamcl%40gmail.com/public/basic.ics');
		
		if (is_array($ical->getEvents()))
		{
			//Sort the events by date and only show new ones
			$events = $ical->getEvents();
			usort($events, 'calendar_usort');
			
			//Build the list items
			$items = array();
			foreach($events as $event) {
				
				//If any dates are older than the current date, ignore them
				if ($event->getStart() < time())
					continue;
				
				$item_html = "";
				
				if ($event->isWholeDay())
					$item_html .= "<span class='cal_allday'>All Day</span>";
				else
					$item_html .= "<span class='cal_time'>" . date('G:i', $event->getStart()) . "</span>";
				
				$item_html .= "<span class='cal_title'>". $event->getProperty('summary') ."</span>";
								
				if ($event->getProperty('location'))
					$item_html .= "<span class='cal_loc'>". $event->getProperty('location') ."</span>";
				
				//Get the timestamp for midnight that day
				$idate = strtotime(date('m/d/Y', $event->getStart()));
				
				//echo "<br/>" . $event->getEnd();
				$items[$idate][] = "<li>" . $item_html . "</li>";
			}
			
			//Build the output
			$output = array();
			foreach($items as $date => $ditems) {
				
				$item_html  = "<p class='cal_dateline'>";
				$item_html .= "<span class='cal_dow'>" . date('D', $date) . "</span>";
				$item_html .= "<span class='cal_month'>" . date('M', $date) . "</span>";
				$item_html .= "<span class='cal_date'>" . date('j', $date) . "</span>";
				$item_html .= "<span class='cal_year'>" . date('Y', $date) . "</span>";
				$item_html .= "</p>";
				
				$item_html .= "<ul class='cal_dateitems'>" . implode($ditems) . "</ul>";
				
				$output[] = "<li class='cal_datelist'>" . $item_html . "</li>";
			} 
			
			//Show the output
			
			echo "<ul class='calendar_agenda'>"  . implode("\n", $output) . "</ul>";
		}
		else
		{
			echo "<p>The Calendar is not available at the moment.  Please try again later";
		}
		
		function calendar_usort($a, $b) {
			
			if ($a->getStart() == $b->getStart())
				return 0;
			
			return ($a->getStart() < $b->getStart()) ? -1 : 1;
		}
	?>
	
	
</div>