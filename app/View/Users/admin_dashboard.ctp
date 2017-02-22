<div class="warper container-fluid">

    <div class="row">

        <div class="col-md-3">
            <ul class="list-group"> 
                <li class="list-group-item active"> Manage Users <span class="badge">View All</span></li> 
                <li class="list-group-item"> <span class="badge"><?php echo $user_statics['pending']; ?></span> Pending Users </li> 
                <li class="list-group-item"> <span class="badge"><?php echo $user_statics['active']; ?></span> Active Users </li> 
                <li class="list-group-item"> <span class="badge"><?php echo $user_statics['inactive']; ?></span> Inactive Users </li> 
            </ul>
        </div>


        <div class="col-md-3">
            <ul class="list-group"> 
                <li class="list-group-item active"> Quick View</li> 
                <a href="<?php echo $this->Html->url(array('controller' => 'notes', 'action' => 'index')) ?>"><li class="list-group-item"> <span class="badge"><?php echo $user_statics['notes_pending']; ?></span> Pending Notes </li></a> 
                <li class="list-group-item"> <span class="badge"><?php echo $user_statics['question_pending']; ?></span> Pending Questions </li> 
                <li class="list-group-item"> <span class="badge">-</span> ----- </li> 
            </ul>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"> Last 5 Login </div>
                <div class="panel-body">
                    <div class="table-responsive table-bordered">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Last Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //pr($lastlogin_list); 
                                $_i = 1;
                                foreach ($lastlogin_list as $login_list) {
                                    ?>
                                    <tr>
                                        <td><?php echo $_i; ?></td>
                                        <td><?php echo $login_list['User']['email']; ?></td>
                                        <td><?php echo $login_list['User']['last_login']; ?></td>
                                    </tr>
                                    <?php
                                    $_i++;
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
        
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"> Last 5 Test given by </div>
                <div class="panel-body">
                    <div class="table-responsive table-bordered">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Last Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //pr($lastlogin_list); 
                                $_i = 1;
                                foreach ($lastTest_list as $play_list) {
                                    ?>
                                    <tr>
                                        <td><?php echo $_i; ?></td>
                                        <td><?php echo $play_list['User']['email']; ?></td>
                                        <td><?php echo $play_list['Test']['created']; ?></td>
                                    </tr>
                                    <?php
                                    $_i++;
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>