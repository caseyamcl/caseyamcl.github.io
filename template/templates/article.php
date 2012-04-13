<?php /*@TODO: Use schema.org vocabulary here! */ ?>
<article class="article">
  <h1><?php echo $page_title; ?></h1>

  <section class="article_content">
    <?php echo $content; ?>
  </section>
  
  <section class="article_meta">
    
    <p class="article_picture">
      <img src="http://dummyimage.com/225x225/999/fff" alt="Article Img" />
    </p>
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
    
    <p class="article_discuss_link">Discussion (via Disqus)</p>
    
    <p class="article_stats">
      <strong>Statistics</strong>
      <ul>
        <li>(from Piwik? - only include with pagehits)</li>
      </ul>
    </p>
    
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

