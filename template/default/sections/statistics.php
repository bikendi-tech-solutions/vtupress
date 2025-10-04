<?php
if(is_plugin_active("vpmlm/vpmlm.php")  && vp_option_array($option_array,'mlm') == "yes"){
						
						
						echo'
					
					<!-- REFERAL -->
					
					
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Statistics</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
									
                                    <div class="card-body">
                                        <h5 class="card-title" id="refstats">Refer Stats</h5>
                                        <table class="table table-dark">
                                            <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Value</th>
                                            </tr>
                                            </thead>
                                             <tbody>
												<tr>
                                                    <td>Refered By</td>
                                                    <td>'.$ref_by.'</td>
                                                </tr>
                                                    <tr>
                                                <td>Total PVs</td>
                                                <td>'.get_userdata($id)->vp_user_pv.'</td>
                                                     </tr>
												';
											if(isset($level) && isset($level[0]->total_level)){
											$total_level = $level[0]->total_level;
											
											for($lev = 1; $lev <= $total_level; $lev++){
												if($lev == 1){
													echo'
                                                <tr>
                                                    <td>No. Direct Referee</td>
                                                    <td>'.$total_refered.'</td>
                                                </tr>
												';
												}
												elseif($lev == 2){
													echo'
												
                                                <tr>
                                                    <td>No. Second Level Referee</td>
                                                    <td>'.$total_inrefered.'</td>
                                                </tr>
												';
												}
												elseif($lev == 3){
													echo'
												<tr>
                                                    <td>No. Third level Referee</td>
                                                    <td>'.$total_inrefered3.'</td>
                                                </tr>
												';
												}else{}
											}
											}
											if(isset($level) && isset($level[0]->total_level)){
											$total_level = $level[0]->total_level;
											
											for($lev = 1; $lev <= $total_level; $lev++){
												if($lev == 1){
													echo'
												<tr>
                                                    <td>Total Earned From Direct Referee Upgrades</td>
                                                    <td>'.$symbol.$total_dir_earn.'</td>
                                                </tr>
												';
												}
												
												elseif($lev == 2){
													echo'
												<tr>
                                                    <td>Total Earned From Second Level Referee Upgrades</td>
                                                    <td>'.$symbol.$total_indir_earn.'</td>
                                                </tr>
												';
												}
												elseif($lev == 3){
													echo'
												<tr>
                                                    <td>Total Earned From Third Level Referee Upgrades Downward</td>
                                                    <td>'.$symbol.$total_indir_earn3.'</td>
                                                </tr>
												';
												}else{}
											}
											
}
												echo'
                                           </tbody>
                                        </table>
                                    </div>
									
									
									
									
									<div class="card-body">
                                        <h5 class="card-title" id="bonusstats">Transaction & Bonus Stats</h5>
                                        <table class="table table-light">
                                            <thead class="bg-gray-100">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
												<tr>
                                                    <td>No. Of Transactions Attempted</td>
                                                    <td>'.$total_trans_attempt.'</td>
                                                </tr>
												<tr>
                                                    <td>No. Of Successful Transactions Made</td>
                                                    <td>'.$total_suc_trans.'</td>
                                                </tr>
												';
											if(strtolower(vp_getoption("totcons")) == "yes"){
												echo'
												<tr>
                                                    <td>Total Amount Of Transactions Consumed</td>
                                                    <td>'.$symbol.$cur_suc_trans_amt.'</td>
                                                </tr>
												';
											}
												echo'
												<tr>
                                                    <td>Total Amount Of Transaction Bonus Earned</td>
                                                    <td>'.$symbol.$total_trans_bonus.'</td>
                                                </tr>
												';
										if(isset($level) && isset($level[0]->total_level)){
											$total_level = $level[0]->total_level;
											
											for($lev = 1; $lev <= $total_level; $lev++){
												if($lev == 1){
													echo'
												<tr>
                                                    <td>Total Amount Of Transaction Bonus Earned From Direct Referee</td>
                                                    <td>'.$symbol.$total_dirtrans_bonus.'</td>
                                                </tr>
												';
												}
													elseif($lev == 2){
													echo'
												<tr>
                                                    <td>Total Amount Of Transaction Bonus Earned From Second Level Referee</td>
                                                    <td>'.$symbol.$total_indirtrans_bonus.'</td>
                                                </tr>
												';
													}
														elseif($lev == 3){
													echo'
												<tr>
                                                    <td>Total Amount Of Transaction Bonus Earned From Third Level Referee Downwards</td>
                                                    <td>'.$symbol.$total_indirtrans_bonus3.'</td>
                                                </tr>
												';
														}else{}
											}
										}
										echo'
                                           </tbody>
                                        </table>
                                    </div>
									
									
									
									
									<!-- WITHDRAWALS -->
									
				';
									
					if(strtolower(vp_option_array($option_array,"allow_withdrawal")) == "yes" ){
						echo'
									
									<div class="card-body">
                                        <h5 class="card-title">Withdrawals</h5>
                                        <table class="table table-dark">
                                            <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
												<tr>
                                                    <td>No. Of Withdrawals Made</td>
                                                    <td>'.$total_withdraws.'</td>
                                                </tr>
												<tr>
                                                    <td>Total Balance</td>
                                                    <td>'.$symbol.$total_bal_with.'</td>
                                                </tr>
												<tr>
                                                    <td>Minimum Amount Withdrawable</td>
                                                    <td>'.$symbol.$minwithle.'</td>
                                                </tr>
												<tr>
                                                    <td>Action</td>
                                                    <td>';
                                              if(strtolower($myplan) != strtolower("customer")){
                                                      echo'
													<a href="?vend=withdraw">
                                                    <button style="color:white;">Withdraw</button>
													</a>
                                                    ';
													
                                                    }
                                                    else{
                                                      echo'
                                                     <a href="?vend=upgrade"> <button style="color:white;"> Upgrade </button></a>
                                                      ';
                                                    }
                                                    
                                                    echo'</td>
                                                </tr>
                                           </tbody>
                                        </table>
                                    </div>
									
									';
					}
									
											
							echo'		
									
									
                                </div>
                            </div>
';

					}
					
					?>