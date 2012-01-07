<section class="two-thirds column">
	
	<?php
	
		$pl = get_page_lister();
		$posts = $pl->get_types('post')->order_by('date_published DESC')->go();
	?>
	
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


<section class="one-third column">
		
	<h3>Jump To&hellip;</h3>
	
	<ul>
		<li><a href="#">Web Development Posts</a></li>
		<li><a href="#">IT Administration Posts</a></li>
		<li><a href="#">Code Projects</a></li>
		<li><a href="#">Work Portfolio</a></li>
		<li><a href="#">Stuff from Yore</a></li>
	</ul>
	
</section>