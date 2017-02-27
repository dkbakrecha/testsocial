<div class="box">
    <div class="box-header">
        <h3 class="box-title">Management Panel</h3>
    </div>

    <div class="box-content">

        <?php
        echo $this->Form->create('ToolSetting', array(
            'class' => 'site-from',
        ));
        ?>
        <?php
            foreach ($toolsetting as $setting) {
        ?>

        <div class="row">
            <div class="col-lg-6">
                <?php 
                    echo $this->Form->input($setting['ToolSetting']['setting_key'], array(
                            'type' => $setting['ToolSetting']['field_type'],
                            'label' => $setting['ToolSetting']['setting_title'],
                            'value' => $setting['ToolSetting']['setting_value'],
                        )
                    ); 
                ?>
            </div>
        </div>

        <?php
            }
        ?>

        <br/><br/>
        
        <div class="box-bottom-butngroup">
            <?php echo $this->Form->submit('save', array('class' => 'box-submitbtn', 'div' => false)); ?>
        </div>
        <?php
        echo $this->Form->end();
        ?>
    </div>
</div>