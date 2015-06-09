<aside class="content-wrapper">                
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadPo('Users'); ?>
            <small><?php echo $welcome->loadPo('User') . " " . $welcome->loadPo('List') ?></small>&nbsp;&nbsp;<a href="<?php echo base_url() ?>user/register" class="btn btn-success btn-flat"><i class="fa fa-fw fa-plus-square"></i><?php echo $welcome->loadPo('Add New'); ?></a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Users') ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php echo $this->session->flashdata('message'); ?>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary collapsed-box">
                    <div class="box-header">
                        <div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
			</div>
                        <!-- tools box -->
                        
                        <h3 class="box-title">Search User</h3>
                    </div>
                    <div class="box-body" style="display:none;">
                        <!-- form start -->
                        <?php $search = $this->session->userdata('serach_user'); ?>
                        <form  method="post" action="<?php echo base_url(); ?>user/index" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <div class="input text">
                                            <label for=""><?php echo $welcome->loadPo('User Name') ?></label>
                                            <input type="text" name="username" class="form-control" value="<?php echo (isset($search['username'])) ? $search['username'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('User Name') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <div class="input select">
                                            <label><?php echo $welcome->loadPo('Status') ?></label>
                                            <select name="status" class="form-control">
                                                <option value="">--Select--</option>
                                                <option value="active" <?= (isset($search['status']) && $search['status'] == 'active') ? 'selected' : '' ?> >Active</option>
                                                <option value="inactive" <?= (isset($search['status']) && $search['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                                                <option value="pending" <?= (isset($search['status']) && $search['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <div class="input text">
                                            <label for=""><?php echo $welcome->loadPo('Email') ?></label>
                                            <input type="text" name="email" class="form-control" value="<?php echo (isset($search['email'])) ? $search['email'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('User Name') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <div class="input text">
                                            <label for=""><?php echo $welcome->loadPo('name') ?></label>
                                            <input type="text" name="name" class="form-control" value="<?php echo (isset($search['name'])) ? $search['name'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('User Name') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer" style="display:none;">
                                <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                            </div>
                        </form>
                </div>
            </div><!--/.col (left) -->
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo $welcome->loadPo('User Name'); ?></th>
                                    <th><?php echo $welcome->loadPo('Name'); ?></th>
                                    <th><?php echo $welcome->loadPo('Email'); ?></th>
                                    <th><?php echo $welcome->loadPo('Role'); ?></th>                                   
                                    <th width="12%"><?php echo $welcome->loadPo('Status'); ?></th>
                                    <th><?php echo $welcome->loadPo('Token'); ?></th>
                                    <th><?php echo $welcome->loadPo('Created Date'); ?></th>
                                    <th width="12%"><?php echo $welcome->loadPo('Permission'); ?></th>
                                    <th width="12%"><?php echo $welcome->loadPo('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result as $value) { ?>
                                    <tr>
                                        <td><a href="<?= base_url() . 'layout/profile/' . $value->id ?>" class="profile"><?php echo $value->username; ?></a></td>
                                        <td><?php echo $value->first_name . " " . $value->last_name; ?></td>
                                        <td><?php echo $value->email; ?></td>
                                        <td><?php echo $value->role; ?></td>        
                                        <td><?php if (($value->status) == 'active') { ?>
                                                <a class="status" data="active" href="<?php echo base_url() ?>user/changestatus/?id=<?php echo $value->id; ?>&email=<?= $value->email ?>&domain=<?= $value->domain ?>&status=" title="Click To Inactive"><i class="fa fa-fw fa-check-circle-o"></i>Active&nbsp;&nbsp;</a>
                                            <?php } else {
                                                if (($value->status) == 'pending') {
                                                    echo "Pending";
                                                } else {
                                                    ?> 
                                                    <a class="status" data="inactive" href="<?php echo base_url() ?>user/changestatus/?id=<?php echo $value->id; ?>&email=<?= $value->email ?>&domain=<?= $value->domain ?>&status=" title="Click To Active"><i class="fa fa-fw fa-circle-o"></i> Inactive</a>
                                                <?php } } ?>
                                        </td>
                                        <td><?= $value->token ?></td>
                                        <td><?php echo $value->created; ?></td>
                                        <td><a href="<?= base_url() . 'acl/index/' . $value->id ?>">Permission </a></td>
                                        <td> 
                                            <?php if ($welcome->action_per('DeleteUser', 'user')) { ?>
                                                <a class="confirm_delete" href="<?php echo base_url() . 'user/DeleteUser?id=' . $value->id ?>" ><button class="btn btn-danger btn-sm confirm_delete" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete') ?></button></a>
                                            <?php
                                            }
                                            if ($welcome->action_per('updateprofile', 'user')) {
                                                ?>
                                                &nbsp;
                                                <a href="<?php echo base_url() ?>user/updateprofile/?id=<?php echo $value->id; ?>" ><button class="btn btn-warning btn-sm"><?php echo $welcome->loadPo('Edit') ?></button></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                            <?php
                                if ($this->pagination->total_rows == '0') {
                                    echo "<tr><td colspan=\"8\"><h4>No Record Found</td></tr></h4>";
                                } else {
                            ?>
                            </table>
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
                                echo "Showing <b>" . $off . "-" . $param . "</b> of <b>" . $this->pagination->total_rows . "</b> total results";
                            }
                            ?>
                        </div>
                        <div class="row pull-right">
                            <div class="col-xs-12">
                                <div class="dataTables_paginate paging_bootstrap">
                                    <ul class="pagination"><li><?php echo $links ?></li></ul> 
                                </div>
                            </div>
                        </div>
                    </div>		
                    <!-- Pagination end -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
        </div>
    </section><!-- /.content -->
</aside><!-- /.right-side -->
<script>
    $(".status").click(function () {
        var element = $(this);
        var url = element.attr('href');
        var status = element.attr('data');
        element.html("<img src='<?= base_url() ?>/assets/img/spinner.gif'>");
        $.ajax({
            url: url + status
        }).done(function (res) {
            res = JSON.parse(res);
            //console.log(res.status);
            if (res.status === 'active') {
                element.html("<i class='fa fa-fw fa-circle-o'></i>Inactive");
                element.attr('data', 'inactive');
                element.closest('td').next('td').html('User disabled');
                bootbox.alert('User Successfully Disabled');
            } else {
                element.html("<i class='fa fa-fw fa-check-circle-o'></i>Active");
                element.attr('data', 'active');
                element.closest('td').next('td').html(res.token);
                bootbox.alert('User Successfully Activated and token send');
            }
        })
        return false;
    })
</script>