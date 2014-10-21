<?php if(count($result) !='0') { ?>
<form action="<?php echo base_url()?>package/price" method="POST" id="registerId">

<label for="package_name" generated="true" class="error"></label><br>
    <input type="radio" name="package_type" class="package_type" value="free" <?php if($type['0']->type == 'free'){ echo "checked";} ?>/>Free
    <input type="radio" name="package_type" class="package_type" value="paid" <?php if($type['0']->type == 'paid'){ echo "checked";} ?>/>Paid
<br>
<div class="video">
    <table class="table table-bordered table-hover dataTable">
        <tr>
            <td>Duration Name</td>
            <td>Days</td>
            <td>Price</td>
        </tr>
<?php foreach($result as $value){?>
    <tr>
        <td><?php echo $value->name; ?></td>
        <td><?php echo $value->days; ?></td>
        <td><input type="number" name="prive[<?php echo $value->id?>]" value="<?php if(isset($value->price)){ echo $value->price; } ?>"></td>
    </tr>
<?php } ?>
    </table>
</div>
<br>
<input type="hidden" name="content_id" value="<?php echo $this->uri->segment(3)?>">
<input type="hidden" name="content_type" value="<?php echo $_GET['type']?>">
<input class="btn btn-success" type="submit" name="submit" value="submit">
</form>
<?php }else{
    echo "No Duration Found";
} ?>

<script src="http://localhost/multitvfinal/assets/js/jquery-1.10.2.js"></script>
<script>
    $(function(){
        if('<?php echo $type['0']->type; ?>'=="free"){
                $(".video").hide();
        }
        $('.package_type').on('change',function(){
            if($(this).attr("value")=="free"){
                $(".video").hide();
            }
            if($(this).attr("value")=="paid"){
                $(".video").show();
            }
        });
    });
</script>