<aside class="right-side">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Audio
            <small>Audio List</small> &nbsp;<a class="btn btn-success" href="<?php echo base_url().'audio/upload'; ?>"><i class="fa fa-fw fa-upload"></i> Upload</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php base_url(); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Audio</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="msg_div">
                <?php echo $this->session->flashdata('message'); ?>
            </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Audios</h3>                                    
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div id="example2_wrapper" class="dataTables_wrapper form-inline" role="grid">
                            <div class="row"><div class="col-xs-6"></div><div class="col-xs-6"></div></div>
                            <table id="example2" class="table table-bordered table-hover dataTable" aria-describedby="example2_info">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th width="8%">Status</th>
                                        <th width="20%">Play</th>
                                        <th width="12%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result as $val) { ?>
                                        <tr>
                                            <td><?= $val->title ?></td>
                                            <td><?= $val->description ?></td>
                                            <td><?= $val->category ?></td>
                                            <td align="center"><?php echo ($val->status == 1)? '<i class="fa fa-fw fa-check-circle-o text-green lead"></i>':'<i class="fa fa-fw fa-circle-o text-red lead"></i>'; ?></td>
                                            <td>
                                                <audio controls>
                                                    <source src="<?= $val->file_path ?>" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </td>
                                            <td>
                                                <a class="btn btn-warning btn-sm" href="<?=  base_url().'audio/edit?action='. base64_encode($val->id)?>">Edit</a>&nbsp;
                                                <a class="btn btn-danger btn-sm confirm_delete" href="<?=  base_url().'audio/delete?action='. base64_encode($val->id).'&file='.$val->absalute_path?>">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>
    </section><!-- /.content -->
</aside>