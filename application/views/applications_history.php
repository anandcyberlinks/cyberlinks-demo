<?php $uri = $this->uri->segment(1); ?>
<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php echo $welcome->loadPo('Applications') ?><small><?php echo $welcome->loadPo('') ?></small>
                <a class="btn btn-success btn-sm" href="<?php echo base_url();?>applications/registration">Create Application</a>
            </h1>
            <!--ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Notification') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('History') ?></li>
            </ol-->
        </section>
                <?php echo $this->session->flashdata('message'); ?>
            <?php if (isset($error) && !empty($error)) { ?><div id="msg_div"><?php echo $error; ?></div><?php } ?>
        <!-- Main content -->
        <section class="content">
            <?php $search = $this->session->userdata('search_form');
            ?></pre>
            <div id="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo $welcome->loadPo('Name') ?></th>
                                            <th><?php echo $welcome->loadPo('Category') ?></th>
                                            <th><?php echo $welcome->loadPo('Version') ?></th>
                                            <th><?php echo $welcome->loadPo('App Key') ?></th>
                                            <th><?php echo $welcome->loadPo('Created On') ?></th>
                                            <th><?php echo $welcome->loadPo('Action') ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($result as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                            <td><?php echo $value->app_name ?></td>
                                            <td><?php echo $value->app_category ?></td>
                                            <td><?php echo $value->app_version ?></td>
                                            <td><?php echo $value->app_key ?></td>
                                            <td><?php echo date('m/d/Y - h:i a', strtotime($value->created)); ?></td>
                                            <td><a href="<?php echo base_url(); ?>applications/registration?edit=<?php echo $value->id;?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit') ?></a>
                                                <a class="confirm" onclick="return delete_application(<?php echo $value->id; ?>);" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete'); ?></button></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>


                                </table>
                                <!-- Pagination start --->
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
                            <!-- Pagination end -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>
            <!--/div-->
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->

<!-- Model player  -->
<div class="modal fade" id="playerModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body"><div align="center" id="jsplayer"></div></div>
        </div>
    </div>
</div>

<script>
</script>
<!--  this div for  jwplyer reponce -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true" onclick='stopvideo("prevElement")'>
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Preview
            </div>
            <div class="modal-body no-padding">        
                <center>   <div id="prevElement"></div></center>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<script>
    function delete_application(id)
    {
        bootbox.confirm("<?php echo $welcome->loadPo('Are you sure you want to delete') . ' ' . $welcome->loadPo('Application'); ?>", function (confirmed)
        {
            if (confirmed)
            {
                location.href = '<?php echo base_url() . $uri; ?>/deleteApplication?id=' + id;
            }
        });
        return false;
    }
</script> 
