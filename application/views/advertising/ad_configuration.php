<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Ad Configuration') ?><small><?php echo $welcome->loadPo('Control panel') ?></small>
            <a class="btn btn-success" href="<?php echo base_url().'advertising/add_source'; ?>">Add Source</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Advertising') ?></li>
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
            <?php $search = $this->session->userdata('search_form');
            ?></pre>
            <div id="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <form  method="post" action="<?php echo base_url(); ?>advertising/configuration" accept-charset="utf-8">
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="15px"><?php echo $welcome->loadPo('Sr. No') ?></th>
                                                <th><?php echo $welcome->loadPo('Title') ?></th>
                                                <th><?php echo $welcome->loadPo('Action') ?></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $sr = 1; foreach ($adSources as $value) { ?>
                                            <tr id="<?php echo $value->id ?>">

                                                    <td  width="15px"><?php echo $sr; ?></td>
                                                    <td><?php echo $value->title; ?></td>
                                                    <td><input type="radio" name="ad_config" value="<?php echo $value->title; ?>" <?php if($value->title==$userAdsConfig) {?> checked="checked" <?php }?> ></td>
<!--                                                    <td><input type="checkbox" name="ad_config[]" value="<?php echo $value->title; ?>"></td>-->
                                                </tr>
                                            <?php $sr++; } ?>
                                                <tr>
                                                    <td align="center" colspan="3">
                                                        <button type="submit" name="submit" value="save" class="btn btn-primary"><?php echo $welcome->loadPo('Save') ?></button>
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </form>
                                
<!--                                 Pagination start -
                                <?php
                                if ($this->pagination->total_rows == '0') {
                                    echo "<tr><td colspan=\"7\"><h4>" . $welcome->loadPo('No Record Found') . "</td></tr></h4>";
                                } else {
                                    ?>
                                    </table>
                                    <div><a class="linkClicklive"   data-toggle="modal" data-backdrop="static" href="#tester" data-toggle="modal" value='Edit'>Edit</a>

                                    </div>
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
                             Pagination end -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->