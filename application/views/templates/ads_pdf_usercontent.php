                        <?php $search = $this->session->userdata('search_form');
                              if($search['startdate']){
                                echo '<h4>'.date('M d, Y',strtotime($search['startdate'])).' - '.date('M d, Y',strtotime($search['enddate'])).'</h4>';
                              }
                                ?>
                                <table cellspacing="0" cellpadding="1" border="1">
                                   
                                        <tr style="background-color:#428bca;color:#fff;">
                                           <th width="6%">Sl.No.</th>
                                           <th width="25%">Name</th>                                           
                                           <th width="10%">Content Provider</th>
                                           <th width="10%">Platform</th>
                                           <th width="10%">Browser</th>
                                           <th width="10%">Location</th>
                                           <th width="10%">Date</th>
                                            <th width="5%"> Hits</th>
                                            <th width="10%">Total Time Watched</th>	
                                        </tr>
                                   
                                        <?php foreach ($result as $value) { $i++;?>
                                        <tr>
                                                <td width="6%"><?php echo $i;?></td>
                                                <td  width="25%"><!--a href="<?php echo base_url(); ?>analytics/user/<?php echo $value->id; ?>"--><?php echo $value->ad_title; ?></td>
                                                <td width="10%"><?php echo $value->content_provider; ?></td>
                                                <td width="10%"><?php echo $value->platform; ?></td>
                                                <td width="10%"><?php echo $value->browser; ?></td>
                                                <td width="10%"><?php echo $value->country; ?></td>
                                                <td width="10%"><?php echo $value->created; ?></td>
                                                <td  width="5%"><?php echo $value->total_hits; ?></td>
                                                <td width="10%"><?php echo time_from_seconds($value->total_watched_time); ?></td>
                                            </tr>
                                        <?php } ?>                                    
                                </table>                                                    
                           