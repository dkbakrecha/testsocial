<style type="text/css">
	#remote-img{
		margin-top: 20px;
	}
	#scrapeMsg{
		color: red;
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
                	echo $this->Form->input('article_url', array(
                			'type' => 'text',
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
        <br/><br/><br/>
        <?php /* ?>
        <div class="row">
            <div class="col-lg-6">
                <?php 
                    echo $this->Form->input('rss_feed_url', array(
                            'type' => 'select',
                            'options' => $feed_data,
                            'empty' => 'Select Feed Url'
                        )
                    ); 
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php 
                    $social_type = array(1 => 'Twitter', 'LinkedIn', 'Facebook', 'Google');
                    echo $this->Form->input('social_type', array(
                            'type' => 'select',
                            'options' => $social_type,
                            'empty' => 'Select Social Type'
                        )
                    ); 
                ?>
            </div>
        </div>

        <?php */?>
        <div class="box-bottom-butngroup">
            <?php echo $this->Form->submit('save', array('class' => 'box-submitbtn', 'div' => false)); ?>
            <?php echo $this->Form->button('cancel', array('class' => 'box-cancelbtn')); ?>
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