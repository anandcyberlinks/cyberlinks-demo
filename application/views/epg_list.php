<?php $searchterm = $this->session->userdata('search_epg'); ?>
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Live Channel
            <small>EPG List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url() . 'webtv' ?>">WebTV</a></li>
            <li class="active">EPG</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Search</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <form method="post" action="<?= base_url() . 'livestream/epg/' . $this->uri->segment(3) ?>">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Show Title</label>
                                    <input type="text" name="show_title" placeholder="Show Title" class="form-control" value="<?= (isset($searchterm['show_title'])) ? $searchterm['show_title'] : '' ?>">
                                </div>
                                <div class="col-xs-3">
                                    <label>Show Time</label>
                                    <input type="text" name="show_time" placeholder="Show Time" class="form-control show_time" value="<?= (isset($searchterm['show_time'])) ? $searchterm['show_time'] : '' ?>">
                                </div>
                                <div class="col-xs-3">
                                    <label>Show Description</label>
                                    <input type="text" name="show_description" placeholder="Show Description" class="form-control" value="<?= (isset($searchterm['show_description'])) ? $searchterm['show_description'] : '' ?>">
                                </div>
                                <div class="col-xs-3">
                                    <input type="submit" name="search" value="Search" class="btn btn-success" style="margin-top: 24px">
                                    <input type="submit" name="reset" value="Reset" class="btn btn-warning" style="margin-top: 24px">
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">EPG List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            
                                <tr>
                                    <th>Date</th>
                                    <th>Show Title</th>
                                    <th>Show Time</th>
                                    <th>Show Duration</th>
                                    <th>Show Description</th>
                                    <th>Media Type</th>
                                    <th>Edit</th>
                                </tr>
                            
                                <?php foreach ($result as $val) { ?>
                            
                                    <tr>
                                        <td><?= $val->date ?></td>
                                        <td><?= $val->show_title ?></td>
                                        <td><?= $val->show_time ?></td>
                                        <td><?= $val->show_duration ?></td>
                                        <td><?= $val->show_description ?></td>
                                        <td><?= $val->media_type ?></td>
                                        <td><button class="btn btn-default btn-sm edit_epg">Edit</button></td>
                                    </tr>
                                    
                                    <tr class="edit_form">
                                    <form action="" method="post">
                                        <input type="hidden" name="id" value="<?=$val->id?>" />
                                            <td><?= $val->date ?></td>
                                            <td><input type="text" name="show_title" class="form-control" value="<?= $val->show_title ?>" required="true"></td>
                                            <td><input class="form show_time" type="text" name="show_time" value="<?= $val->show_time ?>" required="true"/></td>
                                            <td><input type="text" name="show_duration" class="form-control duration" value="<?= $val->show_duration ?>" required="true"/></td>
                                            <td><input type="text" name="show_description" class="form-control" value="<?= $val->show_description ?>" /></td>
                                            <td>
                                                <select name="media_type" class="form-control">
                                                    <option value="MP" <?= ($val->media_type=='MP')?'selected' : ''; ?>>MP</option>
                                                    <option value="A" <?= ($val->media_type=='A')?'selected' : ''; ?>>A</option>
                                                </select>
                                        
                                            </td>
                                            <td><button class="btn btn-success btn-sm submit_edit">Save</button></td>
                                    </form>
                                    </tr>
                                    
                                <?php } ?>
                                        
                        </table>
                        <?php
                        if ($this->pagination->total_rows == '0') {
                            echo "<tr><td colspan=\"7\"><h4>" . $welcome->loadPo('No Record Found') . "</td></tr></h4>";
                        } else {
                            ?>
                            </table>
                            <div class="row pull-left">
                                <div class="dataTables_info" id="example2_info"><br>
                                    <?php
                                    $param = $this->pagination->cur_page * $this->pagination->per_page;
                                    if ($param > $this->pagination->total_rows) {
                                        $param = $this->pagination->total_rows;
                                    }
                                    if ($this->pagination->cur_page == '0') {
                                        $param = $this->pagination->total_rows;
                                    }
                                    $off = $this->pagination->cur_page;
                                    if ($this->pagination->cur_page > '1') {
                                        $off = (($this->pagination->cur_page * '10') - 9);
                                    }
                                    echo "&nbsp;&nbsp;Showing <b>" . $off . "-" . $param . "</b> of <b>" . $this->pagination->total_rows . "</b> total results";
                                }
                                ?>
                            </div>
                        </div>	
                        <div class="row pull-right">
                            <div class="col-xs-12">
                                <div class="dataTables_paginate paging_bootstrap">
                                    <ul class="pagination"><li><?php echo $welcome->loadPo($links); ?></li></ul> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</aside>
<script>
    $('.edit_form').hide();
    $('.edit_epg').click(function () {
        var tr = $(this).closest('tr');
        tr.hide();
        tr.next('tr').show();
        $('.edit_epg').addClass('disabled');
    });
    
</script>

