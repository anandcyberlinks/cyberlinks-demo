$(function () {
    $("#eventDates").datepicker({
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#datepickerend").datepicker("option", "minDate", $('#datepickerstart').val());
        }
    });
    $("#eventDatee").datepicker({
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#datepickerstart").datepicker("option", "maxDate", $('#datepickerstart').val());
        }
    });
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 1, format: 'YYYY/MM/DD h:mm:00'});
    //Date range as a button
    $('#daterange-btn').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Last 7 Days': [moment().subtract('days', 6), moment()],
            'Last 30 Days': [moment().subtract('days', 29), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        startDate: moment().subtract('days', 29),
        endDate: moment()
    },
    function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
    });
    $('#status').html('');
    $("#time_div").hide();
    $("#time_div").hide();
    $('#durationDiv').hide();
    $(document).on('click', '.ftpdir', function () {
        var path = $(this).attr('href');
        $('#ftpPath').val(path);
        check();
        return false;
    });

    /*	Start Date and Time in Scheduling */
    $(".timepicker").timepicker({
        minuteStep: 1,
        showInputs: false,
        disableFocus: true
    });
    /*	End Date and Time in Scheduling */

    /*	Start Radio Hide/Show */
    //$("#id_radio1").prop("checked", true);
    $('#id_radio1').click(function () {
        $('#div2').hide();
    });
    $('#id_radio2').click(function () {
        $('#div2').show();
    });
    /* tagit function for keywork in video basic */
    $("#myTags").tagit();
    $('#cropbox').Jcrop({
        aspectRatio: 1,
        onSelect: updateCoords
    });
    /* function to upload thumbnail image using ajax(ry) */
    $('a.thumb_upload').click(function (e) {
        $('#status').html('');
        var content_id = $(this).attr('content_id');
        var str = '<input type="hidden" name="content_id" id="content_id" value="' + content_id + '" \/>';
        $('#myModal1 #prevElement1').html(str);
    });
    $('#thumbImgUpload').click(function (e) {
        var thumb_image = $('#thumb_img').val();
        if (thumb_image == '')
        {
            $('#status').html('Please Select a file to upload.');
            return false;
        }
    });
    $('a.thumb_crop').click(function (e) {
        var content_id = $(this).attr('content_id');
        var str = '<input type="hidden" name="content_id" id="content_id" value="' + content_id + '" \/>';
        $('#myModal2 #prevElement2').html(str);
    });
    $('a.thumb_grab').click(function (e) {
        var file_path = $(this).attr('data-img-url');
        var str = '<script type="text/javascript">';
        str += 'jwplayer("prevElement3").setup({ ';
        str += 'primary: "html5",';
        str += 'file: ' + '"' + file_path + '",';
        str += 'width: ' + '"' + 600 + '",';
        str += 'height: ' + '"' + 280 + '",';
        str += 'events: ';
        str += '{';
        str += 'onPause : function() {';
        str += 'time = jwplayer("prevElement3").getPosition();';
        str += '}';
        str += '}';
        str += '});';
        str += '<\/script>';
        $('#myModal3 #prevElement3').html(str);
    });
    setInterval(function () {
        //var z = (jwplayer("prevElement3").getPosition());
        //	secondsToTime(z);
    }, 600);
    $('a.confirm_delete').click(function (e) {
        e.preventDefault();
        var location = $(this).attr('href');
        bootbox.confirm('Are you sure you want to Delete', function (confirmed)
        {
            if (confirmed)
            {
                window.location.replace(location);
            }

        });
    });
    $('a.confirm_delete_subs').click(function (e) {
        e.preventDefault();
        var location = $(this).attr('href');
        bootbox.confirm('Are you sure you want to Delete Subscription', function (confirmed)
        {
            if (confirmed)
            {
                window.location.replace(location);
            }

        });
    });
    /* other functions */

    $('#daterange').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
   // $("#datepickerend").datepicker("option", "showAnim", 'drop');
    
    $( "#datepicker" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
    $('a.prev_video').click(function (e) {
        var file_path = $(this).attr('data-img-url')
        var str = '<script type="text/javascript">';
        str += 'jwplayer("prevElement").setup({ ';
        str += 'primary: "html5",';
        str += 'width: 600,';
        str += 'height: 600/1.5,';
        str += 'file: ' + '"' + file_path + '"';
        str += '});';
        str += '<\/script>';
        $('#myModal #prevElement').html(str);
    });
    $('a.jsplayerVideo').click(function (e) {
        var file_path = $(this).attr('data-img-url')
        var str = '<script type="text/javascript">';
        str += 'jwplayer("jsplayerV").setup({ ';
        str += 'primary: "html5",';
        str += 'width: 600,';
        str += 'height: 600/1.5,';
        str += 'file: ' + '"' + file_path + '"';
        str += '});';
        str += '<\/script>';
        $('#playerModel #jsplayerV').html(str);
    });
    $('.manaegvideo').on('click', function () {
        var url = $(this).attr('link');
        $.ajax({
            type: "GET",
            url: url,
            dataType: "html",
            success: function (response) {
                bootbox.dialog({
                    message: response,
                    title: "Select Your Video",
                });
            }
        });
    });
    $('.price').on('click', function () {
        var url = $(this).attr('link');
        bootbox.dialog({message: 'wait...', title: "Price"});
        $.ajax({
            type: "GET",
            url: url,
            dataType: "html",
            success: function (response) {
                $('.modal-dialog .modal-content .modal-body .bootbox-body').html(response);
            }
        });
    });
});
/*  function to crop thumbnail image(ry) */
function updateCoords(c) {
    var orig_width = $('#orig_width').val();  // original width of image
    var orig_height = $('#orig_height').val(); // original height of image
    var width_ratio = (orig_width / 560).toFixed(2);  // ratio with displayed image with width
    var height_ratio = (orig_height / 350).toFixed(2);   // ratio with displayed image with height
    var crop_x = c.x;
    var crop_y = c.y;
    var crop_w = c.w;
    var crop_h = c.h;
    var mod_x = Math.round(width_ratio * crop_x);
    var mod_y = Math.round(height_ratio * crop_y);
    var mod_w = Math.round(width_ratio * crop_w);
    var mod_h = Math.round(height_ratio * crop_h);
    $('#x').val(mod_x);
    $('#y').val(mod_y);
    $('#w').val(mod_w);
    $('#h').val(mod_h);
}
;

