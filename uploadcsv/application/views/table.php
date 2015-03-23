<div id="debug" style="float:right;"></div>
<table id="table-1" cellspacing="0" cellpadding="2" class="table table-bordered table-hover">
    <tr>
        <?php for($l=0; $l<count($csv['-1']); $l++) { ?>
            <th><?php echo $csv['-1'][$l]; ?></th>
        <?php }
        
        unset($csv['-1']);
       // echopre($csv);
        ?>
    </tr>
    <?php
    for ($i = 0; $i < count($csv); $i++) {
        $connt_c = count($csv[$i]); 
        
        if ($csv[$i] != "") {
            ?>
            <tr id="<?php echo $i; ?>">
                <?php for($j=0; $j<$connt_c; $j++) { ?>
                <td><?php echo $csv[$i][$j]; ?></td>
                <?php } ?>
            </tr>
        <?php }
    }?>
</table>
<input type="button" name="button" onclick="myfun();" value="Export" class="btn btn-dropbox"/>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.tablednd.0.7.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#table-1").tableDnD();
    $("#table-2 tr:even").addClass("alt");
    $("#table-2").tableDnD({
        onDragClass: "myDragClass",
        onDrop: function (table, row) {
            var rows = table.tBodies[0].rows;
            var debugStr = "Row dropped was " + row.id + ". New order: ";
            for (var i = 0; i < rows.length; i++) {
                debugStr += rows[i].id + " ";
            }
            $("#debugArea").html(debugStr);
        },
        onDragStart: function (table, row) {
            $("#debugArea").html("Started dragging row " + row.id);
        }
    });

    $('#table-3').tableDnD({
        onDrop: function (table, row) {
            alert("Result of $.tableDnD.serialise() is " + $.tableDnD.serialize());
            $('#AjaxResult').load("server/ajaxTest.php?" + $.tableDnD.serialize());
        }
    });

    $('#table-4').tableDnD(); // no options currently

    $('#table-5').tableDnD({
        onDrop: function (table, row) {
            alert($('#table-5').tableDnDSerialize());
        },
        dragHandle: ".dragHandle"
    });

    $("#table-5 tr").hover(function () {
        $(this.cells[0]).addClass('showDragHandle');
    }, function () {
        $(this.cells[0]).removeClass('showDragHandle');
    });
});
function myfun() {
    var data = Array();
    $("table tr").each(function (i, v) {
            data[i] = Array();
            $(this).children('td').each(function (ii, vv) {
                data[i][ii] = $(this).text();
            });
    })
    alert('<?= base_url() ?>csv/createXML');
    $.ajax({
        type: 'POST',
        async: true,
        url: '<?= base_url() ?>csv/createXML',
        data: {data:data},
        success: function (responce) {
            console.log(responce);
            window.open('<?= base_url() ?>' + responce, '_blank');
        }
    });
}
</script>
