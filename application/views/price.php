<form action="<?php echo base_url()?>package/price" method="POST" id="registerId">
<div id="video">
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
        <td><input type="number" name="prive[<?php echo $value->id?>]" value="<?php if(isset($value->price)){ echo $value->price; } ?>" required></td>
        
    </tr>
<?php } ?>

    </table>
</div>
<br>
<input type="hidden" name="package_id" value="<?php echo $this->uri->segment(3)?>">
<input class="btn btn-success" type="submit" name="submit" value="submit">
</form>