<aside class="right-side">                
    <!-- Content Header (Page header) -->
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
    <div>
        <div id="msg_div">
            <?php echo $this->session->flashdata('message'); ?>
        </div>	
    </div>
    <!-- Main content -->
    <section class="content">
        <?php /*
                        <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary collapsed-box">
                        <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">Search User</h3>
		    </div>
                            <!-- form start -->
                            <?php $search = $this->session->userdata('serach_user');?>
                            <form  method="post" action="<?php echo base_url(); ?>user/index" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                            
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                
                                <div class="box-body" style="display:none;">
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
                                                <select name="category" class="form-control" placeholder="<?php echo $welcome->loadPo('Category') ?>" id="searchCategory">
                                                    <option value=""><?php echo $welcome->loadPo('Select') ?></option>
                                                    <?php foreach ($category as $key=>$val) { ?>
                                                        <option value="<?php echo $key; ?>" <?php
                                                        if (isset($search['category'])) {
                                                            if ($key == $search['category']) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>  ><?php echo $val ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
                                                <input type="text" class="form-control"  id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($search['datepickerstart'])) ? $search['datepickerstart'] : ''; ?>" >											
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                                                <input type="text" class="form-control"  id="datepickerend" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($search['datepickerend'])) ? $search['datepickerend'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer" style="display:none;">
                                        <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                                    <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                    <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                                </div>
                            </form>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
         * */?>
         
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
                                    <th><?php echo $welcome->loadPo('Created Date'); ?></th>
                                    <th width="12%"><?php echo $welcome->loadPo('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result as $value) { ?>
                                    <tr>
                                        <td><a href="<?=  base_url().'layout/profile/'.$value->id?>" class="profile"><?php echo $value->username; ?></a></td>
                                        <td><?php echo $value->first_name . " " . $value->last_name; ?></td>
                                        <td><?php echo $value->email; ?></td>
                                        <td><?php echo $value->role; ?></td>                                       
                                        <td><?php if (($value->status) == 'active') { ?>
                                                <a href="<?php echo base_url() ?>user/changestatus/?id=<?php echo $value->id; ?>&status=active" title="Click To Inactive"><i class="fa fa-fw fa-check-circle-o" style="color: green; font-size: 20px"></i>Active&nbsp;&nbsp;</a>
                                            <?php } else { ?> 
                                                <a href="<?php echo base_url() ?>user/changestatus/?id=<?php echo $value->id; ?>&status=inactive" title="Click To Active"><i class="fa fa-fw fa-circle-o" style="color: red; font-size: 20px"></i> InActive</a><?php } ?></td>
                                        <td><?php echo $value->created; ?></td>
                                        <td>
                                            <a class="confirm" onclick="return delete_user(<?php echo $value->id; ?>);" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete') ?></button></a>
                                            <a class="confirm" href="<?php echo base_url() ?>user/updateprofile/?id=<?php echo $value->id; ?>" ><button class="btn btn-warning btn-sm"><?php echo $welcome->loadPo('Edit') ?></button></a>
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