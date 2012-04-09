<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Casey McLaughlin.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
  
  <link rel="stylesheet" type="text/css" href="<?php echo $template_url; ?>css/main.css" />
  
</head>

<body>
  
  <header class="clearfix">
    <section class="content clearfix">
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
          
          echo build_navigation($nav, $site_url);          
        ?>
      </nav>
    </section>
  </header>
  
  <div id="main" class="clearfix">
    <section class="content clearfix">
      <?php echo $content; ?>
    </section>
  </div>
  
  <footer class="clearfix">
    <section class="content clearfix">
      cc Casey McLaughlin
    </section>
  </footer>
  
</body>

</html>
