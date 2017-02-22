<style>
.wrapper_404page {
    background-color: #eeeeee;
    border-radius: 3px;
    display: inline-block;
    padding: 0 15px;
    width: 100%;
}

.wrapper_404page .content-404page{
	float: right;
	margin-top: 40px;
	max-width: 500px;
	text-align: center;
}

.wrapper_404page .content-404page .top-text, .wrapper_404page .content-404page .bottom-text {
    font-size: 133.33%;
}

.wrapper_404page .content-404page .img-404{
	margin: 30px 0;
}

.wrapper_404page .content-404page .button-404{
	padding-top: 40px;
}

.wrapper_404page .content-404page .btn-404{
	border-radius: 3px;
    color: #fff;
    display: inline-block;
    height: 38px;
    line-height: 38px;
    margin: 0 4px 10px;
    padding: 0 20px;
    text-transform: uppercase;
    transition: all 0.3s ease 0s;
}

.wrapper_404page .content-404page .prev-page-btn {
    background-color: #ea3a3c;
}
.wrapper_404page .content-404page .prev-page-btn:hover {
    background-color: #c43031;
}
.wrapper_404page .content-404page .back2home {
    background-color: #3cb7e7;
}
.wrapper_404page .content-404page .back2home:hover {
    background-color: #3094bb;
}
.wrapper_404page .img-right-404 {
    margin-top: 60px;
}
</style>

<div class="container">
	<div class="row">
		<div class="wrapper_404page">
			<div class="col-lg-7 col-md-7">
				<div class="content-404page">
					<p class="top-text">Don't worry you will be back on track in no time!</p>
					<p class="img-404"><img alt="" src="http://demo.magentech.com/themes/sm_supershop/media/wysiwyg/404image/404-img-text.png"></p>
					<p class="bottom-text">Page doesn't exist or some other error occured. Go to our home page or go back previous page</p>
					<div class="button-404">
						<a title="PREVIOUS PAGE" class="btn-404 prev-page-btn" onclick="goBack()" href="javascript:void(0);">PREVIOUS PAGE</a>
						<a title="BACK TO HOMEPAGE" class="btn-404 back2home" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'home')) ?>">BACK TO HOMEPAGE</a>
					</div>
				</div>
			</div>

			<div class="col-lg-5 col-md-5">
				<div class="img-right-404">
					<img alt="" src="http://demo.magentech.com/themes/sm_supershop/media/wysiwyg/404image/404-image.png">
				</div>
			</div>
			<div style="clear:both; height:0px">&nbsp;</div>
			<script>
                function goBack() {
                    window.history.back()
                }
			</script>
		</div>
	</div>
</div>
<?php /*
<h2><?php echo $message; ?></h2>
<p class="error">
	<strong><?php echo __d('cake', 'Error'); ?>: </strong>
	<?php
	printf(
		__d('cake', 'The requested address %s was not found on this server.'), "<strong>'{$url}'</strong>"
	);
	?>
</p>
<?php
if (Configure::read('debug') > 0):
	echo $this->element('exception_stack_trace');
endif;
?>
*/ ?>