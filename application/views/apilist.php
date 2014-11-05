<script type="text/javascript">
	if (typeof JSON === 'undefined') {
		document.write('<sc' + 'ript type="text/javascript" src="<?php echo base_url();?>assets/js/validatejson/json2.js"></sc' + 'ript>');
	}
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/validatejson/jsl.parser.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/validatejson/jsl.format.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/validatejson/jsl.interactions.js"></script>
<div class="wrapper row-offcanvas row-offcanvas-left">
	<!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">                
	
		<div>
			<div id="msg_div">
				<?php echo $this->session->flashdata('message');?>				
			</div>	
		</div>
		                <!-- Content Header (Page header) -->
                <section class="content-header no-margin">
                    <h1>
                        API
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <!-- MAILBOX BEGIN -->
                    <div class="mailbox row">
                        <div class="col-xs-12">
                            <div class="box box-solid">
                                <div class="box-body">
									<!-- BOXES are complex enough to move the .box-header around.
										 This is an example of having the box header within the box body -->
									<div class="box-header">
										<i class="fa fa-inbox"></i>
										<h3 class="box-title">API List</h3>
									</div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-4">
                                            <!-- Navigation - folders-->
                                            <div class="sidebar" style="margin-top: 15px;">
                                                <ul class="sidebar-menu nav nav-pills nav-stacked">
                                                    <?php /*/ ?><li class="<?php if($tab == 'users') { echo 'active'; } else { } ?>"><a href="<?php echo base_url(); ?>apilist/indexapilist/index?action=<?php echo base64_encode('users'); ?>"><i class="fa fa-user"></i> Users</a></li><?php /*/ ?>
                                                    <li class="<?php if($tab == 'category') { echo 'active'; } else { } ?>"><a href="<?php echo base_url(); ?>apilist/index?action=<?php echo base64_encode('category'); ?>"><i class="fa fa-list-ul"></i> Category</a></li>
                                                    <li class="treeview <?php echo (($tab == 'videoslist') || ($tab == 'categoryvideos') || ($tab == 'videodetails') || ($tab == 'mostpopular') || ($tab == 'relatedvideos') || ($tab == 'featuredvideos') || ($tab == 'recentvideos')  || ($tab == 'recentvideos'))?'active':''; ?>">
														<a href=""><i class="fa fa-play-circle"></i> Video<i class="fa fa-angle-left pull-right"></i></a> 
														<ul class="treeview-menu">
															<li class="<?php echo ($tab == 'videoslist')?'active':'';?>"><a href="<?php echo base_url(); ?>apilist/index?action=<?php echo base64_encode('videoslist'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Videos List'); ?></a></li>
															<li class="<?php echo ($tab == 'categoryvideos')?'active':'';?>"><a href="<?php echo base_url() ?>apilist/index?action=<?php echo base64_encode('categoryvideos'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Category Videos'); ?></a></li>
															<li class="<?php echo ($tab == 'videodetails')?'active':'';?>"><a href="<?php echo base_url() ?>apilist/index?action=<?php echo base64_encode('videodetails'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Video Details'); ?></a></li>
															<li class="<?php echo ($tab == 'mostpopular')?'active':'';?>"><a href="<?php echo base_url() ?>apilist/index?action=<?php echo base64_encode('mostpopular'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Most Popular'); ?> </a></li>
															<li class="<?php echo ($tab == 'relatedvideos')?'active':'';?>"><a href="<?php echo base_url() ?>apilist/index?action=<?php echo base64_encode('relatedvideos'); ?>"><i class="fa fa-angle-double-right"></i>  <?php echo $welcome->loadPo('Related Videos'); ?></a></li>
															<li class="<?php echo ($tab == 'featuredvideos')?'active':'';?>"><a href="<?php echo base_url() ?>apilist/index?action=<?php echo base64_encode('featuredvideos'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Featured Videos'); ?></a></li>                   
															<li class="<?php echo ($tab == 'recentvideos')?'active':'';?>"><a href="<?php echo base_url() ?>apilist/index?action=<?php echo base64_encode('recentvideos'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $welcome->loadPo('Recent Videos'); ?></a></li>                   
														</ul>
													</li>
                                                </ul>
                                            </div>
                                        </div><!-- /.col (LEFT) -->
                                        <div class="col-md-9 col-sm-8">
											<div class="table-responsive">
												<?php if(isset($categoryData)) { ?>
												<div class="row">
													<div class="form-group col-lg-10">
														<div class="input select">
															<label for="searchParentId"><?php echo $welcome->loadPo('Parent').' '.$welcome->loadPo('Category'); ?></label>
															<select name="parent_id" class="form-control" placeholder="Parent Category" id="searchParentId" onchange="return getCatDataApi(this.value, '<?php echo base64_encode($tab); ?>');">
																<option value="">--<?php echo $welcome->loadPo('Select'); ?>--</option>
																<?php foreach($categoryData as $cat){?>
																	<option value="<?php echo base64_encode($cat->id);?>" <?php if(isset($catIdN)&& $cat->id==$catIdN){ echo 'selected="selected"';}?>><?php echo $cat->category;?></option>
																<?php } ?>
															</select>
														</div>
													</div>
												</div>
												<?php } ?>
												<div class="row">
													<div class="form-group col-lg-10">
														<div class="input text">
															<label for="apiurl"><?php echo $welcome->loadPo('Api Url'); ?></label>
															<input name="apiurl" class="form-control" placeholder="<?php echo $welcome->loadPo('Api url'); ?>" maxlength="255" type="text" id="apiurl" value="<?php echo $url; ?>" readonly />
															<?php echo form_error('category','<span class="text-danger">','</span>'); ?>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-lg-10">
														<div class="input text">
															<label for="response"><?php echo $welcome->loadPo('Api Response'); ?></label>
															    <form id="JSONValidate" method="post" action="." name="JSONValidate">
																	<input type="hidden" id="reformat" value="1" />
																	<input type="hidden" id="compress" value="0" />
																	<div>																		
																		<textarea name="json_input" id="json_input" class="form-control json_input" readonly placeholder="<?php echo $welcome->loadPo('Response...'); ?>" rows="15"><?php echo $response;?></textarea>
																	</div>
																	<button name="validate" id="validate" value="Validate" class="button left bold" onclick="javascript: pageTracker._trackPageview('/validate');">Validate</button>
																</form>
																
														</div>
													</div>
												</div>
												<div id="results_header" class="hide"></div>
											</div><!-- /.table-responsive -->
                                        </div><!-- /.col (RIGHT) -->
                                    </div><!-- /.row -->
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col (MAIN) -->
                    </div>
                    <!-- MAILBOX END -->

                </section><!-- /.content -->
            
	</aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	if (typeof _gat !== "undefined" ) {
		var pageTracker = _gat._getTracker("UA-2118091-3");
		pageTracker._initData();
		pageTracker._trackPageview();
	}
	
	function getCatDataApi(catid, tab)
	{	
		location.href = '<?php echo base_url() ?>apilist/index?action='+tab+'&id='+catid ;
	}
</script>
