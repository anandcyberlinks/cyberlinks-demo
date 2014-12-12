                        <?php $search = $this->session->userdata('search_form');
                               if($search['startdate']){
                                echo '<h4>'.date('M d, Y',strtotime($search['startdate'])).' - '.date('M d, Y',strtotime($search['enddate'])).'</h4>';
                                }
                                ?>
                                <table cellspacing="0" cellpadding="1" border="1">
                                   
                                        <tr style="background-color:#428bca;color:#fff;">
                                           <th width="8%">Sl.No.</th>
                                           <th width="45%">Name</th>
                                           <th width="20%">Content Provider</th>
                                            <th width="10%">Total Hits</th>
                                            <th width="20%">Total Time Watched</th>	
                                        </tr>
                                   
                                        <?php foreach ($result as $value) { $i++;?>
                                        <tr>
                                                <td width="8%"><?php echo $i;?></td>
                                                <td  width="45%"><!--a href="<?php echo base_url(); ?>analytics/user/<?php echo $value->id; ?>"--><?php echo $value->title; ?></td>
                                                <td width="20%"><?php echo $value->content_provider; ?></td>                                                
                                                <td  width="10%"><?php echo $value->total_hits; ?></td>
                                                <td width="20%"><?php echo time_from_seconds($value->total_watched_time); ?></td>                                                                                        
                                            </tr>
                                        <?php } ?>                                    
                                </table>                                                    
                           