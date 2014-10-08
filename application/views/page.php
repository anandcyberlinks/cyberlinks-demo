<aside class="right-side">                
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
<?php echo $welcome->loadPo('Pages'); ?>
&nbsp;&nbsp;<a href="<?php echo base_url() ?>pages/addpage" class="btn btn-success btn-flat"><i class="fa fa-fw fa-plus-square"></i><?php echo $welcome->loadPo('Add New'); ?></a>
</h1>
<ol class="breadcrumb">
<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
<li class="active"><?php echo $welcome->loadPo('Pages') ?></li>
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
<div class="box">
<div class="box-body table-responsive">
<table id="example2" class="table table-bordered table-striped">
<thead>
<tr>
<th><?php echo $welcome->loadPo('Page Title'); ?></th>
<th><?php echo $welcome->loadPo('Status'); ?></th>
<th><?php echo $welcome->loadPo('Action'); ?></th>
</tr>
</thead>
<tbody>
<?php foreach ($result as $value) { ?>
<tr>

<td><?php echo $value->page_title; ?></td>
<td><?php if (($value->status) == '1') { ?>
<a href="<?php echo base_url() ?>pages/changestatus/?id=<?php echo $value->id; ?>&status=active" title="Click To Inactive"><i class="fa fa-fw fa-check-circle-o" style="color: green; font-size: 20px"></i>Active&nbsp;&nbsp;</a>
<?php } else { ?> 
<a href="<?php echo base_url() ?>pages/changestatus/?id=<?php echo $value->id; ?>&status=inactive" title="Click To Active"><i class="fa fa-fw fa-circle-o" style="color: red; font-size: 20px"></i> InActive</a><?php } ?></td>
<td>
<a href="<?php echo base_url(); ?>pages/addpage?id=<?php echo base64_encode($value->id); ?>" class="btn btn-info btn-sm"><?php echo $welcome->loadPo('Edit'); ?></a>&nbsp;

<a class="confirm" onclick="return delete_page(<?php echo $value->id;?>,'<?php echo base_url().'pages/deletePage' ?>');" href="" ><button class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo  $welcome->loadPo('Delete') ?></button></a>

</td>
</tr>
<?php } ?>
</tbody>
<!-- Pagination start --->

</div>		
<!-- Pagination end -->
</div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</div>

</section><!-- /.content -->
</aside><!-- /.right-side -->


