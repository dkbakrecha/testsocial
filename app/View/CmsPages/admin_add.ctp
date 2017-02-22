<div class="warper container-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">
			New FAQ
			<a class='btn btn-success btn-sm pull-right' href='<?php echo $this->Html->url(array('controller' => 'faqs', 'action' => 'index','admin' => true));?>'>Back</a>
		</div>
		
		<div class="panel-body">
			<div class="form-horizontal" >
<?php echo $this->Form->create('Faq', array("role" => "form")); ?>  
				<div class="form-group">
					<label class="col-sm-2 control-label">Question</label>
					<div class="col-sm-7">
						<?php
						echo $this->Form->input('question', array(
							'class' => 'form-control',
							'placeholder' => 'Question',
							'label' => false,
						));
						?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Answer</label>
					<div class="col-sm-7">
						<?php
						echo $this->Form->input('answer', array(
							'class' => 'form-control',
							'placeholder' => 'Answer',
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