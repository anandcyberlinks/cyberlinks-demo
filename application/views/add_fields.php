<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo $welcome->loadPo('Fields'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
			 <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
            <li><a href="<?php echo base_url() ?>role"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('Category') ?></a></li>
            <li class="active"><?php echo $welcome->loadPo('Add Category') ?></li>
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
								<h3 class="box-title"><?php echo $welcome->loadPo('Fields').' '.$welcome->loadPo('Add'); ?></h3>
								<div class="box-tools pull-right">
									<a href="<?php echo base_url(); ?>category" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
								</div>
							</div><!-- /.box-header -->
							<!-- form start -->
							<form action="" id="CategoryForm" method="post" accept-charset="utf-8">
							
								
								<div class="box-body">
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input text">
												<label for="Category"><?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Title'); ?></label>
												<input name="field_title" class="form-control" placeholder="<?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Title'); ?>" maxlength="255" type="text" id="Category" value="<?php echo set_value('category'); ?>" onblur="category_check(this.value);" />
												<?php echo form_error('field_title','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input text">
												<label for="Category"><?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Name'); ?></label>
												<input name="field_name" class="form-control" placeholder="<?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Name'); ?>" maxlength="255" type="text" id="Category" value="<?php echo set_value('category'); ?>" onblur="category_check(this.value);" />
												<?php echo form_error('field_name','<span class="text-danger">','</span>'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-5">
											<div class="input select">
												<label><?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Type');?> </label>
												<select name="field_type" class="form-control" placeholder="Field Type" >
													<option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
													<?php 
														for($i=1; $i<count($fieldtype)+1;$i++){
															echo $fieldtype[$i];
															echo "<br>";?>
														  <option value="<?php echo $fieldtype[$i];?>"><?php echo $fieldtype[$i];?></option>
														<?php }
														?>
												</select>
												<?php echo form_error('field_type','<span class="text-danger">','</span>'); ?>
											</div>
											                            
										</div>
									</div>
									<div class="row" id="options">
										<div class="form-group col-lg-5">
											<div class="input text">
												<label for="Category"><?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Options'); ?></label>
												<input name="field_options" class="form-control" id="myTags" type="text" placeholder="<?php echo $welcome->loadPo('Field').' '.$welcome->loadPo('Options'); ?>"  value="" /> 
											</div>
										</div>
									</div>
									
									 <div class="row">    
										<div class="form-group col-lg-5">
											<label for="Status"><?php echo $welcome->loadPo('Validate'); ?>
											<input type="hidden" name="field_validate" id="field_validate" value="0"/>
											<span align="left"><input type="checkbox" name="field_validate" value="1"/></span></label>
											
										</div>
									</div>
									<div class="row">    
										<div class="form-group col-lg-5">
											<label for="Status"><?php echo $welcome->loadPo('Status'); ?>
											<input type="hidden" name="status" id="CategoryStatus" value="0"/>
											<span align="left"><input type="checkbox" name="status" value="1"/></span></label>											
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">									
									<input type="hidden" name="form_id" id="form_id" value="<?php echo $_GET['id'] ?>"/>
									<button type="submit" name="submit" value="Submit" class="btn btn-primary"><?php echo $welcome->loadPo('Submit'); ?></button>
									<a href="<?php echo base_url(); ?>dform/field/?id=<?php echo $_GET['id'] ?>" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>
								</div>
							</form>
						</div><!-- /.box -->
					</div><!--/.col (left) -->
				</div>
			</div>
		</section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<script type="text/javascript">
	//alert($(this).attr("value"));
     $(document).ready(function(){
		//alert($(this).attr("value"));
        $("select").change(function(){
            $( "select option:selected").each(function(){
                if($(this).attr("value")=="radio"){
                    $("#options").show();
                }else if($(this).attr("value")=="checkbox"){
                    $("#options").show();
                }
				 else{
                    $("#options").hide();
                }
            });
        }).change();
    }); 
</script>

 
