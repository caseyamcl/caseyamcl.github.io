<h1>Articles</h1>

<p>
  @TODO: Finish This 
  Refer to http://192.168.5.5/casey-sandbox/projects/caseyamcl.com/greenandbrown/?page=articles_short_list
</p>

<?php 

  //Sort posts descending by date
  uasort($page_children, function($a, $b) {
    if ( ! isset($a->meta->date_published)) {
      return -1;
    }
    elseif ( ! isset($b->meta->date_published)) {
      return 1;
    }
    else {
      $a_year = substr($a->meta->date_published, 0, 4);
      $b_year = substr($b->meta->date_published, 0, 4);
      
      if ($a_year == $b_year) {
        return 0;
      }
      else {
        return ($a_year > $b_year) ? -1 : 1;
      }
    }
  });
  
?>

<?php if (count($page_children) > 0): ?>
<div class="article_list">
  <?php 
    $other = array();
    foreach($page_children as $url => $child): 
  ?>
  
  <?php
  
    //Print the year header
    if ( ! isset($child->meta->date_published)) {
      $other[$url] = $child;
      continue;
    }
    elseif ($child->meta->date_published) {
      
      $year = substr($child->meta->date_published, 0, 4);
      
      if ( ! isset($curr_year)) {
        $curr_year = $year;
        echo "<h4>$year</h4>\n";
        echo "<ul>";
      }
      elseif ($year != $curr_year) {
        $curr_year = $year;
        echo "</ul>";
        echo "<h4>$year</h4>\n";
        echo "<ul>\n";
      }
    }
    
    //Print all of the list items:
  ?>
  
  <li>
    <a href="<?php echo $url; ?>">
      <strong>Mon ## -</strong>
      <span class="title"><?php echo $child->title; ?></span>
      <span class="summary"><?php echo $child->meta->summary; ?></span>
    </a>
  </li>
  
  <?php
    echo "</ul>";
    endforeach;
  ?>
</div>

<?php else: ?>

<p>Hmm. There don't seem to be any articles here.  Odd.</p>

<?php endif; ?>
