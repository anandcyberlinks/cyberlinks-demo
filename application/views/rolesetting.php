<aside class="right-side">    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $welcome->loadPo('Role') ?>
            <small>advanced Setting</small>
            <a class="btn btn-success" href="<?php echo base_url()?>role/addrole"><i class="fa fa-fw fa-plus-square"></i><?php echo $welcome->loadPo('Add Role') ?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Role') ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <div>
        <div id="msg_div">
            <?php echo $this->session->flashdata('message'); ?>
        </div>	
    </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $welcome->loadPo('Role Setting') ?></h3>                                    
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover dataTable" aria-describedby="example2_info">
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php if(count($role) == '0' ){
                                    echo '<tr><th>No Roll Added by you, You Can add new roll</th><tr>';
                                }else{ ?>
                                <tr class="odd">
                                    <th rowspan="2"><?php echo $welcome->loadPo('Role') ?></th>
                                    <th colspan="3"><?php echo $welcome->loadPo('Permission') ?></th>
                                    <th rowspan="2"><?php echo $welcome->loadPo('Delete') ?></th>
                                </tr>
                                <tr class="even">
                                    <th><?php echo $welcome->loadPo('Add') ?><i class="fa fa-fw fa-plus-square-o"></i></th>
                                    <th><?php echo $welcome->loadPo('Edit') ?><i class="fa fa-fw fa-edit"></i></th>
                                    <th><?php echo $welcome->loadPo('Delete') ?><i class="fa fa-fw fa-trash-o"></i></th>
                                </tr>
                                
                                <?php
                                
                                
                                foreach ($role as $value){ ?>
                                <tr>
                                    <th><?php echo $value->name ?></th>
                                    <td><?php $st= $welcome->checkpermission($value->id, 'add');
                                    if($st){ ?>
                                        <a href="<?php echo base_url()?>role/deletepermission/?role_id=<?php echo $value->id; ?>&permission=add">
                                            <i class="fa fa-fw fa-check-circle-o" style="color: green; "></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url()?>role/addpermission/?role_id=<?php echo $value->id; ?>&permission=add">
                                            <i class="fa fa-fw fa-circle-o" style="color: red; "></i>
                                        </a>
                                    <?php } ?>
                                    </td>
                                    <td><?php $st= $welcome->checkpermission($value->id, 'edit');
                                    if($st){ ?>
                                        <a href="<?php echo base_url()?>role/deletepermission/?role_id=<?php echo $value->id; ?>&permission=edit">
                                            <i class="fa fa-fw fa-check-circle-o" style="color: green; "></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url()?>role/addpermission/?role_id=<?php echo $value->id; ?>&permission=edit">
                                            <i class="fa fa-fw fa-circle-o" style="color: red; "></i>
                                        </a>
                                    <?php } ?>
                                    </td>
                                    <td><?php $st= $welcome->checkpermission($value->id, 'delete');
                                    if($st){ ?>
                                        <a href="<?php echo base_url()?>role/deletepermission/?role_id=<?php echo $value->id; ?>&permission=delete">
                                            <i class="fa fa-fw fa-check-circle-o" style="color: green; "></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url()?>role/addpermission/?role_id=<?php echo $value->id; ?>&permission=delete">
                                            <i class="fa fa-fw fa-circle-o" style="color: red; "></i>
                                        </a>
                                    
                                <?php }  ?>
                                    </td>
                                    <td><a onclick="return delete_role(<?php echo $value->id;?>);" href=""><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm"><?php echo $welcome->loadPo('Delete') ?></button></a></td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.box -->

    </section><!-- /.content -->
</aside>               
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- dialog body -->
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                Hello world!
            </div>
            <!-- dialog buttons -->
            <div class="modal-footer"><button type="button" class="btn btn-primary">OK</button></div>
        </div>
    </div>
</div>
