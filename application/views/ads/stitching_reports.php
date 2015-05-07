<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('In Stream Stitching Report') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>ads_analytics/report"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Ads Analytics') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('In Stream Stitching Report') ?></li>
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
                
        <div class="col-md-3 col-sm-4">
            Export
        <a target='_blank' href='<?php echo base_url()?>ads_analytics/export_stitching/pdf'  title='pdf'><i class="fa fa-fw fa-file-text-o"></i></a>
        <a target='_blank' href='<?php echo base_url()?>ads_analytics/export_stitching/csv' title='csv'><i class="fa fa-fw fa-list-alt"></i></a>
        </div>     
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
<!--                                           <th>Id</th>-->
                                           <th>Creative</th>
                                           <th>Channel</th>
                                           <th>Duration</th>
                                           <th>Impression</th>
                                           <th>DateTime</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($content as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
<!--                                                <td><?php //echo $value->id; ?></td>                                                                                               -->
                                            <td>
                                                <?php if(isset($value->ad_title)) { ?>
                                                    <a href="<?php echo base_url();?>ads/detail/<?php echo $value->ads_id; ?>"><?php echo $value->ad_title; ?></a>
                                                <?php } else { echo $value->Commercial; }?>
                                            </td>                                                
                                            <td><?php echo $value->channel; ?></td>
                                            <td><?php echo $value->Duration; ?></td>
                                            <td><?php echo $value->UserCount; ?></td>                                                
                                            <td><?php echo $value->StartTime; ?></td>
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
<!--/div--><!-- ./wrapper -->

 <script>

  $(function(){
     $( ".datepicker" ).datepicker({
  dateFormat: 'dd-mm-yy',
  numberOfMonths: 1,
});

//-- auto suggest bootstrap-typeahead --//
    $('#country').typeahead({
        source: <?php echo (json_encode($country));?>
        //itemSelected: displayResult
    });
  });
 
  </script>
