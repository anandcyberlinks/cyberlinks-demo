<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Video') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Video') ?></li>
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
                        <div class="box box-primary">
                            <!-- form start -->
                            <form  method="post" action="<?php echo base_url(); ?>video/debug" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for=""><?php echo $welcome->loadPo('Title') ?></label>
                                                <input type="text" name="content_title" id="content_title" class="form-control" value="<?php echo (isset($search['content_title'])) ? $search['content_title'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Title') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                        <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                                    <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
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
                                            <th width="15px"><?php echo $welcome->loadPo('Title') ?></th>                                            
                                            <th><?php echo $welcome->loadPo('Video File') ?></th>
                                            <th><?php echo $welcome->loadPo('Thumb') ?></th>
                                            <th><?php echo $welcome->loadPo('Small Thumb') ?></th>
                                            <th><?php echo $welcome->loadPo('Medium Thumb') ?></th>
                                            <th><?php echo $welcome->loadPo('Large Thumb') ?></th>
                                            <?php /*/ ?><th align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $welcome->loadPo('Action') ?></th><?php /*/ ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($result as $value) {
                                            if(($value->contentId)){    
                                        ?>
                                        <tr id="<?php echo $value->contentId ?>">
                                            <td  width="350"><a href="<?php echo base_url(); ?>video/detail/<?php echo $value->contentId; ?>"><?php echo strlen($value->title) > 40 ?  substr($value->title,0,40).'...' : $value->title; ?></td>
                                            <td><img src="<?php echo $value->video_relative_path; ?>" alt="Active" /></td>
                                            <td><img src="<?php echo $value->thumb_relative_path; ?>" alt="Active" /></td>
                                            <td><img src="<?php echo $value->thumb_small; ?>" alt="Active" /></td>
                                            <td><img src="<?php echo $value->thumb_medium; ?>" alt="Active" /></td>
                                            <td><img src="<?php echo $value->thumb_large; ?>" alt="Active" /></td>
                                            <?php /*/ ?><td  width="150"> 
                                                <a href="<?php echo base_url(); ?>video/videoOpr/Basic?action=<?php echo base64_encode($value->id) . '&'; ?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit') ?></a>
                                                &nbsp;
                                                <a class="confirm" onclick="return delete_video(<?php echo $value->id; ?>, '<?php echo base_url() . 'video/deletevideo' ?>', '<?php echo current_full_url(); ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Delete') ?></button></a>
                                            </td><?php /*/ ?>
                                        </tr>
                                        <?php } } ?>
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