function checkCoords() {
    if (parseInt($('#w').val()))
        return true;
    alert('Please select a crop region then press submit.');
    return false;
}
;
function secondsToTime(secs) {
    secs = Math.floor(secs);
    var hours = Math.floor(secs / (60 * 60));
    var divisor_for_minutes = secs % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);
    var divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);
    if (seconds) {
        $("#thumbgrabHours").val(hours);
        $("#thumbgrabMinutes").val(minutes);
        $("#thumbgrabSeconds").val(seconds);
    }
}


/* other functions */
function form_submit(id, fla, con) {
    var str = 'vid=' + id + '&fid=' + fla + '&conid=' + con;
    //alert(str);
    $.ajax({
        url: '<?php echo base_url();?>admin/videos/save_flavor_select',
        type: 'post',
        dataType: 'json',
        data: str,
        success: function (data) {

            if (data == 1)
            {
                var ht = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The video transcode in flavor request send successfully.</div>'
                jQuery("#msg_div").html(ht);
                var hts = '<span class="label label-primary">In progress</span>'
                jQuery("#statu_change_" + fla).html(hts);
                $("#btn_" + fla).attr("disabled", "disabled");
            }
            if (data == 0) {
                var ht = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The video transcode in flavor request already sent successfully.</div>'
                jQuery("#msg_div").html(ht);
            }
        }
    });
}
function date_check() {
    var startDate = $('#datepickerstart').val();
    var endDate = $('#datepickerend').val();
    if (startDate != "" && endDate != "") {
        var regExp = /(\d{1,2})\/(\d{1,2})\/(\d{2,4})/;
        if (parseInt(endDate.replace(regExp, "$3$2$1")) < parseInt(startDate.replace(regExp, "$3$2$1"))) {
            bootbox.alert("End date should be greater then to start date");
            return false;
        }
    }
    return true;
}
$('td a.prev_video').click(function (e) {
    var file_path = $(this).attr('data-img-url')
    var str = '<script type="text/javascript">';
    str += 'jwplayer("prevElement").setup({ ';
    str += 'primary: "html5",';
    str += 'width: "850",';
    str += 'height: "850",';
    str += 'file: ' + '"' + file_path + '"';
    str += '});';
    str += '<\/script>';
    $('#myModal #prevElement').html(str);
});

function stopvideo(id) {
    jwplayer(id).stop();
}

function delete_video(id, url, curl)
{
    bootbox.confirm("Are you sure you want to Delete video", function (confirmed) {
        if (confirmed) {
            location.href = url + '?id=' + id + '&curl=' + curl;
        }
    })
}

function delete_adsLocation(id, url, curl)
{
    bootbox.confirm("Are you sure you want to Delete this Ads Location", function (confirmed) {
        if (confirmed) {
            location.href = url + '?id=' + id + '&curl=' + curl;
        }
    })
}

function delete_event(id, url, curl) {
    bootbox.confirm("Are you sure you want to Delete ", function (confirmed) {
        if (confirmed) {
            location.href = url + '?id=' + id + '&curl=' + curl;
        }
    })
}

function delete_url(url) {
    bootbox.confirm("Are you sure you want to Delete", function (confirmed) {
        if (confirmed) {
            location.href = url;
        }
    })
}

