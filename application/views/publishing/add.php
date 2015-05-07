<style>
    .error{
        color: red;
    }
</style>
<?php $uri = $this->uri->segment(1); ?>
<!--div class="wrapper row-offcanvas row-offcanvas-left"-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $welcome->loadPo('Skins'); ?><small><?php echo $welcome->loadPo('Control panel'); ?></small></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i><?php echo $welcome->loadPo('Dashboard') ?></a></li>
                <li><a href="<?php echo base_url() . $uri ?>"><i class="fa fa-laptop"></i><?php echo $welcome->loadPo('Skin') ?></a></li>
                <li class="active"><?php if(isset($result['id'])&&($result['id']!='')){ $skin='Edit';echo $welcome->loadPo('Edit Skin');}else{$skin='Add';echo $welcome->loadPo('Add Skin');} ?></li>
            </ol>
            
        </section>
        <!-- Main content -->
        <section class="content">                
            <div id="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title"><?php echo $welcome->loadPo('Skin') . ' ' . $welcome->loadPo($skin); ?></h3>
                                <div class="box-tools pull-right">
                                    <a href="<?php echo base_url() . $uri; ?>" class="btn btn-default btn-sm"><?php echo $welcome->loadPo('Back'); ?></a>
                                </div>
                            </div><!-- /.box-header -->
                            <link href="<?php echo base_url(); ?>assets/css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
                            <!-- form start -->
                            <form action="" id="CategoryForm" method="post" accept-charset="utf-8" enctype="multipart/form-data" onsubmit="return validate();">
                                <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
                                <input type="hidden" name="data[Category][id]" id="CategoryId"/>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <div class="input text">
                                                <label for="Title"><?php echo $welcome->loadPo('Title'); ?></label>
                                                <input name="title" value="<?php if(isset($result['title'])&&($result['title']!='')){echo $result['title'];}else{echo set_value('title');} ?>" class="form-control" required placeholder="<?php echo $welcome->loadPo('Title'); ?>" maxlength="255" type="text" id="title" />
                                                <span id="error_title" class="text-danger"></span>
                                            </div>
                                            
                                        </div>
                                    </div>                                                                    
                                
                                    <div class="row">
                                        <div class="form-group col-lg-5">
                                            <label for="Description"><?php echo $welcome->loadPo('Description'); ?></label>
                                            <textarea name="description" class="form-control" placeholder="<?php echo $welcome->loadPo('Description'); ?>" id="Description"><?php if (isset($_POST['description'])) {
                                                    echo $_POST['description'];
                                                }else if(isset($result['description'])&&($result['description']!='')){echo $result['description'];} ?></textarea>
                                                <span id="error_Description" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="form-group col-lg-5">
                                            <label for="Dimension"><?php echo $welcome->loadPo('Dimensions'); ?></label>
                                            <div class="input-group">                                            
                                                <div class="input text">
                                                    <input name="dimension" required class="form-control" placeholder="<?php echo $welcome->loadPo('Dimension'); ?>" type="text" value="<?php if(isset($result['dimension'])&&($result['dimension']!='')){echo $result['dimension'];} ?>" id="dimension"/>
                                                <span id="error_dimension" class="text-danger"></span>
                                                </div>                                                
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php if(isset($result['id'])&&($result['id']!='')){ ?>
                                        <div class="form-group col-lg-5">
                                            <label for="Image"><?php echo $welcome->loadPo('Preview'); ?></label>&nbsp;&nbsp;
                                            <img width="300" height="100" src='<?php if(isset($result['image'])&&($result['image']!='')){echo '../'.$result['image'];} ?>' >
                                           <br/> <br/> <label for="Image"><?php echo $welcome->loadPo('Image'); ?></label>&nbsp;&nbsp;
                                            <span class="btn btn-default btn-file btn-sm">
                                             <?php echo $welcome->loadPo('Choose Media') ?> <input name="image_file"  id="image_file"  atr="files" type="file" onchange="return validateFileSelected(this,'image_file');"/>
                                            </span>
                                            <span id="name_image_file" class="text-success"></span>
                                            <span id="error_image_file" class="text-danger"></span>
                                        </div>
                                        <?php }else{?>
                                        <div class="form-group col-lg-5">
                                            <label for="Image"><?php echo $welcome->loadPo('Image'); ?></label>&nbsp;&nbsp;
                                            <span class="btn btn-default btn-file btn-sm">
                                             <?php echo $welcome->loadPo('Choose Media') ?> <input name="image_file"  id="image_file"  atr="files" type="file" onchange="return validateFileSelected(this,'image_file');"/>
                                            </span>
                                            <span id="name_image_file" class="text-success"></span>
                                            <span id="error_image_file" class="text-danger"></span>
                                        </div>
                                        <?php }?>
                                    </div>
                                    
                                     <div class="row">
                                       
                                        <div class="form-group col-lg-5">
                                            <label for="Skin"><?php echo $welcome->loadPo('Skin'); ?></label>&nbsp;&nbsp;
                                            <span class="btn btn-default btn-file btn-sm">
                                             <?php echo $welcome->loadPo('Choose File') ?> <input name="skin_file"  id="skin_file"  atr="files" type="file" onchange="return validateFileSelected(this,'skin_file');"/>
                                            </span>
                                            <span id="name_skin_file" class="text-success"></span>
                                            <span id="error_skin_file" class="text-danger"></span>
                                        </div>
                                        
                                    </div>
                                     
                                    <div class="row">    
                                        <div class="form-group col-lg-5">
                                            <label for="Status"><?php echo $welcome->loadPo('Status'); ?>
                                                <input type="hidden" name="status" id="status" value="0"/>
                                                <span align="left"><input type="checkbox" name="status" value="1" checked="<?php if(isset($result['status'])&&($result['status']=='1')){echo 'checked';}?>" /></span></label>
                                            <span id="error_status" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" name="submit" value="<?php if(isset($result['id'])&&($result['id']!='')){echo 'Update';}else{echo 'Submit';}?>" class="btn btn-primary"><?php echo $welcome->loadPo('Submit'); ?></button>
                                    <a href="<?php echo base_url() . $uri; ?>" class="btn btn-default"><?php echo $welcome->loadPo('Cancel'); ?></a>
                                </div>
                                
                                <input type='hidden' name='errorimage' id='errorimage' value='error'/>
                                <input type='hidden' name='errorzip' id='errorzip' value='error'/>
                            </form>                            
                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
