</div><!-- ./wrapper -->


<!-- jQuery 2.0.2 -->
<script src="<?= base_url() ?>assets/js/jquery.min.js" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="<?= base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>assets/js/AdminLTE/app.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script>
    $('#fileUpload').on('change', function () {
        var fileType = this.files[0].type;
        var fileName = this.files[0].name;
        if (fileType == 'text/csv' || fileType == 'application/csv' || fileType == 'application/vnd.ms-excel') {
            $('#fileUpload').hide();
            $("#msg_file").html(fileName);
        } else {
            $('#fileUpload').val('');
            var msg = "<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button><b>Alert!</b> Please Select Valid CSV File</div>";
            $("#msg").html(msg);
            $("#msg").fadeIn();
            $("#msg").fadeOut(3500);
        }
    });
    
    $('#submitcsv')
            .submit(function (e) {
                $.ajax({
                    url: $('#submitcsv').attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false
                }).done(function (data) {
                    $('#dvCSV').html(data);
                });
                e.preventDefault();
            });

</script>
</body>
</html>