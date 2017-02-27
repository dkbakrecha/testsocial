<style type="text/css">
	#remote-img{
		margin-top: 20px;
	}
	#scrapeMsg{
		color: red;
	}

    .label-width-100-per{
        width: 100%;
    }

    .twitter-account-container .checkbox, .linkedin-account-container .checkbox, .fb-account-container .checkbox, .google-account-container .checkbox{
        width: auto;
        display: inline-block;
        padding-right: 15px;
    }

    .twitter-account-container .checkbox label, .linkedin-account-container .checkbox label, .fb-account-container .checkbox label, .google-account-container .checkbox label{
        margin-left: 6px;
        padding-left: 0px;
    }

    .twitter-account-container .checkbox input[type="checkbox"], .linkedin-account-container .checkbox input[type="checkbox"], .fb-account-container .checkbox input[type="checkbox"], .google-account-container .checkbox input[type="checkbox"]{
        position: inherit;
        margin-left: 0px;

    }


</style>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Add Article</h3>
    </div>

    <div class="box-content">

        <?php
        echo $this->Form->create('Article', array(
            'class' => 'site-from',
            'enctype' => 'multipart/form-data'
        ));
        ?>
        
        <div class="row">
            <div class="col-lg-6">
                <?php 
                	echo $this->Form->input('link', array(
                			'type' => 'text',
                            'id' => 'ArticleArticleUrl',
                            'label' => 'Article Url',
                		)
                	); 
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php 
                	echo $this->Form->input('title', array(
                			'type' => 'text'
                		)
                	); 
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->Form->input('description'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php 
                	echo $this->Form->input('media', array(
                			'type' => 'file',
                		)
                	); 
                ?>
            </div>
        </div>

        <div class="row" id="remote-img-preview">
            <div class="col-lg-6">
            	<div id="remote-img"></div>
            	<?php
            		echo $this->Form->input('previewImg', array(
                			'type' => 'hidden',
                			'label' => false,
                			'div' => false,
                			'id' => 'preview-image-url'
                		)
                	); 
            	?>
            	<label for="custom-image">
            		Use this image
            		<?php 
	                	echo $this->Form->input('media_1', array(
	                			'type' => 'checkbox',
	                			'label' => false,
	                			'div' => false,
	                			'id' => 'custom-image',
	                			'class' => 'custom-image'
	                		)
	                	); 
	                ?>
            	</label>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <?php 
                	echo $this->Form->input('schedule_date',array(
                			'id' => 'datepicker',
                            'type' => 'text',
                		)
                	); 
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php 
                	echo $this->Form->input('schedule_time',array(
                			'id' => 'timepicker',
                            'type' => 'text',
                		)
                	); 
                ?>
            </div>
        </div>
        <?php if(isset($twitterAccount) && !empty($twitterAccount)){?>
        <div class="row twitter-account-container">
            <div class="col-lg-6">
                <?php 
                    /*echo $this->Form->input('email_warning_chb', array('type'=>'select', 'multiple'=>'checkbox', 'label'=> __('Email notice'), 'class'=>'multiple-chb', 'options'=> array('title...'=>array( '5'=>'5 days', '15'=>'15 days', '30'=>'30 days', '60'=>'60 days');*/

                    echo $this->Form->input('twitter_account',array(
                            'id' => 'twitter-account',
                            'type' => 'select',
                            'label' => [
                                'text' => 'Twitter Account',
                                'class' => 'label-width-100-per',
                            ],
                            'multiple' => 'checkbox',
                            'options' => $twitterAccount,
                            'selected' => $selectedTwitterAcc
                        )
                    ); 
                ?>
            </div>
        </div>
        <?php }?>

        <?php if(isset($linkedinAccount) && !empty($linkedinAccount)){?>
        <div class="row linkedin-account-container">
            <div class="col-lg-6">
                <?php 
                    echo $this->Form->input('linkedin_account',array(
                            'id' => 'linkedin-account',
                            'type' => 'select',
                            'label' => [
                                'text' => 'LinkedIn Account',
                                'class' => 'label-width-100-per',
                            ],
                            'multiple' => 'checkbox',
                            'options' => $linkedinAccount,
                            'selected' => $selectedLinkedinAcc
                        )
                    ); 
                ?>
            </div>
        </div>
        <?php }?>

        <?php if(isset($fbAccount) && !empty($fbAccount)){?>
        <div class="row fb-account-container">
            <div class="col-lg-6">
                <?php 
                    echo $this->Form->input('fb_account',array(
                            'id' => 'fb-account',
                            'type' => 'select',
                            'label' => [
                                'text' => 'Facebook Account',
                                'class' => 'label-width-100-per',
                            ],
                            'multiple' => 'checkbox',
                            'options' => $fbAccount,
                            'selected' => $selectedFbAcc
                        )
                    ); 
                ?>
            </div>
        </div>
        <?php } ?>

        <?php if(isset($googleAccount) && !empty($googleAccount)){?>
        <div class="row google-account-container">
            <div class="col-lg-6">
                <?php 
                    echo $this->Form->input('google_account',array(
                            'id' => 'google-account',
                            'type' => 'select',
                            'label' => [
                                'text' => 'Google Account',
                                'class' => 'label-width-100-per',
                            ],
                            'multiple' => 'checkbox',
                            'options' => $googleAccount,
                            'selected' => $selectedGoogleAcc
                        )
                    ); 
                ?>
            </div>
        </div>
        <?php } ?>
        <br/><br/>
        
        <div class="box-bottom-butngroup">
            <?php echo $this->Form->submit('save', array('class' => 'box-submitbtn', 'div' => false)); ?>
        </div>
        <?php
        echo $this->Form->end();
        ?>
    </div>
</div>
<script type="text/javascript">
	var url = '<?php echo Router::url(['controller' => 'articles', 'action' => 'getHtml'],true)?>';
	
	$('#datepicker').datepicker({
		orientation: "bottom left",
		startDate: '+1d',
    	autoclose: true
	});

	$('#timepicker').timepicker({ 'scrollDefault': 'now' });

	$('#remote-img-preview').hide();

	$('#custom-image').click(function(){
		if($(this).prop('checked')){
			$('#ArticleMedia').prop('disabled',true);
		}else{
			$('#ArticleMedia').prop('disabled',false);
		}

	});

	$('#ArticleArticleUrl').change(function(){
		var newUrl = $(this).val();
		$('#ArticleArticleUrl').after('<span id="scrapeMsg">Processing url to get data. This may take a while ...</span>');

		if($.trim(newUrl) != ''){
			$.ajax({
			  url: url,
			  data:{url: newUrl},

			})
			  .done(function( data ) {
			  	$('#scrapeMsg').remove();
			    res = JSON.parse(data);
			    if(res.error == 0){
			    	alert(res.msg);

			    	//check title
			    	if($.trim(res.data['title']) != ''){
			    		$('#ArticleTitle').val(res.data['title']);
			    	}

			    	//check for description
			    	if($.trim(res.data['description']) != ''){
			    		$('#ArticleDescription').val(res.data['description']);
			    	}

			    	//check for image
			    	if($.trim(res.data['image']) != ''){
			    		$('#preview-image-url').val(res.data['image']);
			    		$('#remote-img').html('<img src="'+res.data['image']+'" width="300" />');
			    		$('#remote-img-preview').css('display','block');
			    	}

			    }else if(res.error == 1){
			    	alert(res.msg);
			    }else if(res.error == 2){
			    	alert(res.msg);
			    }
			});
		}
	})
</script>