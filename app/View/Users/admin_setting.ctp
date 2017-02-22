<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span>Manage Setting List</span>
            </div>    
            <div class="panel-body">
                <div style="float:right;">                    
                </div>                        
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-users">
                        <thead>
                            <tr class="heading" >
                                <th>Sr. No.</th>
                                <th>Title</th>
                                <th>Value</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="filter">
                                <td></td>
                                <td>
                                    <input class="search_init" type="text" value="" placeholder="Search Title" name="title"></td>
                                <td>
                                    <input class="search_init" type="text" value="" placeholder="Search Value" name="value"></td>
                                <td>
                                    <input type="button" id="search_button" class="btn btn-success btn-xs" value="Search">                        
                                    <input type="button" id="reset_button" class="btn btn-danger btn-xs" value="Reset"></td>
                            </tr>
                        </tfoot>
                    </table>
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
			"pageLength": <?php echo $record_pr_pg; ?>,
			//"filter":false,        
			"ajax": '<?php echo $this->Html->url(array("controller" => "users", "action" => "settingData", "admin" => TRUE)); ?>',
			"columns": [
				{"name": "Websetting.id", "orderable": false, "searchable": false, 'width': '9%', 'sClass': 'text-center'},
				{"name": "Websetting.title", 'width': '25%', "orderable": false},
				{"name": "Websetting.value", 'width': '21%', "orderable": false},
				{"name": "User.common", "orderable": false, "searchable": false, 'width': '15%', 'sClass': 'text-center'},
			],
			"order": [
				//[1, "desc"]
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

		$('[name="dob"]').datetimepicker({
			language: 'fr',
			weekStart: 1,
			todayBtn: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,
			format: 'mm/dd/yyyy', //'<?php echo Configure::read("Site.admin_date_time_format") ?>'
			//endDate: new Date(new Date().getFullYear()-15, 10 - 1, 25)
		});

		$('[name="date"]').datetimepicker({
			language: 'fr',
			weekStart: 1,
			todayBtn: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,
			format: 'mm/dd/yyyy', //'<?php echo Configure::read("Site.admin_date_time_format") ?>'
			//endDate: new Date(new Date().getFullYear()-15, 10 - 1, 25)
		});
	});

</script>