<div class="box">
    <div class="box-header">
        <h3 class="box-title">Manage Articles</h3>
        <div class="pull-right">
            <a href="<?php echo Router::url('add',true); ?>" class="btn btn-primary">Add Article</a>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="dataTable_wrapper">
                <table id="dataTableList" class="display new-registration-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= __('Title'); ?></th>
                            <th><?= __('Feed Type'); ?></th>
                            <th><?= __('Created On'); ?></th>
                            <th><?= __('ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="filter">
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
                "url": "<?php echo $this->Html->url(array('controller' => 'Articles', 'action' => 'article_grid')); ?>",
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
                    "name": "Article.title",
                    "data": "title"
                },
                {
                    "name": "FeedUrl.title",
                    "data": "feed_url"
                },
                {
                    "name": "Article.created",
                    "data": "created_on",
                    "className": 'align-center',
                },
                {
                    "data": "action",
                    "className": 'align-center',
                }
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