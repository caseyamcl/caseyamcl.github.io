<?php include(__DIR__) . DIRECTORY_SEPARATOR . 'functions.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Casey McLaughlin.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
  
  <?php echo load_less_css($template_path, $template_url); ?>
  <?php /* echo load_page_specific_css($page_files);*/ ?>
</head>

<body>
  
  <header>
    <section class="content">
      <h1>
        <a href="<?php echo $site_url; ?>">
          Casey McLaughlin
          <span>(dot com)</span>
        </a>
      </h1>

      <nav role="navigation">
        
        <?php        
          $nav = array();
          
          $nav['articles'] = array(
            'display'     => 'Articles',
            'description' => 'Stuff I Write'
          );
          
          $nav['code'] = array(
            'display'     => 'Code',
            'description' => 'and Resources'
          );
          
          $nav['work'] = array(
            'display'     => 'Work',
            'description' => 'CV and More'
          );
          
          $nav['calendar'] = array(
            'display'     => 'Calendar',
            'description' => 'My Schedule'
          );
          
          echo build_navigation($nav, $site_url, $current_url);          
        ?>
      </nav>
    </section>
  </header>
  
  <div id="main">
    <section class="content">
      <?php echo $content; ?>
    </section>
  </div>
  
  <footer>
    <section class="content">
      cc Casey McLaughlin
    </section>
  </footer>
  
</body>

</html>
