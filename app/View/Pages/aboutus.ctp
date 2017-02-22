<div class="home-text">
    <h3><?php echo $homeContent['CmsPage']['title']; ?></h3> 

    <div class="content-text">
		<div class="row">
			<div class="col-lg-6">
				<?php echo $homeContent['CmsPage']['content']; ?>
			</div>
			<div class="col-lg-6">
				<?php echo $this->Html->image('glasses-about.jpg',array('class' => 'img-responsive')); ?>
			</div>
		</div>
	</div>
</div>
