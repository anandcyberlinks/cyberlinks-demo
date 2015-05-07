<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>Video<small>Control panel</small></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Video') ?></a></li>
                                <li class="active"><?php echo $welcome->loadPo('Bulk Upload') ?></li>
			</ol>
		</section>
	<div id ="msgftp">
	</div>
		<!-- Main content -->
		<section class="content">		
			<div id="content">
				<div class="row">
					<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active" ><a href="#tab_1" data-toggle="tab">From CSV</a></li>
								<li class=""><a href="#tab_2" data-toggle="tab">From FTP</a></li>
								<li class="pull-right">&nbsp;</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<div class="box box-solid">
										<div class="box-body">
											<form action="/newsnation/video/bulkupload/csv" id="csvBulkuploadForm" method="post" accept-charset="utf-8">
												<div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
												<div class="form-group">
													<input type="file" name="data[csv][]"  style="display:none;" accept=".csv" id="csvFile"/>
													<button id="chooseFile" class="btn btn-default btn-sm">Choose CSV</button>
												</div>
											</form>
										</div><!-- /.box-body -->
										<div class="box-footer">
											<button type="submit" class="btn btn-primary btn-sm">Upload</button>
											<button type="submit" class="btn btn-sm">Download Sample File</button>
										</div>
									</div><!-- /.box -->
								</div>
								<div class="tab-pane " id="tab_2">
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
																<a class="confirm" onclick="return connect();" href=""  ><button  class="btn btn-success btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Connect'); ?></button></a>
																<!--<button class="btn btn-success btn-sm">Connect</button>-->
															</div>
															<div class="col-xs-1">
																<div class="loader" id='loadingmessage' style='display:none'>
																	
																</div>
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
														<a class="confirm" onclick="return Download();" href=""  ><button  class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" >Download</button></a>
														<a href=""  class="btn btn-primary btn-sm" >Reset</a>
															
														</div>
													</div><!-- box-footer -->
												</div><!-- /.box -->
											</div>	
									</form>
								</div>
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div><!-- /.col -->
				</div> <!-- /.row -->

				<script src="<?php echo  base_url(); ?>assets/js/jquery.csv.js"></script>
			</div>
	    </section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->
	
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