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
            <div id="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <!-- form start -->
                            <form  method="post" action="<?php echo base_url(); ?>package/addVideo/24" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for=""><?php echo $welcome->loadPo('Title') ?></label>
                                                <input type="text" name="content_title" id="content_title" class="form-control" value="<?php echo (isset($search_data['content_title'])) ? $search_data['content_title'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Title') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input select">
                                                <label for="searchCategory"><?php echo $welcome->loadPo('Category') ?></label>
                                                <select name="category" class="form-control" placeholder="<?php echo $welcome->loadPo('Category') ?>" id="searchCategory">
                                                    <option value=""><?php echo $welcome->loadPo('Select') ?></option>
                                                    <?php foreach ($category as $key=>$val) { ?>
                                                        <option value="<?php echo $key; ?>" <?php
                                                        if (isset($search_data['category'])) {
                                                            if ($key == $search_data['category']) {
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
                                                <input type="text" class="form-control"  id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($search_data['datepickerstart'])) ? $search_data['datepickerstart'] : ''; ?>" >											
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                                                <input type="text" class="form-control"  id="datepickerend" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($search_data['datepickerend'])) ? $search_data['datepickerend'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                        <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                                    <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                    <div class="pull-right"><a href="<?php echo base_url()."webtv/video_detail/".$this->uri->segment(3); ?>" class="btn btn-primary">Back To Playlist</a></div>
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
                                            
                                            <th><?php echo $welcome->loadPo('Category') ?></th>
                                            <th><?php echo $welcome->loadPo('User') ?></th>
                                            <th><?php echo $welcome->loadPo('Status') ?></th>
                                            <th><?php echo $welcome->loadPo('Type') ?></th>
                                            <th><?php echo $welcome->loadPo('Price') ?></th>
                                            <th><?php echo $welcome->loadPo('Preview') ?></th>
                                            <th><a href="<?php echo base_url(); ?>video/index/created/<?php echo (!empty($show_ca)) ? $show_ca : 'asc'; ?>"><?php echo $welcome->loadPo('Publish Date') ?></a></th>
                                            <th align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $welcome->loadPo('Action') ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($result as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                                <td  width="350"><a href="<?php echo base_url(); ?>video/detail/<?php echo $value->id; ?>"><?php echo strlen($value->title) > 40 ?  substr($value->title,0,40).'...' : $value->title; ?></td>
                                                <td><?php echo $value->category; ?></td>
                                                <td><?php echo $value->username; ?></td>
                                                <td><?php if ($value->status == 1) { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-pass-icon.png" alt="Active" />
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/test-fail-icon.png" alt="Active" />
                                                    <?php } ?></td>
                                                <td><?php echo ucfirst($value->content_type); ?></td>
                                                <td><a href="#" link="<?php echo base_url()?>package/price/<?php echo $value->id; ?>?type=video" class="price">Price</a></td>
                                                <td style='text-align:center'>
                                                    <?php if(in_array($value->minetype,array('video/wmv','video/avi'))) { ?>
                                                    --
                                                    <?php } else { ?>
                                                    <a class="prev_video" href="#myModal" data-backdrop="static" data-toggle="modal" data-img-url="<?php echo baseurl.serverVideoRelPath.$value->file; ?>">Preview</a>
                                                    <?php } ?>
                                                </td>
                                                <td  width="120"><?php echo date('M d,Y', strtotime($value->created)); ?></td>
                                                <td  width="150"> 
                                                    <a href="#" class="link" links="<?php echo base_url(); ?>webtv/VideoPack/<?php echo $this->uri->segment(3)."/".$value->id; ?>" ><?php echo $welcome->loadPo('Add') ?></a>
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

<script>
</script>
<!--  this div for  jwplyer reponce -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true" onclick='stopvideo()'>
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
<script type="text/javascript">
        $(function(){
            $('.link').click(function(){
                var elem = $(this);
                $.ajax({
                    type: "GET",
                    url: elem.attr('links'),
                    dataType:"json",  
                    success: function(data) {
                        if(data.success){
                            elem.closest("tr").fadeOut('3000');
                        }
                    }
                });
                return false;
            });
        });
</script>