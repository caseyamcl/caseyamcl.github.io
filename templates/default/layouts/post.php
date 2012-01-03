<div class="two-thirds column post">
	
	<h1><?php echo $page_meta->title; ?></h1>
	<p class="post_topline">
		I wrote this on
		<span class='post-publish-date'><?php echo $page_meta->date_published; ?></span>
	
		<?php if ($page_meta->date_updated): ?>
			and last updated it on <span class='post-update-date'><?php echo $page_meta->date_updated; ?></span>
		<?php endif; ?>
	</p>
	
	<div class="post_content">
		<?php echo $page_content; ?>		
	</div>
		
</div>

<div class="one-third column post-meta">
	
	<h3 class="post-about">About this Post</h3>

	<?php if ($page_meta->image): ?>
		<img src="<?php echo $page_url . $page_meta->image; ?>" class="post-image" alt="Post Image" />
	<?php endif; ?>
	
	<p>
		I wrote this on
		<span class='post-publish-date'><?php echo $page_meta->date_published; ?></span>
	
		<?php if ($page_meta->date_updated): ?>
			and last updated it on <span class='post-update-date'><?php echo $page_meta->date_updated; ?></span>
		<?php endif; ?>
	</p>
	
	<p>
		It is about <span class="post-category"><?php echo $page_meta->category; ?></span>
	</p>
	
	<h6 class="tag-header">Tags</h6>	
	<ul class="tag-list">
		<?php foreach($page_meta->tags as $tag): ?>
			<li><?php echo $tag; ?></li>
		<?php endforeach; ?>
	</ul>

</div>