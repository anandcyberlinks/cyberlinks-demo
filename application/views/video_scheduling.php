								<form action="" method="post" accept-charset="utf-8">
								<div class="box box-solid">
									<div class="box-body">
										<div class="row">
											<div class="form-group col-lg-6">                                    
												<label>
													<input type="radio" name="r2" class="minimal-red" value="Always" checked/>  <?php echo $welcome->loadPo('Always')?> 
												&nbsp;&nbsp;&nbsp;
													<input type="radio" name="r2" class="minimal-red" value="Period"/>  <?php echo $welcome->loadPo('Period')?> 
												</label>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-lg-3">
												<div class="input text">
													<label for="url"><?php echo $welcome->loadPo('Start Date') ?></label>
													<input type="text" class="form-control"  id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start Date') ?>">													
												</div>
											</div>
											<div class="col-lg-2">
												<div class="input text">
													<label for="url"><?php echo $welcome->loadPo('Start').' '.$welcome->loadPo('Time') ?></label>
													<div class="bootstrap-timepicker">
														<input type="text" class="form-control timepicker" name="timepickerstart"/>									
													</div>
												</div>
											</div>
											<div class="form-group col-lg-3">
												<div class="input text">
													<label for="url"><?php echo $welcome->loadPo('End Date')?></label>
													<input type="text" class="form-control"  id="datepickerend" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End Date') ?>">													
												</div>
											</div>
											<div class="col-lg-2">
												<div class="input text">
													<label for="url"><?php echo $welcome->loadPo('End').' '.$welcome->loadPo('Time') ?></label>
													<div class="bootstrap-timepicker">
														<input type="text" class="form-control timepicker"name="timepickerend" />									
													</div>
												</div>
											</div>
										</div>						
									</div>
									<div class="box-footer">
										<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Submit"><?php echo  $welcome->loadPo('Save') ?> </button>
									</div>
								</div>
								</form>
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div><!-- /.col -->
				</div> <!-- /.row -->
			</div>
		</section>
	</aside>
</div>
