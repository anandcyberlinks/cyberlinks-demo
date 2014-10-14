<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Youtube Video') ?><small><?php echo $welcome->loadPo('Control panel') ?></small></h1>
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
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <!-- form start -->
                            <form  method="post" action="<?php echo base_url(); ?>youtubevideo/index" onsubmit="return date_check();" id="searchIndexForm" name="searchIndexForm" accept-charset="utf-8">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <div class="input text">
                                                <input type="text" name="title" id="title" class="form-control" value="" placeholder="<?php echo $welcome->loadPo('Title') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <div class="input select">
                                                <select name="videotype" class="form-control" placeholder="<?php echo $welcome->loadPo('Category') ?>" id="videotype">
																	<option value=""><?php echo $welcome->loadPo('Select type') ?></option>
																	<option value=""><?php echo $welcome->loadPo('Channel') ?></option>
																	<option value=""><?php echo $welcome->loadPo('User') ?></option>
																	<option value=""><?php echo $welcome->loadPo('Video') ?></option>
                                                </select>
                                            </div>
                                        </div>
													 <div class="form-group col-lg-4">
                                            <div class="input text">
																<button type="submit" name="submit" value="Search"class="btn btn-primary"><?php echo $welcome->loadPo('Search') ?></button>                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                            </form>
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
               </div>
					<div class="row">
						<div class="col-xs-12">
							<?php if(isset($result)) {
										$reccount = count($result);
										for($i=0; $i < $reccount; $i++) {
											$rem = $i%4;
							?>
									<?php if(($i =='0') || ($rem == '0') ){ ?><div class="col-xs-12"><?php } ?>
										<div class="col-md-3">
											 <!-- Primary box -->
											 <div class="box box-primary">
													<div class="box-body">
													<input type="checkbox" name="youtubeVideoId" id="youtubeVideoId" value="<?php echo $result[$i]->id; ?>" />
													<h5 class="box-title"><?php echo $result[$i]->title; ?></h5>
														<img src="<?php echo $result[$i]->thumbnail->sqDefault; ?>"  />
														
													</div><!-- /.box-body -->
													<div class="box-footer">
													Duration: <code><?php echo $result[$i]->duration; ?></code>
														<p><?php echo $result[$i]->description; ?>
														</p>
													</div><!-- /.box-footer-->
											 </div><!-- /.box -->
										</div><!-- /.col -->
										<?php if(($rem =='3') || ($i == $reccount) ){ ?></div><?php } ?>
									
							<?php } } else { ?>
									No Records
							<?php } ?>
						</div>
					</div>

				</div>

        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->




