<div class="box">
    <div class="box-header">
        <h3 class="box-title">Manage Social Account</h3>
        <div class="pull-right">
            <a href="<?php echo Router::url('add_account',true); ?>" class="btn btn-primary">Add Social Account</a>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="dataTable_wrapper">
                <table id="dataTableList" class="display new-registration-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= __('Account Name'); ?></th>
                            <th><?= __('Social Type'); ?></th>
                            <th><?= __('Feed Url'); ?></th>
                            <th><?= __('Status'); ?></th>
                            <th><?= __('ACTION'); ?></th>
                        </tr>
                        <tr class="filter">
                            <th width="5%"></th>    
                            <th width="20%"><input class="search_init" type="text" value="" placeholder="Search By Name" name="name"></th>
                            <th width="20%"><input class="search_init" type="text" value="" placeholder="Search By Social Type" name="social_type"></th>    
                            <th width="15%"><input class="search_init" type="text" value="" placeholder="Search By Feed " name="feed_url"></th>    
                            <th width="5%"></th>    
                            <th width="10%"></th>    
                        </tr>Url
                    </thead>
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
                "url": "<?php echo $this->Html->url(array('controller' => 'social_account', 'action' => 'account_grid')); ?>",
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
                    "name": "SocialAccount.name",
                    "data": "name"
                },
                {
                    "name": "SocialAccount.social_type",
                    "data": "social_type"
                },
                {
                    "name": "FeedUrl.title",
                    "data": "feed_url",
                    "className": 'align-center',
                },
                {
                    "name": "SocialAccount.status",
                    "data": "status",
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
                console.log(colIdx);
                console.log(tbl);
                console.log(tbl.column(colIdx).length);

                if ($('input,select', tbl.column(colIdx).length)) {
                    tbl
                    .column(colIdx)
                    .search($('input,select', tbl.column(colIdx)).val());
                }
            });
            tbl.draw();
        }

        // For width of search element according to bootstrap
        $('.search_init').addClass('form-control input-sm col-xs-12');

    });
    
</script>