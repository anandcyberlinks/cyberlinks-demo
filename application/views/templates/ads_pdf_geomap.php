                        <?php $search = $this->session->userdata('search_form');
                      
                               if($search['startdate']){
                                echo '<h4>'.date('M d, Y',strtotime($search['startdate'])).' - '.($search['enddate'] !='' ? date('M d, Y',strtotime($search['enddate'])):"Till now").'</h4>';
                                }
                                ?>
                                <table cellspacing="0" cellpadding="1" border="1">
                                   
                                        <tr style="background-color:#428bca;color:#fff;">
                                           <th width="8%">Sl.No.</th>
                                           <th width="30%">Country</th>
                                           <?php if($c!=1){?><th width="30%">Region</th><?php }?>
                                            <th width="10%">Total Impressions</th>
                                            <th width="20%">Total Time Watched</th>	
                                        </tr>
                                   
                                        <?php foreach ($result as $row) { $i++;?>
                                        <tr>
                                          <td width="8%"><?php echo $i;?></td>
                                          <td  width="30%"><?php echo $row->country;?></td>
                                          <?php if($c!=1){?><td width="30%"><?php echo $row->state;?></td><?php }?>
                                          <td width="10%"><?php echo $row->total_hits;?></td>
                                          <td width="20%"><?php echo time_from_seconds($row->total_watched_time);?></td>                                                                                        
                                            </tr>
                                        <?php } ?>                                    
                                </table>                                                    
                           