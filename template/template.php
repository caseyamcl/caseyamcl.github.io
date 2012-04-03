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
        <a href="#">
          Casey McLaughlin
          <span>(dot com)</span>
        </a>
      </h1>

      <nav role="navigation">
        <ul>
          <li><a href="#">Articles <span>Stuff I write</span></a></li>
          <li><a href="#">Code <span>and Resources</span></a></li>
          <li><a href="#">Work <span>CV and More</span></a></li>
          <li><a href="calendar">Calendar <span>My Schedule</span></a></li>
        </ul>
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