function delete_field(id, url, curl) {
    bootbox.confirm("Are you sure you want to Delete Field", function (confirmed) {
        if (confirmed) {
            location.href = url + '?id=' + id + '&curl=' + curl;
        }
    })
}
function delete_pack(id) {
    bootbox.confirm("Are you sure you want to Package", function (confirmed) {
        if (confirmed) {
            location.href = 'package/deletePackage/' + id;
        }
    })
}
function delete_form(id, url, curl) {
    bootbox.confirm("Are you sure you want to Delete Form", function (confirmed) {
        if (confirmed) {
            location.href = url + '?id=' + id + '&curl=' + curl;
        }
    })
}
function delete_comment(id) {
    bootbox.confirm("Are you sure you want to Delete video", function (confirmed) {
        if (confirmed) {
            location.href = url + '?id=' + id;
        }
    })
}
function delete_page(id, url) {
    bootbox.confirm("Are you sure you want to Delete Page", function (confirmed) {
        if (confirmed) {
            location.href = url + '?id=' + id;
        }
    })
}
function delete_role(id) {
    bootbox.confirm("Are you sure you want to Delete video", function (confirmed) {
        if (confirmed) {
            location.href = 'role/deleterole?id=' + id;
        }
    })
}

function delete_video1(id) {
    bootbox.confirm("Are you sure you want to Delete video", function (confirmed) {
        if (confirmed) {
            location.href = '/mobiletv/video/deletevideo?id=' + id;
        }
    })
}

function delete_user(id) {
    bootbox.confirm("Are you sure you want to Delete", function (confirmed) {
        if (confirmed) {
            location.href = 'user/DeleteUser?id=' + id;
        }
    })
}

/* start date and end date  search video page */

$(document).ready(function () {
    $("#datepickerstart").datepicker({
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 1,
        maxDate: 0,
        onSelect: function (selected) {
            $("#datepickerend").datepicker("option", "minDate", $('#datepickerstart').val());
        }
    });
    $("#datepickerend").datepicker({
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 1,
        maxDate: 0,
        onSelect: function (selected) {
            $("#datepickerstart").datepicker("option", "maxDate", $('#datepickerstart').val());
        }
    });
    $("#datepick").datepicker({
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
    });
});



/* bulk upload by CSV file only */

// Variable to store your files
var files;
var csvFilesArr = [];
var colLength = 5;
// uplaod events
$('#csv_file').on('change', prepareUpload);
// Grab the files and set them to our variable

function prepareUpload(event) {
    $('#status_csv_file').html('');
    $('#csvFileList').html('');
    files = event.target.files;
    var fileName = this.files[0].name;
    var fileSize = this.files[0].size;
    var fileType = this.files[0].type;
    var size = fileSize / 1048576;
    var fsize = size.toFixed(2);
    if (this.files && this.files.length > 0) {
        $('#displayfile').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
        filesArray = this.files;
        $.each(this.files, function (index, value) {
            if (value.type == 'text/csv' || value.type=='application/csv') {
                var reader = new FileReader();
                var link_reg = /(http:\/\/|https:\/\/)/i;
                reader.readAsText(value);
                reader.onload = function (file) {
                    var content = file.target.result;
                    var rows = file.target.result.split(/\r\n|\n/);
                    document.getElementById("csvFileList").innerHTML = "";
                    var table = document.createElement('table');
                    table.className = "table table-bordered";
                    var tbody = document.createElement('tbody');
                    for (var i = 0; i < rows.length; i++) {
                        var tr = document.createElement('tr');
                        tr.id = "tr_" + i;
                        var arr = rows[i].split(',');
                        if (arr.length == colLength) {
                            var tmpArr = [];
                            for (var j = 0; j < arr.length; j++) {
                                if (i == 0) {
                                    var td = document.createElement('th');
                                } else {
                                    var td = document.createElement('td');
                                    tmpArr.push(arr[j]);
                                }

                                if (link_reg.test(arr[j])) {
                                    var a = document.createElement('a');
                                    a.href = arr[j];
                                    a.target = "_blank";
                                    a.innerHTML = arr[j];
                                    td.appendChild(a);
                                } else {
                                    td.innerHTML = arr[j];
                                }

                                tr.appendChild(td);
                            }
                            if (i != 0) {
                                csvFilesArr.push(tmpArr);
                            }
                            tbody.appendChild(tr);
                        }
                    }
                    table.appendChild(tbody);
                    document.getElementById('csvFileList').appendChild(table);
                }
            } else {
                var row_data1 = "";
                row_data1 += '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i>Only .csv file accepted</div><div></section>';
                $('#msgftp').html(row_data1).fadeTo(3000, 500).slideUp(3000);
                $('#displayfile').hide();
                return false;
            }
        });
    }
    $('#displayfile').html('');

}

