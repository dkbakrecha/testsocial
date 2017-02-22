

<section id="primary" class="content-full-width">
	<article class="post-2779 page type-page status-publish hentry" id="post-2779">
		<ul class="side-nav">
			<li class="">
				<a title="Fully Responsive Design" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>">
					Dashboard
					<span class="fa fa-desktop"></span>
				</a>
			</li>
			<li class="current_page_item">
				<a title="Multi News Page Options" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>">
					Profile
					<span class="fa fa-pencil"></span>
				</a>
			</li>
			<li class="">
				<a title="Multi Gallery Page Options" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>">
					Courses
					<span class="fa fa-picture-o"></span>
				</a>
			</li>
			<li class="">
				<a title="Multi Shop Page Options" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>">
					Upgrade
					<span class="fa fa-shopping-cart"></span>
				</a>
			</li>
		</ul>
		<div class="with-side-nav">
			<div class="hr-title">
				<h2>Multi News Page Options</h2>
				<div class="title-sep"><span></span></div>
			</div>
			<p>
				
			<div style="text-align: left;">
				WELCOME To Dashboard || <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>">LOGOUT</a><br>
				
				<?php pr($this->Session->read('Auth')); ?>
				</div>
				<img class="aligncenter size-full wp-image-3267" alt="Blog" src="//designthemes.iamdesigning.netdna-cdn.com/themes/dt-guru/wp-content/uploads/2014/05/multi-news-page.png">
			</p>
			

		</div>
	</article>
</section>
