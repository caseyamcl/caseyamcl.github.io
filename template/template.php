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
          <span class="fname">Casey</span>
          <span class="lname">McLaughlin</span>
          <span class="dotcom">(dot com)</span>
        </a>
      </h1>

      <nav role="navigation">
        
        <?php        
          $nav = array();
          
          $nav[''] = array(
            'display'     => 'Home',
            'description' => 'Home Page'
          );
          
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
        
      <img src="<?php echo $template_url; ?>images/headshot.jpg" alt="Picture of Casey" />

      <ul>
        <li>
          <a class="cc_footer_link" href="http://creativecommons.org" title="Creative Commons License">(cc) Casey McLaughlin</a>
        </li>
        <li>
          <a class="fb_footer_link" href="http://facebook.com" title="My Facebook Page">I'm on Facebook</a>
        </li>
        <li>
          <a class="tw_footer_link" href="http://twitter.com/caseyamcl" title="My Twitter Profile">I'm on Twitter</a>
        </li>
      </ul>

    </section>
  </footer>
  
</body>

</html>
