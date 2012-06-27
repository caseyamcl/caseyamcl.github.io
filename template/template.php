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
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/04-skeleton.css"  media="all" />
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/05-layout.css"  media="all" />
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/06-print.css"  media="print" />
  <?php echo load_page_specific_css($page_files); ?>
</head>

<body>

  <header>
      <nav class='container' role="navigation">
        
        <h1 class='four columns'>
          <a href='<?php echo $site_url; ?>'>Casey McLaughlin</a>
        </h1>

        <div class='twelve columns'>
        <?php        
          
          $nav = array();

          $nav['resume'] = array(
            'display'     => 'CV'
          );

          $nav['articles'] = array(
            'display'     => 'Articles'
          );

          $nav['code'] = array(
            'display'     => 'Code'
          );

          $nav['calendar'] = array(
            'display'     => 'Calendar'
          );

          echo build_navigation($nav, $site_url, $current_url);
          
        ?>
        </div>
      </nav>
  </header>
  
  <div id="main" class='container'>
      <?php echo $content; ?>
  </div>
  
  <footer>
    <ul>
      <li><a href='http://creativecommons.org/licenses/by-nc-sa/3.0/' class='lb'>cc Casey McLaughlin</a></li>
      <li><a href='http://idiginfo.org/team' title='FSU iDigInfo Team Page'>FSU iDigInfo</a></li>
      <li><a href='http://twitter.com/caseyamcl' title='My Twitter Feed'>Twitter</a></li>
      <li><a href='http://facebook.com/caseyamcl' title='My Facebook Profile'>Facebook</a></li>
      <li><a href='http://github.com/caseyamcl' title='My Code on Github'>Github</a></li>
    </ul>
  </footer>

</body>

</html>
