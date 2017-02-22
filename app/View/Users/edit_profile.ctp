<div class="box">
    <div class="box-header">
        <h3 class="box-title">Edit Profile</h3>
    </div>

    <div class="box-content">

        <?php
        echo $this->Form->create('User', array(
            'class' => 'site-from'
        ));
        ?>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->Form->input('name'); ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->Form->input('email',array(
                    'readonly' => true
                )); ?>
            </div>
        </div>

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
    $(document).ready(function () {
        $('textarea#NoteDescription').ckeditor();
    });


    $('#NoteCategoryId').change(function () {
        if ($(this).val() == '') {
            $('#NoteSubCategoryId').html('<option value="">' + "<?php echo __('Select Sub Category'); ?>" + '</option>');
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
                    $('#NoteSubCategoryId').html(retData);
                    //$('.selectpicker').selectpicker('refresh');
                }
            },
            error: function (xhr) {
                alert("<?php echo __('No Subtopic found for this Topic.'); ?>");
            }
        });
    }

</script>