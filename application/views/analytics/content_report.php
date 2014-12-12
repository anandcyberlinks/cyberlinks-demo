<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Content Wise Report') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>analytics/report"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Analytics') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Content wise report') ?></li>
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
           // $search = $_GET;
            ?></pre>
            <div id="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <!-- form start -->
                            <form  method="post" action="" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for=""><?php echo $welcome->loadPo('Title') ?></label>
                                                <input type="text" name="title" id="title" class="form-control" value="<?php echo (isset($search['title'])) ? $search['title'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Title') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input select">
                                                <label for="searchCategory"><?php echo $welcome->loadPo('Content Provider') ?></label>
                                                <select name="contentprovider" class="form-control" placeholder="<?php echo $welcome->loadPo('Content Provider') ?>" id="contentprovider">
                                                    <option value=""><?php echo $welcome->loadPo('Select') ?></option>
                                                    <?php foreach ($content_provider as $row) { ?>
                                                        <option value="<?php echo $row->id; ?>" <?php
                                                        if (isset($search['contentprovider'])) {
                                                            if ($row->id == $search['contentprovider']) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>  ><?php echo $row->name; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
                                                <input type="text" class="form-control datepicker"  id="startdate" name="startdate" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($search['startdate'])) ? $search['startdate'] : ''; ?>" >											
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                                                <input type="text" class="form-control datepicker"  id="enddate" name="enddate" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($search['enddate'])) ? $search['enddate'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                        <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                                    <button type="submit" name="search" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                    <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                                </div>
                            </form>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
              
        <!-- Small boxes (Stat box) -->
	
        <div class="row">
            
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h3>
                            <?php echo $summary->total_hits;?>	
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Total Hits'); ?> 
                        </p>
                    </div>
                   
                    <!--a href="<?php //echo base_url() ?>video" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>-->
                </div>
            </div>
	    <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>
                            <?php echo $summary->unique_hits;?>	
                        </h3>
                        <p>
                           <?php echo $welcome->loadPo('Unique Users'); ?> 
                        </p>
                    </div>
                   
                    <!--a href="<?php //echo base_url() ?>video" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>-->
                </div>
            </div>
                                   
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>
                            <?php echo time_from_seconds($summary->total_watched_time); //-- common helper ?>
                       
                        </h3>
                        <p>
                            <?php echo $welcome->loadPo('Total Time Watched'); ?>
                        </p>
                    </div>
                   
                    <!--a href="#" class="small-box-footer">
                        <?php //echo $welcome->loadPo('More info'); ?> <i class="fa fa-arrow-circle-right"></i>
                    </a>-->
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->
        <div class="col-md-3 col-sm-4">
            Export
        <a target='_blank' href='<?php echo base_url()?>analytics/export/r/pdf/<?php echo $sort_i;?>/<?php echo $sort_by;?>/type/content'  title='pdf'><i class="fa fa-fw fa-file-text-o"></i></a>
        <a target='_blank' href='<?php echo base_url()?>analytics/export/r/csv/<?php echo $sort_i;?>/<?php echo $sort_by;?>/type/content' title='csv'><i class="fa fa-fw fa-list-alt"></i></a>
        </div>     
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                           <th><a href="<?php echo base_url(); ?>analytics/content/v/<?php echo (!empty($show_c)) ? $show_c : 'asc'; ?>">Content</a></th>
                                           <th><a href="<?php echo base_url(); ?>analytics/content/p/<?php echo (!empty($show_p)) ? $show_p : 'asc'; ?>">Content Provider</a></th>
                                            <th><a href="<?php echo base_url(); ?>analytics/content/h/<?php echo (!empty($show_h)) ? $show_h : 'asc'; ?>">Total Hits</a></th>
                                            <th><a href="<?php echo base_url(); ?>analytics/content/t/<?php echo (!empty($show_t)) ? $show_t : 'asc'; ?>">Total Time Watched</a></th>	
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($content as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                                <td  width="70%"><!--a href="<?php echo base_url(); ?>analytics/user/<?php echo $value->id; ?>"--><?php echo $value->title; ?></td>
                                                <td><?php echo $value->content_provider; ?></td>                                                
                                                <td><?php echo $value->total_hits; ?></td>
                                                <td><?php echo time_from_seconds($value->total_watched_time); ?></td>                                                                                        
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


 <script>

  $(function(){
     $( ".datepicker" ).datepicker({
  dateFormat: 'dd-mm-yy',
  numberOfMonths: 1,
});
  });
 
  </script>
