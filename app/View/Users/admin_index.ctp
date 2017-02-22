<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php
                $title = "Manage User List";
                ?>
                <span><?php echo $title; ?></span>
                <a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'users', 'action' => 'add')); ?>" class="btn btn-success btn-xs pull-right">Add User</a>
                <?php
                //echo $this->Html->link('Add User', array('controller' => 'users', 'action' => 'add', 'full_base' => true,
                // ), array( 'class'=>'btn btn-success btn-xs pull-right')) ;
                ?>
            </div>    
            <div class="panel-body">

                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-users">
                        <thead>
                            <tr class="heading" >
                                <th style="min-width: 22px;"><input name="checkall" type="checkbox" class="checkall" value="ON" /></th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email Address</th>
                                <th>Join Date</th>                                
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="filter">
                                <td></td>
                                <td>
                                    <input class="search_init" type="text" value="" placeholder="Search Name" name="fname"></td>
                                <td>
                                    <input class="search_init" type="text" value="" placeholder="Search Name" name="lname"></td>
                                <td >
                                    <input class="search_init" type="text" value="" placeholder="Search Email" name="email"></td>
                                <td >
                                    <input class="search_init" type="text" value="" placeholder="Search Date" name="dob"></td>    
                                <td>
                                    <select class="search_init" type="text" value="" placeholder="Select" name="status"> 
                                        <option value="" selected="selected">Select</option>
                                        <option value="1">Active</option>
                                        <option value="0" >Inactive</option>
                                    </select> 
                                </td>
                                <td valign="top">
                                    <input type="button" id="search_button" class="btn btn-success btn-xs" value="Search">
                                    <input type="button" id="reset_button" class="btn btn-danger btn-xs" value="Reset">
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="col-lg-12">
                        <form method="post" id="sendMailFrom" action="<?php echo $this->Html->url(array('controller' => 'emails', 'action' => 'compose')); ?>">

                            <?php echo $this->Form->hidden('mailAddr', array('id' => 'mailAddr')); ?>
                            <?php echo $this->Form->hidden('ReqFrom', array('value' => 'user')); ?>
                            <?php echo $this->Form->button('Send Mail', array('type' => 'button', 'class' => 'btn btn-primary', 'onCLick' => 'mailSend()')); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $record_pr_pg = trim(Configure::read('Site.admin_record_per_page')); ?> 

<script type="text/javascript">

    var table;
    $(document).ready(function () {

        table = $('#dataTables-users').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [10, 20, 50, 100], //[[2,3,10, 25, 50, -1], [2,3,10, 25, 50, "All"]],
            "pageLength": 10, //echo $record_pr_pg; ?>,
            //"filter":false,        
            "ajax": '<?php echo $this->Html->url(array("controller" => "users", "action" => "userGridData", "admin" => TRUE)); ?>',
            "columns": [
                {"name": "User.id", "orderable": false, "searchable": false, 'width': '4%', 'sClass': 'text-center'},
                {"name": "User.first_name", 'width': '12%'},
                {"name": "User.last_name", 'width': '12%'},
                {"name": "User.email", 'width': '20%'},
                {"name": "User.created", "orderable": true, "searchable": true, 'width': '12%'},
                {"name": "User.status", "orderable": false, "searchable": true, 'width': '8%', 'sClass': 'text-center'},
                {"name": "User.common", "orderable": false, "searchable": false, 'width': '10%', 'sClass': 'text-center'},
            ],
            "order": [
                [4, "desc"]//4 is here column name
            ],
            "language": {
                "sLengthMenu": "Shows _MENU_",
                "oPaginate":
                        {
                            "sNext": '>',
                            "sLast": '>>',
                            "sFirst": '<<',
                            "sPrevious": '<'
                        }
            }
        });

        // Apply the search
        $("#search_button").click(function () {
            table.columns().eq(0).each(function (colIdx) {
                if ($('input,select', table.column(colIdx).footer().length)) {
                    table
                            .column(colIdx)
                            .search($('input,select', table.column(colIdx).footer()).val());


                }
            });
            table.draw();
        });

        //reset search 
        $("#reset_button").click(function () {
            table.columns().eq(0).each(function (colIdx) {
                if ($('input', table.column(colIdx).footer().length)) {
                    $('.search_init', table.column(colIdx).footer()).val("");
                    table
                            .column(colIdx)
                            .search("");

                }
            });
            table.draw();
        });
        //to remove default filter
        $(".dataTables_filter").remove();

        /*
         $('[name="dob"]').datetimepicker({
         // language:  'fr',
         weekStart: 1,
         todayBtn: 0,
         autoclose: 1,
         todayHighlight: 0,
         startView: 2,
         minView: 2,
         bootcssVer: 3,
         forceParse: 0,
         format: 'dd-mm-yyyy', //'<?php //echo Configure::read("Site.admin_date_time_format")  ?>'
         //endDate: new Date(new Date().getFullYear()-15, 10 - 1, 25)
         });
         
         */
    });

    function changeUserStatus(id, status) {

        URL = '<?php echo $this->Html->url(array("controller" => "users", "action" => "change_status", "admin" => TRUE)); ?>';

        $.ajax({
            url: URL,
            type: "POST",
            data: ({id: id, status: status}),
            beforeSend: function (XMLHttpRequest) {
            },
            complete: function (XMLHttpRequest, textStatus) {
                $("#reset_button").click();
            },
            success: function (data) {
                if (data == 1) {
                    $("#list").trigger("reloadGrid");
                } else {
                    bootbox.alert("Error while changing the user status.", function () {
                    });
                }
            }
        });
    }

    function delete_user(id) {
        var r = confirm("Press a button!");

        //bootbox.confirm("Are you sure want to Delete selected User ?", function (r) {
        if (r == true) {

            URL = '<?php echo $this->Html->url(array("controller" => "users", "action" => "deleteUser", "admin" => TRUE)); ?>';

            $.ajax({
                url: URL,
                type: 'POST',
                data: ({id: id}),
                success: function (data) {
                    if (data == 1) {
                        $("#reset_button").click();
                    } else {
                        bootbox.alert("Error while deleting the user.", function () {
                        });
                    }
                }
            });
        }
        //});
    }

    function mailSend() {
        var oTable = $('#dataTables-users').dataTable();
        var rowcollection = oTable.$(".chkBox:checked", {"page": "all"});
        var addrString = "";
        rowcollection.each(function (index, elem) {
            var checkbox_value = $(elem).val();
            //Do something with 'checkbox_value'
            addrString += checkbox_value + ", ";
        });

        $("#mailAddr").val(addrString);

        if (addrString != '') {
            $("#sendMailFrom").submit();
        } else {
            alert('Please select some users to sent Newsletter');
        }
    }

</script>

<!-- <style>
tfoot {
    display: table-header-group;
}
</style> -->