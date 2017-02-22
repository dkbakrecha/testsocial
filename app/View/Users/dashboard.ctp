<div class="row">
    <div class="col-lg-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Dashboard</h3>
            </div>

            <div class="box-content">
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="box">
            <div class="box-content">
                <ul class="side-links">
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'notes','action' => 'add')); ?>"> Submit a Note </a></li>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'questions','action' => 'add')); ?>"> Submit a Question </a></li>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'pages','action' => 'feedback')); ?>"> Make a Feedback </a></li>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'pages','action' => 'help')); ?>"> Help </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>