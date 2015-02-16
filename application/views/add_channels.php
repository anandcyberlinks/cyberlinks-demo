<style>
    .error{
        color: red;
    }
</style>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Add Channels'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url() ?>event"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('WebTV') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Add Channels') ?></li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div id="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header">
                                <?php if (!isset($value)) {  ?>
                                    <h3 class="box-title"><?php echo $welcome->loadPo('Add') . ' ' . $welcome->loadPo('Channels'); ?></h3>
                                <?php } else { ?>
                                    <h3 class="box-title"><?php echo $welcome->loadPo('Edit') . ' ' . $welcome->loadPo('Channels'); ?></h3>
                                <?php } ?>
                                <div class="box-tools pull-right">
                                    <a href="<?php echo base_url(); ?>webtv" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <form id="registerId" action="" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Channels Name'); ?></label>
                                            <input class="form-control" type="text" name="name" value="<?php
                                            if (isset($value)) {
                                                echo $value[0]->name;
                                            }
                                            ?>">
                                        </div>
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Category'); ?></label>
                                            <select name="category_id" id="category_id" class="form-control" <?=(isset($value))? 'readonly="true"':''?>>
                                                <option value="">Select</option>
                                                <?php foreach ($catogory as $val) { ?>
                                                    <option value="<?= $val->id ?>"
                                                    <?php
                                                    if (isset($value)) {
                                                        if ($value[0]->category_id == $val->id) {
                                                            echo "selected";
                                                        }
                                                    }
                                                    ?>
                                                            ><?= $val->category ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                        <div id="load" class="col-md-4"></div>
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Channels Number'); ?></label>
                                            <?php if(isset($value)){ ?>
                                            <input type="text" name="number" class="form-control" value="<?=$value[0]->number?>" readonly="true" />
                                                <?php }else{ ?>
                                            <select name="number" id="ch_number" class="form-control">
                                                <option value="">Select Number</option>
                                            </select>
                                                <?php } ?>
                                        </div>
                                        <?php if (!isset($value)) { ?>
                                            <div class="form-group col-md-10">
                                                <label><?php echo $welcome->loadPo('Channels Type'); ?></label>
                                                <select name="type" class="form-control">
                                                    <option value="Loop">Loop TV</option>
                                                    <option value="Live">Live Stream</option>
                                                    <option value="Youtube">Youtube Channel</option>
                                                    <option value="Linear">Linear</option>
                                                </select>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group col-md-10">
                                            <label><?php echo $welcome->loadPo('Status'); ?></label>
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" name="status" value="1" <?php
                                            if (isset($value)) {
                                                if ($value[0]->status == '1') {
                                                    echo "checked";
                                                }
                                            }
                                            ?>>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="exampleInputEmail1"><?php echo $welcome->loadPo('Keywords') ?></label>
                                            <input name="tags" class="form-control" id="myTags" type="text" placeholder="Enter keywords"  value="<?php echo @$value[0]->keywords; ?>" /> 
                                            <?php //echo form_error('content_keyword', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <input type="submit" name="submit" value="Submit" class="btn btn-success">
                                            <a class="btn btn-warning" href="<?php echo base_url() ?>webtv"><i class="fa fa-mail-reply"></i> Back</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<script>
    $("#category_id").change(function () {
        $('#ch_number').empty();
        $('#load').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
        var c_id = $("#category_id").val();
        $.ajax({
            url: '<?= base_url() . 'webtv/ch_number?cat_id=' ?>' + c_id,
            dataType: "json",
            success: function (response) {
                $('#ch_number').append('<option value="*">Select</option>');
                $.each(response, function (key, value) {
                    $('#ch_number').append('<option value=' + value.range + '>' + value.range + '</option>');
                });
            }
        });
        $('#load').html('');
    });
</script>