$('#uploadcsv').on('click', function () {
    $('#displayfile').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
    var csvFilesCount = csvFilesArr.length;
    for (var i = 0; i < csvFilesCount; i++) {
        if (csvFilesArr[i].length == colLength) {
            var content_title = csvFilesArr[i]['0'];
            var category = csvFilesArr[i]['1'];
            var keyword = csvFilesArr[i]['2'];
            var url = csvFilesArr[i]['3'];
            var description = csvFilesArr[i]['4'];
            var rowIdTbl = i + 1;
            $.ajax({
                type: "POST",
                url: baseurl + "video/csvupload",
                data: {'content_title': content_title, 'category': category, 'keyword': keyword, 'url': url, 'description': description, 'rowId': rowIdTbl},
                dataType: 'json',
                success: function (data, textStatus, jqXHR)
                {
                    if (typeof data.error === 'undefined')
                    {
                        var rowId = data.rowId;
                        // Success so call function to process the form
                        if (data.message == 'success') {
                            $('#tr_' + rowId).css('backgroundColor', '#82FA58');
                            if (rowId == csvFilesCount) {
                                $('#displayfile').html('');
                            }
                        } else {
                            $('#tr_' + rowId).css('backgroundColor', '#FA5858');
                            if (rowId == csvFilesCount) {
                                $('#displayfile').html('');
                            }
                        }
                    }
                    else
                    {
                        // Handle errors here
                        console.log('ERRORS: ' + data.error);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus);
                }
            });
        }
    }
    return false;
});


/* bulk upload Ads by CSV file only */

// Variable to store your files
var filesAds;
var csvFilesArr1 = [];
var colLength1 = 4;
// uplaod events
$('#csv_ads_file').on('change', prepareUploadads);
// Grab the files and set them to our variable

function prepareUploadads(event) {
    $('#status_csv_file').html('');
    $('#csvFileList').html('');
    filesAds = event.target.files;
   
    var fileName = filesAds[0].name;
    var fileSize = filesAds[0].size;
    var fileType = filesAds[0].type;
    var size = fileSize / 1048576;
    var fsize = size.toFixed(2);
    if (filesAds && filesAds.length > 0) {
        $('#displayfile').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
        filesArray = filesAds
        $.each(filesAds, function (index, value) {            
            if (value.type == 'text/csv' || value.type=='application/csv') {
                var reader = new FileReader();
                var link_reg = /(http:\/\/|https:\/\/)/i;
                reader.readAsText(value);
                reader.onload = function (file) {
                    var content = file.target.result;
                    var rows = file.target.result.split(/\r\n|\n/);
                    document.getElementById("csvFileList").innerHTML = "";
                    var table = document.createElement('table');
                    table.className = "table table-bordered";
                    var tbody = document.createElement('tbody');
                    for (var i = 0; i < rows.length; i++) {
                        var tr = document.createElement('tr');
                        tr.id = "tr_" + i;
                        var arr = rows[i].split(',');
                        if (arr.length == colLength1) {
                            var tmpArr = [];
                            for (var j = 0; j < arr.length; j++) {
                                if (i == 0) {
                                    var td = document.createElement('th');
                                } else {
                                    var td = document.createElement('td');
                                    tmpArr.push(arr[j]);
                                }

                                if (link_reg.test(arr[j])) {
                                    var a = document.createElement('a');
                                    a.href = arr[j];
                                    a.target = "_blank";
                                    a.innerHTML = arr[j];
                                    td.appendChild(a);
                                } else {
                                    td.innerHTML = arr[j];
                                }

                                tr.appendChild(td);
                            }
                            if (i != 0) {
                                csvFilesArr1.push(tmpArr);
                            }
                            tbody.appendChild(tr);
                        }
                    }
                    table.appendChild(tbody);
                    document.getElementById('csvFileList').appendChild(table);
                }
            } else {
                var row_data1 = "";
                row_data1 += '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i>Only .csv file accepted</div><div></section>';
                $('#msgftp').html(row_data1).fadeTo(3000, 500).slideUp(3000);
                $('#displayfile').hide();
                return false;
            }
        });
    }
    $('#displayfile').html('');

}

$('#uploadadscsv').on('click', function () {
    $('#displayfile').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
    var csvFilesCount = csvFilesArr1.length;
    
    for (var i = 0; i < csvFilesCount; i++) {
        if (csvFilesArr1[i].length == colLength1) {
            var content_title = csvFilesArr1[i]['0'];
            var category = csvFilesArr1[i]['1'];
            //var keyword = csvFilesArr[i]['2'];
            var url = csvFilesArr1[i]['2'];
            var description = csvFilesArr1[i]['3'];
            var rowIdTbl = i + 1;
            $.ajax({
                type: "POST",
                url: baseurl + "ads/csvupload",
                data: {'content_title': content_title, 'category': category, 'url': url, 'description': description, 'rowId': rowIdTbl},
                dataType: 'json',
                success: function (data, textStatus, jqXHR)
                {
                    if (typeof data.error === 'undefined')
                    {
                        var rowId = data.rowId;
                        // Success so call function to process the form
                        if (data.message == 'success') {
                            $('#tr_' + rowId).css('backgroundColor', '#82FA58');
                            if (rowId == csvFilesCount) {
                                $('#displayfile').html('');
                            }
                        } else {
                            $('#tr_' + rowId).css('backgroundColor', '#FA5858');
                            if (rowId == csvFilesCount) {
                                $('#displayfile').html('');
                            }
                        }
                    }
                    else
                    {
                        // Handle errors here
                        console.log('ERRORS: ' + data.error);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus);
                }
            });
        }
    }
    return false;
});

