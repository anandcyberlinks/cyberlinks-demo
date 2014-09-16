<?php if (!$schedule) { ?>
    <form action="" id="form" method="post" accept-charset="utf-8">
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <input type="radio" name="r2" value="Always" id="id_radio1" checked/>  <?php echo $welcome->loadPo('Always'); ?> 
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="r2" value="Period" id="id_radio2" /> <?php echo $welcome->loadPo('Period'); ?>
                    </div>
                </div>											
                <div class="row collapse" id="div2" >												
                    <div class="form-group col-lg-3">
                        <div class="input text">
                            <label for="url"><?php echo $welcome->loadPo('Start') . ' ' . $welcome->loadPo('Date'); ?></label>
                            <input type="text" class="form-control" id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start') . ' ' . $welcome->loadPo('Date'); ?>" value="">
                            <?php echo form_error('datepickerstart', '<span class="text-danger">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="input text">
                            <label for="url"><?php echo $welcome->loadPo('Start') . ' ' . $welcome->loadPo('Time') ?></label>
                            <div class="bootstrap-timepicker">
                                <input type="text" class="form-control timepicker" id="timepickerstart" name="timepickerstart"value="" />									
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <div class="input text">
                            <label for="url"><?php echo $welcome->loadPo('End') . ' ' . $welcome->loadPo('Date'); ?></label>
                            <input type="text" class="form-control" id="datepickerend" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End') . ' ' . $welcome->loadPo('Date'); ?>" value="">
                            <?php echo form_error('datepickerend', '<span class="text-danger">', '</span>'); ?>

                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="input text">
                            <label for="url"><?php echo $welcome->loadPo('End') . ' ' . $welcome->loadPo('Time') ?></label>
                            <div class="bootstrap-timepicker">
                                <input type="text" class="form-control timepicker" name="timepickerend" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary btn-sm" type="submit" name="submit" value="Submit"><?php echo $welcome->loadPo('Save') ?> </button>
            </div>
        </div>
    </form>
<?php
} else {
    foreach ($schedule as $value) {
        ?>
        <form action="" id="form" method="post" accept-charset="utf-8">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="radio" name="r2" value="Always" id="id_radio1" <?php echo $value->schedule == 'Always' ? "checked" : ''; ?>/>  <?php echo $welcome->loadPo('Always'); ?> 
                            &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="r2" value="Period" id="id_radio2" <?php echo $value->schedule == 'Period' ? "checked" : ''; ?>/> <?php echo $welcome->loadPo('Period'); ?>
                        </div>
                    </div>											
                    <div class="row <?php echo $value->schedule == 'Always' ? "collapse" : '' ?>" id="div2" >
                        <?php
                        $datetime = new DateTime($value->startDate);
                        $startDate = $datetime->format('d-m-Y');
                        $startTime = $datetime->format('h:i A');
                        ?>
                        <div class="form-group col-lg-3">
                            <div class="input text">
                                <label for="url"><?php echo $welcome->loadPo('Start') . ' ' . $welcome->loadPo('Date'); ?></label>
                                <input type="text" class="form-control" id="datepickerstart" name="datepickerstart" placeholder="<?php echo $welcome->loadPo('Start') . ' ' . $welcome->loadPo('Date'); ?>" value="<?php echo $startDate == $startDate ? $startDate : ""; ?>">
                                <?php echo form_error('datepickerstart', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="input text">
                                <label for="url"><?php echo $welcome->loadPo('Start') . ' ' . $welcome->loadPo('Time') ?></label>
                                <div class="bootstrap-timepicker">
                                    <input type="text" class="form-control timepicker" id="timepickerstart" name="timepickerstart"value="<?php echo $startTime; ?>" />									
                                </div>
                            </div>
                        </div>
                        <?php
                        $datetime = new DateTime($value->endDate);
                        $endDate = $datetime->format('d-m-Y');
                        $endTime = $datetime->format('h:i A');
                        ?>
                        <div class="form-group col-lg-3">
                            <div class="input text">
                                <label for="url"><?php echo $welcome->loadPo('End') . ' ' . $welcome->loadPo('Date'); ?></label>
                                <input type="text" class="form-control" id="datepickerend" name="datepickerend" placeholder="<?php echo $welcome->loadPo('End') . ' ' . $welcome->loadPo('Date'); ?>" value="<?php echo $endDate; ?>">
                                <?php echo form_error('datepickerend', '<span class="text-danger">', '</span>'); ?>

                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="input text">
                                <label for="url"><?php echo $welcome->loadPo('End') . ' ' . $welcome->loadPo('Time') ?></label>
                                <div class="bootstrap-timepicker">
                                    <input type="text" class="form-control timepicker" name="timepickerend" value="<?php echo $endTime; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary btn-sm" type="submit" name="submit" value="Submit"><?php echo $welcome->loadPo('Save') ?> </button>
                </div>
            </div>
        </form>
    <?php }
} ?>
</div><!-- /.tab-content -->
</div><!-- nav-tabs-custom -->
</div><!-- /.col -->
</div> <!-- /.row -->
</div>
</section>
</aside>
</div>
