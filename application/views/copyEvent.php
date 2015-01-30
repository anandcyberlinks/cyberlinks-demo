<div id="form">
<form action="<?=  base_url().'webtv/eventCopy?playlist_id='.$_GET['playlist_id']?>" method="post" id="copyform">
    <input type="hidden" value="<?php echo date('Y-m-d',strtotime($_GET['date'])); ?>" name="datecopy" />
    <input type="hidden" value="<?php echo $_GET['url']?>" name="url" />
    <input type="hidden" name="playlist_id" value="<?= $_GET['playlist_id'] ?>" />
    <input type="text" class="form-control" id="datepickerstart" autocomplete="off" name="date" placeholder="Select Date" readonly="true"/><br>
    <input type="submit" name="submit" value="Copy" class="btn btn-success" id="submit"/><div id="loader"></div>
</form>
</div><br>
<div id="failed" style="color: red"></div>
<script>
    $("#datepickerstart").datepicker({
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
    });
    $("#copyform").submit(function (event) {
        $("#failed").html('');
        $("#submit").addClass('disabled');
        $("#loader").html('Loading......')
        event.preventDefault();
        var $form = $(this),
            playlist_id = $form.find("input[name='playlist_id']").val();
            date = $form.find("input[name='date']").val();
            if(date == ""){
                //console.log("blank");
                $("#loader").html('');
                $("#failed").html('Please select date to copy');
                $("#submit").removeClass('disabled');
                return false;
            }
            datecopy = $form.find("input[name='datecopy']").val();
            url = $form.find("input[name='url']").val();
            
            url = $form.attr("action");
        var posting = $.post(url, {playlist_id: playlist_id, date: date, datecopy:datecopy, url:url});
        posting.done(function (data) {
            data = JSON.parse(data);
            
            if(data.success != 0){
                
                $("#form").html(data.success+' Events Succesfully Copied');
            }else{
                $("#submit").removeClass('disabled');
            }
            if(data.failed != 0){
                $("#loader").html('');
                $("#failed").html(data.failed+' Events Allready exist at same time')
            }
            console.log(data);
            
        });
    });
</script>