/* function to validate url starts */

function validatesrc_url() {
    var srcurl = $('#source_url').val();
    var regYoutube = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var regVimeo = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
    var regDailymotion = /^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/;
    var regMetacafe = /^.*(metacafe\.com)(\/watch\/)(\d+)(.*)/i;
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if (regYoutube.test(srcurl)) {
        return true;
    } else if (regMetacafe.test(srcurl)) {
        return true;
    } else if (regDailymotion.test(srcurl)) {
        return true;
    } else if (regVimeo.test(srcurl)) {
        return true;
    } else if (regexp.test(srcurl)) {
        return true;
    } else {
        alert('Please enter a valid url.');
        $('#source_url').focus();
        return false;
    }
}

/* function to validate url ends */


/* functions to check png image in video setting section starts */

function upload_logo_video() {
    var LogoLinkUrl = $('#playerLogoLink').val();
    if (LogoLinkUrl != "")
    {
        var Extension = LogoLinkUrl.substring(LogoLinkUrl.lastIndexOf('.') + 1).toLowerCase();
        if (Extension == "png")
        {
            return true; // Valid file type
        }
        else {
            alert('Please upload png image only.');
            $('#playerLogoLink').focus();
            return false; // Not valid file type
        }
    }
}


/* functions to check png image in video setting section starts */

/* functions to upload file using ftp section starts */

function connect() {
    var ftpserver = $("#ftpserver").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var ftpPath = $("#ftpPath").val();
    if (ftpserver == "")
    {
        $('#error1').html("Please Fill the FTP Server Name").show();
        $('#loadingmessage').hide();
        return false;
    }
    else {
        $('#error1').hide();
    }
    if (username == "")
    {
        $('#error2').html("please Fill the FTP Server Username").show();
        $('#loadingmessage').hide();
        return false;
    }
    else {
        $('#error2').hide();
    }
    if (password == "")
    {
        $('#error3').html("Please Fill the FTP Server Password").show();
        $('#loadingmessage').hide();
        return false;
    }
    else {
        $('#error3').hide();
    }
    $('#displayfileftp').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');

    $.ajax({
        type: "POST",
        url: baseurl + "video/ftpLogin",
        data: {'ftpserver': ftpserver, 'username': username, 'password': password, 'ftpPath': ftpPath},
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success')
            {
                $('#displayfileftp').html('');
                $('#ftpcontainer').show();
                $('#ftpdata').html(response.data);
                $('#ftpdata,#search').show();

            } else {
                $('#displayfileftp').html('');
                //$('#ftpBulkuploadForm .loader').html('');
            }
        }
    });
    return false;
}

/* functions to check png image in video setting section starts */

/* functions to upload file using ftp section starts */

function connect_ads() {
    var ftpserver = $("#ftpserver").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var ftpPath = $("#ftpPath").val();
    if (ftpserver == "")
    {
        $('#error1').html("Please Fill the FTP Server Name").show();
        $('#loadingmessage').hide();
        return false;
    }
    else {
        $('#error1').hide();
    }
    if (username == "")
    {
        $('#error2').html("please Fill the FTP Server Username").show();
        $('#loadingmessage').hide();
        return false;
    }
    else {
        $('#error2').hide();
    }
    if (password == "")
    {
        $('#error3').html("Please Fill the FTP Server Password").show();
        $('#loadingmessage').hide();
        return false;
    }
    else {
        $('#error3').hide();
    }
    $('#displayfileftp').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');

    $.ajax({
        type: "POST",
        url: baseurl + "ads/ftpLogin",
        data: {'ftpserver': ftpserver, 'username': username, 'password': password, 'ftpPath': ftpPath},
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success')
            {
                $('#displayfileftp').html('');
                $('#ftpcontainer').show();
                $('#ftpdata').html(response.data);
                $('#ftpdata,#search').show();

            } else {
                $('#displayfileftp').html('');
                //$('#ftpBulkuploadForm .loader').html('');
            }
        }
    });
    return false;
}

function check() {
    var form = $(this);
    $('#displayfileftp').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
    var ftpserver = $("#ftpserver").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var ftpPath = $("#ftpPath").val();
    $.ajax({
        type: "POST",
        url: baseurl + "video/ftpLogin",
        dataType: "json",
        data: {ftpserver: ftpserver, username: username, password: password, ftpPath: ftpPath},
        success: function (response) {
            if (response.status == 'success')
            {
                $('#displayfileftp').html('');
                $('#ftpcontainer').show();
                $('#ftpdata').html(response.data);
                $('#ftpdata,#search').show();

            } else {
                $('#displayfileftp').html('');
                //$('#ftpBulkuploadForm .loader').html('');
            }
        }
    });
    return false;
}

