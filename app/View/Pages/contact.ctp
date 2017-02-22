<div class="home-text">
    <h3>Drop A <span>message</span></h3> 

    <div class="content-text">
<div class="col-lg-8 col-lg-offset-2">
    <div class="users form">
        <?php echo $this->Form->create('Contact',array('class'=>'box-form')); ?>
            <div class="col-lg-6"><?php echo $this->Form->input('first_name',array('label' => false, 'placeholder' => 'First Name')); ?></div>
            <div class="col-lg-6"><?php echo $this->Form->input('last_name',array('label' => false, 'placeholder' => 'Last Name')); ?></div>
            <div class="col-lg-6"><?php echo $this->Form->input('email',array('label' => false, 'placeholder' => 'Email Address')); ?></div>
            <div class="col-lg-6"><?php echo $this->Form->input('phone_no',array('label' => false, 'placeholder' => 'Phone Number')); ?></div>
            <div class="col-lg-12"><?php echo $this->Form->input('message',array('type' => 'textarea','label' => false, 'placeholder' => 'Message')); ?></div>
            
        <?php echo $this->Form->end(__('Submit Now')); ?>
    </div>
</div>    
</div>
</div>