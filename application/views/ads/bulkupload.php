<?php $tab = $this->uri->segment(3); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo  $welcome->loadPo('Ads') ?><small><?php  echo  $welcome->loadPo('Control panel') ?></small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                                <li><a href="<?php echo base_url(); ?>ads"><i class="fa fa-play-circle"></i><?php echo $welcome->loadPo('Ads') ?></a></li>
                                <li class="active"><?php echo $welcome->loadPo('Bulk Upload') ?></li>
			</ol>
		</section>
		<div id="msg_div"><?php echo $this->session->flashdata('message');?></div>	
		<?php if(isset($s_error)&& !empty($s_error)){ ?><div id="msg_div"><?php echo $s_error; ?></div><?php } ?>
		<?php if(isset($f_error_d)&& !empty($f_error_d)){ ?><div id="msg_div"><?php echo $f_error_d; ?></div><?php } ?>
		<?php if(isset($f_error_s)&& !empty($f_error_s)){ ?><div id="msg_div"><?php echo $f_error_s; ?></div><?php } ?>
		<?php if(isset($f_error_m)&& !empty($f_error_m)){ ?><div id="msg"><?php echo $f_error_m; ?></div><?php } ?>
		<?php if(isset($error)&& !empty($error)){ ?><div id="msg_div"><?php echo $error; ?></div><?php } ?>
		<div id ="msgftp"></div>
		<!-- Main content -->
		<section class="content">		
			<div id="content">
				<div class="row">
					<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="<?=($tab==='csv')?'active':''?>" ><a href="<?php echo base_url();?>ads/bulkupload/csv"><?php echo  $welcome->loadPo('From CSV') ?></a></li>
								<li class="<?=($tab==='ftp')?'active':''?>" ><a href="<?php echo base_url();?>ads/bulkupload/ftp"><?php echo  $welcome->loadPo('From FTP') ?></a></li>
								<li class="pull-right">&nbsp;</li>
							</ul>
							<div class="tab-content">
								<!-- simple upload section starts -->
								<?php if($tab == 'csv') {?>
								<div class="tab-pane active" id="tab_csv">
									<div class="box box-solid">
										<form action="" id="csvBulkuploadForm" name="csvBulkuploadForm" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
											<input type="hidden" name="redirect_url" id="redirect_url" value="<?php echo base_url(); ?>ads" />											
											<div class="box-body">
											<!-- 	<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div> -->
												<div class="form-group">
													<div class="row">
														<div class="col-xs-10">
															<span class="btn btn-default btn-file btn-sm">
																<?php echo  $welcome->loadPo('Choose CSV') ?> <input name="csv_ads_file"  id="csv_ads_file"  atr="files" type="file"/>
															</span>
														</div>
														<div class="col-xs-2" id="displayfile"></div>
													</div>
												 </div>
												<div id="status_csv_file" style="color:red;"  class="callout-danger" ></div>
												<div id="csvFileList" ></div>
										 	</div><!-- /.box-body -->
											<div class="box-footer">
												<button name="Submit" id="uploadadscsv" value="Upload" class="btn btn-primary btn-sm"><?php  echo  $welcome->loadPo('Upload') ?></button>
												<a href="<?php echo base_url(); ?>assets/upload/csv/sample_ads.csv" class="btn btn-default btn-sm"><?php  echo  $welcome->loadPo('Download Sample File') ?></a>
											</div>
										</form>
										 <!--<div id="target-div5"></div> -->
									</div><!-- /.box -->
								</div>
								<?php } ?>
								<!-- csv bulk upload section ends -->
								
								<!-- ftp bulk upload section starts -->
								<?php if($tab == 'ftp') { ?>
								<div class="tab-pane active" id="tab_ftp">
									<form action="" id="ftpBulkuploadForm" method="post" accept-charset="utf-8">
										<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
											<div class="box box-solid">
												<div class="box-body">
													<div class="form-group">
														<div class="row">
															<div class="col-xs-3">
																<div class="input text">
																	<input name="ftpserver" class="form-control" id="ftpserver" type="text" value="<?php echo set_value('ftpserver');?>"   placeholder="<?php echo $welcome->loadPo('FTP Server'); ?>">
																	<span class="text-danger" id="error1" style='display:none'>The FTP Server field is required.</span>	
																</div>
															</div>
															<div class="col-xs-3">
																<div class="input text">
																	<input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $welcome->loadPo('User Name'); ?>" value="<?php echo set_value('username');?>" >
																	<span class="text-danger" id="error2" style='display:none'>The User Name field is required.</span>
																</div>
															</div>
															<div class="col-xs-3">
																<div class="input password">
																	<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $welcome->loadPo('Password'); ?>" value="" >
																	<span class="text-danger" id="error3" style='display:none'>The Password field is required.</span>
																</div>
															</div>
															<div class="col-xs-1">
																<div class="input text">
																	<input name="data[ftp][port]" class="form-control" placeholder="<?php echo $welcome->loadPo('Port'); ?>" type="text" id="ftpPort"/>
																</div>
															</div>
															<div class="col-xs-1">
																<a class="confirm" onclick="return connect_ads();" href=""  ><button  class="btn btn-success btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Connect'); ?></button></a>
																<!--<button class="btn btn-success btn-sm">Connect</button>-->
															</div>
															<div class="col-xs-1">
																 <div id="displayfileftp" ></div>
															</div>
														</div>
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
									
											<div class="box box-solid hide" id="ftpdownloadcontainer">
												<div class="box-body no-padding">
													<table class="table" id="ftpdownloaddataprocess">
														<tbody>
															<tr>
																<th style="width: 40%">Source</th>
																<th style="width: 10%">Size</th>
																<th style="width: 10%">Type</th>
																<th style="width: 35%">Progress</th>
																<th style="width: 5%">&nbsp;</th>
															</tr>
															<tr>
																<td>file:///var/www/newsnation/assests/abp.mp4</td>
																<td>200 MB</td>
																<td>Mp4</td>
																<td>
																	<div class="progress sm" style="margin-top:7px">
																		<div style="width: 55%" class="progress-bar progress-bar-danger"></div>
																	</div>
																</td>
																<td><span class="badge bg-red">55%</span></td>
															</tr>       
														</tbody>
													</table> 
												</div><!-- /.box-body -->
											</div><!-- /.box -->
									
											<div class="box box-solid " id="ftpcontainer"  style='display:none'>
												<div class="box-body">
													<div class="form-group">
														<div class="row">
															<div class="col-xs-12">
																<label>Path</label>
																<div class="input text">
																	<input name="data[ftp][path]" class="form-control" placeholder="Path" value="/" readonly="readonly" type="text" id="ftpPath"/>
																</div>
															</div>  
														</div>
													</div>
													<div style="overflow: auto; height: 270px;" id="ftpdata">
													</div><!-- /.table-responsive -->
													 <div class="box-footer clearfix"  id="search">
														<div class="pull-right">
														<a class="confirm" onclick="return Download_Ads();" href=""  ><button  class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" >Download</button></a>
														<a href=""  class="btn btn-primary btn-sm" >Reset</a>
															
														</div>
													</div><!-- box-footer -->
												</div><!-- /.box -->
											</div>	
									</form>
								</div>
								<?php } ?>
								<!-- ftp bulk upload section ends -->

							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div><!-- /.col -->
				</div> <!-- /.row -->

				<!--<script src="<?php //echo  base_url(); ?>assets/js/jquery.csv.js"></script> -->
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
