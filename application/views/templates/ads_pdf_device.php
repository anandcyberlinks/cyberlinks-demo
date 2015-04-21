                        <?php $search = $this->session->userdata('search_form');
                      
                               if($search['startdate']){
                                echo '<h4>'.date('M d, Y',strtotime($search['startdate'])).' - '.($search['enddate'] !='' ? date('M d, Y',strtotime($search['enddate'])):"Till now").'</h4>';
                                }
                                ?>
                                <table cellspacing="0" cellpadding="1" border="1">
                                   
                                        <tr style="background-color:#428bca;color:#fff;">
                                           <th width="8%">Sl.No.</th>
                                           <th width="30%">Platform</th>
                                           <th width="30%">Browser</th>
                                            <th width="10%">Total Impressions</th>
                                        <?php /*?>    <th width="20%">Total Time Watched</th>	<?php */?>
                                        </tr>
                                   
                                        <?php foreach ($result as $row) { $i++;?>
                                        <tr>
                                          <td width="8%"><?php echo $i;?></td>
                                          <td  width="30%"><?php echo $row->platform;?></td>
                                          <td width="30%"><?php echo $row->browser;?></td>
                                          <td width="10%"><?php echo $row->total_hits;?></td>
                                        <?php /*?>  <td width="20%"><?php echo time_from_seconds($row->total_watched_time);?></td>                                                                                        
                                         <?php */?>   </tr>
                                        <?php } ?>                                    
                                </table>                                                    
                           