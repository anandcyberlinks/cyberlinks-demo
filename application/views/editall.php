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
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Save</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php foreach ($record as $val) { ?>
                                                <tr id="<?= $val->id ?>">
                                                    <form action="" method="POST" class="editAll">
                                                        <td class="col-xs-1">
                                                                <input name="content_id" type="tel" value="<?= $val->id ?>" readonly="true" class="form-control"/>
                                                        </td>
                                                        <td class="col-xs-7">
                                                            <input name="content_title" type="text" value="<?php if($this->uri->segment(1)== 'ads'){ echo $val->ad_title; } else{ echo $val->title; } ?>" class="form-control" required="required"/>
                                                        </td>
                                                        <td class="col-xs-2">
                                                            <select name="content_category" class="form-control" required="required">
                                                                <option value="">--Select--</option>
                                                        <?php foreach ($category as $k => $v) { ?>
                                                                <option value="<?= $k ?>"><?= $v ?></option>
                                                        <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td class="col-xs-1">
                                                            <select name="status" class="form-control" required="required">
                                                                <option value="">--Select--</option>
                                                                <option value="0">InActive</option>
                                                                <option value="1">Active</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="submit" name="save" value="save" class="btn btn-success"></td>
                                                    </form>
                                                </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                    <tfoot>
                                    <th colspan="6"><a href="<?=  ($this->uri->segment(1)== 'ads')? base_url() . 'ads' : base_url() . 'video/index'?>" class="btn btn-warning">Go to list</a></th>
                                    </tfoot>


                                </table>
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
    $(".editAll").submit(function (event) {

        /* stop form from submitting normally */
        event.preventDefault();


        var temp = $(this).serialize();
        $('.btn-success').addClass('disabled');
        //console.log(temp);
        $.ajax({
            type: "POST",
            url: '<?=($this->uri->segment(1)== 'ads')? base_url() . 'ads/submitAll' : base_url() . 'video/submitAll' ?>',
            data: temp,
        }).done(function (res) {
            console.log(res);
            $("#"+res).fadeOut("slow", function () {
                $('.btn-success').removeClass('disabled');
            });
            
        });
        
    });
</script>

