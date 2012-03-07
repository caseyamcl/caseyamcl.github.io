<div class="big-calendar-container sixteen columns">
	<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showCalendars=0&amp;showTz=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=caseyamcl%40gmail.com&amp;color=%232952A3&amp;ctz=America%2FNew_York" style=" border-width:0 " width="940" height="600" frameborder="0" scrolling="no"></iframe>
</div>



<div class="medium-calendar-container sixteen columns">
	<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showCalendars=0&amp;showTz=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=caseyamcl%40gmail.com&amp;color=%232952A3&amp;ctz=America%2FNew_York" style=" border-width:0 " width="755" height="600" frameborder="0" scrolling="no"></iframe>
</div>



<div class="little-calendar-container sixteen columns">
	
	<?php 
		require_once($page_path . 'simple_html_dom.php');
		
		$cal_html = file_get_html('https://www.google.com/calendar/htmlembed?src=caseyamcl@gmail.com&ctz=America/New_York&mode=AGENDA');		

		$cal_items = array();
		foreach($cal_html->find('div.date-section') as $ds) {
			
			$date = array_shift($ds->find('div.date'))->plaintext;
			$date = DateTime::createFromFormat('D M j, Y H:i', $date . ' 00:00')->format('U');
			
			$date_html  = "<p class='cal_dateline'>";
			$date_html .= "<span class='cal_dow'>" . date('D', $date) . "</span>";
			$date_html .= "<span class='cal_month'>" . date('M', $date) . "</span>";
			$date_html .= "<span class='cal_date'>" . date('j', $date) . "</span>";
			$date_html .= "<span class='cal_year'>" . date('Y', $date) . "</span>";
			$date_html .= "</p>";
				
			$date_items = array();
			foreach($ds->find('tr.event') as $event) {				
				$time = array_shift($event->find('td.event-time'))->plaintext;
				$desc = array_shift($event->find('span.event-summary'))->plaintext;				
								
				if (trim($time) == '')
					$item_html = "<span class='cal_allday'>All Day</span>";
				else
					$item_html = "<span class='cal_time'>" . $time ."</span>";
				
				$item_html .= "<span class='cal_title'>". $desc ."</span>";
				//$item_html .= "<span class='cal_loc'>". $event->getProperty('location') ."</span>";
				
				$date_items[] = "<li>" . $item_html . "</li>";
			}
			
			$cal_items[] = "<li class='cal_datelist'>$date_html<ul class='cal_dateitems'>" . implode("\n", $date_items) . "</ul></li>";
		}
		
		echo "<ul class='calendar_agenda'>" . implode("\n", $cal_items) . "</ul>";
	?>
	
	<p class='calendar_agenda_more_link'>
		<a href="https://www.google.com/calendar/embed?src=caseyamcl%40gmail.com&ctz=America/New_York" title="My Calendar at Google.com"></a>
	</p>
	
</div>