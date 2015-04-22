
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('All Templates') ?><small><?php echo $welcome->loadPo('Control panel') ?></small>            
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li class="active"><?php echo $welcome->loadPo('Templates') ?></li>
            </ol>
        </section>
                <?php echo $this->session->flashdata('message'); ?>
            <?php if (isset($error) && !empty($error)) { ?><div id="msg_div"><?php echo $error; ?></div><?php } ?>
        <!-- Main content -->
        <section class="content">
            <?php $search = $this->session->userdata('search_form');
            ?></pre>
            <div id="content">
                <?php /*<div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary collapsed-box">
                        <div class="box-header">
			<!-- tools box -->
			<div class="pull-right box-tools">
			    <button class="btn btn-danger btn-sm" data-widget='collapse' data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			</div><!-- /. tools -->
			<h3 class="box-title">Search Videos</h3>
		    </div>
                            <!-- form start -->
                            <form  method="post" action="<?php echo base_url(); ?>video/index" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                            
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                
                                <div class="box-body" style="display:none;">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <label for=""><?php echo $welcome->loadPo('Templates') ?></label>
                                                <input type="text" name="title" id="title" class="form-control" value="<?php echo (isset($search['title'])) ? $search['title'] : ''; ?>" placeholder="<?php echo $welcome->loadPo('Title') ?>">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                </div><!-- /.box-body -->
                                <div class="box-footer" style="display:none;">
                                        <!--	<input type="text" id="hddstarddt" name="hddstarddt" value="<?php echo @$_POST['hddstarddt'] ?>"> -->
                                    <button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>
                                    <button type="submit" name="reset" value="Reset"class="btn btn-primary"><?php echo $welcome->loadPo('Reset') ?></button>
                                </div>
                            </form>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div> */?>
                <div class="row">
                    <div class="col-xs-8">
			<div class="box-footer" >
			    <form action='' method='post'>
				   <input type='hidden' name='skin_id' id='skin_id'>											                                        
				<?php if(isset($role_id)&&($role_id!='2')){?>
					<button type="submit" name="save" value="Save"class="btn btn-primary"><?php echo $welcome->loadPo('Publish Now') ?></button>
				 <?php
				 }
				 if(isset($role_id)&&($role_id=='2')){?>
				 <a id='addhref' href="<?=base_url()?>publishing/add"><button type="button" id='addbutton' name="add" class="btn btn-primary"><?php echo $welcome->loadPo('Add') ?></button></a>
				 <a id='edithref'><button type="button" id='editbutton' disabled name="edit" class="btn btn-primary"><?php echo $welcome->loadPo('Edit') ?></button></a>
				 <a id='deletehref'><button type="button" id='deletebutton' disabled name="delete" class="btn btn-primary"><?php echo $welcome->loadPo('Delete') ?></button></a>
				<?php }?></form>
                        </div><br/>
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="skin" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style='width:75%'><?php echo $welcome->loadPo('Title') ?></th>                                                                                        
                                            <th><?php echo $welcome->loadPo('Dimensions') ?></th>                                                                                        
                                            <th><?php echo $welcome->loadPo('Publish Date') ?></th>                                          
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php  foreach ($result as $value) { ?>
                                        <tr id="<?php echo $value->id ?>" onclick=changefunction('<?php echo base64_encode($value->id);?>'); alt="<?php echo $value->image;?>" title="<?php echo $value->title;?>" dimen="<?php echo $value->dimension;?>">
                                                <td  width="350"><?php echo strlen($value->title) > 40 ?  substr($value->title,0,40).'...' : $value->title; ?><br>
                                                (<?php echo $value->description; ?>)</td>
                                                
                                                <td><?php echo $value->dimension; ?></td>
                                               
                                                <td  width="120"><?php echo date('M d,Y', strtotime($value->created)); ?></td>                                                
                                            </tr>
                                        <?php } ?>
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
                    <div class="col-xs-4">
                        <div class="box">
                            <div class="box-body table-responsive">
                                <table class="table table-bordered">  
                                    <tbody>
										 <tr>
                                            <td>Preview</td>
                                        </tr> 
                                        <tr>
                                            <td id="preview_skin"></td>
                                        </tr>
										
										<tr>
                                            <td id="skin_title"></td>
                                        </tr>
										<tr>
                                            <td id="skin_dimenstion"></td>
                                        </tr>										
                                    </tbody>
                                </table>
                </div>
            </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<script>
	$(document).ready(function () {
		var roleid='<?php echo $role_id;?>';
		
		if(roleid=='2'){
			document.getElementById('deletebutton').disabled = true;
			document.getElementById('editbutton').disabled = true;
		}
 <?php foreach ($result as $value) {
	if($value->skin_id>0){?>
	var id="<?php echo $value->skin_id;?>";
		var url ="<?php echo $value->image;?>";
		var title ="<?php echo $value->title;?>";
		var dimen ="<?php echo $value->dimension;?>";
		$('#'+id).css("background", "#ddd");
		 $('#preview_skin').html('<img src="'+url+'" width="380" height="">');
		   $('#skin_title').html("Title<br>"+title);
		    $('#skin_dimenstion').html("Dimension<br>"+dimen);
		$('#skin_id').val(id);
	<?php }?>
 
 <?php }?>
     $('#skin tr').click(function (event) {
		var id = $(this).attr('id');
		var url =$(this).attr('alt');
		var title =$(this).attr('title');
		var dimen =$(this).attr('dimen');
		$(this).prevAll().removeAttr('style');
		$(this).nextAll().removeAttr('style');
		$(this).css("background", "#ddd");
		  $('#preview_skin').html('<img src="'+url+'" width="380" height="250">');
		   $('#skin_title').html("Title<br>"+title);
		    $('#skin_dimenstion').html("Dimension<br>"+dimen);
		$('#skin_id').val(id);
     });
 });
	function changefunction(id){
		var role='<?php echo $role_id;?>';
		
		if(role=='2'){
			document.getElementById('editbutton').disabled = false;
			document.getElementById("edithref").href="<?=base_url()?>publishing/add?id="+id;
			document.getElementById('deletebutton').disabled = false;
			document.getElementById("deletehref").setAttribute("onclick","return deletebox('"+id+"');");
		}
	}
	function deletebox(id){
		bootbox.confirm("Are you sure you want to Delete video", function (confirmed) {
				if (confirmed) {
					var url = "<?=base_url()?>publishing/delete?id="+id;
					window.location.assign(url);
				}
		})
		return false;
	}

	
</script>

