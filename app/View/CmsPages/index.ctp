<style>

</style>

<section class="testimonial_wrapper">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-8 clearfix">
				<?php
				foreach ($faqList as $faq)
				{
					?>
					<div class="testimonial-info">
						<?php echo $faq['Faq']['question']; ?>	
					</div>
					<div class="testinomail-head">
						<strong><?php echo $faq['Faq']['answer']; ?>	</strong>
						<!--<span>designation</span>-->
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>

</section>