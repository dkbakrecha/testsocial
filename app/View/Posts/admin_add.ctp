<?php
echo $this->Html->script(array(
    'ckeditor/ckeditor',
    'ckeditor/adapters/jquery',
));
?>

<div class="warper container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            Add New Post
            <a class='btn btn-purple btn-sm pull-right' href='<?php
            echo $this->Html->url(array('controller' => 'posts', 'action' => 'index',
                'admin' => true));
            ?>'>Back</a>
        </div>
        <div class="panel-body">
            <div class="form-horizontal" >
                <?php echo $this->Form->create('Post', array("role" => "form")); ?>  
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-7">
                        <?php
                        echo $this->Form->input('title', array(
                            'class' => 'form-control',
                            'label' => false,
                            'placeholder' => 'Post Title'
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
                            'label' => false,
                            'placeholder' => 'Content',
                            'type' => 'textarea'
                        ));
                        ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-2 col-sm-4 col-xs-12 control-label">
                        <span></span>
                    </div>
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


    <script type="text/javascript">
        $(document).ready(function () {
            $('textarea#PostContent').ckeditor();
        });
    </script>