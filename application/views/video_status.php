<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Video') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
            <ol class="breadcrumb">
                       <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                       <li><a href="<?php echo base_url(); ?>video"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Video') ?></a></li>
                       <li class="active"><?php echo $welcome->loadPo('Video')." ".$welcome->loadPo('Status') ?></li>
            </ol>
        </section>
		<div>
			<div id="msg_div">
				<?php echo $this->session->flashdata('message');?>
			</div>	
		</div>
        <!-- Main content -->
        <section class="content">           
            <div id="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab"><?php echo $welcome->loadPo('Flavors') ?></a></li>
                                <li class="pull-right">&nbsp;</li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box box-solid">
                                        <div class="box-body no-padding">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th><?php echo $welcome->loadPo('Title') ?></th>
                                                        <th><?php echo $welcome->loadPo('Category') ?></th>
                                                        <th><?php echo $welcome->loadPo('Flavor Name') ?></th>
                                                        <th><?php echo $welcome->loadPo('Bitrate') ?></th>
                                                        <th><?php echo $welcome->loadPo('Video Bitrate') ?></th>
                                                        <th><?php echo $welcome->loadPo('Resolution') ?></th>
                                                        <th><?php echo $welcome->loadPo('Assigned Time') ?></th>
														<th><?php echo $welcome->loadPo('Preview') ?></th>
                                                        <th><?php echo $welcome->loadPo('Completed Time') ?></th>
                                                        <th><?php echo $welcome->loadPo('Status') ?></th>
                                                    </tr>
                                                    <?php foreach ($status as $status) { //echo '<pre>';print_r($status); ?>
                                                        <tr>
                                                            <td><?php echo $status->title; ?></td>
                                                            <td><?php echo $status->category; ?></td>
                                                            <td><?php echo $status->flavor_name; ?></td>
                                                            <td><?php echo $status->bitrate; ?></td>
                                                            <td><?php echo $status->video_bitrate; ?></td>
                                                            <td><?php echo $status->width . '*' . $status->height; ?></td>
                                                            <td><?php echo date('d/m/Y h:i:s',strtotime($status->assignedTime)); ?></td>
							    
							    <?php  if ($status->status == 'completed') { ?>
								<td><a class="jsplayerVideo" href="#playerModel" data-backdrop="static" data-toggle="modal" data-img-url="<?php echo $status->previewPath; ?>">Preview</a></td>
								<td><?php echo date('d/m/Y h:i:s',strtotime($status->completedTime)); ?></td>
							    <?php } else { ?>
								<td>--</td>
								<td>--</td>
							    <?php } ?>
							    
                                                            
                                                            <td><span class="<?php
                                                                if ($status->status == 'inprocess') {
                                                                    echo 'label label-warning';
                                                                }
                                                                if ($status->status == 'completed') {
                                                                    echo 'label label-success';
                                                                }
                                                                ?>">
                                                                          <?php echo $status->status; ?>
                                                                </span></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>

                                        </div><!-- /.box-body -->
                                    </div><!-- /.box -->
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
                            </div>
                        </div><!-- /.tab-content -->

                    </div><!-- nav-tabs-custom -->
                </div><!-- /.col -->
            </div> <!-- /.row -->
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->

<!-- Model player  -->
<div class="modal fade" id="playerModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick='stopvideo("jsplayerV")'><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $welcome->loadPo('Video')." : ". $welcome->loadPo('Preview'); ?></h4>
            </div>
            <div class="modal-body no-padding">
                <div align="center" id="jsplayerV"></div>
             </div>
        </div>
    </div>
</div>