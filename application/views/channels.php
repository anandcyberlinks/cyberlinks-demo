<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Channels') ?><small><?php echo $welcome->loadPo('Control panel') ?></small>
                <a class="btn btn-success" href="<?php echo base_url() . 'webtv/add_channels'; ?>">Add Channel</a>
                

                <div class="btn-group">
                    <button class="btn btn-info" type="button">Action</button>
                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle" type="button">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul role="menu" class="dropdown-menu">
                        <li><a class="confirmc" href="<?php echo base_url() . 'webtv/publishplaylist/all'; ?>">Publish All</a></li>
                        <li><a class="confirmc" href="<?php echo base_url() . 'webtv/unpublishplaylist/all'; ?>">UnPublish All</a></li>
                        <li class="divider"></li>
                        <li><a class="confirmc" href="<?php echo base_url() . 'webtv/resetplaylist/all'; ?>">Reset Dynamic Playlist</a></li>
                    </ul>
                </div>


            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('WebTV Playlists') ?></li>
            </ol>
        </section>
        <div>
            <div id="msg_div">
                <?php echo $this->session->flashdata('message'); ?>
            </div>	
            <?php if (isset($error) && !empty($error)) { ?><div id="msg_div"><?php echo $error; ?></div><?php } ?>
        </div>
        <!-- Main content -->
        <section class="content">
            <?php $search = $this->session->userdata('search_form'); ?>
            <div id="content">

                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary collapsed-box">
                            <div class="box-header">
					<!-- tools box -->
					<div class="pull-right box-tools">
					    <button title="Collapse" data-toggle="tooltip" data-widget="collapse" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i></button>
					</div><!-- /. tools -->
					<h3 class="box-title">Search</h3>
				    </div><!-- /.box-header -->
                            <!-- form start -->
                            <form  method="post" action="<?php echo base_url(); ?>webtv/index" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <div class="box-body" style='display:none;'>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for=""><?php echo $welcome->loadPo('Name') ?></label>
                                                <input type="text" name="name" class="form-control" value="<?php echo (isset($search['name'])) ? $search['name'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Channel Name') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('Number') ?></label>
                                                <input type="number" class="form-control" name="number" placeholder="<?php echo $welcome->loadPo('Channel Number') ?>" value="<?php echo (isset($search['number'])) ? $search['number'] : ''; ?>" >											
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('Type') ?></label>
                                                <select name="type" class="form-control">
                                                    <option value="">Select Type</option>
                                                    <option value="Linear" <?php if (isset($search['type']) && $search['type'] == 'Linear') {
                echo 'selected';
            } ?>>Linear</option>
                                                    <option value="Live" <?php if (isset($search['type']) && $search['type'] == 'Live') {
                echo 'selected';
            } ?>>Live</option>
                                                    <option value="Loop" <?php if (isset($search['type']) && $search['type'] == 'Loop') {
                echo 'selected';
            } ?>>Loop</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('Category') ?></label>
                                                <select name="category" class="form-control">
                                                    <option value="">Select Category</option>
<?php foreach ($category as $cate) { ?>
                                                        <option value="<?= $cate->id ?>" <?php if (isset($search['category']) && $search['category'] == $cate->id) {
        echo 'selected';
    } ?>><?= $cate->category ?></option>
<?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div> 
                                </div><!-- /.box-body -->
                                <div class="box-footer" style='display:none;'>
                                    <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                    <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                                </div>
                            </form>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div> 
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                                    <th><?php echo $welcome->loadPo('Channel Name') ?></th>
                                                    
                                                    <th><?php echo $welcome->loadPo('Type') ?></th>
                                                    <th><?php echo $welcome->loadPo('Category') ?></th>
                                                    <th><?php echo $welcome->loadPo('Status') ?></th>
                                                    <th><?php echo $welcome->loadPo('Manage'); ?></th>
                                                    <th align="center"><?php echo $welcome->loadPo('Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                <?php foreach ($res as $value) { ?>

                                            <tr id="<?php echo $value->id ?>">
                                                <td><?php echo $value->name; ?></td>
                                                
                                                <td><?php echo $value->type; ?></td> 
                                                <td><?php echo $value->category; ?></td>
                                                <td><?php if ($value->status == 1) { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-pass-icon.png" alt="Active" />
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-fail-icon.png" alt="Active" />
                                                        <?php } ?></td>
                                                                                                    <td>
                                                        <?php if ($value->type == 'Loop' || $value->type == 'Linear') { ?>
                                                                                                            <a href="<?php echo base_url() ?>webtv/playlist/<?php echo $value->id; ?>">Manage Playlist</a>
                                                        <?php } else if ($value->type == 'Youtube' || $value->type == 'Live') { ?>
                                                        <a href="<?php echo base_url() ?>livestream/index/<?php echo $value->id; ?>">Manage  </a>||<a href="<?php echo base_url() ?>livestream/epg/<?php echo $value->id; ?>">  EPG</a>
                                            <?php } ?>
                                                </td>
                                                <td  width="150"> 
                                                    <a href="<?php echo base_url(); ?>webtv/edit_channels?action=<?php echo base64_encode($value->id) . '&'; ?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit') ?></a>
                                                    &nbsp;
                                                    <a class="confirm" onclick="return delete_event(<?php echo $value->id; ?>, '<?php echo base_url() . 'webtv/delete_channels' ?>', '<?php echo current_full_url(); ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete') ?></button></a>
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
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

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