function Download() {
    $('#displayfileftp').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
    //myFunction(); // call function for Size count
    var ftpPath = $("#ftpPath").val();
    var ftpserver = $("#ftpserver").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var redirect_url = $("#redirect_url").val();
    var chkarr = [];
    $("input[type=checkbox]:checked").each(function () {
        var filePath = $(this).val();
        var lastPart = filePath.split("/").pop();
        chkarr.push(lastPart);
    });
    /* we join the array separated by the comma */
    var selected;
    selected = chkarr.join(',') + ",";
    if (selected.length > 1) {
        $.ajax({
            type: "POST",
            url: baseurl + "video/uploadFtp",
            data: {chk: selected, ftpserver: ftpserver, username: username, password: password, ftpPath: ftpPath, redirect_url: redirect_url},
            success: function (data)
            {
                var obj = $.parseJSON(data);
                if (obj.flag == 0)
                {
                    var row_data = "";
                    $('#displayfileftp').html('');
                    row_data += '<div class="alert alert-danger alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error in downloading files' + chkarr + '</div>';
                    document.getElementById('msgftp').innerHTML = row_data;
                    $('#msgftp').html(row_data);
                } else {
                    var row_data = "";
                    $('#displayfileftp').html('');
                    row_data += '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>DownLoad completed successfully ' + chkarr + '</div>';
                    document.getElementById('msgftp').innerHTML = row_data;
                    $('#msgftp').html(row_data);
                }
            }
        });
    }

}

/* functions to upload file using ftp section ends */

function Download_Ads() {
    $('#displayfileftp').html('<img src="' + baseurl + 'assets/img/loader.gif"> loading...');
    //myFunction(); // call function for Size count
    var ftpPath = $("#ftpPath").val();
    var ftpserver = $("#ftpserver").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var redirect_url = $("#redirect_url").val();
    var chkarr = [];
    $("input[type=checkbox]:checked").each(function () {
        var filePath = $(this).val();
        var lastPart = filePath.split("/").pop();
        chkarr.push(lastPart);
    });
    /* we join the array separated by the comma */
    var selected;
    selected = chkarr.join(',') + ",";
    if (selected.length > 1) {
        $.ajax({
            type: "POST",
            url: baseurl + "ads/uploadFtp",
            data: {chk: selected, ftpserver: ftpserver, username: username, password: password, ftpPath: ftpPath, redirect_url: redirect_url},
            success: function (data)
            {
                var obj = $.parseJSON(data);
                if (obj.flag == 0)
                {
                    var row_data = "";
                    $('#displayfileftp').html('');
                    row_data += '<div class="alert alert-danger alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error in downloading files' + chkarr + '</div>';
                    document.getElementById('msgftp').innerHTML = row_data;
                    $('#msgftp').html(row_data);
                } else {
                    var row_data = "";
                    $('#displayfileftp').html('');
                    row_data += '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>DownLoad completed successfully ' + chkarr + '</div>';
                    document.getElementById('msgftp').innerHTML = row_data;
                    $('#msgftp').html(row_data);
                }
            }
        });
    }

}

/* functions used for comment section starts */

//Active/Inactive Status
function comment_status(ID, PAGE, status) {
    var str = 'id=' + ID + '&status=' + status;
    jQuery.ajax({
        type: "POST",
        url: PAGE,
        data: str,
        success: function (data)
        {
            if (data == 1)
            {
                var a_spanid = 'active_' + ID;
                var d_spanid = 'dactive_' + ID;
                if (status != "active")
                {
                    /* var ht = '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The Comment has been successfully Inactive.</div></div></section>' */
                    $("#" + a_spanid).hide();
                    $("#" + d_spanid).show();
                    jQuery("#msg_div").html();
                }
                else
                {
                    /* var ht = '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The Comment has been successfully Active.</div></div></section>' */
                    $("#" + d_spanid).hide();
                    $("#" + a_spanid).show();
                    jQuery("#msg_div").html();
                }
            }
        }
    });
}

