<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Package') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
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
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header col-md-6">
                                <h3 class="box-tittle">Package Name :- <?php echo ucfirst($package[0]->name); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo $welcome->loadPo('Title') ?></th>
                                            
                                            <th><?php echo $welcome->loadPo('Category') ?></th>
                                            <th><?php echo $welcome->loadPo('Preview') ?></th>
                                            <th><?php echo $welcome->loadPo('Publish Date') ?></th>
                                            <th align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $welcome->loadPo('Action') ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($result as $value) { ?>
                                        <tr id="<?php echo $value->id ?>">
                                                <td  width="350"><a href="<?php echo base_url(); ?>video/detail/<?php echo $value->id; ?>"><?php echo strlen($value->title) > 40 ?  substr($value->title,0,40).'...' : $value->title; ?></td>
                                                <td><?php echo $value->category; ?></td>
                                                                                                <td style='text-align:center'>
                                                    <?php if(in_array($value->minetype,array('video/wmv','video/avi'))) { ?>
                                                    --
                                                    <?php } else { ?>
                                                    <a class="prev_video" href="#myModal" data-backdrop="static" data-toggle="modal" data-img-url="<?php echo baseurl.serverVideoRelPath.$value->file; ?>">Preview</a>
                                                    <?php } ?>
                                                </td>
                                                <td  width="120"><?php echo date('M d,Y', strtotime($value->created)); ?></td>
                                                <td><a href="#" class="link" links="<?php echo base_url(); ?>package/delete/<?php echo $value->vpid; ?>" ><?php echo $welcome->loadPo('Delete') ?></a></td>
                                                </tr>
                                        <?php } ?>
                                            
                                    </tbody>
                                </table>
                                <br>
                                <a href="<?php echo base_url()."package/addVideo/".$this->uri->segment(3) ?>" class="btn btn-success">Add Video</a>
                                <a href="<?php echo base_url(); ?>package" class="btn btn-primary">Back</a>
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
                               $('#msg_div').html(data.message);
                        }
                    }
                });
                return false;
            });
        });
</script>