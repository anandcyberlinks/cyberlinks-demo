<aside class="right-side">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Program scheduler
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Blank page</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div id="msg"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-danger">
                    <div class="box-header">
                        <div class="row"><br>
                            <div class="col-xs-3">
                                <form id="submitcsv" action="<?= base_url() ?>csv/readCSV" method="post" enctype="multipart/form-data" >
                                        <div class="form-group">
                                            <span>
                                                &nbsp;<a href="<?= base_url() ?>assets/sample/samplefile.csv" target="_blank">Download sample</a>
                                            </span><br/><br/>
                                            <span class="btn btn-default btn-file btn-sm"><i class="fa fa-fw fa-folder-open-o"></i>
                                                Import CSV <input name="csv" id="fileUpload" atr="files" type="file">
                                            </span>
                                            <span id="msg_file"></span>
                                            </div>
                                        <input type="submit" name="submit" value="Submit" class="btn btn-success"><div id="load"></div>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div id="dvCSV">
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>

    </section><!-- /.content -->
</aside><!-- /.right-side -->

<style>
    .fileUpload {
        position: relative;
        overflow: hidden;
        margin: 10px;
    }
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }

</style>
