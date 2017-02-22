<!--//app/View/Users/login.ctp-->
<div class="row">
	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
<div class="users form">
    <div class="login-panel panel panel-default">
        <div class="panel-heading">Log in</div>
			<div class="panel-body">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <div class="form-group">
        <?php 
            echo $this->Form->input('email',array('class' => 'form-control'));
            ?>
            </div>
            <div class="form-group">
            <?php
            echo $this->Form->input('password',array('class' => 'form-control'));
        ?>
        </div>
        <div class="checkbox">
            <?php echo $this->Form->submit(__('Login'),array('class'=>'btn btn-primary pull-right')); ?>
        </div>
    </fieldset>
<?php echo $this->Form->end(); ?>
            </div>
</div>
    </div>
</div>
</div>