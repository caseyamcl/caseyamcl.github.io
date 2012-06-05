<?php include(__DIR__) . DIRECTORY_SEPARATOR . 'functions.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Casey McLaughlin.com</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
  
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/01-reset.css" media="all" />
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/02-base.css"  media="all" />
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/03-typography.css" media="all"  />
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/04-layout.css"  media="all" />
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/05-print.css"  media="print" />
</head>

<body>
  
  <header>
      <h1><a href="<?php echo $site_url; ?>">Casey McLaughlin</a></h1>
      <h2>IT, Development, Systems Administration, and Life in Tallahassee, Florida</h2>
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
