<h1>Articles</h1>

<p>Occasionally, I write things :)</p>

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
<div class="article_list_area">
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
        echo "<h3 class='article_list_year'>$year</h3>\n";
        echo "<ul class='article_list'>";
      }
      elseif ($year != $curr_year) {
        $curr_year = $year;
        echo "</ul>";
        echo "<h3 class='article_list_year'>$year</h3>\n";
        echo "<ul class='article_list'>\n";
      }
    }
    
    //Print all of the list items:
  ?>
  
  <li>
    <a href="<?php echo $url; ?>">
      <strong class="date"><?php echo substr($child->meta->date_published, 5); ?> -</strong>
      <span class="title"><?php echo $child->title; ?></span>
      <span class="summary"><?php echo $child->meta->summary; ?></span>
    </a>
  </li>
  
  <?php
    endforeach;
    echo "</ul>";
  ?>
</div>

<?php else: ?>

<p>Hmm. There don't seem to be any articles here.  Odd.</p>

<?php endif; ?>