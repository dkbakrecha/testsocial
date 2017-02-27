<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="form-inline col-lg-12" >
                <?php
                echo $this->Form->create('FeedUrl', array("role" => "form", 'enctype' => 'multipart/form-data', 'class' => 'form-inline'));
                ?>  
                <div class="form-group">
                    <label><strong>Add New RSS Feed Url</strong>&nbsp;&nbsp;</label>
                </div>
                <div class="form-group">
                    <?php
                    echo $this->Form->input('title', array(
                        'class' => 'form-control',
                        'label' => false,
                        'placeholder' => 'Title',
                        'div' => false
                    ));
                    ?>
                </div>
                <div class="form-group">
                    <?php
                        echo $this->Form->input('rss_url', array(
                            'class' => 'form-control',
                            'label' => false,
                            'placeholder' => 'RSS Url',
                            'div' => false
                        ));
                    ?>
                </div>

                <?php
                echo $this->Form->button(__('Add'), array(
                    'class' => 'btn btn-default',
                    'type' => 'submit'
                ));
                ?>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>

    </div>
    <div class="panel-body">

        <div class="row">
            <div class="dataTable_wrapper">
                <table id="dataTableList" class="display new-registration-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= __('Title'); ?></th>
                            <th><?= __('Feed Url'); ?></th>
                            <th><?= __('DATE CREATED'); ?></th>
                            <th><?= __('ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="filter">
                            <th width="5%"></th>    
                            <th width="20%"><input class="search_init" type="text" value="" placeholder="Search By Title" name="answer"></th>
                            <th width="20%"><input class="search_init" type="text" value="" placeholder="Search By Feed Url" name="email"></th>    
                            <th width="15%"></th>    
                            <th width="5%"></th>    
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

        table = $("#dataTableList").dataTable({
            "processing": true,
            "serverSide": true,
            "sDom": '<"top">rt<"wrapper"pi><"clear">',
            "ajax": {
                "url": "<?php echo $this->Html->url(array('controller' => 'feed_urls', 'action' => 'data')); ?>",
                "type": "POST",
                "cache": false
            },
            "data": {
                'newval': "2"
            },
            "order": [[1, "desc"]],
            "columns": [
                {
                    "name": "id",
                    "data": "sr_no",
                    "className": 'align-center',
                },
                {
                    "name": "FeedUrl.title",
                    "data": "title"
                },
                {
                    "name": "FeedUrl.rss_url",
                    "data": "rss_url"
                },
                {
                    "name": "FeedUrl.created",
                    "data": "created",
                    "className": 'align-center',
                },
                {
                    "className": 'align-center',
                }
            ],
            "columnDefs": [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": [ 4]
                },
                {
                    "render": function (data, type, row) { //data-id="'+row.id+'"
                        var st = (row.status == '1') ? 'status-green' : 'status-red';

                        var _title = "";
                        var _action = "";
                        if (row.role == '2' && row.instructor_id == '0') {
                            _title = "Edit Student (Individual)";
                            _action = "studentedit";
                        } else if (row.role == '2' && row.instructor_id != '0') {
                            _title = "Edit Student (Under Instructor)";
                            _action = "students_instructor_edit";
                        } else if (row.role == '4') {
                            _title = "Edit Instructor";
                            _action = "instructoredit";
                        } else if (row.role == '5') {
                            _title = "Edit Practitioner";
                            _action = "practitioneredit";
                        }

                        var dv = '<span class="tbl-row-actions">';
                        dv += '<a href="<?= $this->Html->url(array('controller' => 'users')) ?>/'+ _action +'/' + row.id + '", title = "' + _title + '"><i class="fa fa-pencil"></i></a>'
                        dv += '</span>';
                        return dv;
                    },
                    "targets": 4,
                }
            ]
        });

        $('input.search_init').on('keyup', function () {
            filterGlobal();
        });

        $("select.search_init").change(function () {
            filterGlobal();
        });

        function filterGlobal() {
            //console.log("asd");
            var tbl = table.api();
            tbl.columns().eq(0).each(function (colIdx) {
                if ($('input,select', tbl.column(colIdx).footer().length)) {
                    tbl
                    .column(colIdx)
                    .search($('input,select', tbl.column(colIdx).footer()).val());
                }
            });
            tbl.draw();
        }

        // For width of search element according to bootstrap
        $('.search_init').addClass('form-control input-sm col-xs-12');

    });
    
</script>