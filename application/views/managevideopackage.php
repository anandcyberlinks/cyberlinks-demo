<style>
    div#video{
        width: 100%;
        height: 300px;
        overflow: scroll;
    }
</style>
<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Packages'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small> <a href="<?php echo base_url() ?>package/creatpackage" class="btn btn-success btn-sm">Add New Package</a></h1>

            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Package') ?></li>
            </ol>
        </section>
        <div>
            <div id="msg_div">
                <?php echo $this->session->flashdata('message'); ?>				
            </div>	
        </div>
        <!-- Main content -->
        <section class="content">                
            <div id="content">
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body table-responsive">
                            <form action="<?php echo base_url() ?>package" method="POST">
                                <table class="table table-bordered table-hover dataTable">
                                    <tr>
                                        <th>Title</th>
                                        <th>Catagory</th>
                                    </tr>
                                    <?php foreach ($result as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                            <td><input type="checkbox" name="content_id[]" value="<?php echo $value->id; ?>"<?php if ($welcome->check($value->id, $this->uri->segment(3)) == '1') {
                                        echo "checked";
                                    } ?> ></td>
                                            <td><?php echo $value->title ?></td>
                                            <td><?php echo $value->category ?></td>
                                        </tr>
<?php } ?>
                                </table>
                                <br>
                                <input type="hidden" name="package_id" value="<?php echo $this->uri->segment(3) ?>">
                                <input class="btn btn-success" type="submit" name="submit" value="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside>
<!--/div-->