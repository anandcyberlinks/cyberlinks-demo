                        <?php $search = $this->session->userdata('search_form');
                               if($search['startdate']){
                                echo '<h4>'.date('M d, Y',strtotime($search['startdate'])).' - '.date('M d, Y',strtotime($search['enddate'])).'</h4>';
                                }
                                ?>
                                <table cellspacing="0" cellpadding="1" border="1">
                                   
                                        <tr style="background-color:#428bca;color:#fff;">
                                           <th width="10%">Sr No.</th>
                                           <th width="30%">Creative</th>                                           
                                           <th width="20%">Duration</th>
                                           <th width="20%">UserCount</th>
                                           <th width="20%">DateTime</th>
                                        </tr>
                                   
                                        <?php foreach ($result as $value) { $i++;?>
                                        <tr>
                                                <td width="10%"><?php echo $i;?></td>
                                                <td width="30%"><?php echo $value->Commercial; ?></td>
                                                <td width="20%"><?php echo $value->Duration; ?></td>
                                                <td width="20%"><?php echo $value->UserCount; ?></td>
                                                <td width="20%"><?php echo $value->StartTime; ?></td>
                                            </tr>
                                        <?php } ?>                                    
                                </table>                                                    
                           