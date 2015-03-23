<aside class="right-side">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadPo('Customers'); ?>
          <small><?php echo $welcome->loadPo('Customers')." ".$welcome->loadPo('List') ?></small>&nbsp;&nbsp;
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Customers') ?></li>
        </ol>
    </section>
    <div>
        <div id="msg_div">
            <?php echo $this->session->flashdata('message'); ?>
        </div>	
    </div>
    <!-- Main content -->
    <section class="content">
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
                                    <th><?php echo $welcome->loadPo('Gender'); ?></th>
                                    <th><?php echo $welcome->loadPo('DOB'); ?></th>
                                    <th><?php echo $welcome->loadPo('Contact'); ?></th>
                                    <th><?php echo $welcome->loadPo('Image'); ?></th>
                                    <th width="12%"><?php echo $welcome->loadPo('Status'); ?></th>
                                    <th><?php echo $welcome->loadPo('Created Date'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result as $value) { ?>
                                    <tr>
                                        <td><?php echo $value->username; ?></td>
                                        <td><?php echo ucfirst($value->first_name) . " " . ucfirst($value->last_name); ?></td>
                                        <td><?php echo $value->email; ?></td>
                                        <td><?php echo ucfirst($value->gender); ?></td>
                                        <td><?php echo $value->dob; ?></td>
                                        <td><?php echo $value->contact_no; ?></td>
                                        <td> <image height="80px" src="<?php echo $value->image; ?>"> </td>
                                        <td><?php if (($value->status) == 'active') { ?>
                                                <i class="fa fa-fw fa-check-circle-o" style="color: green; font-size: 20px"></i>Active&nbsp;&nbsp;
                                            <?php } else { ?> 
                                                <i class="fa fa-fw fa-circle-o" style="color: red; font-size: 20px"></i> InActive<?php } ?>
                                        </td>
                                        <td><?php echo $value->created; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <!-- Pagination start --->
                            <?php if ($this->pagination->total_rows == '0') {
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