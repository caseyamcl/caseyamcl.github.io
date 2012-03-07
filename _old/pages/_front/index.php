<section class="two-thirds column">
	<p class="big_text">
		I am a developer and IT Administrator in Tallahassee, Florida.
		This is my personal site.
	</p>

	<p>
		I am a faculty practicioner at the Institute for Digital Information and
		Scientific Communication at the Florida State University, where I work
		in information management, web application development, systems administration,
		and team leadership.  I also enjoy teaching Advanced Web Development
	</p>
	
	<?php 
		$post = get_page_lister()->get_types('post')->order_by('date_published DESC')->limit(1)->go(); 
		$post = array_shift($post);
	?>

	<h3>
		What's on the Site		
	</h3>
	
	<ul class="post-list">
		<li>
			<a href="<?php echo $site_url . $post->page_path; ?>" title="<?php echo $post->page_meta->title; ?>">
				<span class="post_title">Latest Post</span>
				<span class="post_desc"><?php echo $post->page_meta->title; ?></span>
				<span class="post_date"><?php echo $post->page_meta->date_published; ?></span>
			</a>
		</li>
	</ul>
	
	<ul class="floating post-list">
		<li>
			<a href="<?php echo $site_url; ?>content/resume" title="My CV">
				<span class="post_title">My Resume</span>
				<span class="post_desc">AKA my Curriculum Vita</span>
			</a>
		</li>
		<li>
			<a href="<?php echo $site_url; ?>calendar" title="Google Calendar">
				<span class="post_title">My Calendar</span>
				<span class="post_desc">See what I'm up to</span>
			</a>
		</li>
		<li>
			<a href="<?php echo $site_url; ?>content" title="Content">
				<span class="post_title">Posts and other Content</span>
				<span class="post_desc">Articles, ramblings, etc.</span>
			</a>
		</li>
		<li>
			<a href="<?php echo $site_url; ?>content/code" title="Code">
				<span class="post_title">Code</span>
				<span class="post_desc">I write code.  This is a list of my projects.</span>
			</a>
		</li>
	</ul>
	
</section>

<section class="one-third column">
	<img class="sidebar-image" src="<?php echo $page_url; ?>fp_image.jpg" alt="Picture of Me" />	

	<h3 style="margin-top: 20px;">
		Around the web
	</h3>

	<ul class="around-the-web">
		<li class="lin"><a href="http://www.linkedin.com/in/caseyamcl" title="LinkedIn Profile">LinkedIn</a></li>
		<li class="fsu"><a href="http://idiginfo.net" title="FSU iDigInfo">FSU iDigInfo</a></li>
		<li class="sof"><a href="http://stackoverflow.com/users/143201/caseyamcl" title="Stack Overflow">StackOverflow</a></li>
		<li class="ghb"><a href="http://github.com/caseyamcl" title="Github">Github</a></li>
		<li class="twt"><a href="http://twitter.com/caseyamcl" title="Twitter">Twitter</a></li>
		<li class="fbk"><a href="http://facebook.com/caseyamcl" title="Facebook">Facebook</a></li>
		<li class="dpl"><a href="http://drupal.org/user/1545572" title="Drupal">Drupal</a></li>
	</ul>

</section>