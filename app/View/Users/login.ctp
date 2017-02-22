<!--//app/View/Users/login.ctp-->
<div class="box login-box">
    <h3 class="be-center">Log In</h3> 

<div class="col-lg-4 col-lg-offset-4">
    <div class="users box-form">
        <?php echo $this->Session->flash('auth'); ?>
        <?php echo $this->Form->create('User'); ?>
        <fieldset>
            <?php
            echo $this->Form->input('email', array('label' => false,'placeholder' => 'Username or Email'));
            echo $this->Form->input('password', array('label' => false,'placeholder' => 'Password'));
            ?>
        </fieldset>
        <?php echo $this->Form->end(__('Login')); ?>
		<div class="box-form-action">
			<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register')); ?>">Register</a> | 
			<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lost_password')); ?>">Lost Password</a>
		</div>
    </div>
</div>

</div>

<script type="text/javascript">
    $('#UserLoginForm').validate({
        rules: {
            'data[User][email]': {
                required: true,
            },
            'data[User][password]': {
                required: true,
            }
        },
        messages: {
            'data[User][email]': {
                required: "Please enter your correct email address or contact number",
            },
            'data[User][password]': {
                required: "Please enter your password",
            }
        }
    });
</script>