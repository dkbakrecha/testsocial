<div class="box">
    <div class="box-header">
        <h3 class="box-title">Shared Articles Log</h3>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="dataTable_wrapper">
                <table id="dataTableList" class="display new-registration-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= __('Feed Name'); ?></th>
                            <th><?= __('Article Title'); ?></th>
                            <th><?= __('Shared on'); ?></th>
                            <th><?= __('Social Network'); ?></th>
                            <th><?= __('Status'); ?></th>
                            <th><?= __('Share date'); ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="filter">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
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
                "url": "<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'shared_grid')); ?>",
                "type": "POST",
                "cache": false
            },
            "data": {
                'newval': "2"
            },
            "order": [[0, "desc"]],
            "columns": [
                {
                    "name": "SharedLog.id",
                    "data": "sr_no",
                    "className": 'align-center',
                },
                {
                    "name": "Article.title",
                    "data": "feed_title"
                },
                {
                    "name": "FeedUrl.title",
                    "data": "article_title"
                },
                {
                    "name": "Article.created",
                    "data": "shared_on",
                    "className": 'align-center',
                },
                {
                    "name": "Article.created",
                    "data": "social_type",
                    "className": 'align-center',
                },
                {
                    "name": "Article.created",
                    "data": "status",
                    "className": 'align-center',
                },
                {
                    "name": "Article.created",
                    "data": "updated",
                    "className": 'align-center',
                },
                
            ],
            "columnDefs": [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": [ 4]
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