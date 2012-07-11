<?php

  /*@TODO: Use schema.org vocabulary here! */ 
?>
<article class="article">

  <section class="article-content eleven columns">
    <section class="article-title">
      <h1><?php echo $page_title; ?></h1>
      <time pubdate="pubdate" datetime="XX">
        <?php echo (isset($page_meta->date_updated)) ? $page_meta->date_updated : $page_meta->date_published; ?>
      </time>
    </section>

    <?php echo $content; ?>
  </section>
  
  <section class="article-meta four columns offset-by-one">

    <?php if (isset($page_meta->image)): ?>
      <p class="article-picture">
        <img src="<?php echo $current_url . '/' . $page_meta->image; ?>" alt="Article Img" />
      </p>
    <?php endif; ?>
    
    <p class="article-summary">
      <strong>Summary</strong>
      <span>
        <?php echo $page_meta->summary; ?>
      </span>
    </p>
    
    
    <p class="article-date-pub">
      Published: <?php echo $page_meta->date_published; ?>
    </p>
    
    <?php if (isset($page_meta->date_updated)): ?>
      <p class="article-date-rev">
        Revised: <?php echo $page_meta->date_updated; ?>
      </p>
    <?php endif; ?>
    
    <p class="article-formats">
      <strong>Other Formats</strong>
      <ul>
        <li><a class='pdf' href="<?php echo $current_url . '?content_type=application/pdf'; ?>">PDF</a></li>
        <li><a class='xml' href="<?php echo $current_url . '?content_type=application/xml'; ?>">XML</a></li>
        <li><a class='json' href="<?php echo $current_url . '?content_type=application/json'; ?>">JSON</a></li>
      </ul>
    </p>
    
    <p class="article-share"></p>

  </section>  
  
</article>

