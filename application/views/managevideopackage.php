<style>
    div#video{
        width: 100%;
        height: 300px;
        overflow: scroll;
    }
</style>
  
<form action="<?php echo base_url() ?>package" method="POST">
<div id="video">
    <table class="table table-bordered table-hover dataTable">
        <tr>
            <th>Select</th>
            <th>Title</th>
            <th>Catagory</th>
        </tr>
        <?php foreach($result as $value){ ?>
        <tr id="<?php echo $value->id ?>">
            <td><input type="checkbox" name="content_id[]" value="<?php echo $value->id;?>"<?php if($welcome->check($value->id, $this->uri->segment(3)) == '1'){ echo "checked";}?> ></td>
            <td><?php echo $value->title ?></td>
            <td><?php echo $value->category ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
<br>
<input type="hidden" name="package_id" value="<?php echo $this->uri->segment(3)?>">
<input class="btn btn-success" type="submit" name="submit" value="submit">
</form>