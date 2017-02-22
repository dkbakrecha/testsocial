 <?php echo $this->Session->flash(); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php echo $this->Form->create('Sitesetting', array('role' => 'form','class'=>'form-horizontal')); ?>
        <div class="panel-body"><label class="col-md-12"><?php //echo "Change Setting for value";?></label></div>
        <div class="row">
            <div class="col-lg-10">
                <div class="form-group">
                            <label class="col-md-3 control-label">Value</label>
                            <div class="col-md-7">
                            <?php
                            echo $this->Form->hidden('id');
                            echo $this->Form->input('value', array(
                                'type' => 'text',
                                'maxlength' => '50',
                                'class' => 'form-control',
                                "placeholder" => "Value",
                                'div' => false,
                                'label' => false,
                            ));
                            ?>
                            </div>
                        </div>        
        </div>
        <div class="col-lg-10">
                <div class="form-group">
                    <div class="col-md-3"></div>
                    <div class="col-md-7">
                    <?php
                        echo $this->Form->submit('Submit', array(
                            'div' => FALSE,
                            'class' => 'btn btn-primary',
                    ));
                    ?>
                    <?php 
                        echo $this->Html->link(
                            'Cancel',
                            array('admin'=>TRUE, 'controller'=>'users', 'action' => 'setting'),
                            array('escape' => false,'class'=>'btn btn-warning')
                         ); 
                    ?>
                    </div>
                </div>
            </div>    
    <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">

  /*  var PathUrl = "<?php echo $this->webroot; ?>";
$(document).ready(function(){    
    $('#UserDob').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format:'mm/dd/yyyy',
        endDate: new Date(new Date().getFullYear()-15, 10 - 1, 25)
    });

    $('#UserDob').val('<?php echo date("m/d/Y",strtotime($this->request->data["User"]["dob"]))?>')
})  */
   
</script>


