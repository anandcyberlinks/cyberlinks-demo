                                
                                <table cellspacing="0" cellpadding="1" border="1" width="100%">
                             
                                        <tr style="background-color:#428bca;color:#fff;">
                                           <th width="6%">Sl.No.</th>
                                           <th width="6%">Playlist Id</th>                                           
                                           <th width="30%">Title</th>
                                           <th width="10%">Content Id</th>
                                           <th width="20%">Start Time</th>
                                           <th width="20%">End Time</th>
                                           <th width="6%">Status</th>
                                        </tr>
                                        <?php $i = 0; foreach ($result as $value) { $i++;?>
											<tr>
                                                <td><?php echo $i;?></td>
                                                <td><?php echo $value->playlist_id; ?></td>
                                                <td><?php echo $value->title; ?></td>
                                                <td><?php echo $value->content_id; ?></td>
                                                <td><?php echo dateFormat($value->start_date); ?></td>
                                                <td><?php echo dateFormat($value->end_date); ?></td>
                                                <td><?php echo $value->status; ?></td>
                                                                                  
                                            </tr>
                                        <?php } ?>                                    
                                </table>                                                    
                           
<?php
function dateFormat($date) {
        $temp = strtotime($date);
        if ($temp > 0) {
            return date('d-m-Y m:i:s', $temp);
        } else {
            return false;
        }
    }

?>
