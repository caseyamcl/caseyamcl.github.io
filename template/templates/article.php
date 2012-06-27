<?php

  /*@TODO: Use schema.org vocabulary here! */ 
?>
<article class="article">

  <section class="article-title sixteen columns">
    <h1><?php echo $page_title; ?></h1>
    <time pubdate="pubdate" datetime="XX">
      <?php echo (isset($page_meta->date_updated)) ? $page_meta->date_updated : $page_meta->date_published; ?>
    </time>
  </section>

  <section class="article-content twelve columns">
    <?php echo $content; ?>
  </section>
  
  <section class="article-meta four columns">

    <?php if (isset($page_meta->image)): ?>
      <p class="article_picture">
        <img src="<?php echo $current_url . '/' . $page_meta->image; ?>" alt="Article Img" />
      </p>
    <?php endif; ?>
    
    <p class="article_summary">
      <strong>Summary</strong>
      <span>
        <?php echo $page_meta->summary; ?>
      </span>
    </p>
    
    
    <p class="article_date_pub">
      Published: <?php echo $page_meta->date_published; ?>
    </p>
    
    <?php if (isset($page_meta->date_updated)): ?>
      <p class="article_date_rev">
        Revised: <?php echo $page_meta->date_updated; ?>
      </p>
    <?php endif; ?>
    
    <p class="article_versions">
      <strong>Other Formats</strong>
      <ul>
        <li><a href="<?php echo $current_url . '?content_type=application/pdf'; ?>">PDF</a></li>
        <li><a href="<?php echo $current_url . '?content_type=application/xml'; ?>">XML</a></li>
        <li><a href="<?php echo $current_url . '?content_type=application/json'; ?>">JSON</a></li>
      </ul>
    </p>
    
    <p class="article_share"></p>

  </section>  
  
</article>

