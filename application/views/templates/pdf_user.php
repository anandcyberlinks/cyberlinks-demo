                    <?php $search = $this->session->userdata('search_form');
                    if($search['startdate']){
                    echo '<h4>'.date('M d, Y',strtotime($search['startdate'])).' - '.date('M d, Y',strtotime($search['enddate'])).'</h4>';
                    }
                    ?>
                                <table cellspacing="0" cellpadding="1" border="1">
                                   
                                        <tr style="background-color:#428bca;color:#fff;">
                                           <th width="8%">Sl.No.</th>
                                           <th width="30%">Name</th>                                          
                                            <th width="10%">Total Hits</th>
                                            <th width="10%">Total Time Watched</th>	
                                            <th width="15%">Browser</th>	
                                            <th width="10%">IP</th>	
                                            <th width="15%">Date</th>	
                                        </tr>
                                   
                                        <?php foreach ($result as $value) { $i++;?>
                                        <tr>
                                                <td width="8%"><?php echo $i;?></td>
                                                <td  width="30%"><!--a href="<?php echo base_url(); ?>analytics/user/<?php echo $value->id; ?>"--><?php echo ($value->name!='') ? $value->name : 'guest'; ?></td>                                               
                                                <td  width="10%"><?php echo $value->total_hits; ?></td>
                                                <td width="10%"><?php echo time_from_seconds($value->total_watched_time); ?></td>                                                                                        
                                                <td width="15%"><?php echo $value->browser; ?></td>                                                                                        
                                                <td width="10%"><?php echo $value->ip; ?></td>                                                                                        
                                                <td width="15%"><?php echo date("d/m/Y H:i:s", strtotime($value->created)); ?></td>                                                                                           
                                            </tr>
                                        <?php } ?>                                    
                                </table>                                                    
                           