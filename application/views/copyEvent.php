<form action="<?=  base_url().'webtv/eventCopy?playlist_id='.$_GET['playlist_id']?>" method="post" id="copyform">
    <input type="hidden" name="playlist_id" value="<?= $_GET['playlist_id'] ?>" />
    <input type="text" class="form-control" id="datepickerstart" autocomplete="off" name="date" placeholder="Select Date"/><br>
    <input type="submit" name="submit" value="Copy" class="btn btn-success" id="submit"/><div id="loader"></div>
</form>
<script>
    $("#datepickerstart").datepicker({
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 1
    });
    $("#copyform").submit(function (event) {
        $("#loader").html('Loading......')
        event.preventDefault();
        var $form = $(this),
            playlist_id = $form.find("input[name='playlist_id']").val(),
            date = $form.find("input[name='date']").val(),
            url = $form.attr("action");
        var posting = $.post(url, {playlist_id: playlist_id, date: date});
        posting.done(function (data) {
            console.log(data);
            $("#submit").addClass('disabled');
        });
    });
</script>

