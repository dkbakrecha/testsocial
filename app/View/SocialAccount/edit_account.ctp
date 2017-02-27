<div class="box">
    <div class="box-header">
        <h3 class="box-title">Add Social Account</h3>
    </div>

    <div class="box-content">

        <?php
        echo $this->Form->create('SocialAccount', array(
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
                            'empty' => 'Select Social Type',
                            'disabled' => true,
                        )
                    ); 
                ?>
            </div>
        </div>

        <div class="box-bottom-butngroup">
            <?php echo $this->Form->submit('save', array('class' => 'box-submitbtn', 'div' => false)); ?>
            <?php echo $this->Form->button('cancel', array('class' => 'box-cancelbtn', 'onClick' => 'return goBack();')); ?>
        </div>
        <?php
        echo $this->Form->end();
        ?>
    </div>
</div>
<script type="text/javascript">
    $('.box-cancelbtn').click(function(e){
            e.preventDefault();
            url = "<?php echo Router::url('account_list', true); ?>";
            console.log(url);
            window.location.href = url;
    });
    function goBack(){
        url = "<?php echo Router::url('account_list', true); ?>";
        console.log(url);
        window.location.href = url;
    }
</script>