<div class="content-wrapper" style="min-height: 308px;">
    <!-- Main content -->
    <section>
        <div class="content body">
            <?php echo $this->session->flashdata('message'); ?>
            <h2 class="page-header">Manage Permission For User:-  <b><?php echo ucfirst($username) ?></b></h2>
            <form action="" method="post"><div class="pull-right"><input type="submit" name="save" value="Save" class="btn btn-success btn-sm"></div>
                <p class="lead">
                    Modules and Sub-Modules
                </p>
                <div class="row">
                    <?php foreach ($module as $val) { ?>
                        <div class="col-sm-3">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $val->name ?></h3>
                                    <span class="label label-primary pull-right"><i class="fa <?php echo $val->icon ?>"></i></span>
                                </div><!-- /.box-header -->
                                <ul>
                                    <li>
                                        <input class="icheckbox_flat-green" type="checkbox" <?php if ($val->permit == 1) { echo 'checked';} ?> /> <?php echo $val->name ?>
                                        <ul>
                                            <?php foreach ($val->child as $child) { ?>
                                                <li>
                                                    <input name="module[<?php echo $child->id ?>]" value="<?php echo $val->id ?>" type="checkbox" <?php if ($child->permit == 1) { echo 'checked';} ?> /><?php echo $child->name ?>
                                                </li>
                                        <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                        <?php } ?>
                </div><!-- /.col -->
            </form>
        </div><!-- /.row -->
    </section>
</div><!-- /.content -->
</div>
<script>
    $('li :checkbox').on('click', function () {
        var $chk = $(this),
                $li = $chk.closest('li'),
                $ul, $parent;
        if ($li.has('ul')) {
            $li.find(':checkbox').not(this).prop('checked', this.checked)
        }
        do {
            $ul = $li.parent();
            $parent = $ul.siblings(':checkbox');
            if ($chk.is(':checked')) {
                $parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0);
            } else {
                $parent.prop('checked', false)
            }
            $chk = $parent;
            $li = $chk.closest('li');
        } while ($ul.is(':not(.someclass)'));
    });
</script>
<style>
    .box-primary{
        height: 300px!important;
    }
    ul
    {
        list-style-type: none;
    }
</style>