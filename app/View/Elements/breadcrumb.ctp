<?php
    //$moduleTitle = "";
?>

<section class="breadcrumb-wrapper">
	<div class="container">
		<h1><?php  ?></h1>
		<div class="breadcrumb">
			<a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'home')); ?>">Home</a>
			<span class="default"> </span>
			<h4><?php echo $this->fetch('title'); ?></h4>
		</div>
	</div>
</section>