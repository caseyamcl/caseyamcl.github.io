<section class="one-third column">
		
	<h2>Jump To&hellip;</h2>
	
	<ul>
		<li><a href="#posts_and_articles">Posts and Articles</a></li>
		<li><a href="#">Work Portfolio</a></li>
		<li><a href="#">Stuff from Yore</a></li>
	</ul>
	
	<h2>Also&hellip;</h2>
	
	<ul>
		<li><a href="<?php echo $site_url; ?>content/resume">My Resume / CV</a></li>
		<li><a href="<?php echo $site_url; ?>calendar">My Calendar</a></li>
		<li><a href="<?php echo $site_url; ?>content/code">Code Projects</a></li>
	</ul>
	
</section>

<section class="two-thirds column">
	
	<?php
		$posts =  get_page_lister()->get_types('post')->order_by('date_published DESC')->go();
	?>

	<h3>The Latest</h3>
	
	<?php
		$latest = get_page_lister()->get_types('post')->order_by('date_published DESC')->limit(1)->go(); 
		$latest = array_shift($latest);	
	?>
	
	<ul class="post-list">
		<li>
			<a href="<?php echo $site_url . $latest->page_path; ?>" title="<?php echo $latest->page_meta->title; ?>">
				<span class="post_title"><?php echo $latest->page_meta->title; ?></span>
				<span class="post_desc"><?php echo $latest->page_meta->summary; ?></span>
			</a>
		</li>
	</ul>
	
	<h3 id="posts_and_articles">Posts &amp; Articles</h3>
	
	<ul class="post-list">
		
		<?php foreach($posts as $post): ?>
		
		<li>
			<a href="<?php echo $site_url . $post->page_path; ?>" title="<?php echo $post->page_meta->title; ?>">
				<span class="post_title"><?php echo $post->page_meta->title; ?></span>
				<span class="post_desc"><?php echo $post->page_meta->summary; ?></span>
			</a>
		</li>
		
		<?php endforeach; ?>	
	
</section>