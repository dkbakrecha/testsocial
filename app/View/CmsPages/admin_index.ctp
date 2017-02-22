<div class="warper container-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">
			CMS Content
		</div>
		<div class="panel-body">
			<div class="dataTable_wrapper">
				<table class="table table-striped table-bordered table-hover" id="dataTables-users">
					<thead>
						<tr class="heading" >
							<th style="min-width: 22px;">#ID</th>
							<th>Title</th>
							<th>Content</th>
							<th>Action</th>
						</tr>
					</thead>
					<tfoot>
						<tr class="filter">
							<td></td>
							<td>
								<input class="search_init" type="text" value="" placeholder="Search Name" name="fname">
							</td>
							<td>
								<input class="search_init" type="text" value="" placeholder="Search Name" name="fname">
							</td>
							<td valign="top">
								<input type="button" id="search_button" class="btn btn-success btn-xs" value="Search">
								<input type="button" id="reset_button" class="btn btn-danger btn-xs" value="Reset">
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>	
</div>

<script type="text/javascript">
    var table;
    $(document).ready(function () {

        table = $('#dataTables-users').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [10, 20, 50, 100], //[[2,3,10, 25, 50, -1], [2,3,10, 25, 50, "All"]],
            "pageLength": 10, ////echo $record_pr_pg;,
            //"filter":false,        
            "ajax": '<?php echo $this->Html->url(array("controller" => "cms_pages", "action" => "cmsGrid", "admin" => TRUE)); ?>',
            "columns": [
                {"name": "CmsPage.id", "orderable": false, "searchable": false, 'width': '5%', 'sClass': 'text-center'},
                {"name": "CmsPage.question", 'width': '20%'},
                {"name": "CmsPage.answer", 'width': '60%'},
                {"name": "CmsPage.common", "orderable": false, "searchable": false, 'width': '15%', 'sClass': 'text-center'},
            ],
            "order": [
                [0, "asc"]//4 is here column name
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

    });

    function delete_question(id) {
        bootbox.confirm("Are you sure want to Delete selected Question ?", function (r) {
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
        });
    }
</script>