<!--/div--><!-- ./wrapper -->
<script>
    function validate(){
        var id='<?php if(isset($result['id'])&&($result['id']!='')){echo $result['id'];}else{echo '0';} ?>';
        var valerr=0;
        var title=$("#title").val();
        var Description=$("#Description").val();
        var dimension=$("#dimension").val();
        var image_file=$("#image_file").val();
        var skin_file=$("#skin_file").val();
        var status=$("#status").val();
        var errorimage=$("#errorimage").val();
        var errorzip=$("#errorzip").val();
        if (id=='0'){
            if(errorzip == 'error'){
               document.getElementById("error_skin_file").innerHTML = "Please select file";
               valerr++;
            }
            if (errorimage == 'error' ) {
               document.getElementById("error_image_file").innerHTML = "Please select file";
               valerr++;
            }
        }
        if(errorzip == 'success'){
            if (errorimage == 'error' ) {
               document.getElementById("error_image_file").innerHTML = "Please select file";
               valerr++;
            }
        }
        if (valerr > 0) {
           return false;
        }else{
           return true;
        }
        //code
    }
    function validateFileSelected(_obj,type) {
        //var id='<?php if(isset($result['id'])&&($result['id']!='')){echo $result['id'];}else{echo '0';} ?>';
       
        document.getElementById("name_"+type).innerHTML = _obj.value;
        var errorimg = document.getElementById("errorimage");
        var errorzip = document.getElementById("errorzip");
        var fileSizeLimitInMB = 5;
        var sFileName = _obj.name;
        var sFileCHK = _obj.value.split('.');
        var fileName = sFileCHK['0'];
        var file_exist = false;
        $.ajax({
            type: 'POST',
            async: false,
            url: '<?= base_url() ?>publishing/validfile',
            data: {fileName:fileName},
            success: function (responce) {
                if (responce=='exist') {
                    file_exist=true;
                }
            }
        });
        var file_list = _obj.files;
        if (file_list.length > 0) {
            var sFileExtension = _obj.value.split('.').pop();
            var iFileSize = file_list[0].size;
            var iConvert = (iFileSize / (1024 * 1024)).toFixed(2);
        
                 /* if (!(sFileExtension === "txt" || sFileExtension === "csv" || sFileExtension === "xlsx" || sFileExtension === "xls" || sFileExtension === "zip" || sFileExtension === "z7" || sFileExtension === "rar") || iConvert > fileSizeLimitInMB) {*/
                if(type=='image_file'){
                    if (!(sFileExtension === "png" || sFileExtension === "jpeg" )   || (iConvert > fileSizeLimitInMB )) {
                            txt = "File type : " + sFileExtension + "\n\n";
                            txt += "Size: " + iConvert + " MB " + "\n\n";
                            txt += "<br>Please make sure your image is in png, jpeg format and less than " + fileSizeLimitInMB + " MB. " + "\n\n";
                            document.getElementById("name_"+type).innerHTML='';
                            errorimg.value='error';
                            document.getElementById("error_"+type).innerHTML = txt.replace("\n\n", "<br>");
                            document.getElementById("error_"+type).style.display = 'block';
                            var input = $(_obj);
                            input.replaceWith(input.val('').clone(true));
                            //alert(txt);
                            return false;
                    }else {
                        errorimg.value='success';
                        document.getElementById("error_"+type).innerHTML = '';
                        document.getElementById("error_"+type).style.display = 'none';
                        return true;
                    }
                }else{
                    if (!(sFileExtension === "zip" || sFileExtension === "z7" || sFileExtension === "rar")   || (iConvert > fileSizeLimitInMB )) {
                                txt = "File type : " + sFileExtension + "\n\n";
                                txt += "Size: " + iConvert + " MB " + "\n\n";
                                txt += "<br>Please make sure your file is of UNIQUE NAME and is in zip, z7, rar file format and less than " + fileSizeLimitInMB + " MB. " + "\n\n";
                                document.getElementById("name_"+type).innerHTML='';
                                errorzip.value='error';
                                document.getElementById("error_"+type).innerHTML = txt.replace("\n\n", "<br>");
                                document.getElementById("error_"+type).style.display = 'block';
                                var input = $(_obj);
                                input.replaceWith(input.val('').clone(true));
                                //alert(txt);
                                return false;
                         
                    }else if(file_exist==true){
                                txt = "<br>Skin already exist." + "\n\n";
                                document.getElementById("name_"+type).innerHTML='';
                                errorzip.value='error';
                                document.getElementById("error_"+type).innerHTML = txt.replace("\n\n", "<br>");
                                document.getElementById("error_"+type).style.display = 'block';
                                var input = $(_obj);
                                input.replaceWith(input.val('').clone(true));
                    } else {
                        errorzip.value='success';
                        document.getElementById("error_"+type).innerHTML = '';
                        document.getElementById("error_"+type).style.display = 'none';
                        return true;
                    }
                }
        }
        return false;
    }
</script>