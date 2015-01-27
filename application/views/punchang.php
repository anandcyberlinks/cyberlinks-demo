<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Punchang') ?><small><?php echo $welcome->loadPo('List') ?></small>
                <a href="punchang/add" class="btn btn-success btn-sm">Add</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Punchang') ?></li>
            </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php $search = $this->session->userdata('search_form');
            ?></pre>
            <div id="content">
                 <div id="msg">
                     <?php $msg = $this->session->flashdata('message');
                     if($msg != ''){
                        echo $msg;
                     }
                     ?>
                </div>
                <?php /* <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="box box-primary">
                  <!-- form start -->
                  <form  method="post" action="<?php echo base_url(); ?>waptv/index" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                  <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                  <div class="box-body">
                  <div class="row">
                  <div class="form-group col-lg-8">
                  <div class="input text">
                  <label for=""><?php echo $welcome->loadPo('Title') ?></label>
                  <input type="text" name="name" class="form-control" value="<?php echo (isset($search['name'])) ? $search['name'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Playlists Name') ?>">
                  </div>
                  </div>
                  </div>
                  <div class="row">
                  <div class="form-group col-lg-4">
                  <div class="input text">
                  <label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
                  <input type="text" class="form-control"  id="eventDates" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start Date') ?>" value="<?php echo (isset($search['datepickerstart'])) ? $search['datepickerstart'] : ''; ?>" >
                  </div>
                  </div>
                  <div class="form-group col-lg-4">
                  <div class="input text">
                  <label for="url"><?php echo $welcome->loadPo('End Date') ?></label>
                  <input type="text" class="form-control"  id="eventDatee" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End Date') ?>" value="<?php echo (isset($search['datepickerend'])) ? $search['datepickerend'] : ''; ?>">
                  </div>
                  </div>
                  </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                  <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                  <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>

                  </div>
                  </form>
                  </div><!-- /.box -->
                  </div><!--/.col (left) -->
                  </div> -- */ ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box box-header">
                                <form id="submitcsv" action="<?php base_url() ?>punchang/submitcsv" method="post" enctype="multipart/form-data">
                                    <div class="box box-title">
                                        <div class="form-group">
                                            <span class="btn btn-default btn-file btn-sm"><i class="fa fa-fw fa-folder-open-o"></i>
                                                Import CSV <input name="csv" id="video_file" atr="files" type="file">
                                            </span>
                                        </div>
                                        <input type="submit" name="submit" value="Submit" class="btn btn-success"><div id="load"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo $welcome->loadPo('Date') ?></th>
                                            <th><?php echo $welcome->loadPo('Month') ?></th>
                                            <th><?php echo $welcome->loadPo('Pakshya') ?></th>
                                            <th><?php echo $welcome->loadPo('Tithi') ?></th>
                                            <th><?php echo $welcome->loadPo('Sunrise') ?></th>
                                            <th><?php echo $welcome->loadPo('Sunset') ?></th>
                                            <th><?php echo $welcome->loadPo('Rahukal'); ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php foreach ($result as $val){ ?>
                                    <tr>
                                        <td><?=$val->date?></td>
                                        <td><?=$val->month?></td>
                                        <td><?=$val->pakshya?></td>
                                        <td><?=$val->tithi?></td>
                                        <td><?=$val->sunrise?></td>
                                        <td><?=$val->sunset?></td>
                                        <td><?=$val->rahukal?></td>
                                        <td>
                                            <a href="<?= base_url(). 'punchang/delete/'.$val->id ?>" class="btn btn-danger btn-sm confirm_delete">Delete</a>
                                            <a href="<?= base_url(). 'punchang/add/'.$val->id ?>" class="btn btn-warning btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                        <?php } ?>
                                    <tbody>
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
<script>
    $("#msg").fadeOut(3500);
    $('#submitcsv')
            .submit(function (e) {
                $("#load").html('loading.....')
                $.ajax({
                    url: $('#submitcsv').attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false
                }).done(function (data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    if (res.result == 'success') {
                        $("#load").html('');
                        var msg = "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button><b>Alert!</b> Record Successfully Added</div>";
                        $("#msg").html(msg);
                        $("#msg").fadeIn();
                        $("#msg").fadeOut(3500);
                        window.setTimeout(function () {
                            window.location.href = "<?php echo base_url().'punchang'; ?>";
                        }, 2000);
                    }else{
                    $("#load").html('');
                         var msg = "<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button><b>Alert!</b> Please Select Valid CSV File</div>";
                        $("#msg").html(msg);
                        $("#msg").fadeIn();
                        $("#msg").fadeOut(3500);
                    }
                });
                e.preventDefault();
            });
</script>