<div class="warper container-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">
			Edit Content
			<a class='btn btn-success btn-sm pull-right' href='<?php echo $this->Html->url(array('controller' => 'cms_pages', 'action' => 'index','admin' => true));?>'>Back</a>
		</div>
		
		<div class="panel-body">
			<div class="form-horizontal" >
<?php 
echo $this->Form->create('CmsPage', array("role" => "form")); 
echo $this->Form->hidden('id'); 
?>  
				<div class="form-group">
					<label class="col-sm-2 control-label">Title</label>
					<div class="col-sm-7">
						<?php
						echo $this->Form->input('title', array(
							'class' => 'form-control',
							'placeholder' => 'Title',
							'label' => false,
						));
						?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Content</label>
					<div class="col-sm-7">
						<?php
						echo $this->Form->input('content', array(
							'class' => 'form-control',
							'placeholder' => 'Content',
							'label' => false,
						));
						?>
					</div>
				

				
				<div class="form-group">
					<div class="col-md-5 col-sm-6 col-xs-12">
						<?=
						$this->Form->button(__('Save'), array(
							'class' => 'btn btn-primary btn-flat',
							'type' => 'submit'
						));
						?>
						&nbsp;
						<?=
						$this->Form->button(__('Cancel'), array(
							'class' => 'btn btn-default btn-flat',
							'type' => 'button',
							'onclick' => 'goBack()',
						));
						?>
					</div>
				</div>


<?php echo $this->Form->end(); ?>
			</div>
		</div>

	</div>
	<!-- Warper Ends Here (working area) -->    

	<script>
        $('#QuestionCategoryId').change(function () {
            if ($(this).val() == '') {
                $('#QuestionSubCategoryId').html('<option value="">' + "<?php echo __('Select Sub Category'); ?>" + '</option>');
            } else {
                changeTopic($(this).val());
            }
        });

        function changeTopic(topic) {
            // Fire the ajax
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'findSubCategory')); ?>",
                type: "POST",
                data: {category_id: topic},
                success: function (retData, response) {
                    if (retData != '0') {
                        $('#QuestionSubCategoryId').html(retData);
                        //$('.selectpicker').selectpicker('refresh');
                    }
                },
                error: function (xhr) {
                    alert("<?php echo __('No Subtopic found for this Topic.'); ?>");
                }
            });
        }

	</script>