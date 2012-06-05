<?php include(__DIR__) . DIRECTORY_SEPARATOR . 'functions.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Casey McLaughlin.com</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
  
  <?php echo load_less_css($template_path, $template_url); ?>
</head>

<body>
  
  <header>
      <h1>
        <a href="<?php echo $site_url; ?>">Casey McLaughlin</a>
      </h1>
      <nav role="navigation">
        
        <?php        
          $nav = array();
          $nav[''] = array(
            'display'     => 'Home',
            'description' => 'Home Page'
          );
          echo build_navigation($nav, $site_url, $current_url);          
        ?>
      </nav>
  </header>
  
  <div id="main">
      <?php echo $content; ?>
  </div>
  
  <footer>
  </footer>

</body>

</html>
