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
                <div class="box">
                    <div class="tab-pane active" id="tab_upload">
                        <div class="box box-solid">
                            <div class="box-header">
                                <h3 class="box-title"><?php echo $welcome->loadPo('Upload') . " " . $welcome->loadPo('Audio') ?></h3>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <!-- form start -->
                            <form action="<?php echo base_url() ?>audio/upload" id="videoUploadForm" class="filse" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                <div style="display:none;">
                                    <input type="hidden" name="_method" value="POST"/></div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <span class="btn btn-default btn-file btn-sm">
                                            <?php echo $welcome->loadPo('Choose MP3') ?> <input name="audio"  id="audio_file"  atr="files" type="file"/>
                                        </span>
                                    </div>
                                    <div id="status_video_file" style="color:red;"  class="callout-danger" ></div>
                                    <div class="box-body" id="displayfile" >
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <a class="confirm"  href=""  ><button id="load" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm" ><?php echo $welcome->loadPo('Upload') ?></button></a>
                                    <a href="<?=  base_url().'audio'?>" class="btn btn-warning btn-sm"><i class="fa fa-mail-reply"></i>&nbsp; Cencel</a>                
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section><!-- /.content -->
</aside>
<script type="text/javascript">
// Variable to store your files
    var files;
// uplaod events
    $('#audio_file').on('change', prepareUpload);
    // Grab the files and set them to our variable
    function prepareUpload(event)
    {
        $('#status_video_file').html('');
        files = event.target.files;
        var fileName = this.files[0].name;
        var fileSize = this.files[0].size;
        var fileType = this.files[0].type;
        var size = fileSize / 1048576;
        var fsize = size.toFixed(2);
        if (!fileType.match(/(?:mp3|mpeg)$/))
        {
            // inputed file path is not an image of one of the above types
            var row_data1 = "";
            row_data1 += '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-check"></i>only avi,wmp,mp4,mpeg,mpg,flv video file is allow!</div><div></section>';
            $('#msg_div').html(row_data1).fadeTo(3000, 500).slideUp(3000);
            $('#displayfile').hide();
            $('#video_file').val('');
            return false;
        }
        var row_data = "";
        row_data += '<table class="table table-bordered"><tr class="unread"><th class="small-col"><?php echo $welcome->loadPo('Filename') ?></th><th class="small-col"><?php echo $welcome->loadPo('FileSize') ?></th><th class="small-col"><?php echo $welcome->loadPo('File Type') ?></th><th class="small-col"><?php echo $welcome->loadPo('Progress') ?></th></tr><tr class="unread"><td class="small-col">' + fileName + '</td><td class="small-col">' + fsize + ' MB</td><td class="small-col">' + fileType + '</td><td class="small-col" id="size"  width="300"><div class="progress progress-striped "><div style="width: ' + 0 + '%" class="progress-bar progress-bar-primary" id="progressbar"></div></div></td></tr></table>';
        $('#displayfile').html(row_data).show();
    }


    $('#load').on('click', uploadFiles);
    // Catch the form submit and upload the files
    function uploadFiles(event)
    {
        var videoFileName = $('#audio_file').val();
        if (videoFileName == "")
        {
            $('#status_video_file').html('Please Select a file to upload.');
            return false;
        } else {
            var data = new FormData();
            $.each(files, function (key, value)
            {
                data.append(key, value);
            });
            $.ajax({
                xhr: function ()
                {
                    var xhr = new window.XMLHttpRequest();
                    //Upload progress
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total * 100;
                            //Do something with upload progress
                            var row_data = "";
                            row_data += '<div class="progress progress-striped"><div style="width: ' + percentComplete + '%" class="progress-bar progress-bar-primary"></div></div></div>';
                            document.getElementById('size').innerHTML = row_data;
                            //console.log(percentComplete);
                        }
                    }, false);
                    //Download progress
                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total * 100;
                            //Do something with download progress
                            //console.log(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
                url: "<?php echo base_url() ?>audio/uploader",
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function (data, textStatus, jqXHR)
                {
                    if (data.flag == 0)
                    {
                        $('#mgs_div').html(data.message).fadeTo(3000, 500).slideUp(3000);
                        $('#displayfile').hide();
                    }
                    else
                    {
                        $('#msg_div').html(data.message).fadeTo(3000, 500).slideUp(3000);
                        $('#displayfile').hide();
                        location.href = "<?php echo base_url() ?>audio/edit?action=" + data.id;
                    }

                }
            });
        }
    }

</script>
