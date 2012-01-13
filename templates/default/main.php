<?php 

	require_once(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'funcs.php'; 

?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>Casey McLaughlin</title>
	<meta name="description" content="The personal site of Casey McLaughlin">
	<meta name="author" content="Casey McLaughlin">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="<?php echo $template_url; ?>stylesheets/base.css">
	<link rel="stylesheet" href="<?php echo $template_url; ?>stylesheets/skeleton.css">
	<link rel="stylesheet" href="<?php echo $template_url; ?>stylesheets/layout.css">

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<?php echo $template_url; ?>images/favicon.ico">
	<link rel="apple-touch-icon" href="<?php echo $template_url; ?>images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $template_url; ?>images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $template_url; ?>images/apple-touch-icon-114x114.png">

</head>
<body>

	<!-- Primary Page Layout
	================================================== -->
	
	<header>
		<div class="container">

			<div class="six columns">
				<h1>
					<a href="<?php echo $site_url; ?>">Casey McLaughlin</a>
				</h1>
			</div>

			<nav class="ten columns">
				<ul>
					<?php 
						echo draw_menu(
							$site_url, $current_url,
							array(
								'' => 'Home',
								'content' => 'Content',
								'calendar' => 'My Calendar'
							)
						);
					?>
				</ul>
			</nav>		
			
		</div>
		
	</header>


	
	
	<div class="container main">
		
		<?php echo $page_content; ?>
				
	</div> <!-- End Main Container -->
	

	
	
	<footer>
		
		<div class="container">
			
			<p class="sixteen columns">
				<a href="http://creativecommons.org/licenses/by-nc-nd/3.0/" title="Creative Commons Attribution No Commercial No Deriviatives 3.0 Unported License" rel="license"><img src="<?php echo $template_url; ?>images/cc_12x12_gray.png" alt="Creative Commons" /></a>
				Casey McLaughlin
			</p>
					
		</div>
		
	</footer>
	

	<!-- JS
	================================================== -->
	
	<!--[if lt IE 9]>
		<script src="javascripts/respond.js"></script>
	<![endif]-->
		
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script src="javascripts/tabs.js"></script>

<!-- End Document
================================================== -->
</body>
</html>