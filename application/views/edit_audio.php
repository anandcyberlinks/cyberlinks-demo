<style>
    .error{
        color: red;
    }
</style>
<aside class="right-side">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Audio
            <small>Audio Upload</small> &nbsp;<a href="<?=  base_url().'audio'?>" class="btn btn-warning btn-sm"><i class="fa fa-mail-reply"></i>&nbsp; Back</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php base_url(); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php base_url() . 'audio'; ?>"><i class="fa fa-dashboard"></i> Audio</a></li>
            <li class="active">Upload</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="msg_div"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Quick Example</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form action="" method="post" id="registerId">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="title" name="title" class="form-control" placeholder="Title" value="<?= (isset($result)) ? $result[0]->title : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control"><?= (isset($result)) ? $result[0]->description : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="Category">Category</label>
                                <select class="form-control" name="category_id">
                                    <option value="">-Select-</option>
                                    <?php foreach ($cat as $val){ ?>
                                    <option value="<?=$val->id?>" <?= (isset($result[0]) && $result[0]->category_id == $val->id) ? 'selected="true"' : '' ?>><?=$val->category?></option>
                                        <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <input type="checkbox" name="status" value="1">
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            <a href="<?=  base_url().'audio'?>" class="btn btn-warning"><i class="fa fa-mail-reply"></i>&nbsp; Cancel</a>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
    </section><!-- /.content -->
</aside>
