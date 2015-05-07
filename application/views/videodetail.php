<aside class="content-wrapper">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadPo('Video Detail'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Video') ?></li>
        </ol>
    </section>
    <div>
        <div id="msg_div">
            <?php echo $this->session->flashdata('message'); ?>
        </div>	
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                   
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td rowspan="6" width="30%"><div id="player"></div></td>
                            <th>Tittle</th>
                            <td><?php echo $result[0]->title; ?></td>
                        </tr>
                        <tr>
                            <th>description</th>
                            <td><?php echo $result[0]->description; ?></td>
                        </tr>
                        <tr>
                            <th>category</th>
                            <td><?php echo $result[0]->category; ?></td>
                        </tr>
                        <tr>
                            <th>status</th>
                            <td><?php if($result[0]->status =='0'){ echo "Inactive"; }else{ echo "Active"; } ?></td>
                        </tr>
                        <tr>
                            <th>created</th>
                            <td><?php echo date('M d,Y',strtotime($result[0]->created)); ?></td>
                        </tr>
                        <tr>
                            <th>modified</th>
                            <td><?php echo date('M d,Y',strtotime($result[0]->modified)); ?></td>
                        </tr>
                        <tr>
                            <td><a class="btn btn-warning" href="<?php echo base_url(); ?>video/videoOpr/Basic?action=<?php echo base64_encode($result[0]->id) . '&'; ?>">Edit</a>       
                                &nbsp; &nbsp; <a class="btn btn-default" href="<?php echo base_url(); ?>video">Back</a></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div><!-- /.box -->
        </div>
    </section><!-- /.content -->
</aside>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jwplayer.js" ></script>
<script type="text/javascript">jwplayer.key = "BC9ahgShNRQbE4HRU9gujKmpZItJYh5j/+ltVg==";</script>
<script>
    jwplayer("player").setup({
        file: "<?php echo baseurl.serverVideoRelPath.$result[0]->file ?>",
        height: 270,
        width: 480
    });
</script>