//Aprroved/Bloack
function comment_approved_status(ID, PAGE, approve) {
    var str = 'id=' + ID + '&approved=' + approve;
    jQuery.ajax({
        type: "POST",
        url: PAGE,
        data: str,
        success: function (data)
        {
            if (data == 1)
            {
                var a_spanid = 'approve_' + ID;
                var d_spanid = 'block_' + ID;
                if (approve != "YES")
                {
                    /* var ht = '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The Comment has been successfully Block.</div></div></section>' */
                    $("#" + a_spanid).hide();
                    $("#" + d_spanid).show();
                    jQuery("#msg_div").html();
                }
                else
                {
                    /* var ht = '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The Comment has been successfully Approved.</div></div></section>' */
                    $("#" + d_spanid).hide();
                    $("#" + a_spanid).show();
                    jQuery("#msg_div").html();
                }
            }
        }
    });
}
$("#linkClick").click(function(){
    cuepoint();
});
function cuepoint()
{
    $(".main_tr").remove();
    $(".popOver").hide();
    var IDs = [];
    var innerHtml = ''; 
    var className = "main_tr";
    $('.video:checked').each(function() {
        IDs.push($(this).val());
     });
     JSON.stringify(IDs);
           $.ajax({
            type: "POST",
            url: baseurl + "advertising/getlistdetail",
            data: {"IDs":IDs}  ,
            success: function (data1)
            {
                
                var videInfo = $.parseJSON(data1);
                //console.log(videInfo);
            var i=1;
            var maxDuration = videInfo['result'][0].duration;
            $("#maxDuration").val(maxDuration);
            //console.log("===============>"+first.duration);
            $.each(videInfo['result'],function(key,val){
            var from_percentageSet = val.duration *(0.05301561);
            var finalPercentageSet = from_percentageSet.toFixed(5);
            innerHtml +='<tr  class="'+className+'"><td style="border-right: 1px solid gray;">'+i+'</td><td style="border-right: 1px solid gray;"><img src="'+val.thumbnail+'"></td><td style="border-right: 1px solid gray;" class="loading"><div class="progress xs"><div style="width: '+finalPercentageSet+'%;" class="progress-bar progress-bar-reen"></div></div></td><input type="hidden" class="video_id" name="video_id" value="'+val.id+'"><input type="hidden" class="duration" name="duration" value="'+val.duration+'"></tr>';
            i++;
            });
            
            if(innerHtml!=''){    
              $(".innerResponse tbody").append(innerHtml);
          } 
  $.ajax({
            type: "POST",
            url: baseurl + "advertising/setcuepoint",
            data: {"IDs" : IDs}  ,
            success: function (data)
            {
                //console.log(data);
                var cuePointInfo = $.parseJSON(data);
                //return cuePointInfo;
                //console.log(cuePointInfo);
                var lastKey;
                $.each(cuePointInfo['result'],function(k,v){
                    //var onePercentage = 0.02302;
                    lastKey = v.cue_points;
                    var from_percentage = v.cue_points *(0.05301561);
                    var finalPercentage = from_percentage.toFixed(5);
                    var removeClass = "append_"+v.cue_points;
                    $(".irs").append("<span class='irs-single mybar1 "+ removeClass +"' style='left: "+finalPercentage +"%;'>"+ v.cue_points +"</span>");
                    $(".irs-with-grid").append("<span class='irs-bar mybar1 "+ removeClass +"' style='width:"+ finalPercentage +"%'></span>");
                    $(".irs-with-grid").append("<span class='irs-slider single mybar1 "+ removeClass +"' style='left: "+ finalPercentage +"%;'></span>");                    

                 $(".irs-bar").remove();
                });
                
                //console.log(lastKey);
            } 
        });          
            } 
        });
}
   $(function () {
        $('#text').val('0');
        $('#percentage').val('0');
         var slider = $("#range").data("ionRangeSlider");
        $("#range").ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: 0,
            max: 1800,
            from: 0,
            to: 1800,
            type: 'single',
            step: 1,
           grid: true,
           grid_num: 20,
           from_shadow :true,
            onStart: function (data) {
                     $(".irs-bar").remove();
                     $("#addFlag").val('0');
               var initialVal = $(".irs-single").text();
               var initialValPer = $(".irs-single").css("left");
                 $("#percentage").val(initialVal);
                  $("#popOver").hide();
             },           
            onChange: function (data) {
                var checkHide = $("#popOver").css("display");
                var percentage = $("#percentage").val();
                var text       = $("#text").val();
                var inialValPoint       = $("#inialValPoint").val();
                var inialValPercentage       = $("#inialValPercentage").val();
                //console.log(data.from);
               // console.log(inialValPoint);
                //console.log(div_removeClass);
                //console.log(text);
                
                if(text!=0)
                {
                    var status = $("#addFlag").val();
                        var div_removeClass = "append_"+text;
                        var from_percent = text *(0.05301561)
                        $(".irs-bar").remove();
                        $(".irs").append("<span class='irs-single mybar1 "+ div_removeClass +"' style='left: "+ from_percent +"%;'>"+ text +"</span>");
                        $(".irs-with-grid").append("<span class='irs-bar mybar1 "+ div_removeClass +"' style='left: 0.9009%; width:"+ from_percent +"'></span>");
                        $(".irs-with-grid").append("<span class='irs-slider single mybar1 "+ div_removeClass +"' style='left: "+ from_percent +"%;'></span>");     
                } if(text==0)
                {
                    var div_removeClass = "append_"+data.from;
                    $(".js-irs-0 .irs .irs-single,.js-irs-0 .irs-slider,.js-irs-0 .irs-single").not(".mybar1").addClass(div_removeClass);
                    
                }
                         console.log("Change Event");
                        console.log(div_removeClass);
         //console.log(globalCheck);

                var addFlag = $("#addFlag").val();
                if(addFlag == 1)
                {
                  $(".irs-single:last").remove();
                   $(".irs-bar:last,.irs-slider:last").remove();
                   $(".js-irs-0 .irs .irs-single:last").remove();
                    
                }
               $("#text").val(data.from);
               $("#percentage").val(data.from_percent);
               $(".popOver").show();
               var intiaSetValPoint = $("#text").val();
               var intiaSetValPer = $("#percentage").val();
                $(".ss").val(intiaSetValPoint);
                $("#inialValPoint").val(intiaSetValPoint);
                $("#inialValPercentage").val(intiaSetValPer); 
               document.getElementById('closeClickEvent').style.pointerEvents = 'none';
             },
            onFinish: function (data) {
                 $(".irs-bar").remove();
                /*var maxDurationGet = $("#maxDuration").val();
                if(data.from > maxDurationGet )
                {
                var removeDiv = "append_"+data.from;
                    $(".js-irs-0 .irs "+removeDiv).remove(); 
                    $(".js-irs-0 .irs .irs "+removeDiv).remove();
                     $(".js-irs-0 .irs-slider"+removeDiv).remove(); 
                      $(".js-irs-0 .irs-single"+removeDiv).remove();                   
                    
                }*/
                document.getElementById('closeClickEvent').style.pointerEvents = 'none';
             },
        });
    });
    $("#cancel").click(function(){
        $(".popOver").hide();
        var globalCheck;
        //jQuery( ".tester" ).css("cursor","default");
         document.getElementById('closeClickEvent').style.pointerEvents = 'auto';
         var intialPoint = $('#text').val();
         var intialpercentage = $('#percentage').val();
         var removeDiv = ".append_"+intialPoint;
         globalCheck = "append_"+intialPoint;
         $("#addFlag").attr("globalCheck",globalCheck);
         console.log("Cancel Event");
        console.log(removeDiv);
         console.log(globalCheck);
        // $("#addFlag").val('0');
         //$("#text").val(0);
         //if()
        $(".js-irs-0 .irs "+removeDiv).remove(); 
        $(".js-irs-0 .irs .irs "+removeDiv).remove();
        $(".js-irs-0 .irs-slider"+removeDiv).remove(); 
        $(".js-irs-0 .irs-single"+removeDiv).remove(); 
        //$(".js-irs-0 .irs .irs-single:last").remove();
        // $(".js-irs-0 .irs-slider:last").remove();
        // $(".js-irs-0 .irs-single:last").remove();
    });

