<!-- app/View/Users/add.ctp -->
<div class="box login-box">
    <h3 class="be-center">Sign Up</h3> 
	
<div class="col-lg-4 col-lg-offset-4">
    <div class="users box-form">
        <?php echo $this->Form->create('User'); ?>
        <fieldset>
            <?php
            echo $this->Form->input('first_name', array('label' => false,'placeholder' => 'First Name'));
            echo $this->Form->input('last_name', array('label' => false,'placeholder' => 'Last Name'));
            echo $this->Form->input('email', array('label' => false,'placeholder' => 'Email Address'));
            echo $this->Form->input('password', array('label' => false,'placeholder' => 'Password'));
            ?>
        </fieldset>
        <?php echo $this->Form->end(__('Submit')); ?>
		<div class="box-form-action">
			<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>">Login</a> | 
			<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lost_password')); ?>">Lost Password</a>
		</div>
    </div>
</div>

</div>