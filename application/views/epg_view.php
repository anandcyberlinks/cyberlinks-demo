<?php //echo '<pre>'; print_r($result); die;
if (count($result) != '0') { ?>
    <form action="<?php echo base_url() ?>package/price" method="POST" id="registerId">
        <table class="table table-bordered dataTable">
            <tr>
                <td>Date</td>
                <td>Title</td>
                <td>Time</td>
                <td>Thumb</td>
                <td>Language</td>
                <td>Type</td>
            </tr>
    <?php foreach ($result as $value) { ?>
                <tr <?php if($value['valid']=='invalid'){ echo "style='background-color:red';"; } ?>>
                    <td><?=$value['date']?></td>
                    <td><?=($value['show_title']=='')?'<b>Title Missing</b>':$value['show_title']?></td>
                    <td><?=($value['show_time']=='')?'<b>Time Missing</b>':$value['show_time']?></td>
                    <td><?=$value['show_thumb']?></td>
                    <td><?=$value['show_language']?></td>
                    <td><?=$value['media_type']?></td>
                </tr>
    <?php } ?>
        </table>
    </div>
    </form>
    <?php
} else {
    echo "No Data Found";
}
?>