$("#add").click(function(){
   var hh =  $('.hh').val();
   var mm = $('.mm').val();
   var ss =  $('.ss').val();
   var ms =  $('.ms').val();
    //var timeInMillisec  = (hh *60 *60000) + (mm*60000) + (ss*1000) + ms;
    var timeInMillisec  = ss;
    var cueName = $("#cueName").val();
    var IDs = [];
     $('.video_id').each(function() {
        IDs.push($(this).val());
     });
    $.ajax({
            type: "POST",
            url: baseurl + "advertising/inserCuePoint",
            data: {"timeInMillisec":timeInMillisec,"IDs" : IDs,"cueName" : cueName}  ,
            success: function (data)
            {
                console.log(data);
                $(".hh,.ms,ss,.mm,#cueName").val('');
                $('.popOver').hide();
                document.getElementById('closeClickEvent').style.pointerEvents = 'auto';
                $("#text").val(timeInMillisec);
                var from_percentage1 = timeInMillisec *(0.05301561);
                    var finalPercentage1 = from_percentage1.toFixed(5);
               $("#percentage").val(finalPercentage1);
               $("#text").val(timeInMillisec);
               $("#percentage").val(finalPercentage1);
               var div_removeClass1 = "append_"+timeInMillisec;
              // console.log(div_removeClass1);
               $("#addFlag").val('1');
                    $(".irs").append("<span class='irs-single mybar1 "+ div_removeClass1 +"' style='left: "+ finalPercentage1 +"%;'>"+ timeInMillisec +"</span>");
                    $(".irs-with-grid").append("<span class='irs-bar mybar1 "+ div_removeClass1 +"' style='left: 0.9009%; width:"+ finalPercentage1 +"'></span>");
                    $(".irs-with-grid").append("<span class='irs-slider single mybar1 "+ div_removeClass1 +"' style='left: "+ finalPercentage1 +"%;'></span>");
             // $(".innerResponse tbody").append(data);
            } 
        });
   //console.log("gffgfg"); 
});
$(".ss").on("change",function(){
    $(this).val();
    alert($(this).val());    
});

/* functions used for comment section ends */




