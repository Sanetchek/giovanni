<?php
// MLM - PHP Script

if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
if(!checkSession()) {
    $redirect = $settings['url']."login";
    header("Location: $redirect");
}
$membership_id = idinfo($_SESSION['uid'],"membership");
$id = $db->query("SELECT * FROM referral_membership WHERE id='$membership_id'");
$mem = $id->fetch_assoc();
$date = date('Y-m-d');
?>

		<div class="container-fluid py-4">
		        <?php echo warn("<b>Referral Allowed</b> means how many users can join under your account. Check Earning table to calculate your earnings."); ?>
			
				<?php 
				if (isset($_POST['upgrade'])) {
					$name = protect($_POST['upgrade']);
					$currency = $settings['default_currency'];
					
					$check = $db->query("SELECT * FROM referral_membership WHERE name='$name'");
					$row = $check->fetch_assoc();
					$amount = $row['price'];
					if(get_wallet_balance($_SESSION['uid'],$currency) < $row['price']) {
						echo error($lang['error_8']);
					} elseif(idinfo($_SESSION['uid'],"email") == $email) {
						echo error($lang['error_9']);
					} else {
						$txid = strtoupper(randomHash(10));
						$time = time();
						UpdateUserWallet($_SESSION['uid'],$row['price'],$currency,2);
						
						$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
						VALUES ('$txid','299','$_SESSION[uid]','$description','$amount','$currency','1','$time')");
            
						$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
						VALUES ('$txid','299','$_SESSION[uid]','$amount','$currency','1','$time')");
						
						UpdateAdminWallet($amount,$currency);
						$insert_admin_log = $db->query("INSERT admin_logs (type,time,u_field_1,u_field_2,u_field_3) VALUES ('299','$time','$amount','$currency','$txid')");
						
						$update = $db->query("UPDATE users SET membership='$row[id]' WHERE id='$_SESSION[uid]'");
						$duration = $row['duration'];
						$date = date('Y-m-d');      //Date of activation
                        $date_complete = date('Y-m-d', strtotime($date. ' + '.$duration.' days'));      //Date of completion
						
						UpdateMembership($txid,$row['id'],$_SESSION['uid'],$amount,$currency,1,$time,$date,$date_complete);
						
						if ($m["referral_system"] == "1") {
						    
						
						    $date = date('Y-m-d');
						    
						    $ref_01 = idinfo($_SESSION['uid'],"ref1"); //current player referred by lvl-1        //5
						    $ref_02 = idinfo($_SESSION['uid'],"ref2"); //current player referred by lvl-2        //4
						    $ref_03 = idinfo($_SESSION['uid'],"ref3"); //current player referred by lvl-3        //1
						    $ref_04 = idinfo($_SESSION['uid'],"ref4"); //current player referred by lvl-4
						    $ref_05 = idinfo($_SESSION['uid'],"ref5"); //current player referred by lvl-5
						    $ref_06 = idinfo($_SESSION['uid'],"ref6"); //current player referred by lvl-6
						    $ref_07 = idinfo($_SESSION['uid'],"ref7"); //current player referred by lvl-7
						    $ref_08 = idinfo($_SESSION['uid'],"ref8"); //current player referred by lvl-8
						    $ref_09 = idinfo($_SESSION['uid'],"ref9"); //current player referred by lvl-9
						    $ref_10 = idinfo($_SESSION['uid'],"ref10"); //current player referred by lvl-10
						    
						    $calcu_1 = $db->query("SELECT * FROM users WHERE ref1='$ref_01'");
                		    $t_lv_1 = $calcu_1->num_rows;
                		    
                		    if($ref_01 > 0) {
						        
						        $plyer = $ref_01;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 1 && $t_lv_1 <= $prow['limits']) {
					                $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_1'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
					        }
						    if($ref_02 > 0) {
						        
						        $plyer = $ref_02;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 2 && $t_lv_1 <= $prow['limits']) {
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_2'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_03 > 0) {
						        
						        $plyer = $ref_03;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 3 && $t_lv_1 <= $prow['limits']) {
					            
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_3'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_04 > 0) {
						        
						        $plyer = $ref_04;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 4 && $t_lv_1 <= $prow['limits']) {
    					                
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_4'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_05 > 0) {
						        
						        $plyer = $ref_05;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 5 && $t_lv_1 <= $prow['limits']) {
					            
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_5'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_06 > 0) {
						        
						        $plyer = $ref_06;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 6 && $t_lv_1 <= $prow['limits']) {
					                
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_6'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_07 > 0) {
						        
						        $plyer = $ref_07;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 7 && $t_lv_1 <= $prow['limits']) {
					                
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_7'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_08 > 0) {
						        
						        $plyer = $ref_08;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 8 && $t_lv_1 <= $prow['limits']) {
					            
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_8'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_09 > 0) {
						        
						        $plyer = $ref_09;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 9 && $t_lv_1 <= $prow['limits']) {
					                
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_9'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						    if($ref_10 > 0) {
						        
						        $plyer = $ref_10;
						        $plyer_email = idinfo($_SESSION['uid'],"email");
						        $plyer_mem_id = idinfo($plyer,"membership");
						        $pcheck = $db->query("SELECT * FROM referral_membership WHERE id='$plyer_mem_id'");
					            $prow = $pcheck->fetch_assoc();
					            
					            if($prow['levels_allow']  >= 10 && $t_lv_1 <= $prow['limits']) {
					            
    						        $bonusQuery = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_10'");
    							    $bonus_settings = $bonusQuery->fetch_assoc();
    						        
    						        $upline_infoQuery= $db->query("SELECT * FROM users WHERE id='$plyer'"); 
    							    $upline_info = $upline_infoQuery->fetch_assoc();
    						        
    						        $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$upline_info[id]'");
                                    $mem_row_2 = $CheckMembership_2->fetch_assoc();
                                    if ($date < $mem_row_2['end_date']) {
            							$prize_per = $amount*($bonus_settings['per_com']/100);
            							$prize_fix = $bonus_settings['fix_com'];
            							$prize = $prize_per + $prize_fix;
            							$prize = number_format($prize, 2, '.', '');
            							
            							UpdateUserWallet($plyer,$prize,$currency,1);
            							$insert_bonus_logs = $db->query("INSERT bonus_logs (uid,user_email,from_who,commission,currency,date) VALUES ('$upline_info[id]','$upline_info[email]','$plyer_email','$prize','$currency','$date')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$description','$prize','$currency','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','300','$upline_info[id]','$prize','$currency','1','$time')");
                                    }
					            }
						    }
						}
						
						echo success("Membership $name has been activated.");
						header("Refresh:0");
					}
				}
				
				
				
				?>
                <form action="" method="POST">
                    
					<div class="row">
						<?php
						$statement = "referral_membership";
						$query = $db->query("SELECT * FROM {$statement}");
						if($query->num_rows>0) {
							while($row = $query->fetch_assoc()) {
								?>
								<div class="col-md-3 mb-4">
									<div class="card card-pricing">
									  <div class="card-header bg-gradient-warning text-center pt-4 pb-5 position-relative">
										<div class="z-index-1 position-relative">
										  <h5 class="text-white"><?= $row['name']; ?></h5>
										  <h1 class="text-white mt-2 mb-0">
											<small style="font-size:18px;"><?= $settings['default_currency'] ?></small> <?= $row['price']; ?></h1>
										 </div>
									  </div>
									  <div class="position-relative mt-n5" style="height: 50px;">
										<div class="position-absolute w-100">
											<svg class="waves waves-sm" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 40" preserveAspectRatio="none" shape-rendering="auto">
											  <defs>
												<path id="card-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
											  </defs>
											  <g class="moving-waves">
												<use xlink:href="#card-wave" x="48" y="-1" fill="rgba(255,255,255,0.30"></use>
												<use xlink:href="#card-wave" x="48" y="3" fill="rgba(255,255,255,0.35)"></use>
												<use xlink:href="#card-wave" x="48" y="5" fill="rgba(255,255,255,0.25)"></use>
												<use xlink:href="#card-wave" x="48" y="8" fill="rgba(255,255,255,0.20)"></use>
												<use xlink:href="#card-wave" x="48" y="13" fill="rgba(255,255,255,0.15)"></use>
												<use xlink:href="#card-wave" x="48" y="16" fill="rgba(255,255,255,0.99)"></use>
											  </g>
											</svg>
										  </div>
									  </div>
									  <div class="card-body text-center">
										<ul class="list-unstyled max-width-200 mx-auto">
										  <li>
											 Active for <b class="text-warning"><?= $row['duration']; ?></b> Days
											<hr class="horizontal dark">
										  </li>
										  <li>
											 <b class="text-warning"><?= $row['limits']; ?></b> Referrals Allowed
											<hr class="horizontal dark">
										  </li>
										  <li>
											 <b class="text-warning"><?= $row['levels_allow']; ?></b> Levels
											<hr class="horizontal dark">
										  </li>
										</ul>
										<?php
										    $CheckMembership = $db->query("SELECT * FROM membership_log WHERE uid='$_SESSION[uid]' and plan='$row[id]'");
                                            $mem_row = $CheckMembership->fetch_assoc();
                                        ?>
										<?php if ($membership_id > 0 && $mem['id'] == $row['id'] && $date < $mem_row['end_date']) { ?>
											<a href="javascript:;" class="btn btn-success w-100 mt-4 mb-0">Currently Active</a>
											<p></p>
											<div class="alert alert-info info" style="color:white;"><i class="fa fa-info-circle"></i> Expires on <?=$mem_row['end_date']?></div>
										<?php } else { ?>
											<button type="submit" class="btn bg-gradient-warning w-100 mt-4 mb-0" value="<?= $row['name']; ?>" name="upgrade">Activate Now</button>
										<?php } ?>
									  </div>
									</div>
								  </div>
								<?php
							}
						} else {
							echo '<tr><td colspan="3">Currently No Membership Plan Available</td></tr>';
						}
						?>
					</div>
				</form>






<script src="/assets/plugins/nouislider/nouislider.min.js"></script>
<link rel="stylesheet" href="/assets/plugins/nouislider/nouislider.min.css" media="all">
	
<section class="calculators" id="calculators-4">
<div class="shape-1 d-none d-lg-block"></div>
<div class="container position-relative z-3">
        <div class="row align-items-end">
            <div class="col-lg-8 order-lg-1 order-2">
                <div class="desc">
                    <h2>Investment Calculator</h2>
<p>This innvestment calculator helps you estimate the future value of an investment based  on initial investment.</p>
                </div>
            </div>
            <div class="col-lg-4 order-lg-2 order-1">
                <div class="tabs-nav-wrapper overflow-auto">
                    <ul class="tabs justify-content-lg-end" role="tablist">
                        <li class="nav-item" id="tablink-1" role="presentation">
                            <a class="nav-link active" id="tablink-1" data-bs-toggle="tab" href="#tab-1" role="tab" aria-selected="true">
                                Можу купити                            </a>
                        </li>  
                        <li class="nav-item" id="tablink-2" role="presentation">
                            <a class="nav-link" id="tablink-2" data-bs-toggle="tab" href="#tab-2" role="tab" aria-selected="false" tabindex="-1">
                                Хочу отримувати                            </a>
                        </li>  
                    </ul>
                </div>
            </div>
        </div> 
    </div>
    <div class="tabs-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tablink-1">
                            <div class="row">
                                <div class="col-lg-12 col-xxl-12 m-auto">
                                    <div id="calculator-1" class="calculator-form" data-product-price="3">
                                        <div class="range-block text-start">
                                            <div class="d-block range-input">
                                                <h3 for="calculator-1-forever-count">Кількість Forevers, <span id="calculator-1-product-count" class="orange-text">300</span> шт</h3>
                                            </div>
                                            <!-- Range Slider Here -->
                                            <div id="calculator-1-forever-count" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr"><div class="noUi-base"><div class="noUi-connects"></div><div class="noUi-origin" style="transform: translate(-988%, 0px); z-index: 4;"><div class="noUi-handle noUi-handle-lower" data-handle="0" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="0.0" aria-valuemax="25000.0" aria-valuenow="300.0" aria-valuetext="300"><div class="noUi-touch-area"></div></div></div></div><div class="noUi-pips noUi-pips-horizontal"><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 0%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="0" style="left: 0%;">0</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 3.33333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 6.66667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 10%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="2500" style="left: 10%;">2500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 13.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 16.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 20%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="5000" style="left: 20%;">5000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 23.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 26.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 30%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="7500" style="left: 30%;">7500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 33.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 36.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 40%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="10000" style="left: 40%;">10000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 43.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 46.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 50%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="12500" style="left: 50%;">12500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 53.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 56.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 60%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="15000" style="left: 60%;">15000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 63.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 66.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 70%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="17500" style="left: 70%;">17500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 73.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 76.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 80%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="20000" style="left: 80%;">20000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 83.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 86.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 90%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="22500" style="left: 90%;">22500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 93.3333%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-normal" style="left: 96.6667%;"></div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 100%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="25000" style="left: 100%;">25000</div></div></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="line-block text-start d-block d-md-flex justify-content-between align-items-center">
                                                <strong>Ціна Forevers</strong>
                                                <p class="price">
                                                    <span class="value">3.00</span> 
                                                    <span class="currency">USD</span>
                                                </p>
                                            </div>
                                            <div id="invested" class="no-line-block text-start d-block d-md-flex justify-content-between align-items-center">
                                                <strong>Інвестовано:</strong>
                                                <p class="price">
                                                    <span class="value">900.00</span> 
                                                    <span class="currency">USD</span>
                                                </p>
                                            </div>
                                            <div id="year-income" class="line-block text-start d-block d-md-flex justify-content-between align-items-center">
                                                <strong>Річний дохід з оренди Forevers:</strong>
                                                <p class="price">
                                                    <span class="value">255.00</span> 
                                                    <span class="currency">USD</span>
                                                </p>
                                            </div>
                                            <div id="price-growth-income" class="no-line-block text-start d-block d-md-flex justify-content-between align-items-center">
                                                <strong>Дохід з росту ціни Forevers</strong>
                                                <p class="price ">
                                                    <span class="value">2 400.00</span> 
                                                    <span class="currency">USD</span>
                                                </p>
                                            </div>
                                            <!-- <div id="general-price" class="text-center text-md-start d-block d-md-flex justify-content-between align-items-center">
                                                <strong>Загальний дохід:</strong>
                                                <p class="price">
                                                    <span class="value">3</span> 
                                                    <span class="currency">USD</span>
                                                </p>
                                            </div> -->
                                        </div>
                                        <div class="form-group">
                                            <div class="line-block">
                                                <strong>
                                                    Порівняти річну дохідність Forevers з
                                                </strong>
                                            </div>
                                            <div class="bottom-prices-block">
                                                <div class="row">
                                                    <div id="deposyt" class="col-lg-3  text-start text-lg-center">
                                                        <div class="price-wrapper">
                                                            <strong>Депозит в $ <br>під 3.5% річних:</strong>
                                                            <div class="d-block mt-3 mt-lg-4">
                                                                <p class="price dark-text-color">
                                                                    <span class="value">31.50</span> 
                                                                    <span class="currency">USD</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="residental-realestate-income" class="col-lg-3  text-start text-lg-center">
                                                        <div class="price-wrapper">
                                                            <strong>Житлова <br>нерухомість:</strong>
                                                            <div class="d-block mt-3 mt-lg-4">
                                                                <p class="price dark-text-color">
                                                                    <span class="value">45.00</span> 
                                                                    <span class="currency">USD</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="commercial-realestate-income" class="col-lg-3  text-start text-lg-center">
                                                        <div class="price-wrapper">
                                                            <strong>Комерційна <br>нерухомість:</strong>
                                                            <div class="d-block mt-3 mt-lg-4">
                                                                <p class="price dark-text-color">
                                                                    <span class="value">72.00</span> 
                                                                    <span class="currency">USD</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="general-price" class="col-lg-3  text-start text-lg-center">
                                                        <div class="price-wrapper">
                                                            <strong>Загальний дохід <br>Forevers</strong>
                                                            <div class="d-block mt-3 mt-lg-4">
                                                                <p class="price">
                                                                    <span class="value">1 755.00</span> 
                                                                    <span class="currency">USD</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tablink-2">
                            <div class="row">
                                <div class="col-lg-12 col-xxl-12 m-auto">
                                    <div id="calculator-2" class="calculator-form" data-product-price="3">
                                        <div class="range-block text-start">
                                            <div class="d-block range-input">
                                                <h3>Хочу базовий дохід в місяць, <span class="orange-text">$</span><span id="calculator-2-month-income-value">50.00</span>   
                                            </h3></div>
                                            <!-- Range Slider Here -->
                                            <div id="calculator-2-month-income" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr"><div class="noUi-base"><div class="noUi-connects"></div><div class="noUi-origin" style="transform: translate(-1000%, 0px); z-index: 4;"><div class="noUi-handle noUi-handle-lower" data-handle="0" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="50.0" aria-valuemax="5000.0" aria-valuenow="50.0" aria-valuetext="50"><div class="noUi-touch-area"></div></div></div></div><div class="noUi-pips noUi-pips-horizontal"><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 0%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="50" style="left: 0%;">50</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 9.09091%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="500" style="left: 9.09091%;">500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 19.1919%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="1000" style="left: 19.1919%;">1000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 29.2929%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="1500" style="left: 29.2929%;">1500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 39.3939%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="2000" style="left: 39.3939%;">2000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 49.495%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="2500" style="left: 49.495%;">2500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 59.596%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="3000" style="left: 59.596%;">3000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 69.697%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="3500" style="left: 69.697%;">3500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 79.798%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="4000" style="left: 79.798%;">4000</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 89.899%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="4500" style="left: 89.899%;">4500</div><div class="noUi-marker noUi-marker-horizontal noUi-marker-large" style="left: 100%;"></div><div class="noUi-value noUi-value-horizontal noUi-value-large" data-value="5000" style="left: 100%;">5000</div></div></div>
                                        </div>
                                        <div id="invest-sum" class="line-block">
                                            <div class="text-start d-block d-md-flex align-items-center justify-content-between">
                                                <h4>Сума інвестицій у Forevers</h4>
                                                <p class="price fs-22">
                                                    <span class="value">2 117.65</span> 
                                                    <span class="currency">USD</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="no-line-block">
                                                <strong>
                                                    Порівняти інвестиції Forevers з
                                                </strong>
                                            </div>
                                            <div class="bottom-prices-block">
                                                <div class="row">
                                                    <div id="sum-commercial-realestate-income" class="col-lg-4 text-start text-lg-center">
                                                        <div class="price-wrapper">
                                                            <strong>Сума інвестицій під 8% річних в комерційну нерухомість</strong>
                                                            <div class="d-block mt-3 mt-lg-4">
                                                                <p class="price dark-text-color">
                                                                    <span class="value">7 575.76</span> 
                                                                    <span class="currency">USD</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="sum-residental-realestate-income" class="col-lg-4  text-start text-lg-center">
                                                        <div class="price-wrapper">
                                                            <strong>Сума інвестицій під 5% річних в житлову нерухомість</strong>
                                                            <div class="d-block mt-3 mt-lg-4">
                                                                <p class="price dark-text-color">
                                                                    <span class="value">12 019.23</span> 
                                                                    <span class="currency">USD</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="sum-deposyt" class="col-lg-4 text-start text-lg-center">
                                                        <div class="price-wrapper">
                                                            <strong>Депозит в $ під <br>3.5% річних:</strong>
                                                            <div class="d-block mt-3 mt-lg-4">
                                                                <p class="price dark-text-color">
                                                                    <span class="value">17 182.13</span> 
                                                                    <span class="currency">USD</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>






<style>
@-webkit-keyframes progress-bar-stripes {
    0% {
        background-position-x: 1rem
    }
}

@keyframes progress-bar-stripes {
    0% {
        background-position-x: 1rem
    }
}

@-webkit-keyframes spinner-border {
    to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg)
    }
}

@keyframes spinner-border {
    to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg)
    }
}

@-webkit-keyframes spinner-grow {
    0% {
        -webkit-transform: scale(0);
        transform: scale(0)
    }

    50% {
        opacity: 1;
        -webkit-transform: none;
        transform: none
    }
}

@keyframes spinner-grow {
    0% {
        -webkit-transform: scale(0);
        transform: scale(0)
    }

    50% {
        opacity: 1;
        -webkit-transform: none;
        transform: none
    }
}

@-webkit-keyframes placeholder-glow {
    50% {
        opacity: .2
    }
}

@keyframes placeholder-glow {
    50% {
        opacity: .2
    }
}

@-webkit-keyframes placeholder-wave {
    to {
        -webkit-mask-position: -200% 0;
        mask-position: -200% 0
    }
}

@keyframes placeholder-wave {
    to {
        -webkit-mask-position: -200% 0;
        mask-position: -200% 0
    }
}

@-webkit-keyframes stroke {
    to {
        stroke-dashoffset: 0
    }
}

@keyframes stroke {
    to {
        stroke-dashoffset: 0
    }
}

@-webkit-keyframes scale {
    0%,to {
        -webkit-transform: none;
        transform: none
    }

    50% {
        -webkit-transform: scale3d(1.1,1.1,1);
        transform: scale3d(1.1,1.1,1)
    }
}

@keyframes scale {
    0%,to {
        -webkit-transform: none;
        transform: none
    }

    50% {
        -webkit-transform: scale3d(1.1,1.1,1);
        transform: scale3d(1.1,1.1,1)
    }
}

@-webkit-keyframes fill {
    to {
        -webkit-box-shadow: inset 0 0 0 30px #ff6319;
        box-shadow: inset 0 0 0 30px #ff6319
    }
}

@keyframes fill {
    to {
        -webkit-box-shadow: inset 0 0 0 30px #ff6319;
        box-shadow: inset 0 0 0 30px #ff6319
    }
}


@media (min-width: 1200px) {
    .h1,h1 {
        font-size:2.5rem
    }
}

.h2,h2 {
    font-size: calc(1.325rem + .9vw)
}

@media (min-width: 1200px) {
    .h2,h2 {
        font-size:2rem
    }
}

.h3,h3 {
    font-size: calc(1.3rem + .6vw)
}

@media (min-width: 1200px) {
    .h3,h3 {
        font-size:1.75rem
    }
}

.h4,h4 {
    font-size: calc(1.275rem + .3vw)
}

@media (min-width: 1200px) {
button,input,optgroup,select,textarea {
    margin: 0;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit
}

button,select {
    text-transform: none
}

[role=button] {
    cursor: pointer
}

select {
    word-wrap: normal
}

select:disabled {
    opacity: 1
}

[list]:not([type=date]):not([type=datetime-local]):not([type=month]):not([type=week]):not([type=time])::-webkit-calendar-picker-indicator {
    display: none!important
}

[type=button],[type=reset],[type=submit],button {
    -webkit-appearance: button
}

.form-control-color:not(:disabled):not([readonly]),.form-control[type=file]:not(:disabled):not([readonly]),[type=button]:not(:disabled),[type=reset]:not(:disabled),[type=submit]:not(:disabled),button:not(:disabled) {
    cursor: pointer
}

::-moz-focus-inner {
    padding: 0;
    border-style: none
}

textarea {
    resize: vertical
}

fieldset {
    min-width: 0;
    padding: 0;
    margin: 0;
    border: 0
}

legend {
    float: left;
    width: 100%;
    padding: 0;
    margin-bottom: .5rem;
    font-size: calc(1.275rem + .3vw);
    line-height: inherit
}

@media (min-width: 1200px) {
    legend {
        font-size:1.5rem
    }
}

legend+* {
    clear: left
}

::-webkit-datetime-edit-day-field,::-webkit-datetime-edit-fields-wrapper,::-webkit-datetime-edit-hour-field,::-webkit-datetime-edit-minute,::-webkit-datetime-edit-month-field,::-webkit-datetime-edit-text,::-webkit-datetime-edit-year-field {
    padding: 0
}

::-webkit-inner-spin-button {
    height: auto
}

[type=search] {
    -webkit-appearance: textfield;
    outline-offset: -2px
}

::-webkit-search-decoration {
    -webkit-appearance: none
}

::-webkit-color-swatch-wrapper {
    padding: 0
}

::-webkit-file-upload-button {
    font: inherit;
    -webkit-appearance: button
}

::file-selector-button {
    font: inherit;
    -webkit-appearance: button
}

iframe {
    border: 0
}

summary {
    display: list-item;
    cursor: pointer
}

progress {
    vertical-align: baseline
}

[hidden] {
    display: none!important
}

.lead {
    font-size: 1.25rem;
    font-weight: 300
}

.display-1 {
    font-size: calc(1.625rem + 4.5vw);
    font-weight: 300;
    line-height: 1.2
}

@media (min-width: 1200px) {
    .display-1 {
        font-size:5rem
    }
}

.display-2 {
    font-size: calc(1.575rem + 3.9vw);
    font-weight: 300;
    line-height: 1.2
}

@media (min-width: 1200px) {
    .display-2 {
        font-size:4.5rem
    }
}

.display-3 {
    font-size: calc(1.525rem + 3.3vw);
    font-weight: 300;
    line-height: 1.2
}

@media (min-width: 1200px) {
    .display-3 {
        font-size:4rem
    }
}

.display-4 {
    font-size: calc(1.475rem + 2.7vw);
    font-weight: 300;
    line-height: 1.2
}

@media (min-width: 1200px) {
    .display-4 {
        font-size:3.5rem
    }
}

.display-5 {
    font-size: calc(1.425rem + 2.1vw);
    font-weight: 300;
    line-height: 1.2
}

@media (min-width: 1200px) {
    .display-5 {
        font-size:3rem
    }
}

.display-6 {
    font-size: calc(1.375rem + 1.5vw);
    font-weight: 300;
    line-height: 1.2
}

@media (min-width: 1200px) {
    .display-6 {
        font-size:2.5rem
    }
}

.list-inline,.list-unstyled {
    padding-left: 0;
    list-style: none
}

.list-inline-item {
    display: inline-block
}

.list-inline-item:not(:last-child) {
    margin-right: .5rem
}

.initialism {
    font-size: .875em;
    text-transform: uppercase
}

.blockquote {
    margin-bottom: 1rem;
    font-size: 1.25rem
}

.blockquote>:last-child {
    margin-bottom: 0
}

.blockquote-footer {
    margin-top: -1rem;
    margin-bottom: 1rem;
    font-size: .875em;
    color: #6c757d
}

.blockquote-footer::before {
    content: "— "
}

.img-fluid,.img-thumbnail {
    max-width: 100%;
    height: auto
}

.img-thumbnail {
    padding: .25rem;
    background-color: var(--bs-body-bg);
    border: var(--bs-border-width) solid var(--bs-border-color);
    border-radius: var(--bs-border-radius)
}

.figure {
    display: inline-block
}

.figure-img {
    margin-bottom: .5rem;
    line-height: 1
}

.figure-caption {
    font-size: .875em;
    color: var(--bs-secondary-color)
}

.container,.container-fluid,.container-lg,.container-md,.container-sm,.container-xl,.container-xxl {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    width: 100%;
    padding-right: calc(var(--bs-gutter-x)*.5);
    padding-left: calc(var(--bs-gutter-x)*.5);
    margin-right: auto;
    margin-left: auto
}

@media (min-width: 576px) {
    .container,.container-sm {
        max-width:540px
    }
}

@media (min-width: 768px) {
    .container,.container-md,.container-sm {
        max-width:720px
    }
}

@media (min-width: 992px) {
    .container,.container-lg,.container-md,.container-sm {
        max-width:960px
    }
}

@media (min-width: 1200px) {
    .container,.container-lg,.container-md,.container-sm,.container-xl {
        max-width:1140px
    }
}

@media (min-width: 1400px) {
    .container,.container-lg,.container-md,.container-sm,.container-xl,.container-xxl {
        max-width:1320px
    }
}

:root {
    --bs-breakpoint-xs: 0;
    --bs-breakpoint-sm: 576px;
    --bs-breakpoint-md: 768px;
    --bs-breakpoint-lg: 992px;
    --bs-breakpoint-xl: 1200px;
    --bs-breakpoint-xxl: 1400px
}

.row {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-top: calc(-1*var(--bs-gutter-y));
    margin-right: calc(-.5*var(--bs-gutter-x));
    margin-left: calc(-.5*var(--bs-gutter-x))
}

.row>* {
    -ms-flex-negative: 0;
    flex-shrink: 0;
    width: 100%;
    max-width: 100%;
    padding-right: calc(var(--bs-gutter-x)*.5);
    padding-left: calc(var(--bs-gutter-x)*.5);
    margin-top: var(--bs-gutter-y)
}

.col {
    -webkit-box-flex: 1;
    -ms-flex: 1 0 0%;
    flex: 1 0 0%
}

.row-cols-1>*,.row-cols-auto>* {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto
}

.row-cols-1>* {
    width: 100%
}

.row-cols-2>*,.row-cols-3>*,.row-cols-4>*,.row-cols-5>* {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: 50%
}

.row-cols-3>*,.row-cols-4>*,.row-cols-5>* {
    width: 33.33333333%
}

.row-cols-4>*,.row-cols-5>* {
    width: 25%
}

.row-cols-5>* {
    width: 20%
}

.col-1,.col-2,.col-3,.col-4,.col-5,.col-auto,.row-cols-6>* {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: 16.66666667%
}

.col-1,.col-2,.col-3,.col-4,.col-5,.col-auto {
    width: auto
}

.col-1,.col-2,.col-3,.col-4,.col-5 {
    width: 8.33333333%
}

.col-2,.col-3,.col-4,.col-5 {
    width: 16.66666667%
}

.col-3,.col-4,.col-5 {
    width: 25%
}

.col-4,.col-5 {
    width: 33.33333333%
}

.col-5 {
    width: 41.66666667%
}

.col-10,.col-11,.col-12,.col-6,.col-7,.col-8,.col-9 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: 50%
}

.col-10,.col-11,.col-12,.col-7,.col-8,.col-9 {
    width: 58.33333333%
}

.col-10,.col-11,.col-12,.col-8,.col-9 {
    width: 66.66666667%
}

.col-10,.col-11,.col-12,.col-9 {
    width: 75%
}

.col-10,.col-11,.col-12 {
    width: 83.33333333%
}

.col-11,.col-12 {
    width: 91.66666667%
}

.col-12 {
    width: 100%
}

.offset-1 {
    margin-left: 8.33333333%
}

.offset-2 {
    margin-left: 16.66666667%
}

.offset-3 {
    margin-left: 25%
}

.offset-4 {
    margin-left: 33.33333333%
}

.offset-5 {
    margin-left: 41.66666667%
}

.offset-6 {
    margin-left: 50%
}

.offset-7 {
    margin-left: 58.33333333%
}

.offset-8 {
    margin-left: 66.66666667%
}

.offset-9 {
    margin-left: 75%
}

.offset-10 {
    margin-left: 83.33333333%
}

.offset-11 {
    margin-left: 91.66666667%
}

.g-0,.gx-0 {
    --bs-gutter-x: 0
}

.g-0,.gy-0 {
    --bs-gutter-y: 0
}

.g-1,.gx-1 {
    --bs-gutter-x: 0.25rem
}

.g-1,.gy-1 {
    --bs-gutter-y: 0.25rem
}

.g-2,.gx-2 {
    --bs-gutter-x: 0.5rem
}

.g-2,.gy-2 {
    --bs-gutter-y: 0.5rem
}

.g-3,.gx-3 {
    --bs-gutter-x: 1rem
}

.g-3,.gy-3 {
    --bs-gutter-y: 1rem
}

.g-4,.gx-4 {
    --bs-gutter-x: 1.5rem
}

.g-4,.gy-4 {
    --bs-gutter-y: 1.5rem
}

.g-5,.gx-5 {
    --bs-gutter-x: 3rem
}

.g-5,.gy-5 {
    --bs-gutter-y: 3rem
}

@media (min-width: 576px) {
    .col-sm {
        -webkit-box-flex:1;
        -ms-flex: 1 0 0%;
        flex: 1 0 0%
    }

    .row-cols-sm-1>*,.row-cols-sm-auto>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto
    }

    .row-cols-sm-1>* {
        width: 100%
    }

    .row-cols-sm-2>*,.row-cols-sm-3>*,.row-cols-sm-4>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 50%
    }

    .row-cols-sm-3>*,.row-cols-sm-4>* {
        width: 33.33333333%
    }

    .row-cols-sm-4>* {
        width: 25%
    }

    .col-sm-1,.col-sm-auto,.row-cols-sm-5>*,.row-cols-sm-6>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 20%
    }

    .col-sm-1,.col-sm-auto,.row-cols-sm-6>* {
        width: 16.66666667%
    }

    .col-sm-1,.col-sm-auto {
        width: auto
    }

    .col-sm-1 {
        width: 8.33333333%
    }

    .col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 16.66666667%
    }

    .col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7 {
        width: 25%
    }

    .col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7 {
        width: 33.33333333%
    }

    .col-sm-5,.col-sm-6,.col-sm-7 {
        width: 41.66666667%
    }

    .col-sm-6,.col-sm-7 {
        width: 50%
    }

    .col-sm-7 {
        width: 58.33333333%
    }

    .col-sm-10,.col-sm-11,.col-sm-12,.col-sm-8,.col-sm-9 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 66.66666667%
    }

    .col-sm-10,.col-sm-11,.col-sm-12,.col-sm-9 {
        width: 75%
    }

    .col-sm-10,.col-sm-11,.col-sm-12 {
        width: 83.33333333%
    }

    .col-sm-11,.col-sm-12 {
        width: 91.66666667%
    }

    .col-sm-12 {
        width: 100%
    }

    .offset-sm-0 {
        margin-left: 0
    }

    .offset-sm-1 {
        margin-left: 8.33333333%
    }

    .offset-sm-2 {
        margin-left: 16.66666667%
    }

    .offset-sm-3 {
        margin-left: 25%
    }

    .offset-sm-4 {
        margin-left: 33.33333333%
    }

    .offset-sm-5 {
        margin-left: 41.66666667%
    }

    .offset-sm-6 {
        margin-left: 50%
    }

    .offset-sm-7 {
        margin-left: 58.33333333%
    }

    .offset-sm-8 {
        margin-left: 66.66666667%
    }

    .offset-sm-9 {
        margin-left: 75%
    }

    .offset-sm-10 {
        margin-left: 83.33333333%
    }

    .offset-sm-11 {
        margin-left: 91.66666667%
    }

    .g-sm-0,.gx-sm-0 {
        --bs-gutter-x: 0
    }

    .g-sm-0,.gy-sm-0 {
        --bs-gutter-y: 0
    }

    .g-sm-1,.gx-sm-1 {
        --bs-gutter-x: 0.25rem
    }

    .g-sm-1,.gy-sm-1 {
        --bs-gutter-y: 0.25rem
    }

    .g-sm-2,.gx-sm-2 {
        --bs-gutter-x: 0.5rem
    }

    .g-sm-2,.gy-sm-2 {
        --bs-gutter-y: 0.5rem
    }

    .g-sm-3,.gx-sm-3 {
        --bs-gutter-x: 1rem
    }

    .g-sm-3,.gy-sm-3 {
        --bs-gutter-y: 1rem
    }

    .g-sm-4,.gx-sm-4 {
        --bs-gutter-x: 1.5rem
    }

    .g-sm-4,.gy-sm-4 {
        --bs-gutter-y: 1.5rem
    }

    .g-sm-5,.gx-sm-5 {
        --bs-gutter-x: 3rem
    }

    .g-sm-5,.gy-sm-5 {
        --bs-gutter-y: 3rem
    }
}

@media (min-width: 768px) {
    .col-md {
        -webkit-box-flex:1;
        -ms-flex: 1 0 0%;
        flex: 1 0 0%
    }

    .row-cols-md-1>*,.row-cols-md-auto>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto
    }

    .row-cols-md-1>* {
        width: 100%
    }

    .row-cols-md-2>*,.row-cols-md-3>*,.row-cols-md-4>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 50%
    }

    .row-cols-md-3>*,.row-cols-md-4>* {
        width: 33.33333333%
    }

    .row-cols-md-4>* {
        width: 25%
    }

    .col-md-1,.col-md-auto,.row-cols-md-5>*,.row-cols-md-6>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 20%
    }

    .col-md-1,.col-md-auto,.row-cols-md-6>* {
        width: 16.66666667%
    }

    .col-md-1,.col-md-auto {
        width: auto
    }

    .col-md-1 {
        width: 8.33333333%
    }

    .col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 16.66666667%
    }

    .col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7 {
        width: 25%
    }

    .col-md-4,.col-md-5,.col-md-6,.col-md-7 {
        width: 33.33333333%
    }

    .col-md-5,.col-md-6,.col-md-7 {
        width: 41.66666667%
    }

    .col-md-6,.col-md-7 {
        width: 50%
    }

    .col-md-7 {
        width: 58.33333333%
    }

    .col-md-10,.col-md-11,.col-md-12,.col-md-8,.col-md-9 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 66.66666667%
    }

    .col-md-10,.col-md-11,.col-md-12,.col-md-9 {
        width: 75%
    }

    .col-md-10,.col-md-11,.col-md-12 {
        width: 83.33333333%
    }

    .col-md-11,.col-md-12 {
        width: 91.66666667%
    }

    .col-md-12 {
        width: 100%
    }

    .offset-md-0 {
        margin-left: 0
    }

    .offset-md-1 {
        margin-left: 8.33333333%
    }

    .offset-md-2 {
        margin-left: 16.66666667%
    }

    .offset-md-3 {
        margin-left: 25%
    }

    .offset-md-4 {
        margin-left: 33.33333333%
    }

    .offset-md-5 {
        margin-left: 41.66666667%
    }

    .offset-md-6 {
        margin-left: 50%
    }

    .offset-md-7 {
        margin-left: 58.33333333%
    }

    .offset-md-8 {
        margin-left: 66.66666667%
    }

    .offset-md-9 {
        margin-left: 75%
    }

    .offset-md-10 {
        margin-left: 83.33333333%
    }

    .offset-md-11 {
        margin-left: 91.66666667%
    }

    .g-md-0,.gx-md-0 {
        --bs-gutter-x: 0
    }

    .g-md-0,.gy-md-0 {
        --bs-gutter-y: 0
    }

    .g-md-1,.gx-md-1 {
        --bs-gutter-x: 0.25rem
    }

    .g-md-1,.gy-md-1 {
        --bs-gutter-y: 0.25rem
    }

    .g-md-2,.gx-md-2 {
        --bs-gutter-x: 0.5rem
    }

    .g-md-2,.gy-md-2 {
        --bs-gutter-y: 0.5rem
    }

    .g-md-3,.gx-md-3 {
        --bs-gutter-x: 1rem
    }

    .g-md-3,.gy-md-3 {
        --bs-gutter-y: 1rem
    }

    .g-md-4,.gx-md-4 {
        --bs-gutter-x: 1.5rem
    }

    .g-md-4,.gy-md-4 {
        --bs-gutter-y: 1.5rem
    }

    .g-md-5,.gx-md-5 {
        --bs-gutter-x: 3rem
    }

    .g-md-5,.gy-md-5 {
        --bs-gutter-y: 3rem
    }
}

@media (min-width: 992px) {
    .col-lg {
        -webkit-box-flex:1;
        -ms-flex: 1 0 0%;
        flex: 1 0 0%
    }

    .row-cols-lg-1>*,.row-cols-lg-auto>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto
    }

    .row-cols-lg-1>* {
        width: 100%
    }

    .row-cols-lg-2>*,.row-cols-lg-3>*,.row-cols-lg-4>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 50%
    }

    .row-cols-lg-3>*,.row-cols-lg-4>* {
        width: 33.33333333%
    }

    .row-cols-lg-4>* {
        width: 25%
    }

    .col-lg-1,.col-lg-auto,.row-cols-lg-5>*,.row-cols-lg-6>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 20%
    }

    .col-lg-1,.col-lg-auto,.row-cols-lg-6>* {
        width: 16.66666667%
    }

    .col-lg-1,.col-lg-auto {
        width: auto
    }

    .col-lg-1 {
        width: 8.33333333%
    }

    .col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 16.66666667%
    }

    .col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7 {
        width: 25%
    }

    .col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7 {
        width: 33.33333333%
    }

    .col-lg-5,.col-lg-6,.col-lg-7 {
        width: 41.66666667%
    }

    .col-lg-6,.col-lg-7 {
        width: 50%
    }

    .col-lg-7 {
        width: 58.33333333%
    }

    .col-lg-10,.col-lg-11,.col-lg-12,.col-lg-8,.col-lg-9 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 66.66666667%
    }

    .col-lg-10,.col-lg-11,.col-lg-12,.col-lg-9 {
        width: 75%
    }

    .col-lg-10,.col-lg-11,.col-lg-12 {
        width: 83.33333333%
    }

    .col-lg-11,.col-lg-12 {
        width: 91.66666667%
    }

    .col-lg-12 {
        width: 100%
    }

    .offset-lg-0 {
        margin-left: 0
    }

    .offset-lg-1 {
        margin-left: 8.33333333%
    }

    .offset-lg-2 {
        margin-left: 16.66666667%
    }

    .offset-lg-3 {
        margin-left: 25%
    }

    .offset-lg-4 {
        margin-left: 33.33333333%
    }

    .offset-lg-5 {
        margin-left: 41.66666667%
    }

    .offset-lg-6 {
        margin-left: 50%
    }

    .offset-lg-7 {
        margin-left: 58.33333333%
    }

    .offset-lg-8 {
        margin-left: 66.66666667%
    }

    .offset-lg-9 {
        margin-left: 75%
    }

    .offset-lg-10 {
        margin-left: 83.33333333%
    }

    .offset-lg-11 {
        margin-left: 91.66666667%
    }

    .g-lg-0,.gx-lg-0 {
        --bs-gutter-x: 0
    }

    .g-lg-0,.gy-lg-0 {
        --bs-gutter-y: 0
    }

    .g-lg-1,.gx-lg-1 {
        --bs-gutter-x: 0.25rem
    }

    .g-lg-1,.gy-lg-1 {
        --bs-gutter-y: 0.25rem
    }

    .g-lg-2,.gx-lg-2 {
        --bs-gutter-x: 0.5rem
    }

    .g-lg-2,.gy-lg-2 {
        --bs-gutter-y: 0.5rem
    }

    .g-lg-3,.gx-lg-3 {
        --bs-gutter-x: 1rem
    }

    .g-lg-3,.gy-lg-3 {
        --bs-gutter-y: 1rem
    }

    .g-lg-4,.gx-lg-4 {
        --bs-gutter-x: 1.5rem
    }

    .g-lg-4,.gy-lg-4 {
        --bs-gutter-y: 1.5rem
    }

    .g-lg-5,.gx-lg-5 {
        --bs-gutter-x: 3rem
    }

    .g-lg-5,.gy-lg-5 {
        --bs-gutter-y: 3rem
    }
}

@media (min-width: 1200px) {
    .col-xl {
        -webkit-box-flex:1;
        -ms-flex: 1 0 0%;
        flex: 1 0 0%
    }

    .row-cols-xl-1>*,.row-cols-xl-auto>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto
    }

    .row-cols-xl-1>* {
        width: 100%
    }

    .row-cols-xl-2>*,.row-cols-xl-3>*,.row-cols-xl-4>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 50%
    }

    .row-cols-xl-3>*,.row-cols-xl-4>* {
        width: 33.33333333%
    }

    .row-cols-xl-4>* {
        width: 25%
    }

    .col-xl-1,.col-xl-auto,.row-cols-xl-5>*,.row-cols-xl-6>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 20%
    }

    .col-xl-1,.col-xl-auto,.row-cols-xl-6>* {
        width: 16.66666667%
    }

    .col-xl-1,.col-xl-auto {
        width: auto
    }

    .col-xl-1 {
        width: 8.33333333%
    }

    .col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 16.66666667%
    }

    .col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7 {
        width: 25%
    }

    .col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7 {
        width: 33.33333333%
    }

    .col-xl-5,.col-xl-6,.col-xl-7 {
        width: 41.66666667%
    }

    .col-xl-6,.col-xl-7 {
        width: 50%
    }

    .col-xl-7 {
        width: 58.33333333%
    }

    .col-xl-10,.col-xl-11,.col-xl-12,.col-xl-8,.col-xl-9 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 66.66666667%
    }

    .col-xl-10,.col-xl-11,.col-xl-12,.col-xl-9 {
        width: 75%
    }

    .col-xl-10,.col-xl-11,.col-xl-12 {
        width: 83.33333333%
    }

    .col-xl-11,.col-xl-12 {
        width: 91.66666667%
    }

    .col-xl-12 {
        width: 100%
    }

    .offset-xl-0 {
        margin-left: 0
    }

    .offset-xl-1 {
        margin-left: 8.33333333%
    }

    .offset-xl-2 {
        margin-left: 16.66666667%
    }

    .offset-xl-3 {
        margin-left: 25%
    }

    .offset-xl-4 {
        margin-left: 33.33333333%
    }

    .offset-xl-5 {
        margin-left: 41.66666667%
    }

    .offset-xl-6 {
        margin-left: 50%
    }

    .offset-xl-7 {
        margin-left: 58.33333333%
    }

    .offset-xl-8 {
        margin-left: 66.66666667%
    }

    .offset-xl-9 {
        margin-left: 75%
    }

    .offset-xl-10 {
        margin-left: 83.33333333%
    }

    .offset-xl-11 {
        margin-left: 91.66666667%
    }

    .g-xl-0,.gx-xl-0 {
        --bs-gutter-x: 0
    }

    .g-xl-0,.gy-xl-0 {
        --bs-gutter-y: 0
    }

    .g-xl-1,.gx-xl-1 {
        --bs-gutter-x: 0.25rem
    }

    .g-xl-1,.gy-xl-1 {
        --bs-gutter-y: 0.25rem
    }

    .g-xl-2,.gx-xl-2 {
        --bs-gutter-x: 0.5rem
    }

    .g-xl-2,.gy-xl-2 {
        --bs-gutter-y: 0.5rem
    }

    .g-xl-3,.gx-xl-3 {
        --bs-gutter-x: 1rem
    }

    .g-xl-3,.gy-xl-3 {
        --bs-gutter-y: 1rem
    }

    .g-xl-4,.gx-xl-4 {
        --bs-gutter-x: 1.5rem
    }

    .g-xl-4,.gy-xl-4 {
        --bs-gutter-y: 1.5rem
    }

    .g-xl-5,.gx-xl-5 {
        --bs-gutter-x: 3rem
    }

    .g-xl-5,.gy-xl-5 {
        --bs-gutter-y: 3rem
    }
}

@media (min-width: 1400px) {
    .col-xxl {
        -webkit-box-flex:1;
        -ms-flex: 1 0 0%;
        flex: 1 0 0%
    }

    .row-cols-xxl-1>*,.row-cols-xxl-2>*,.row-cols-xxl-auto>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto
    }

    .row-cols-xxl-1>*,.row-cols-xxl-2>* {
        width: 100%
    }

    .row-cols-xxl-2>* {
        width: 50%
    }

    .row-cols-xxl-3>*,.row-cols-xxl-4>*,.row-cols-xxl-5>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 33.33333333%
    }

    .row-cols-xxl-4>*,.row-cols-xxl-5>* {
        width: 25%
    }

    .row-cols-xxl-5>* {
        width: 20%
    }

    .col-xxl-1,.col-xxl-2,.col-xxl-auto,.row-cols-xxl-6>* {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 16.66666667%
    }

    .col-xxl-1,.col-xxl-2,.col-xxl-auto {
        width: auto
    }

    .col-xxl-1,.col-xxl-2 {
        width: 8.33333333%
    }

    .col-xxl-2 {
        width: 16.66666667%
    }

    .col-xxl-3,.col-xxl-4,.col-xxl-5,.col-xxl-6,.col-xxl-7 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 25%
    }

    .col-xxl-4,.col-xxl-5,.col-xxl-6,.col-xxl-7 {
        width: 33.33333333%
    }

    .col-xxl-5,.col-xxl-6,.col-xxl-7 {
        width: 41.66666667%
    }

    .col-xxl-6,.col-xxl-7 {
        width: 50%
    }

    .col-xxl-7 {
        width: 58.33333333%
    }

    .col-xxl-10,.col-xxl-11,.col-xxl-12,.col-xxl-8,.col-xxl-9 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: 66.66666667%
    }

    .col-xxl-10,.col-xxl-11,.col-xxl-12,.col-xxl-9 {
        width: 75%
    }

    .col-xxl-10,.col-xxl-11,.col-xxl-12 {
        width: 83.33333333%
    }

    .col-xxl-11,.col-xxl-12 {
        width: 91.66666667%
    }

    .col-xxl-12 {
        width: 100%
    }

    .offset-xxl-0 {
        margin-left: 0
    }

    .offset-xxl-1 {
        margin-left: 8.33333333%
    }

    .offset-xxl-2 {
        margin-left: 16.66666667%
    }

    .offset-xxl-3 {
        margin-left: 25%
    }

    .offset-xxl-4 {
        margin-left: 33.33333333%
    }

    .offset-xxl-5 {
        margin-left: 41.66666667%
    }

    .offset-xxl-6 {
        margin-left: 50%
    }

    .offset-xxl-7 {
        margin-left: 58.33333333%
    }

    .offset-xxl-8 {
        margin-left: 66.66666667%
    }

    .offset-xxl-9 {
        margin-left: 75%
    }

    .offset-xxl-10 {
        margin-left: 83.33333333%
    }

    .offset-xxl-11 {
        margin-left: 91.66666667%
    }

    .g-xxl-0,.gx-xxl-0 {
        --bs-gutter-x: 0
    }

    .g-xxl-0,.gy-xxl-0 {
        --bs-gutter-y: 0
    }

    .g-xxl-1,.gx-xxl-1 {
        --bs-gutter-x: 0.25rem
    }

    .g-xxl-1,.gy-xxl-1 {
        --bs-gutter-y: 0.25rem
    }

    .g-xxl-2,.gx-xxl-2 {
        --bs-gutter-x: 0.5rem
    }

    .g-xxl-2,.gy-xxl-2 {
        --bs-gutter-y: 0.5rem
    }

    .g-xxl-3,.gx-xxl-3 {
        --bs-gutter-x: 1rem
    }

    .g-xxl-3,.gy-xxl-3 {
        --bs-gutter-y: 1rem
    }

    .g-xxl-4,.gx-xxl-4 {
        --bs-gutter-x: 1.5rem
    }

    .g-xxl-4,.gy-xxl-4 {
        --bs-gutter-y: 1.5rem
    }

    .g-xxl-5,.gx-xxl-5 {
        --bs-gutter-x: 3rem
    }

    .g-xxl-5,.gy-xxl-5 {
        --bs-gutter-y: 3rem
    }
}

.table {
    --bs-table-color-type: initial;
    --bs-table-bg-type: initial;
    --bs-table-color-state: initial;
    --bs-table-bg-state: initial;
    --bs-table-color: var(--bs-emphasis-color);
    --bs-table-bg: var(--bs-body-bg);
    --bs-table-border-color: var(--bs-border-color);
    --bs-table-accent-bg: transparent;
    --bs-table-striped-color: var(--bs-emphasis-color);
    --bs-table-striped-bg: rgba(var(--bs-emphasis-color-rgb), 0.05);
    --bs-table-active-color: var(--bs-emphasis-color);
    --bs-table-active-bg: rgba(var(--bs-emphasis-color-rgb), 0.1);
    --bs-table-hover-color: var(--bs-emphasis-color);
    --bs-table-hover-bg: rgba(var(--bs-emphasis-color-rgb), 0.075);
    width: 100%;
    margin-bottom: 1rem;
    vertical-align: top;
    border-color: var(--bs-table-border-color)
}

.table>:not(caption)>*>* {
    padding: .5rem;
    color: var(--bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)));
    background-color: var(--bs-table-bg);
    border-bottom-width: var(--bs-border-width);
    -webkit-box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
    box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)))
}

.table>tbody {
    vertical-align: inherit
}

.table>thead {
    vertical-align: bottom
}

.table-group-divider {
    border-top: calc(var(--bs-border-width)*2) solid currentcolor
}

.caption-top {
    caption-side: top
}

.table-sm>:not(caption)>*>* {
    padding: .25rem
}

.table-bordered>:not(caption)>* {
    border-width: var(--bs-border-width) 0
}

.table-bordered>:not(caption)>*>* {
    border-width: 0 var(--bs-border-width)
}

.table-borderless>:not(caption)>*>* {
    border-bottom-width: 0
}

.table-borderless>:not(:first-child) {
    border-top-width: 0
}

.table-striped>tbody>tr:nth-of-type(odd)>* {
    --bs-table-color-type: var(--bs-table-striped-color);
    --bs-table-bg-type: var(--bs-table-striped-bg)
}

.table-striped-columns>:not(caption)>tr>:nth-child(even) {
    --bs-table-color-type: var(--bs-table-striped-color);
    --bs-table-bg-type: var(--bs-table-striped-bg)
}

.table-active {
    --bs-table-color-state: var(--bs-table-active-color);
    --bs-table-bg-state: var(--bs-table-active-bg)
}

.table-hover>tbody>tr:hover>* {
    --bs-table-color-state: var(--bs-table-hover-color);
    --bs-table-bg-state: var(--bs-table-hover-bg)
}

.table-primary,.table-secondary {
    --bs-table-color: #000;
    --bs-table-striped-color: #000;
    --bs-table-active-color: #000;
    --bs-table-hover-color: #000
}

.table-primary {
    --bs-table-bg: #cfe2ff;
    --bs-table-border-color: #a6b5cc;
    --bs-table-striped-bg: #c5d7f2;
    --bs-table-active-bg: #bacbe6;
    --bs-table-hover-bg: #bfd1ec;
    color: var(--bs-table-color);
    border-color: var(--bs-table-border-color)
}

.table-secondary {
    --bs-table-bg: #e2e3e5;
    --bs-table-border-color: #b5b6b7;
    --bs-table-striped-bg: #d7d8da;
    --bs-table-active-bg: #cbccce;
    --bs-table-hover-bg: #d1d2d4
}

.table-info,.table-secondary,.table-success {
    color: var(--bs-table-color);
    border-color: var(--bs-table-border-color)
}

.table-success {
    --bs-table-color: #000;
    --bs-table-bg: #d1e7dd;
    --bs-table-border-color: #a7b9b1;
    --bs-table-striped-bg: #c7dbd2;
    --bs-table-striped-color: #000;
    --bs-table-active-bg: #bcd0c7;
    --bs-table-active-color: #000;
    --bs-table-hover-bg: #c1d6cc;
    --bs-table-hover-color: #000
}

.table-info {
    --bs-table-bg: #cff4fc;
    --bs-table-border-color: #a6c3ca;
    --bs-table-striped-bg: #c5e8ef;
    --bs-table-active-bg: #badce3;
    --bs-table-hover-bg: #bfe2e9
}

.table-danger,.table-info,.table-warning {
    --bs-table-color: #000;
    --bs-table-striped-color: #000;
    --bs-table-active-color: #000;
    --bs-table-hover-color: #000
}

.table-warning {
    --bs-table-bg: #fff3cd;
    --bs-table-border-color: #ccc2a4;
    --bs-table-striped-bg: #f2e7c3;
    --bs-table-active-bg: #e6dbb9;
    --bs-table-hover-bg: #ece1be;
    color: var(--bs-table-color);
    border-color: var(--bs-table-border-color)
}

.table-danger {
    --bs-table-bg: #f8d7da;
    --bs-table-border-color: #c6acae;
    --bs-table-striped-bg: #eccccf;
    --bs-table-active-bg: #dfc2c4;
    --bs-table-hover-bg: #e5c7ca
}

.table-danger,.table-dark,.table-light {
    color: var(--bs-table-color);
    border-color: var(--bs-table-border-color)
}

.table-light {
    --bs-table-color: #000;
    --bs-table-bg: #f8f9fa;
    --bs-table-border-color: #c6c7c8;
    --bs-table-striped-bg: #ecedee;
    --bs-table-striped-color: #000;
    --bs-table-active-bg: #dfe0e1;
    --bs-table-active-color: #000;
    --bs-table-hover-bg: #e5e6e7;
    --bs-table-hover-color: #000
}

.table-dark {
    --bs-table-color: #fff;
    --bs-table-bg: #212529;
    --bs-table-border-color: #4d5154;
    --bs-table-striped-bg: #2c3034;
    --bs-table-striped-color: #fff;
    --bs-table-active-bg: #373b3e;
    --bs-table-active-color: #fff;
    --bs-table-hover-bg: #323539;
    --bs-table-hover-color: #fff
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch
}

@media (max-width: 575.98px) {
    .table-responsive-sm {
        overflow-x:auto;
        -webkit-overflow-scrolling: touch
    }
}

@media (max-width: 767.98px) {
    .table-responsive-md {
        overflow-x:auto;
        -webkit-overflow-scrolling: touch
    }
}

@media (max-width: 991.98px) {
    .table-responsive-lg {
        overflow-x:auto;
        -webkit-overflow-scrolling: touch
    }
}

@media (max-width: 1199.98px) {
    .table-responsive-xl {
        overflow-x:auto;
        -webkit-overflow-scrolling: touch
    }
}

@media (max-width: 1399.98px) {
    .table-responsive-xxl {
        overflow-x:auto;
        -webkit-overflow-scrolling: touch
    }
}

.form-label {
    margin-bottom: .5rem
}

.col-form-label {
    padding-top: calc(.375rem + var(--bs-border-width));
    padding-bottom: calc(.375rem + var(--bs-border-width));
    margin-bottom: 0;
    font-size: inherit;
    line-height: 1.5
}

.col-form-label-lg {
    padding-top: calc(.5rem + var(--bs-border-width));
    padding-bottom: calc(.5rem + var(--bs-border-width));
    font-size: 1.25rem
}

.col-form-label-sm {
    padding-top: calc(.25rem + var(--bs-border-width));
    padding-bottom: calc(.25rem + var(--bs-border-width));
    font-size: .875rem
}

.form-text {
    margin-top: .25rem;
    font-size: .875em;
    color: var(--bs-secondary-color)
}

.form-control {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: var(--bs-body-color);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: var(--bs-body-bg);
    background-clip: padding-box;
    border: var(--bs-border-width) solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    -webkit-transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .form-control {
        -webkit-transition: none;
        transition: none
    }
}

#page.site,.form-control[type=file] {
    overflow: hidden
}

.form-control:focus {
    color: var(--bs-body-color);
    background-color: var(--bs-body-bg);
    border-color: #86b7fe;
    outline: 0;
    -webkit-box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.25)
}

.form-control::-webkit-date-and-time-value {
    min-width: 85px;
    height: 1.5em;
    margin: 0
}

.form-control::-webkit-datetime-edit {
    display: block;
    padding: 0
}

.form-control::-webkit-input-placeholder {
    color: var(--bs-secondary-color);
    opacity: 1
}

.form-control::-moz-placeholder {
    color: var(--bs-secondary-color);
    opacity: 1
}

.form-control:-ms-input-placeholder {
    color: var(--bs-secondary-color);
    opacity: 1
}

.form-control::-ms-input-placeholder {
    color: var(--bs-secondary-color);
    opacity: 1
}

.form-control::placeholder {
    color: var(--bs-secondary-color);
    opacity: 1
}

.form-control:disabled {
    background-color: var(--bs-secondary-bg);
    opacity: 1
}

.form-control::-webkit-file-upload-button {
    padding: .375rem .75rem;
    margin: -.375rem -.75rem;
    -webkit-margin-end: .75rem;
    margin-inline-end:.75rem;color: var(--bs-body-color);
    background-color: var(--bs-tertiary-bg);
    pointer-events: none;
    border-color: inherit;
    border-style: solid;
    border-width: 0;
    border-inline-end-width:var(--bs-border-width);border-radius: 0;
    -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out
}

.form-control::file-selector-button {
    padding: .375rem .75rem;
    margin: -.375rem -.75rem;
    -webkit-margin-end: .75rem;
    margin-inline-end:.75rem;color: var(--bs-body-color);
    background-color: var(--bs-tertiary-bg);
    pointer-events: none;
    border-color: inherit;
    border-style: solid;
    border-width: 0;
    border-inline-end-width:var(--bs-border-width);border-radius: 0;
    -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .form-control::-webkit-file-upload-button {
        -webkit-transition: none;
        transition: none
    }

    .form-control::file-selector-button {
        -webkit-transition: none;
        transition: none
    }
}

.form-control:hover:not(:disabled):not([readonly])::-webkit-file-upload-button {
    background-color: var(--bs-secondary-bg)
}

.form-control:hover:not(:disabled):not([readonly])::file-selector-button {
    background-color: var(--bs-secondary-bg)
}

.form-control-plaintext {
    display: block;
    width: 100%;
    padding: .375rem 0;
    margin-bottom: 0;
    line-height: 1.5;
    color: var(--bs-body-color);
    background-color: transparent;
    border: solid transparent;
    border-width: var(--bs-border-width) 0
}

.form-control-plaintext:focus {
    outline: 0
}

.form-control-plaintext.form-control-lg,.form-control-plaintext.form-control-sm {
    padding-right: 0;
    padding-left: 0
}

.form-control-sm {
    min-height: calc(1.5em + .5rem + calc(var(--bs-border-width)*2));
    padding: .25rem .5rem;
    font-size: .875rem;
    border-radius: var(--bs-border-radius-sm)
}

.form-control-sm::-webkit-file-upload-button {
    padding: .25rem .5rem;
    margin: -.25rem -.5rem;
    -webkit-margin-end: .5rem;
    margin-inline-end:.5rem}

.form-control-sm::file-selector-button {
    padding: .25rem .5rem;
    margin: -.25rem -.5rem;
    -webkit-margin-end: .5rem;
    margin-inline-end:.5rem}

.form-control-lg {
    min-height: calc(1.5em + 1rem + calc(var(--bs-border-width)*2));
    padding: .5rem 1rem;
    font-size: 1.25rem;
    border-radius: var(--bs-border-radius-lg)
}

.form-control-lg::-webkit-file-upload-button {
    padding: .5rem 1rem;
    margin: -.5rem -1rem;
    -webkit-margin-end: 1rem;
    margin-inline-end:1rem}

.form-control-lg::file-selector-button {
    padding: .5rem 1rem;
    margin: -.5rem -1rem;
    -webkit-margin-end: 1rem;
    margin-inline-end:1rem}

textarea.form-control {
    min-height: calc(1.5em + .75rem + calc(var(--bs-border-width)*2))
}

textarea.form-control-sm {
    min-height: calc(1.5em + .5rem + calc(var(--bs-border-width)*2))
}

textarea.form-control-lg {
    min-height: calc(1.5em + 1rem + calc(var(--bs-border-width)*2))
}

.form-control-color {
    width: 3rem;
    height: calc(1.5em + .75rem + calc(var(--bs-border-width)*2));
    padding: .375rem
}

.form-control-color::-moz-color-swatch {
    border: 0!important;
    border-radius: var(--bs-border-radius)
}

.form-control-color::-webkit-color-swatch {
    border: 0!important;
    border-radius: var(--bs-border-radius)
}

.form-control-color.form-control-sm {
    height: calc(1.5em + .5rem + calc(var(--bs-border-width)*2))
}

.form-control-color.form-control-lg {
    height: calc(1.5em + 1rem + calc(var(--bs-border-width)*2))
}

.form-select {
    --bs-form-select-bg-img: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    display: block;
    width: 100%;
    padding: .375rem 2.25rem .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: var(--bs-body-color);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: var(--bs-body-bg);
    background-image: var(--bs-form-select-bg-img),var(--bs-form-select-bg-icon, none);
    background-repeat: no-repeat;
    background-position: right .75rem center;
    background-size: 16px 12px;
    border: var(--bs-border-width) solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    -webkit-transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .form-select {
        -webkit-transition: none;
        transition: none
    }
}

.form-select:focus {
    border-color: #86b7fe;
    outline: 0;
    -webkit-box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.25)
}

.form-select[multiple],.form-select[size]:not([size="1"]) {
    padding-right: .75rem;
    background-image: none
}

.form-select:disabled {
    background-color: var(--bs-secondary-bg)
}

.form-select:-moz-focusring {
    color: transparent;
    text-shadow: 0 0 0 var(--bs-body-color)
}

.form-select-sm {
    padding-top: .25rem;
    padding-bottom: .25rem;
    padding-left: .5rem;
    font-size: .875rem;
    border-radius: var(--bs-border-radius-sm)
}

.form-select-lg {
    padding-top: .5rem;
    padding-bottom: .5rem;
    padding-left: 1rem;
    font-size: 1.25rem;
    border-radius: var(--bs-border-radius-lg)
}

[data-bs-theme=dark] .form-select {
    --bs-form-select-bg-img: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23dee2e6' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e")
}

.form-check {
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5em;
    margin-bottom: .125rem
}

.form-check .form-check-input {
    float: left;
    margin-left: -1.5em
}

.form-check-reverse {
    padding-right: 1.5em;
    padding-left: 0;
    text-align: right
}

.form-check-reverse .form-check-input {
    float: right;
    margin-right: -1.5em;
    margin-left: 0
}

.form-check-input {
    --bs-form-check-bg: var(--bs-body-bg);
    -ms-flex-negative: 0;
    flex-shrink: 0;
    width: 1em;
    height: 1em;
    margin-top: .25em;
    vertical-align: top;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: var(--bs-form-check-bg);
    background-image: var(--bs-form-check-bg-image);
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    border: var(--bs-border-width) solid var(--bs-border-color);
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact
}

.form-check-input[type=checkbox] {
    border-radius: .25em
}

.form-check-input[type=radio] {
    border-radius: 50%
}

.form-check-input:active {
    -webkit-filter: brightness(90%);
    filter: brightness(90%)
}

.form-check-input:focus {
    border-color: #86b7fe;
    outline: 0;
    -webkit-box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.25)
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd
}

.form-check-input:checked[type=checkbox] {
    --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e")
}

.form-check-input:checked[type=radio] {
    --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e")
}

.form-check-input[type=checkbox]:indeterminate {
    background-color: #0d6efd;
    border-color: #0d6efd;
    --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e")
}

.form-check-input:disabled {
    pointer-events: none;
    -webkit-filter: none;
    filter: none;
    opacity: .5
}

.form-check-input:disabled~.form-check-label,.form-check-input[disabled]~.form-check-label {
    cursor: default;
    opacity: .5
}

.form-switch {
    padding-left: 2.5em
}

.form-switch .form-check-input {
    --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
    width: 2em;
    margin-left: -2.5em;
    background-image: var(--bs-form-switch-bg);
    background-position: left center;
    border-radius: 2em;
    -webkit-transition: background-position .15s ease-in-out;
    transition: background-position .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .form-switch .form-check-input {
        -webkit-transition: none;
        transition: none
    }
}

.form-switch .form-check-input:focus {
    --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%2386b7fe'/%3e%3c/svg%3e")
}

.form-switch .form-check-input:checked {
    background-position: right center;
    --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e")
}

.form-switch.form-check-reverse {
    padding-right: 2.5em;
    padding-left: 0
}

.form-switch.form-check-reverse .form-check-input {
    margin-right: -2.5em;
    margin-left: 0
}

.form-check-inline {
    display: inline-block;
    margin-right: 1rem
}

.btn-check {
    position: absolute;
    clip: rect(0,0,0,0);
    pointer-events: none
}

.btn-check:disabled+.btn,.btn-check[disabled]+.btn {
    pointer-events: none;
    -webkit-filter: none;
    filter: none;
    opacity: .65
}

[data-bs-theme=dark] .form-switch .form-check-input:not(:checked):not(:focus) {
    --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28255, 255, 255, 0.25%29'/%3e%3c/svg%3e")
}

.form-range {
    width: 100%;
    height: 1.5rem;
    padding: 0;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: transparent
}

.form-range:focus {
    outline: 0
}

.form-range:focus::-webkit-slider-thumb {
    -webkit-box-shadow: 0 0 0 1px #fff,0 0 0 .25rem rgba(13,110,253,.25);
    box-shadow: 0 0 0 1px #fff,0 0 0 .25rem rgba(13,110,253,.25)
}

.form-range:focus::-moz-range-thumb {
    box-shadow: 0 0 0 1px #fff,0 0 0 .25rem rgba(13,110,253,.25)
}

.form-range::-moz-focus-outer {
    border: 0
}

.form-range::-webkit-slider-thumb {
    width: 1rem;
    height: 1rem;
    margin-top: -.25rem;
    -webkit-appearance: none;
    appearance: none;
    background-color: #0d6efd;
    border: 0;
    border-radius: 1rem;
    -webkit-transition: background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .form-range::-webkit-slider-thumb {
        -webkit-transition: none;
        transition: none
    }
}

.form-range::-webkit-slider-thumb:active {
    background-color: #b6d4fe
}

.form-range::-webkit-slider-runnable-track {
    width: 100%;
    height: .5rem;
    color: transparent;
    cursor: pointer;
    background-color: var(--bs-secondary-bg);
    border-color: transparent;
    border-radius: 1rem
}

.form-range::-moz-range-thumb {
    width: 1rem;
    height: 1rem;
    -moz-appearance: none;
    appearance: none;
    background-color: #0d6efd;
    border: 0;
    border-radius: 1rem;
    -moz-transition: background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .form-range::-moz-range-thumb {
        -moz-transition: none;
        transition: none
    }
}

.form-range::-moz-range-thumb:active {
    background-color: #b6d4fe
}

.form-range::-moz-range-track {
    width: 100%;
    height: .5rem;
    color: transparent;
    cursor: pointer;
    background-color: var(--bs-secondary-bg);
    border-color: transparent;
    border-radius: 1rem
}

.form-range:disabled {
    pointer-events: none
}

.form-range:disabled::-webkit-slider-thumb {
    background-color: var(--bs-secondary-color)
}

.form-range:disabled::-moz-range-thumb {
    background-color: var(--bs-secondary-color)
}

.form-floating {
    position: relative
}

.form-floating>.form-control,.form-floating>.form-control-plaintext,.form-floating>.form-select {
    height: calc(3.5rem + calc(var(--bs-border-width)*2));
    min-height: calc(3.5rem + calc(var(--bs-border-width)*2));
    line-height: 1.25
}

.form-floating>label {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 2;
    height: 100%;
    padding: 1rem .75rem;
    overflow: hidden;
    text-align: start;
    text-overflow: ellipsis;
    white-space: nowrap;
    pointer-events: none;
    border: var(--bs-border-width) solid transparent;
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;
    -webkit-transition: opacity .1s ease-in-out,-webkit-transform .1s ease-in-out;
    transition: opacity .1s ease-in-out,transform .1s ease-in-out;
    transition: opacity .1s ease-in-out,transform .1s ease-in-out,-webkit-transform .1s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .form-floating>label {
        -webkit-transition: none;
        transition: none
    }
}

.form-floating>.form-control,.form-floating>.form-control-plaintext {
    padding: 1rem .75rem
}

.form-floating>.form-control-plaintext::-webkit-input-placeholder,.form-floating>.form-control::-webkit-input-placeholder {
    color: transparent
}

.form-floating>.form-control-plaintext::-moz-placeholder,.form-floating>.form-control::-moz-placeholder {
    color: transparent
}

.form-floating>.form-control-plaintext:-ms-input-placeholder,.form-floating>.form-control:-ms-input-placeholder {
    color: transparent
}

.form-floating>.form-control-plaintext::-ms-input-placeholder,.form-floating>.form-control::-ms-input-placeholder {
    color: transparent
}

.form-floating>.form-control-plaintext::placeholder,.form-floating>.form-control::placeholder {
    color: transparent
}

.form-floating>.form-control-plaintext:not(:-moz-placeholder-shown),.form-floating>.form-control-plaintext:not(:-ms-input-placeholder),.form-floating>.form-control:not(:-moz-placeholder-shown),.form-floating>.form-control:not(:-ms-input-placeholder) {
    padding-top: 1.625rem;
    padding-bottom: .625rem
}

.form-floating>.form-control-plaintext:focus,.form-floating>.form-control-plaintext:not(:placeholder-shown),.form-floating>.form-control:focus,.form-floating>.form-control:not(:placeholder-shown) {
    padding-top: 1.625rem;
    padding-bottom: .625rem
}

.form-floating>.form-control-plaintext:-webkit-autofill,.form-floating>.form-control:-webkit-autofill {
    padding-top: 1.625rem;
    padding-bottom: .625rem
}

.form-floating>.form-select {
    padding-top: 1.625rem;
    padding-bottom: .625rem
}

.form-floating>.form-control:not(:-moz-placeholder-shown)~label,.form-floating>.form-control:not(:-ms-input-placeholder)~label {
    color: rgba(var(--bs-body-color-rgb),.65);
    transform: scale(.85) translateY(-.5rem) translateX(.15rem)
}

.form-floating>.form-control-plaintext~label,.form-floating>.form-control:focus~label,.form-floating>.form-control:not(:placeholder-shown)~label,.form-floating>.form-select~label {
    color: rgba(var(--bs-body-color-rgb),.65);
    -webkit-transform: scale(.85) translateY(-.5rem) translateX(.15rem);
    transform: scale(.85) translateY(-.5rem) translateX(.15rem)
}

.form-floating>.form-control:not(:-moz-placeholder-shown)~label::after,.form-floating>.form-control:not(:-ms-input-placeholder)~label::after {
    position: absolute;
    inset: 1rem .375rem;
    z-index: -1;
    height: 1.5em;
    content: "";
    background-color: var(--bs-body-bg);
    border-radius: var(--bs-border-radius)
}

.form-floating>.form-control-plaintext~label::after,.form-floating>.form-control:focus~label::after,.form-floating>.form-control:not(:placeholder-shown)~label::after,.form-floating>.form-select~label::after {
    position: absolute;
    inset: 1rem .375rem;
    z-index: -1;
    height: 1.5em;
    content: "";
    background-color: var(--bs-body-bg);
    border-radius: var(--bs-border-radius)
}

.form-floating>.form-control:-webkit-autofill~label {
    color: rgba(var(--bs-body-color-rgb),.65);
    -webkit-transform: scale(.85) translateY(-.5rem) translateX(.15rem);
    transform: scale(.85) translateY(-.5rem) translateX(.15rem)
}

.form-floating>.form-control-plaintext~label {
    border-width: var(--bs-border-width) 0
}

.form-floating>.form-control:disabled~label,.form-floating>:disabled~label {
    color: #6c757d
}

.form-floating>.form-control:disabled~label::after,.form-floating>:disabled~label::after {
    background-color: var(--bs-secondary-bg)
}

.input-group {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    width: 100%
}

.input-group>.form-control,.input-group>.form-floating,.input-group>.form-select {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    width: 1%;
    min-width: 0
}

.input-group>.form-control:focus,.input-group>.form-floating:focus-within,.input-group>.form-select:focus {
    z-index: 5
}

.input-group .btn {
    position: relative;
    z-index: 2
}

.input-group .btn:focus {
    z-index: 5
}

.input-group-text {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: var(--bs-body-color);
    text-align: center;
    white-space: nowrap;
    background-color: var(--bs-tertiary-bg);
    border: var(--bs-border-width) solid var(--bs-border-color);
    border-radius: var(--bs-border-radius)
}

.input-group-lg>.btn,.input-group-lg>.form-control,.input-group-lg>.form-select,.input-group-lg>.input-group-text {
    padding: .5rem 1rem;
    font-size: 1.25rem;
    border-radius: var(--bs-border-radius-lg)
}

.input-group-sm>.btn,.input-group-sm>.form-control,.input-group-sm>.form-select,.input-group-sm>.input-group-text {
    padding: .25rem .5rem;
    font-size: .875rem;
    border-radius: var(--bs-border-radius-sm)
}

.input-group-lg>.form-select,.input-group-sm>.form-select {
    padding-right: 3rem
}

.input-group.has-validation>.dropdown-toggle:nth-last-child(n+4),.input-group.has-validation>.form-floating:nth-last-child(n+3)>.form-control,.input-group.has-validation>.form-floating:nth-last-child(n+3)>.form-select,.input-group.has-validation>:nth-last-child(n+3):not(.dropdown-toggle):not(.dropdown-menu):not(.form-floating),.input-group:not(.has-validation)>.dropdown-toggle:nth-last-child(n+3),.input-group:not(.has-validation)>.form-floating:not(:last-child)>.form-control,.input-group:not(.has-validation)>.form-floating:not(:last-child)>.form-select,.input-group:not(.has-validation)>:not(:last-child):not(.dropdown-toggle):not(.dropdown-menu):not(.form-floating) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0
}

.input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    margin-left: calc(var(--bs-border-width)*-1);
    border-top-left-radius: 0;
    border-bottom-left-radius: 0
}

.input-group>.form-floating:not(:first-child)>.form-control,.input-group>.form-floating:not(:first-child)>.form-select {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0
}

.valid-feedback {
    display: none;
    width: 100%;
    margin-top: .25rem;
    font-size: .875em;
    color: var(--bs-form-valid-color)
}

.valid-tooltip {
    position: absolute;
    top: 100%;
    z-index: 5;
    display: none;
    max-width: 100%;
    padding: .25rem .5rem;
    margin-top: .1rem;
    font-size: .875rem;
    color: #fff;
    background-color: var(--bs-success);
    border-radius: var(--bs-border-radius)
}

.is-valid~.valid-feedback,.is-valid~.valid-tooltip,.was-validated :valid~.valid-feedback,.was-validated :valid~.valid-tooltip {
    display: block
}

.form-control.is-valid,.was-validated .form-control:valid {
    border-color: var(--bs-form-valid-border-color);
    padding-right: calc(1.5em + .75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem)
}

.form-control.is-valid:focus,.was-validated .form-control:valid:focus {
    border-color: var(--bs-form-valid-border-color);
    -webkit-box-shadow: 0 0 0 .25rem rgba(var(--bs-success-rgb),.25);
    box-shadow: 0 0 0 .25rem rgba(var(--bs-success-rgb),.25)
}

.was-validated textarea.form-control:valid,textarea.form-control.is-valid {
    padding-right: calc(1.5em + .75rem);
    background-position: top calc(.375em + .1875rem) right calc(.375em + .1875rem)
}

.form-select.is-valid,.was-validated .form-select:valid {
    border-color: var(--bs-form-valid-border-color)
}

.form-select.is-valid:not([multiple]):not([size]),.form-select.is-valid:not([multiple])[size="1"],.was-validated .form-select:valid:not([multiple]):not([size]),.was-validated .form-select:valid:not([multiple])[size="1"] {
    --bs-form-select-bg-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    padding-right: 4.125rem;
    background-position: right .75rem center,center right 2.25rem;
    background-size: 16px 12px,calc(.75em + .375rem) calc(.75em + .375rem)
}

.form-select.is-valid:focus,.was-validated .form-select:valid:focus {
    border-color: var(--bs-form-valid-border-color);
    -webkit-box-shadow: 0 0 0 .25rem rgba(var(--bs-success-rgb),.25);
    box-shadow: 0 0 0 .25rem rgba(var(--bs-success-rgb),.25)
}

.form-control-color.is-valid,.was-validated .form-control-color:valid {
    width: calc(3rem + calc(1.5em + .75rem))
}

.form-check-input.is-valid,.was-validated .form-check-input:valid {
    border-color: var(--bs-form-valid-border-color)
}

.form-check-input.is-valid:checked,.was-validated .form-check-input:valid:checked {
    background-color: var(--bs-form-valid-color)
}

.form-check-input.is-valid:focus,.was-validated .form-check-input:valid:focus {
    -webkit-box-shadow: 0 0 0 .25rem rgba(var(--bs-success-rgb),.25);
    box-shadow: 0 0 0 .25rem rgba(var(--bs-success-rgb),.25)
}

.form-check-input.is-valid~.form-check-label,.was-validated .form-check-input:valid~.form-check-label {
    color: var(--bs-form-valid-color)
}

.form-check-inline .form-check-input~.valid-feedback {
    margin-left: .5em
}

.input-group>.form-control:not(:focus).is-valid,.input-group>.form-floating:not(:focus-within).is-valid,.input-group>.form-select:not(:focus).is-valid,.was-validated .input-group>.form-control:not(:focus):valid,.was-validated .input-group>.form-floating:not(:focus-within):valid,.was-validated .input-group>.form-select:not(:focus):valid {
    z-index: 3
}

.invalid-feedback {
    display: none;
    width: 100%;
    margin-top: .25rem;
    font-size: .875em;
    color: var(--bs-form-invalid-color)
}

.invalid-tooltip {
    position: absolute;
    top: 100%;
    z-index: 5;
    display: none;
    max-width: 100%;
    padding: .25rem .5rem;
    margin-top: .1rem;
    font-size: .875rem;
    color: #fff;
    background-color: var(--bs-danger);
    border-radius: var(--bs-border-radius)
}

.is-invalid~.invalid-feedback,.is-invalid~.invalid-tooltip,.was-validated :invalid~.invalid-feedback,.was-validated :invalid~.invalid-tooltip {
    display: block
}

.form-control.is-invalid,.was-validated .form-control:invalid {
    border-color: var(--bs-form-invalid-border-color);
    padding-right: calc(1.5em + .75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem)
}

.form-control.is-invalid:focus,.was-validated .form-control:invalid:focus {
    border-color: var(--bs-form-invalid-border-color);
    -webkit-box-shadow: 0 0 0 .25rem rgba(var(--bs-danger-rgb),.25);
    box-shadow: 0 0 0 .25rem rgba(var(--bs-danger-rgb),.25)
}

.was-validated textarea.form-control:invalid,textarea.form-control.is-invalid {
    padding-right: calc(1.5em + .75rem);
    background-position: top calc(.375em + .1875rem) right calc(.375em + .1875rem)
}

.form-select.is-invalid,.was-validated .form-select:invalid {
    border-color: var(--bs-form-invalid-border-color)
}

.form-select.is-invalid:not([multiple]):not([size]),.form-select.is-invalid:not([multiple])[size="1"],.was-validated .form-select:invalid:not([multiple]):not([size]),.was-validated .form-select:invalid:not([multiple])[size="1"] {
    --bs-form-select-bg-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    padding-right: 4.125rem;
    background-position: right .75rem center,center right 2.25rem;
    background-size: 16px 12px,calc(.75em + .375rem) calc(.75em + .375rem)
}

.form-select.is-invalid:focus,.was-validated .form-select:invalid:focus {
    border-color: var(--bs-form-invalid-border-color);
    -webkit-box-shadow: 0 0 0 .25rem rgba(var(--bs-danger-rgb),.25);
    box-shadow: 0 0 0 .25rem rgba(var(--bs-danger-rgb),.25)
}

.form-control-color.is-invalid,.was-validated .form-control-color:invalid {
    width: calc(3rem + calc(1.5em + .75rem))
}

.form-check-input.is-invalid,.was-validated .form-check-input:invalid {
    border-color: var(--bs-form-invalid-border-color)
}

.form-check-input.is-invalid:checked,.was-validated .form-check-input:invalid:checked {
    background-color: var(--bs-form-invalid-color)
}

.form-check-input.is-invalid:focus,.was-validated .form-check-input:invalid:focus {
    -webkit-box-shadow: 0 0 0 .25rem rgba(var(--bs-danger-rgb),.25);
    box-shadow: 0 0 0 .25rem rgba(var(--bs-danger-rgb),.25)
}

.form-check-input.is-invalid~.form-check-label,.was-validated .form-check-input:invalid~.form-check-label {
    color: var(--bs-form-invalid-color)
}

.form-check-inline .form-check-input~.invalid-feedback {
    margin-left: .5em
}

.input-group>.form-control:not(:focus).is-invalid,.input-group>.form-floating:not(:focus-within).is-invalid,.input-group>.form-select:not(:focus).is-invalid,.was-validated .input-group>.form-control:not(:focus):invalid,.was-validated .input-group>.form-floating:not(:focus-within):invalid,.was-validated .input-group>.form-select:not(:focus):invalid {
    z-index: 4
}

.btn,.btn-check+.btn:hover {
    color: var(--bs-btn-color);
    background-color: var(--bs-btn-bg)
}

.btn {
    --bs-btn-padding-x: 0.75rem;
    --bs-btn-padding-y: 0.375rem;
    --bs-btn-font-family: ;
    --bs-btn-font-size: 1rem;
    --bs-btn-font-weight: 400;
    --bs-btn-line-height: 1.5;
    --bs-btn-color: var(--bs-body-color);
    --bs-btn-bg: transparent;
    --bs-btn-border-width: var(--bs-border-width);
    --bs-btn-border-color: transparent;
    --bs-btn-border-radius: var(--bs-border-radius);
    --bs-btn-hover-border-color: transparent;
    --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
    --bs-btn-disabled-opacity: 0.65;
    --bs-btn-focus-box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
    display: inline-block;
    padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
    font-family: var(--bs-btn-font-family);
    font-size: var(--bs-btn-font-size);
    font-weight: var(--bs-btn-font-weight);
    line-height: var(--bs-btn-line-height);
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
    border-radius: var(--bs-btn-border-radius);
    -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .btn {
        -webkit-transition: none;
        transition: none
    }
}

.btn:hover {
    color: var(--bs-btn-hover-color);
    background-color: var(--bs-btn-hover-bg);
    border-color: var(--bs-btn-hover-border-color)
}

.btn-check+.btn:hover {
    border-color: var(--bs-btn-border-color)
}

.btn:focus-visible {
    color: var(--bs-btn-hover-color);
    background-color: var(--bs-btn-hover-bg)
}

.btn-check:focus-visible+.btn,.btn:focus-visible {
    border-color: var(--bs-btn-hover-border-color);
    outline: 0;
    -webkit-box-shadow: var(--bs-btn-focus-box-shadow);
    box-shadow: var(--bs-btn-focus-box-shadow)
}

.btn-check:checked+.btn,.btn.active,.btn.show,.btn:first-child:active,:not(.btn-check)+.btn:active {
    color: var(--bs-btn-active-color);
    background-color: var(--bs-btn-active-bg);
    border-color: var(--bs-btn-active-border-color)
}

.btn-check:checked+.btn:focus-visible,.btn.active:focus-visible,.btn.show:focus-visible,.btn:first-child:active:focus-visible,:not(.btn-check)+.btn:active:focus-visible {
    -webkit-box-shadow: var(--bs-btn-focus-box-shadow);
    box-shadow: var(--bs-btn-focus-box-shadow)
}

.btn-check:checked:focus-visible+.btn {
    -webkit-box-shadow: var(--bs-btn-focus-box-shadow);
    box-shadow: var(--bs-btn-focus-box-shadow)
}

.btn.disabled,.btn:disabled,fieldset:disabled .btn {
    color: var(--bs-btn-disabled-color);
    pointer-events: none;
    background-color: var(--bs-btn-disabled-bg);
    border-color: var(--bs-btn-disabled-border-color);
    opacity: var(--bs-btn-disabled-opacity)
}

.btn-primary,.btn-secondary {
    --bs-btn-color: #fff;
    --bs-btn-hover-color: #fff;
    --bs-btn-active-color: #fff;
    --bs-btn-disabled-color: #fff
}

.btn-primary {
    --bs-btn-bg: #0d6efd;
    --bs-btn-border-color: #0d6efd;
    --bs-btn-hover-bg: #0b5ed7;
    --bs-btn-hover-border-color: #0a58ca;
    --bs-btn-focus-shadow-rgb: 49, 132, 253;
    --bs-btn-active-bg: #0a58ca;
    --bs-btn-active-border-color: #0a53be;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-bg: #0d6efd;
    --bs-btn-disabled-border-color: #0d6efd
}

.btn-secondary {
    --bs-btn-bg: #6c757d;
    --bs-btn-border-color: #6c757d;
    --bs-btn-hover-bg: #5c636a;
    --bs-btn-hover-border-color: #565e64;
    --bs-btn-focus-shadow-rgb: 130, 138, 145;
    --bs-btn-active-bg: #565e64;
    --bs-btn-active-border-color: #51585e;
    --bs-btn-disabled-bg: #6c757d;
    --bs-btn-disabled-border-color: #6c757d
}

.btn-info,.btn-secondary,.btn-success,.btn-warning {
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125)
}

.btn-success {
    --bs-btn-color: #fff;
    --bs-btn-bg: #198754;
    --bs-btn-border-color: #198754;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #157347;
    --bs-btn-hover-border-color: #146c43;
    --bs-btn-focus-shadow-rgb: 60, 153, 110;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #146c43;
    --bs-btn-active-border-color: #13653f;
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #198754;
    --bs-btn-disabled-border-color: #198754
}

.btn-info,.btn-warning {
    --bs-btn-color: #000;
    --bs-btn-hover-color: #000;
    --bs-btn-active-color: #000;
    --bs-btn-disabled-color: #000
}

.btn-info {
    --bs-btn-bg: #0dcaf0;
    --bs-btn-border-color: #0dcaf0;
    --bs-btn-hover-bg: #31d2f2;
    --bs-btn-hover-border-color: #25cff2;
    --bs-btn-focus-shadow-rgb: 11, 172, 204;
    --bs-btn-active-bg: #3dd5f3;
    --bs-btn-active-border-color: #25cff2;
    --bs-btn-disabled-bg: #0dcaf0;
    --bs-btn-disabled-border-color: #0dcaf0
}

.btn-warning {
    --bs-btn-bg: #ffc107;
    --bs-btn-border-color: #ffc107;
    --bs-btn-hover-bg: #ffca2c;
    --bs-btn-hover-border-color: #ffc720;
    --bs-btn-focus-shadow-rgb: 217, 164, 6;
    --bs-btn-active-bg: #ffcd39;
    --bs-btn-active-border-color: #ffc720;
    --bs-btn-disabled-bg: #ffc107;
    --bs-btn-disabled-border-color: #ffc107
}

.btn-danger {
    --bs-btn-color: #fff;
    --bs-btn-bg: #dc3545;
    --bs-btn-border-color: #dc3545;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #bb2d3b;
    --bs-btn-hover-border-color: #b02a37;
    --bs-btn-focus-shadow-rgb: 225, 83, 97;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #b02a37;
    --bs-btn-active-border-color: #a52834;
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #dc3545;
    --bs-btn-disabled-border-color: #dc3545
}

.btn-danger,.btn-dark,.btn-light {
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125)
}

.btn-light {
    --bs-btn-color: #000;
    --bs-btn-bg: #f8f9fa;
    --bs-btn-border-color: #f8f9fa;
    --bs-btn-hover-color: #000;
    --bs-btn-hover-bg: #d3d4d5;
    --bs-btn-hover-border-color: #c6c7c8;
    --bs-btn-focus-shadow-rgb: 211, 212, 213;
    --bs-btn-active-color: #000;
    --bs-btn-active-bg: #c6c7c8;
    --bs-btn-active-border-color: #babbbc;
    --bs-btn-disabled-color: #000;
    --bs-btn-disabled-bg: #f8f9fa;
    --bs-btn-disabled-border-color: #f8f9fa
}

.btn-dark {
    --bs-btn-color: #fff;
    --bs-btn-bg: #212529;
    --bs-btn-border-color: #212529;
    --bs-btn-hover-bg: #424649;
    --bs-btn-hover-border-color: #373b3e;
    --bs-btn-focus-shadow-rgb: 66, 70, 73;
    --bs-btn-active-bg: #4d5154;
    --bs-btn-active-border-color: #373b3e;
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #212529;
    --bs-btn-disabled-border-color: #212529
}

.btn-dark,.btn-outline-primary,.btn-outline-secondary {
    --bs-btn-hover-color: #fff;
    --bs-btn-active-color: #fff
}

.btn-outline-primary {
    --bs-btn-color: #0d6efd;
    --bs-btn-border-color: #0d6efd;
    --bs-btn-hover-bg: #0d6efd;
    --bs-btn-hover-border-color: #0d6efd;
    --bs-btn-focus-shadow-rgb: 13, 110, 253;
    --bs-btn-active-bg: #0d6efd;
    --bs-btn-active-border-color: #0d6efd;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #0d6efd;
    --bs-btn-disabled-bg: transparent;
    --bs-btn-disabled-border-color: #0d6efd;
    --bs-gradient: none
}

.btn-outline-secondary {
    --bs-btn-color: #6c757d;
    --bs-btn-border-color: #6c757d;
    --bs-btn-hover-bg: #6c757d;
    --bs-btn-hover-border-color: #6c757d;
    --bs-btn-focus-shadow-rgb: 108, 117, 125;
    --bs-btn-active-bg: #6c757d;
    --bs-btn-active-border-color: #6c757d;
    --bs-btn-disabled-color: #6c757d;
    --bs-btn-disabled-border-color: #6c757d
}

.btn-outline-info,.btn-outline-secondary,.btn-outline-success,.btn-outline-warning {
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-bg: transparent;
    --bs-gradient: none
}

.btn-outline-success {
    --bs-btn-color: #198754;
    --bs-btn-border-color: #198754;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #198754;
    --bs-btn-hover-border-color: #198754;
    --bs-btn-focus-shadow-rgb: 25, 135, 84;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #198754;
    --bs-btn-active-border-color: #198754;
    --bs-btn-disabled-color: #198754;
    --bs-btn-disabled-border-color: #198754
}

.btn-outline-info,.btn-outline-warning {
    --bs-btn-hover-color: #000;
    --bs-btn-active-color: #000
}

.btn-outline-info {
    --bs-btn-color: #0dcaf0;
    --bs-btn-border-color: #0dcaf0;
    --bs-btn-hover-bg: #0dcaf0;
    --bs-btn-hover-border-color: #0dcaf0;
    --bs-btn-focus-shadow-rgb: 13, 202, 240;
    --bs-btn-active-bg: #0dcaf0;
    --bs-btn-active-border-color: #0dcaf0;
    --bs-btn-disabled-color: #0dcaf0;
    --bs-btn-disabled-border-color: #0dcaf0
}

.btn-outline-warning {
    --bs-btn-color: #ffc107;
    --bs-btn-border-color: #ffc107;
    --bs-btn-hover-bg: #ffc107;
    --bs-btn-hover-border-color: #ffc107;
    --bs-btn-focus-shadow-rgb: 255, 193, 7;
    --bs-btn-active-bg: #ffc107;
    --bs-btn-active-border-color: #ffc107;
    --bs-btn-disabled-color: #ffc107;
    --bs-btn-disabled-border-color: #ffc107
}

.btn-outline-danger {
    --bs-btn-color: #dc3545;
    --bs-btn-border-color: #dc3545;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #dc3545;
    --bs-btn-hover-border-color: #dc3545;
    --bs-btn-focus-shadow-rgb: 220, 53, 69;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #dc3545;
    --bs-btn-active-border-color: #dc3545;
    --bs-btn-disabled-color: #dc3545;
    --bs-btn-disabled-border-color: #dc3545
}

.btn-outline-danger,.btn-outline-dark,.btn-outline-light {
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-bg: transparent;
    --bs-gradient: none
}

.btn-outline-light {
    --bs-btn-color: #f8f9fa;
    --bs-btn-border-color: #f8f9fa;
    --bs-btn-hover-color: #000;
    --bs-btn-hover-bg: #f8f9fa;
    --bs-btn-hover-border-color: #f8f9fa;
    --bs-btn-focus-shadow-rgb: 248, 249, 250;
    --bs-btn-active-color: #000;
    --bs-btn-active-bg: #f8f9fa;
    --bs-btn-active-border-color: #f8f9fa;
    --bs-btn-disabled-color: #f8f9fa;
    --bs-btn-disabled-border-color: #f8f9fa
}

.btn-outline-dark {
    --bs-btn-color: #212529;
    --bs-btn-border-color: #212529;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #212529;
    --bs-btn-hover-border-color: #212529;
    --bs-btn-focus-shadow-rgb: 33, 37, 41;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #212529;
    --bs-btn-active-border-color: #212529;
    --bs-btn-disabled-color: #212529;
    --bs-btn-disabled-border-color: #212529
}

.btn-link {
    --bs-btn-font-weight: 400;
    --bs-btn-color: var(--bs-link-color);
    --bs-btn-bg: transparent;
    --bs-btn-border-color: transparent;
    --bs-btn-hover-color: var(--bs-link-hover-color);
    --bs-btn-hover-border-color: transparent;
    --bs-btn-active-color: var(--bs-link-hover-color);
    --bs-btn-active-border-color: transparent;
    --bs-btn-disabled-color: #6c757d;
    --bs-btn-disabled-border-color: transparent;
    --bs-btn-box-shadow: 0 0 0 #000;
    --bs-btn-focus-shadow-rgb: 49, 132, 253;
    text-decoration: underline
}

.btn-link:focus-visible {
    color: var(--bs-btn-color)
}

.btn-link:hover {
    color: var(--bs-btn-hover-color)
}

.btn-group-lg>.btn,.btn-lg {
    --bs-btn-padding-y: 0.5rem;
    --bs-btn-padding-x: 1rem;
    --bs-btn-font-size: 1.25rem;
    --bs-btn-border-radius: var(--bs-border-radius-lg)
}

.btn-group-sm>.btn,.btn-sm {
    --bs-btn-padding-y: 0.25rem;
    --bs-btn-padding-x: 0.5rem;
    --bs-btn-font-size: 0.875rem;
    --bs-btn-border-radius: var(--bs-border-radius-sm)
}

.fade {
    -webkit-transition: opacity .15s linear;
    transition: opacity .15s linear
}

@media (prefers-reduced-motion:reduce) {
    .fade {
        -webkit-transition: none;
        transition: none
    }
}

.fade:not(.show) {
    opacity: 0
}

.collapse:not(.show) {
    display: none
}

.collapsing {
    height: 0;
    overflow: hidden;
    -webkit-transition: height .35s ease;
    transition: height .35s ease
}

@media (prefers-reduced-motion:reduce) {
    .collapsing {
        -webkit-transition: none;
        transition: none
    }
}

.collapsing.collapse-horizontal {
    width: 0;
    height: auto;
    -webkit-transition: width .35s ease;
    transition: width .35s ease
}

@media (prefers-reduced-motion:reduce) {
    .collapsing.collapse-horizontal {
        -webkit-transition: none;
        transition: none
    }
}

.dropdown,.dropdown-center,.dropend,.dropstart,.dropup,.dropup-center {
    position: relative
}

.dropdown-toggle {
    white-space: nowrap
}

.dropdown-toggle::after {
    display: inline-block;
    margin-left: .255em;
    vertical-align: .255em;
    content: "";
    border-top: .3em solid;
    border-right: .3em solid transparent;
    border-bottom: 0;
    border-left: .3em solid transparent
}

.dropdown-toggle:empty::after,.dropend .dropdown-toggle:empty::after,.dropstart .dropdown-toggle:empty::after,.dropup .dropdown-toggle:empty::after {
    margin-left: 0
}

.dropdown-menu {
    --bs-dropdown-zindex: 1000;
    --bs-dropdown-min-width: 10rem;
    --bs-dropdown-padding-x: 0;
    --bs-dropdown-padding-y: 0.5rem;
    --bs-dropdown-spacer: 0.125rem;
    --bs-dropdown-font-size: 1rem;
    --bs-dropdown-color: var(--bs-body-color);
    --bs-dropdown-bg: var(--bs-body-bg);
    --bs-dropdown-border-color: var(--bs-border-color-translucent);
    --bs-dropdown-border-radius: var(--bs-border-radius);
    --bs-dropdown-border-width: var(--bs-border-width);
    --bs-dropdown-inner-border-radius: calc(var(--bs-border-radius) - var(--bs-border-width));
    --bs-dropdown-divider-bg: var(--bs-border-color-translucent);
    --bs-dropdown-divider-margin-y: 0.5rem;
    --bs-dropdown-box-shadow: var(--bs-box-shadow);
    --bs-dropdown-link-color: var(--bs-body-color);
    --bs-dropdown-link-hover-color: var(--bs-body-color);
    --bs-dropdown-link-hover-bg: var(--bs-tertiary-bg);
    --bs-dropdown-link-active-color: #fff;
    --bs-dropdown-link-active-bg: #0d6efd;
    --bs-dropdown-link-disabled-color: var(--bs-tertiary-color);
    --bs-dropdown-item-padding-x: 1rem;
    --bs-dropdown-item-padding-y: 0.25rem;
    --bs-dropdown-header-color: #6c757d;
    --bs-dropdown-header-padding-x: 1rem;
    --bs-dropdown-header-padding-y: 0.5rem;
    position: absolute;
    z-index: var(--bs-dropdown-zindex);
    display: none;
    min-width: var(--bs-dropdown-min-width);
    padding: var(--bs-dropdown-padding-y) var(--bs-dropdown-padding-x);
    margin: 0;
    font-size: var(--bs-dropdown-font-size);
    color: var(--bs-dropdown-color);
    text-align: left;
    list-style: none;
    background-color: var(--bs-dropdown-bg);
    background-clip: padding-box;
    border: var(--bs-dropdown-border-width) solid var(--bs-dropdown-border-color);
    border-radius: var(--bs-dropdown-border-radius)
}

.dropdown-menu[data-bs-popper] {
    top: 100%;
    left: 0;
    margin-top: var(--bs-dropdown-spacer)
}

.dropdown-menu-start {
    --bs-position: start
}

.dropdown-menu-start[data-bs-popper] {
    right: auto;
    left: 0
}

.dropdown-menu-end {
    --bs-position: end
}

.dropdown-menu-end[data-bs-popper] {
    right: 0;
    left: auto
}

@media (min-width: 576px) {
    .dropdown-menu-sm-start {
        --bs-position:start
    }

    .dropdown-menu-sm-start[data-bs-popper] {
        right: auto;
        left: 0
    }

    .dropdown-menu-sm-end {
        --bs-position: end
    }

    .dropdown-menu-sm-end[data-bs-popper] {
        right: 0;
        left: auto
    }
}

@media (min-width: 768px) {
    .dropdown-menu-md-start {
        --bs-position:start
    }

    .dropdown-menu-md-start[data-bs-popper] {
        right: auto;
        left: 0
    }

    .dropdown-menu-md-end {
        --bs-position: end
    }

    .dropdown-menu-md-end[data-bs-popper] {
        right: 0;
        left: auto
    }
}

@media (min-width: 992px) {
    .dropdown-menu-lg-start {
        --bs-position:start
    }

    .dropdown-menu-lg-start[data-bs-popper] {
        right: auto;
        left: 0
    }

    .dropdown-menu-lg-end {
        --bs-position: end
    }

    .dropdown-menu-lg-end[data-bs-popper] {
        right: 0;
        left: auto
    }
}

@media (min-width: 1200px) {
    .dropdown-menu-xl-start {
        --bs-position:start
    }

    .dropdown-menu-xl-start[data-bs-popper] {
        right: auto;
        left: 0
    }

    .dropdown-menu-xl-end {
        --bs-position: end
    }

    .dropdown-menu-xl-end[data-bs-popper] {
        right: 0;
        left: auto
    }
}

@media (min-width: 1400px) {
    .dropdown-menu-xxl-start {
        --bs-position:start
    }

    .dropdown-menu-xxl-start[data-bs-popper] {
        right: auto;
        left: 0
    }

    .dropdown-menu-xxl-end {
        --bs-position: end
    }

    .dropdown-menu-xxl-end[data-bs-popper] {
        right: 0;
        left: auto
    }
}

.dropup .dropdown-menu[data-bs-popper] {
    top: auto;
    bottom: 100%;
    margin-top: 0;
    margin-bottom: var(--bs-dropdown-spacer)
}

.dropend .dropdown-toggle::after,.dropup .dropdown-toggle::after {
    display: inline-block;
    margin-left: .255em;
    vertical-align: .255em;
    content: "";
    border-top: 0;
    border-right: .3em solid transparent;
    border-bottom: .3em solid;
    border-left: .3em solid transparent
}

.dropend .dropdown-menu[data-bs-popper] {
    top: 0;
    right: auto;
    left: 100%;
    margin-top: 0;
    margin-left: var(--bs-dropdown-spacer)
}

.dropend .dropdown-toggle::after {
    border-top: .3em solid transparent;
    border-right: 0;
    border-bottom: .3em solid transparent;
    border-left: .3em solid;
    vertical-align: 0
}

.dropstart .dropdown-menu[data-bs-popper] {
    top: 0;
    right: 100%;
    left: auto;
    margin-top: 0;
    margin-right: var(--bs-dropdown-spacer)
}

.dropstart .dropdown-toggle::after {
    margin-left: .255em;
    vertical-align: .255em;
    content: "";
    display: none
}

.dropstart .dropdown-toggle::before {
    display: inline-block;
    margin-right: .255em;
    content: "";
    border-top: .3em solid transparent;
    border-right: .3em solid;
    border-bottom: .3em solid transparent;
    vertical-align: 0
}

.dropdown-divider {
    height: 0;
    margin: var(--bs-dropdown-divider-margin-y) 0;
    overflow: hidden;
    border-top: 1px solid var(--bs-dropdown-divider-bg);
    opacity: 1
}

.dropdown-item {
    display: block;
    width: 100%;
    padding: var(--bs-dropdown-item-padding-y) var(--bs-dropdown-item-padding-x);
    clear: both;
    font-weight: 400;
    color: var(--bs-dropdown-link-color);
    text-align: inherit;
    text-decoration: none;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    border-radius: var(--bs-dropdown-item-border-radius, 0)
}

.dropdown-item:focus,.dropdown-item:hover {
    color: var(--bs-dropdown-link-hover-color);
    background-color: var(--bs-dropdown-link-hover-bg)
}

.dropdown-item.active,.dropdown-item:active {
    color: var(--bs-dropdown-link-active-color);
    text-decoration: none;
    background-color: var(--bs-dropdown-link-active-bg)
}

.dropdown-item.disabled,.dropdown-item:disabled {
    color: var(--bs-dropdown-link-disabled-color);
    pointer-events: none;
    background-color: transparent
}

.dropdown-menu.show {
    display: block
}

.dropdown-header {
    display: block;
    padding: var(--bs-dropdown-header-padding-y) var(--bs-dropdown-header-padding-x);
    margin-bottom: 0;
    font-size: .875rem;
    color: var(--bs-dropdown-header-color);
    white-space: nowrap
}

.dropdown-item-text {
    display: block;
    padding: var(--bs-dropdown-item-padding-y) var(--bs-dropdown-item-padding-x);
    color: var(--bs-dropdown-link-color)
}

.dropdown-menu-dark {
    --bs-dropdown-color: #dee2e6;
    --bs-dropdown-bg: #343a40;
    --bs-dropdown-border-color: var(--bs-border-color-translucent);
    --bs-dropdown-box-shadow: ;
    --bs-dropdown-link-color: #dee2e6;
    --bs-dropdown-link-hover-color: #fff;
    --bs-dropdown-divider-bg: var(--bs-border-color-translucent);
    --bs-dropdown-link-hover-bg: rgba(255, 255, 255, 0.15);
    --bs-dropdown-link-active-color: #fff;
    --bs-dropdown-link-active-bg: #0d6efd;
    --bs-dropdown-link-disabled-color: #adb5bd;
    --bs-dropdown-header-color: #adb5bd
}

.btn-group,.btn-group-vertical {
    position: relative;
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    vertical-align: middle
}

.btn-group-vertical>.btn,.btn-group>.btn {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto
}

.btn-group-vertical>.btn-check:checked+.btn,.btn-group-vertical>.btn-check:focus+.btn,.btn-group-vertical>.btn.active,.btn-group-vertical>.btn:active,.btn-group-vertical>.btn:focus,.btn-group-vertical>.btn:hover,.btn-group>.btn-check:checked+.btn,.btn-group>.btn-check:focus+.btn,.btn-group>.btn.active,.btn-group>.btn:active,.btn-group>.btn:focus,.btn-group>.btn:hover {
    z-index: 1
}

.btn-toolbar {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start
}

.btn-toolbar .input-group {
    width: auto
}

.btn-group {
    border-radius: var(--bs-border-radius)
}

.btn-group>.btn-group:not(:first-child),.btn-group>:not(.btn-check:first-child)+.btn {
    margin-left: calc(var(--bs-border-width)*-1)
}

.btn-group>.btn-group:not(:last-child)>.btn,.btn-group>.btn.dropdown-toggle-split:first-child,.btn-group>.btn:not(:last-child):not(.dropdown-toggle) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0
}

.btn-group>.btn-group:not(:first-child)>.btn,.btn-group>.btn:nth-child(n+3),.btn-group>:not(.btn-check)+.btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0
}

.dropdown-toggle-split {
    padding-right: .5625rem;
    padding-left: .5625rem
}

.dropdown-toggle-split::after,.dropend .dropdown-toggle-split::after,.dropup .dropdown-toggle-split::after {
    margin-left: 0
}

.dropstart .dropdown-toggle-split::before {
    margin-right: 0
}

.btn-group-sm>.btn+.dropdown-toggle-split,.btn-sm+.dropdown-toggle-split {
    padding-right: .375rem;
    padding-left: .375rem
}

.btn-group-lg>.btn+.dropdown-toggle-split,.btn-lg+.dropdown-toggle-split {
    padding-right: .75rem;
    padding-left: .75rem
}

.btn-group-vertical {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center
}

.btn-group-vertical>.btn,.btn-group-vertical>.btn-group {
    width: 100%
}

.btn-group-vertical>.btn-group:not(:first-child),.btn-group-vertical>.btn:not(:first-child) {
    margin-top: calc(var(--bs-border-width)*-1)
}

.btn-group-vertical>.btn-group:not(:last-child)>.btn,.btn-group-vertical>.btn:not(:last-child):not(.dropdown-toggle) {
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0
}

.btn-group-vertical>.btn-group:not(:first-child)>.btn,.btn-group-vertical>.btn~.btn {
    border-top-left-radius: 0;
    border-top-right-radius: 0
}

.nav {
    --bs-nav-link-padding-x: 1rem;
    --bs-nav-link-padding-y: 0.5rem;
    --bs-nav-link-font-weight: ;
    --bs-nav-link-color: var(--bs-link-color);
    --bs-nav-link-hover-color: var(--bs-link-hover-color);
    --bs-nav-link-disabled-color: var(--bs-secondary-color);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none
}

.nav-link {
    display: block;
    padding: var(--bs-nav-link-padding-y) var(--bs-nav-link-padding-x);
    font-size: var(--bs-nav-link-font-size);
    font-weight: var(--bs-nav-link-font-weight);
    color: var(--bs-nav-link-color);
    text-decoration: none;
    background: 0 0;
    border: 0;
    -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .nav-link {
        -webkit-transition: none;
        transition: none
    }
}

.nav-link:focus,.nav-link:hover {
    color: var(--bs-nav-link-hover-color)
}

.nav-link:focus-visible {
    outline: 0;
    -webkit-box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.25)
}

.nav-link.disabled,.nav-link:disabled {
    color: var(--bs-nav-link-disabled-color);
    pointer-events: none;
    cursor: default
}

.nav-tabs {
    --bs-nav-tabs-border-width: var(--bs-border-width);
    --bs-nav-tabs-border-color: var(--bs-border-color);
    --bs-nav-tabs-border-radius: var(--bs-border-radius);
    --bs-nav-tabs-link-hover-border-color: var(--bs-secondary-bg) var(--bs-secondary-bg) var(--bs-border-color);
    --bs-nav-tabs-link-active-color: var(--bs-emphasis-color);
    --bs-nav-tabs-link-active-bg: var(--bs-body-bg);
    --bs-nav-tabs-link-active-border-color: var(--bs-border-color) var(--bs-border-color) var(--bs-body-bg);
    border-bottom: var(--bs-nav-tabs-border-width) solid var(--bs-nav-tabs-border-color)
}

.nav-tabs .nav-link {
    margin-bottom: calc(-1*var(--bs-nav-tabs-border-width));
    border: var(--bs-nav-tabs-border-width) solid transparent;
    border-top-left-radius: var(--bs-nav-tabs-border-radius);
    border-top-right-radius: var(--bs-nav-tabs-border-radius)
}

.nav-tabs .nav-link:focus,.nav-tabs .nav-link:hover {
    isolation: isolate;
    border-color: var(--bs-nav-tabs-link-hover-border-color)
}

.nav-tabs .nav-item.show .nav-link,.nav-tabs .nav-link.active {
    color: var(--bs-nav-tabs-link-active-color);
    background-color: var(--bs-nav-tabs-link-active-bg);
    border-color: var(--bs-nav-tabs-link-active-border-color)
}

.nav-tabs .dropdown-menu {
    margin-top: calc(-1*var(--bs-nav-tabs-border-width));
    border-top-left-radius: 0;
    border-top-right-radius: 0
}

.nav-pills {
    --bs-nav-pills-border-radius: var(--bs-border-radius);
    --bs-nav-pills-link-active-color: #fff;
    --bs-nav-pills-link-active-bg: #0d6efd
}

.nav-pills .nav-link {
    border-radius: var(--bs-nav-pills-border-radius)
}

.nav-pills .nav-link.active,.nav-pills .show>.nav-link {
    color: var(--bs-nav-pills-link-active-color);
    background-color: var(--bs-nav-pills-link-active-bg)
}

.nav-underline {
    --bs-nav-underline-gap: 1rem;
    --bs-nav-underline-border-width: 0.125rem;
    --bs-nav-underline-link-active-color: var(--bs-emphasis-color);
    gap: var(--bs-nav-underline-gap)
}

.nav-underline .nav-link {
    padding-right: 0;
    padding-left: 0;
    border-bottom: var(--bs-nav-underline-border-width) solid transparent
}

.nav-underline .nav-link:focus,.nav-underline .nav-link:hover {
    border-bottom-color: currentcolor
}

.nav-underline .nav-link.active,.nav-underline .show>.nav-link {
    font-weight: 700;
    color: var(--bs-nav-underline-link-active-color);
    border-bottom-color: currentcolor
}

.nav-fill .nav-item,.nav-fill>.nav-link {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    text-align: center
}

.nav-justified .nav-item,.nav-justified>.nav-link {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    text-align: center
}

.nav-fill .nav-item .nav-link,.nav-justified .nav-item .nav-link {
    width: 100%
}

.tab-content>.tab-pane {
    display: none
}

.tab-content>.active {
    display: block
}

.navbar,.navbar>.container,.navbar>.container-fluid,.navbar>.container-lg,.navbar>.container-md,.navbar>.container-sm,.navbar>.container-xl,.navbar>.container-xxl {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between
}

.navbar {
    --bs-navbar-padding-x: 0;
    --bs-navbar-padding-y: 0.5rem;
    --bs-navbar-color: rgba(var(--bs-emphasis-color-rgb), 0.65);
    --bs-navbar-hover-color: rgba(var(--bs-emphasis-color-rgb), 0.8);
    --bs-navbar-disabled-color: rgba(var(--bs-emphasis-color-rgb), 0.3);
    --bs-navbar-active-color: rgba(var(--bs-emphasis-color-rgb), 1);
    --bs-navbar-brand-padding-y: 0.3125rem;
    --bs-navbar-brand-margin-end: 1rem;
    --bs-navbar-brand-font-size: 1.25rem;
    --bs-navbar-brand-color: rgba(var(--bs-emphasis-color-rgb), 1);
    --bs-navbar-brand-hover-color: rgba(var(--bs-emphasis-color-rgb), 1);
    --bs-navbar-nav-link-padding-x: 0.5rem;
    --bs-navbar-toggler-padding-y: 0.25rem;
    --bs-navbar-toggler-padding-x: 0.75rem;
    --bs-navbar-toggler-font-size: 1.25rem;
    --bs-navbar-toggler-icon-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    --bs-navbar-toggler-border-color: rgba(var(--bs-emphasis-color-rgb), 0.15);
    --bs-navbar-toggler-border-radius: var(--bs-border-radius);
    --bs-navbar-toggler-focus-width: 0.25rem;
    --bs-navbar-toggler-transition: box-shadow 0.15s ease-in-out;
    position: relative;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding: var(--bs-navbar-padding-y) var(--bs-navbar-padding-x)
}

.navbar>.container,.navbar>.container-fluid,.navbar>.container-lg,.navbar>.container-md,.navbar>.container-sm,.navbar>.container-xl,.navbar>.container-xxl {
    -ms-flex-wrap: inherit;
    flex-wrap: inherit
}

.navbar-brand {
    padding-top: var(--bs-navbar-brand-padding-y);
    padding-bottom: var(--bs-navbar-brand-padding-y);
    margin-right: var(--bs-navbar-brand-margin-end);
    font-size: var(--bs-navbar-brand-font-size);
    color: var(--bs-navbar-brand-color);
    text-decoration: none;
    white-space: nowrap
}

.navbar-brand:focus,.navbar-brand:hover {
    color: var(--bs-navbar-brand-hover-color)
}

.navbar-nav {
    --bs-nav-link-padding-x: 0;
    --bs-nav-link-padding-y: 0.5rem;
    --bs-nav-link-font-weight: ;
    --bs-nav-link-color: var(--bs-navbar-color);
    --bs-nav-link-hover-color: var(--bs-navbar-hover-color);
    --bs-nav-link-disabled-color: var(--bs-navbar-disabled-color);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none
}

.navbar-nav .nav-link.active,.navbar-nav .nav-link.show {
    color: var(--bs-navbar-active-color)
}

.navbar-nav .dropdown-menu {
    position: static
}

.navbar-text {
    padding-top: .5rem;
    padding-bottom: .5rem;
    color: var(--bs-navbar-color)
}

.navbar-text a,.navbar-text a:focus,.navbar-text a:hover {
    color: var(--bs-navbar-active-color)
}

.navbar-collapse {
    -ms-flex-preferred-size: 100%;
    flex-basis: 100%;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center
}

.navbar-toggler {
    padding: var(--bs-navbar-toggler-padding-y) var(--bs-navbar-toggler-padding-x);
    font-size: var(--bs-navbar-toggler-font-size);
    line-height: 1;
    color: var(--bs-navbar-color);
    background-color: transparent;
    border: var(--bs-border-width) solid var(--bs-navbar-toggler-border-color);
    border-radius: var(--bs-navbar-toggler-border-radius);
    -webkit-transition: var(--bs-navbar-toggler-transition);
    transition: var(--bs-navbar-toggler-transition)
}

@media (prefers-reduced-motion:reduce) {
    .navbar-toggler {
        -webkit-transition: none;
        transition: none
    }
}

.navbar-toggler:hover,div.woocommerce .shop_table .product-name a {
    text-decoration: none
}

.navbar-toggler:focus {
    text-decoration: none;
    outline: 0;
    -webkit-box-shadow: 0 0 0 var(--bs-navbar-toggler-focus-width);
    box-shadow: 0 0 0 var(--bs-navbar-toggler-focus-width)
}

.navbar-toggler-icon {
    display: inline-block;
    width: 1.5em;
    height: 1.5em;
    vertical-align: middle;
    background-image: var(--bs-navbar-toggler-icon-bg);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 100%
}

.navbar-nav-scroll {
    max-height: var(--bs-scroll-height, 75vh);
    overflow-y: auto
}

@media (min-width: 576px) {
    .navbar-expand-sm {
        -ms-flex-wrap:nowrap;
        flex-wrap: nowrap;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start
    }

    .navbar-expand-sm .navbar-nav {
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .navbar-expand-sm .navbar-nav .dropdown-menu {
        position: absolute
    }

    .navbar-expand-sm .navbar-nav .nav-link {
        padding-right: var(--bs-navbar-nav-link-padding-x);
        padding-left: var(--bs-navbar-nav-link-padding-x)
    }

    .navbar-expand-sm .navbar-nav-scroll {
        overflow: visible
    }

    .navbar-expand-sm .navbar-collapse {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important;
        -ms-flex-preferred-size: auto;
        flex-basis: auto
    }

    .navbar-expand-sm .navbar-toggler,.navbar-expand-sm .offcanvas .offcanvas-header {
        display: none
    }

    .navbar-expand-sm .offcanvas {
        position: static;
        z-index: auto;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: auto!important;
        height: auto!important;
        visibility: visible!important;
        background-color: transparent!important;
        border: 0!important;
        -webkit-transform: none!important;
        transform: none!important;
        -webkit-transition: none;
        transition: none
    }

    .navbar-expand-sm .offcanvas .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible
    }
}

@media (min-width: 768px) {
    .navbar-expand-md {
        -ms-flex-wrap:nowrap;
        flex-wrap: nowrap;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start
    }

    .navbar-expand-md .navbar-nav {
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .navbar-expand-md .navbar-nav .dropdown-menu {
        position: absolute
    }

    .navbar-expand-md .navbar-nav .nav-link {
        padding-right: var(--bs-navbar-nav-link-padding-x);
        padding-left: var(--bs-navbar-nav-link-padding-x)
    }

    .navbar-expand-md .navbar-nav-scroll {
        overflow: visible
    }

    .navbar-expand-md .navbar-collapse {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important;
        -ms-flex-preferred-size: auto;
        flex-basis: auto
    }

    .navbar-expand-md .navbar-toggler,.navbar-expand-md .offcanvas .offcanvas-header {
        display: none
    }

    .navbar-expand-md .offcanvas {
        position: static;
        z-index: auto;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: auto!important;
        height: auto!important;
        visibility: visible!important;
        background-color: transparent!important;
        border: 0!important;
        -webkit-transform: none!important;
        transform: none!important;
        -webkit-transition: none;
        transition: none
    }

    .navbar-expand-md .offcanvas .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible
    }
}

@media (min-width: 992px) {
    .navbar-expand-lg {
        -ms-flex-wrap:nowrap;
        flex-wrap: nowrap;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start
    }

    .navbar-expand-lg .navbar-nav {
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .navbar-expand-lg .navbar-nav .dropdown-menu {
        position: absolute
    }

    .navbar-expand-lg .navbar-nav .nav-link {
        padding-right: var(--bs-navbar-nav-link-padding-x);
        padding-left: var(--bs-navbar-nav-link-padding-x)
    }

    .navbar-expand-lg .navbar-nav-scroll {
        overflow: visible
    }

    .navbar-expand-lg .navbar-collapse {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important;
        -ms-flex-preferred-size: auto;
        flex-basis: auto
    }

    .navbar-expand-lg .navbar-toggler,.navbar-expand-lg .offcanvas .offcanvas-header {
        display: none
    }

    .navbar-expand-lg .offcanvas {
        position: static;
        z-index: auto;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: auto!important;
        height: auto!important;
        visibility: visible!important;
        background-color: transparent!important;
        border: 0!important;
        -webkit-transform: none!important;
        transform: none!important;
        -webkit-transition: none;
        transition: none
    }

    .navbar-expand-lg .offcanvas .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible
    }
}

@media (min-width: 1200px) {
    .navbar-expand-xl {
        -ms-flex-wrap:nowrap;
        flex-wrap: nowrap;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start
    }

    .navbar-expand-xl .navbar-nav {
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .navbar-expand-xl .navbar-nav .dropdown-menu {
        position: absolute
    }

    .navbar-expand-xl .navbar-nav .nav-link {
        padding-right: var(--bs-navbar-nav-link-padding-x);
        padding-left: var(--bs-navbar-nav-link-padding-x)
    }

    .navbar-expand-xl .navbar-nav-scroll {
        overflow: visible
    }

    .navbar-expand-xl .navbar-collapse {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important;
        -ms-flex-preferred-size: auto;
        flex-basis: auto
    }

    .navbar-expand-xl .navbar-toggler,.navbar-expand-xl .offcanvas .offcanvas-header {
        display: none
    }

    .navbar-expand-xl .offcanvas {
        position: static;
        z-index: auto;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: auto!important;
        height: auto!important;
        visibility: visible!important;
        background-color: transparent!important;
        border: 0!important;
        -webkit-transform: none!important;
        transform: none!important;
        -webkit-transition: none;
        transition: none
    }

    .navbar-expand-xl .offcanvas .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible
    }
}

@media (min-width: 1400px) {
    .navbar-expand-xxl {
        -ms-flex-wrap:nowrap;
        flex-wrap: nowrap;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start
    }

    .navbar-expand-xxl .navbar-nav {
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .navbar-expand-xxl .navbar-nav .dropdown-menu {
        position: absolute
    }

    .navbar-expand-xxl .navbar-nav .nav-link {
        padding-right: var(--bs-navbar-nav-link-padding-x);
        padding-left: var(--bs-navbar-nav-link-padding-x)
    }

    .navbar-expand-xxl .navbar-nav-scroll {
        overflow: visible
    }

    .navbar-expand-xxl .navbar-collapse {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important;
        -ms-flex-preferred-size: auto;
        flex-basis: auto
    }

    .navbar-expand-xxl .navbar-toggler,.navbar-expand-xxl .offcanvas .offcanvas-header {
        display: none
    }

    .navbar-expand-xxl .offcanvas {
        position: static;
        z-index: auto;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: auto!important;
        height: auto!important;
        visibility: visible!important;
        background-color: transparent!important;
        border: 0!important;
        -webkit-transform: none!important;
        transform: none!important;
        -webkit-transition: none;
        transition: none
    }

    .navbar-expand-xxl .offcanvas .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible
    }
}

.navbar-expand {
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start
}

.navbar-expand .navbar-nav {
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-direction: row;
    flex-direction: row
}

.navbar-expand .navbar-nav .dropdown-menu {
    position: absolute
}

.navbar-expand .navbar-nav .nav-link {
    padding-right: var(--bs-navbar-nav-link-padding-x);
    padding-left: var(--bs-navbar-nav-link-padding-x)
}

.navbar-expand .navbar-nav-scroll {
    overflow: visible
}

.navbar-expand .navbar-collapse {
    display: -webkit-box!important;
    display: -ms-flexbox!important;
    display: flex!important;
    -ms-flex-preferred-size: auto;
    flex-basis: auto
}

.navbar-expand .navbar-toggler,.navbar-expand .offcanvas .offcanvas-header {
    display: none
}

.navbar-expand .offcanvas {
    position: static;
    z-index: auto;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    width: auto!important;
    height: auto!important;
    visibility: visible!important;
    background-color: transparent!important;
    border: 0!important;
    -webkit-transform: none!important;
    transform: none!important;
    -webkit-transition: none;
    transition: none
}

.navbar-expand .offcanvas .offcanvas-body {
    -webkit-box-flex: 0;
    -ms-flex-positive: 0;
    flex-grow: 0;
    padding: 0;
    overflow-y: visible
}

.navbar-dark,.navbar[data-bs-theme=dark] {
    --bs-navbar-color: rgba(255, 255, 255, 0.55);
    --bs-navbar-hover-color: rgba(255, 255, 255, 0.75);
    --bs-navbar-disabled-color: rgba(255, 255, 255, 0.25);
    --bs-navbar-active-color: #fff;
    --bs-navbar-brand-color: #fff;
    --bs-navbar-brand-hover-color: #fff;
    --bs-navbar-toggler-border-color: rgba(255, 255, 255, 0.1)
}

.navbar-dark,.navbar[data-bs-theme=dark],[data-bs-theme=dark] .navbar-toggler-icon {
    --bs-navbar-toggler-icon-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.55%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e")
}

.card,.navbar-expand .offcanvas .offcanvas-body {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex
}

.card {
    --bs-card-spacer-y: 1rem;
    --bs-card-spacer-x: 1rem;
    --bs-card-title-spacer-y: 0.5rem;
    --bs-card-title-color: ;
    --bs-card-subtitle-color: ;
    --bs-card-border-width: var(--bs-border-width);
    --bs-card-border-color: var(--bs-border-color-translucent);
    --bs-card-border-radius: var(--bs-border-radius);
    --bs-card-box-shadow: ;
    --bs-card-inner-border-radius: calc(var(--bs-border-radius) - (var(--bs-border-width)));
    --bs-card-cap-padding-y: 0.5rem;
    --bs-card-cap-padding-x: 1rem;
    --bs-card-cap-bg: rgba(var(--bs-body-color-rgb), 0.03);
    --bs-card-cap-color: ;
    --bs-card-height: ;
    --bs-card-color: ;
    --bs-card-bg: var(--bs-body-bg);
    --bs-card-img-overlay-padding: 1rem;
    --bs-card-group-margin: 0.75rem;
    position: relative;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    height: var(--bs-card-height);
    color: var(--bs-body-color);
    word-wrap: break-word;
    background-color: var(--bs-card-bg);
    background-clip: border-box;
    border: var(--bs-card-border-width) solid var(--bs-card-border-color);
    border-radius: var(--bs-card-border-radius)
}

.card>hr {
    margin-right: 0;
    margin-left: 0
}

.card>.list-group {
    border-top: inherit;
    border-bottom: inherit
}

.card>.list-group:first-child {
    border-top-width: 0;
    border-top-left-radius: var(--bs-card-inner-border-radius);
    border-top-right-radius: var(--bs-card-inner-border-radius)
}

.card>.list-group:last-child {
    border-bottom-width: 0;
    border-bottom-right-radius: var(--bs-card-inner-border-radius);
    border-bottom-left-radius: var(--bs-card-inner-border-radius)
}

.card>.card-header+.list-group,.card>.list-group+.card-footer {
    border-top: 0
}

.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: var(--bs-card-spacer-y) var(--bs-card-spacer-x);
    color: var(--bs-card-color)
}

.card-title {
    margin-bottom: var(--bs-card-title-spacer-y);
    color: var(--bs-card-title-color)
}

.card-subtitle {
    margin-top: calc(-.5*var(--bs-card-title-spacer-y));
    margin-bottom: 0;
    color: var(--bs-card-subtitle-color)
}

.card-text:last-child {
    margin-bottom: 0
}

.card-link+.card-link {
    margin-left: var(--bs-card-spacer-x)
}

.card-header {
    padding: var(--bs-card-cap-padding-y) var(--bs-card-cap-padding-x);
    margin-bottom: 0;
    color: var(--bs-card-cap-color);
    background-color: var(--bs-card-cap-bg);
    border-bottom: var(--bs-card-border-width) solid var(--bs-card-border-color)
}

.card-header:first-child {
    border-radius: var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius) 0 0
}

.card-footer {
    padding: var(--bs-card-cap-padding-y) var(--bs-card-cap-padding-x);
    color: var(--bs-card-cap-color);
    background-color: var(--bs-card-cap-bg);
    border-top: var(--bs-card-border-width) solid var(--bs-card-border-color)
}

.card-footer:last-child {
    border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius)
}

.card-header-tabs {
    margin-bottom: calc(-1*var(--bs-card-cap-padding-y));
    border-bottom: 0
}

.card-header-tabs .nav-link.active {
    background-color: var(--bs-card-bg);
    border-bottom-color: var(--bs-card-bg)
}

.card-header-pills,.card-header-tabs {
    margin-right: calc(-.5*var(--bs-card-cap-padding-x));
    margin-left: calc(-.5*var(--bs-card-cap-padding-x))
}

.card-img-overlay {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    padding: var(--bs-card-img-overlay-padding);
    border-radius: var(--bs-card-inner-border-radius)
}

.card-img,.card-img-bottom,.card-img-top {
    width: 100%
}

.card-img,.card-img-top {
    border-top-left-radius: var(--bs-card-inner-border-radius);
    border-top-right-radius: var(--bs-card-inner-border-radius)
}

.card-img,.card-img-bottom {
    border-bottom-right-radius: var(--bs-card-inner-border-radius);
    border-bottom-left-radius: var(--bs-card-inner-border-radius)
}

.card-group>.card {
    margin-bottom: var(--bs-card-group-margin)
}

@media (min-width: 576px) {
    .card-group {
        display:-webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-flow: row wrap;
        flex-flow: row wrap
    }

    .card-group>.card {
        -webkit-box-flex: 1;
        -ms-flex: 1 0 0%;
        flex: 1 0 0%;
        margin-bottom: 0
    }

    .card-group>.card+.card {
        margin-left: 0;
        border-left: 0
    }

    .card-group>.card:not(:last-child) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0
    }

    .card-group>.card:not(:last-child) .card-header,.card-group>.card:not(:last-child) .card-img-top {
        border-top-right-radius: 0
    }

    .card-group>.card:not(:last-child) .card-footer,.card-group>.card:not(:last-child) .card-img-bottom {
        border-bottom-right-radius: 0
    }

    .card-group>.card:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0
    }

    .card-group>.card:not(:first-child) .card-header,.card-group>.card:not(:first-child) .card-img-top {
        border-top-left-radius: 0
    }

    .card-group>.card:not(:first-child) .card-footer,.card-group>.card:not(:first-child) .card-img-bottom {
        border-bottom-left-radius: 0
    }
}

.accordion {
    --bs-accordion-color: var(--bs-body-color);
    --bs-accordion-bg: var(--bs-body-bg);
    --bs-accordion-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, border-radius 0.15s ease;
    --bs-accordion-border-color: var(--bs-border-color);
    --bs-accordion-border-width: var(--bs-border-width);
    --bs-accordion-border-radius: var(--bs-border-radius);
    --bs-accordion-inner-border-radius: calc(var(--bs-border-radius) - (var(--bs-border-width)));
    --bs-accordion-btn-padding-x: 1.25rem;
    --bs-accordion-btn-padding-y: 1rem;
    --bs-accordion-btn-color: var(--bs-body-color);
    --bs-accordion-btn-bg: var(--bs-accordion-bg);
    --bs-accordion-btn-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%23212529' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M2 5L8 11L14 5'/%3e%3c/svg%3e");
    --bs-accordion-btn-icon-width: 1.25rem;
    --bs-accordion-btn-icon-transform: rotate(-180deg);
    --bs-accordion-btn-icon-transition: transform 0.2s ease-in-out;
    --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%23052c65' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M2 5L8 11L14 5'/%3e%3c/svg%3e");
    --bs-accordion-btn-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    --bs-accordion-body-padding-x: 1.25rem;
    --bs-accordion-body-padding-y: 1rem;
    --bs-accordion-active-color: var(--bs-primary-text-emphasis);
    --bs-accordion-active-bg: var(--bs-primary-bg-subtle)
}

.accordion-button {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    width: 100%;
    padding: var(--bs-accordion-btn-padding-y) var(--bs-accordion-btn-padding-x);
    font-size: 1rem;
    color: var(--bs-accordion-btn-color);
    text-align: left;
    background-color: var(--bs-accordion-btn-bg);
    border: 0;
    border-radius: 0;
    overflow-anchor: none;
    -webkit-transition: var(--bs-accordion-transition);
    transition: var(--bs-accordion-transition)
}

@media (prefers-reduced-motion:reduce) {
    .accordion-button {
        -webkit-transition: none;
        transition: none
    }
}

.accordion-button:not(.collapsed) {
    color: var(--bs-accordion-active-color);
    background-color: var(--bs-accordion-active-bg);
    -webkit-box-shadow: inset 0 calc(-1*var(--bs-accordion-border-width)) 0 var(--bs-accordion-border-color);
    box-shadow: inset 0 calc(-1*var(--bs-accordion-border-width)) 0 var(--bs-accordion-border-color)
}

.accordion-button:not(.collapsed)::after {
    background-image: var(--bs-accordion-btn-active-icon);
    -webkit-transform: var(--bs-accordion-btn-icon-transform);
    transform: var(--bs-accordion-btn-icon-transform)
}

.accordion-button::after {
    -ms-flex-negative: 0;
    flex-shrink: 0;
    width: var(--bs-accordion-btn-icon-width);
    height: var(--bs-accordion-btn-icon-width);
    margin-left: auto;
    content: "";
    background-image: var(--bs-accordion-btn-icon);
    background-repeat: no-repeat;
    background-size: var(--bs-accordion-btn-icon-width);
    -webkit-transition: var(--bs-accordion-btn-icon-transition);
    transition: var(--bs-accordion-btn-icon-transition)
}

@media (prefers-reduced-motion:reduce) {
    .accordion-button::after {
        -webkit-transition: none;
        transition: none
    }
}

.accordion-button:hover {
    z-index: 2
}

.accordion-button:focus {
    z-index: 3;
    outline: 0;
    -webkit-box-shadow: var(--bs-accordion-btn-focus-box-shadow);
    box-shadow: var(--bs-accordion-btn-focus-box-shadow)
}

.accordion-header,.calculators .tabs-wrapper .calculator-form p {
    margin-bottom: 0
}

.accordion-item {
    color: var(--bs-accordion-color);
    background-color: var(--bs-accordion-bg);
    border: var(--bs-accordion-border-width) solid var(--bs-accordion-border-color)
}

.accordion-item:first-of-type {
    border-top-left-radius: var(--bs-accordion-border-radius);
    border-top-right-radius: var(--bs-accordion-border-radius)
}

.accordion-item:first-of-type>.accordion-header .accordion-button {
    border-top-left-radius: var(--bs-accordion-inner-border-radius);
    border-top-right-radius: var(--bs-accordion-inner-border-radius)
}

.accordion-item:not(:first-of-type) {
    border-top: 0
}

.accordion-item:last-of-type,.accordion-item:last-of-type>.accordion-collapse {
    border-bottom-right-radius: var(--bs-accordion-border-radius);
    border-bottom-left-radius: var(--bs-accordion-border-radius)
}

.accordion-item:last-of-type>.accordion-header .accordion-button.collapsed {
    border-bottom-right-radius: var(--bs-accordion-inner-border-radius);
    border-bottom-left-radius: var(--bs-accordion-inner-border-radius)
}

.accordion-body {
    padding: var(--bs-accordion-body-padding-y) var(--bs-accordion-body-padding-x)
}

.accordion-flush>.accordion-item {
    border-right: 0;
    border-left: 0;
    border-radius: 0
}

.accordion-flush>.accordion-item:first-child {
    border-top: 0
}

.accordion-flush>.accordion-item:last-child {
    border-bottom: 0
}

.accordion-flush>.accordion-item>.accordion-collapse,.accordion-flush>.accordion-item>.accordion-header .accordion-button,.accordion-flush>.accordion-item>.accordion-header .accordion-button.collapsed {
    border-radius: 0
}

[data-bs-theme=dark] .accordion-button::after {
    --bs-accordion-btn-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236ea8fe'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236ea8fe'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e")
}

.breadcrumb,.pagination {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    list-style: none
}

.breadcrumb {
    --bs-breadcrumb-padding-x: 0;
    --bs-breadcrumb-padding-y: 0;
    --bs-breadcrumb-margin-bottom: 1rem;
    --bs-breadcrumb-bg: ;
    --bs-breadcrumb-border-radius: ;
    --bs-breadcrumb-divider-color: var(--bs-secondary-color);
    --bs-breadcrumb-item-padding-x: 0.5rem;
    --bs-breadcrumb-item-active-color: var(--bs-secondary-color);
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding: var(--bs-breadcrumb-padding-y) var(--bs-breadcrumb-padding-x);
    margin-bottom: var(--bs-breadcrumb-margin-bottom);
    font-size: var(--bs-breadcrumb-font-size);
    background-color: var(--bs-breadcrumb-bg);
    border-radius: var(--bs-breadcrumb-border-radius)
}

.breadcrumb-item+.breadcrumb-item {
    padding-left: var(--bs-breadcrumb-item-padding-x)
}

.breadcrumb-item+.breadcrumb-item::before {
    float: left;
    padding-right: var(--bs-breadcrumb-item-padding-x);
    color: var(--bs-breadcrumb-divider-color);
    content: var(--bs-breadcrumb-divider, "/")
}

.breadcrumb-item.active {
    color: var(--bs-breadcrumb-item-active-color)
}

.pagination {
    --bs-pagination-padding-x: 0.75rem;
    --bs-pagination-padding-y: 0.375rem;
    --bs-pagination-font-size: 1rem;
    --bs-pagination-color: var(--bs-link-color);
    --bs-pagination-bg: var(--bs-body-bg);
    --bs-pagination-border-width: var(--bs-border-width);
    --bs-pagination-border-color: var(--bs-border-color);
    --bs-pagination-border-radius: var(--bs-border-radius);
    --bs-pagination-hover-color: var(--bs-link-hover-color);
    --bs-pagination-hover-bg: var(--bs-tertiary-bg);
    --bs-pagination-hover-border-color: var(--bs-border-color);
    --bs-pagination-focus-color: var(--bs-link-hover-color);
    --bs-pagination-focus-bg: var(--bs-secondary-bg);
    --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
    --bs-pagination-disabled-color: var(--bs-secondary-color);
    --bs-pagination-disabled-bg: var(--bs-secondary-bg);
    --bs-pagination-disabled-border-color: var(--bs-border-color);
    padding-left: 0
}

.page-link {
    position: relative;
    display: block;
    padding: var(--bs-pagination-padding-y) var(--bs-pagination-padding-x);
    font-size: var(--bs-pagination-font-size);
    color: var(--bs-pagination-color);
    text-decoration: none;
    background-color: var(--bs-pagination-bg);
    border: var(--bs-pagination-border-width) solid var(--bs-pagination-border-color);
    -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .page-link {
        -webkit-transition: none;
        transition: none
    }
}

.page-link:hover {
    z-index: 2;
    color: var(--bs-pagination-hover-color);
    background-color: var(--bs-pagination-hover-bg);
    border-color: var(--bs-pagination-hover-border-color)
}

.page-link:focus {
    z-index: 3;
    color: var(--bs-pagination-focus-color);
    background-color: var(--bs-pagination-focus-bg);
    outline: 0;
    -webkit-box-shadow: var(--bs-pagination-focus-box-shadow);
    box-shadow: var(--bs-pagination-focus-box-shadow)
}

.active>.page-link,.page-link.active {
    z-index: 3;
    color: var(--bs-pagination-active-color);
    background-color: var(--bs-pagination-active-bg);
    border-color: var(--bs-pagination-active-border-color)
}

.disabled>.page-link,.page-link.disabled {
    color: var(--bs-pagination-disabled-color);
    pointer-events: none;
    background-color: var(--bs-pagination-disabled-bg);
    border-color: var(--bs-pagination-disabled-border-color)
}

.page-item:not(:first-child) .page-link {
    margin-left: calc(var(--bs-border-width)*-1)
}

.page-item:first-child .page-link {
    border-top-left-radius: var(--bs-pagination-border-radius);
    border-bottom-left-radius: var(--bs-pagination-border-radius)
}

.page-item:last-child .page-link {
    border-top-right-radius: var(--bs-pagination-border-radius);
    border-bottom-right-radius: var(--bs-pagination-border-radius)
}

.pagination-lg {
    --bs-pagination-padding-x: 1.5rem;
    --bs-pagination-padding-y: 0.75rem;
    --bs-pagination-font-size: 1.25rem;
    --bs-pagination-border-radius: var(--bs-border-radius-lg)
}

.pagination-sm {
    --bs-pagination-padding-x: 0.5rem;
    --bs-pagination-padding-y: 0.25rem;
    --bs-pagination-font-size: 0.875rem;
    --bs-pagination-border-radius: var(--bs-border-radius-sm)
}

.badge {
    --bs-badge-padding-x: 0.65em;
    --bs-badge-padding-y: 0.35em;
    --bs-badge-font-size: 0.75em;
    --bs-badge-font-weight: 700;
    --bs-badge-color: #fff;
    --bs-badge-border-radius: var(--bs-border-radius);
    display: inline-block;
    padding: var(--bs-badge-padding-y) var(--bs-badge-padding-x);
    font-size: var(--bs-badge-font-size);
    font-weight: var(--bs-badge-font-weight);
    line-height: 1;
    color: var(--bs-badge-color);
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: var(--bs-badge-border-radius)
}

.badge:empty {
    display: none
}

.btn .badge {
    position: relative;
    top: -1px
}

.alert {
    --bs-alert-bg: transparent;
    --bs-alert-padding-x: 1rem;
    --bs-alert-padding-y: 1rem;
    --bs-alert-margin-bottom: 1rem;
    --bs-alert-color: inherit;
    --bs-alert-border-color: transparent;
    --bs-alert-border: var(--bs-border-width) solid var(--bs-alert-border-color);
    --bs-alert-border-radius: var(--bs-border-radius);
    --bs-alert-link-color: inherit;
    position: relative;
    padding: var(--bs-alert-padding-y) var(--bs-alert-padding-x);
    margin-bottom: var(--bs-alert-margin-bottom);
    color: var(--bs-alert-color);
    background-color: var(--bs-alert-bg);
    border: var(--bs-alert-border);
    border-radius: var(--bs-alert-border-radius)
}

.alert-heading {
    color: inherit
}

.alert-link {
    font-weight: 700;
    color: var(--bs-alert-link-color)
}

.alert-dismissible {
    padding-right: 3rem
}

.alert-dismissible .btn-close {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 2;
    padding: 1.25rem 1rem
}

.alert-primary {
    --bs-alert-color: var(--bs-primary-text-emphasis);
    --bs-alert-bg: var(--bs-primary-bg-subtle);
    --bs-alert-border-color: var(--bs-primary-border-subtle);
    --bs-alert-link-color: var(--bs-primary-text-emphasis)
}

.alert-secondary {
    --bs-alert-color: var(--bs-secondary-text-emphasis);
    --bs-alert-bg: var(--bs-secondary-bg-subtle);
    --bs-alert-border-color: var(--bs-secondary-border-subtle);
    --bs-alert-link-color: var(--bs-secondary-text-emphasis)
}

.alert-success {
    --bs-alert-color: var(--bs-success-text-emphasis);
    --bs-alert-bg: var(--bs-success-bg-subtle);
    --bs-alert-border-color: var(--bs-success-border-subtle);
    --bs-alert-link-color: var(--bs-success-text-emphasis)
}

.alert-info {
    --bs-alert-color: var(--bs-info-text-emphasis);
    --bs-alert-bg: var(--bs-info-bg-subtle);
    --bs-alert-border-color: var(--bs-info-border-subtle);
    --bs-alert-link-color: var(--bs-info-text-emphasis)
}

.alert-warning {
    --bs-alert-color: var(--bs-warning-text-emphasis);
    --bs-alert-bg: var(--bs-warning-bg-subtle);
    --bs-alert-border-color: var(--bs-warning-border-subtle);
    --bs-alert-link-color: var(--bs-warning-text-emphasis)
}

.alert-danger {
    --bs-alert-color: var(--bs-danger-text-emphasis);
    --bs-alert-bg: var(--bs-danger-bg-subtle);
    --bs-alert-border-color: var(--bs-danger-border-subtle);
    --bs-alert-link-color: var(--bs-danger-text-emphasis)
}

.alert-light {
    --bs-alert-color: var(--bs-light-text-emphasis);
    --bs-alert-bg: var(--bs-light-bg-subtle);
    --bs-alert-border-color: var(--bs-light-border-subtle);
    --bs-alert-link-color: var(--bs-light-text-emphasis)
}

.alert-dark {
    --bs-alert-color: var(--bs-dark-text-emphasis);
    --bs-alert-bg: var(--bs-dark-bg-subtle);
    --bs-alert-border-color: var(--bs-dark-border-subtle);
    --bs-alert-link-color: var(--bs-dark-text-emphasis)
}

.progress,.progress-bar,.progress-stacked {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    overflow: hidden
}

.progress,.progress-stacked {
    --bs-progress-height: 1rem;
    --bs-progress-font-size: 0.75rem;
    --bs-progress-bg: var(--bs-secondary-bg);
    --bs-progress-border-radius: var(--bs-border-radius);
    --bs-progress-box-shadow: var(--bs-box-shadow-inset);
    --bs-progress-bar-color: #fff;
    --bs-progress-bar-bg: #0d6efd;
    --bs-progress-bar-transition: width 0.6s ease;
    height: var(--bs-progress-height);
    font-size: var(--bs-progress-font-size);
    background-color: var(--bs-progress-bg);
    border-radius: var(--bs-progress-border-radius)
}

.progress-bar {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    color: var(--bs-progress-bar-color);
    text-align: center;
    white-space: nowrap;
    background-color: var(--bs-progress-bar-bg);
    -webkit-transition: var(--bs-progress-bar-transition);
    transition: var(--bs-progress-bar-transition)
}

@media (prefers-reduced-motion:reduce) {
    .progress-bar {
        -webkit-transition: none;
        transition: none
    }
}

.progress-bar-striped {
    background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
    background-size: var(--bs-progress-height) var(--bs-progress-height)
}

.progress-stacked>.progress {
    overflow: visible
}

.progress-stacked>.progress>.progress-bar {
    width: 100%
}

.progress-bar-animated {
    -webkit-animation: 1s linear infinite progress-bar-stripes;
    animation: 1s linear infinite progress-bar-stripes
}

@media (prefers-reduced-motion:reduce) {
    .progress-bar-animated {
        -webkit-animation: none;
        animation: none
    }
}

.list-group {
    --bs-list-group-color: var(--bs-body-color);
    --bs-list-group-bg: var(--bs-body-bg);
    --bs-list-group-border-color: var(--bs-border-color);
    --bs-list-group-border-width: var(--bs-border-width);
    --bs-list-group-border-radius: var(--bs-border-radius);
    --bs-list-group-item-padding-x: 1rem;
    --bs-list-group-item-padding-y: 0.5rem;
    --bs-list-group-action-color: var(--bs-secondary-color);
    --bs-list-group-action-hover-color: var(--bs-emphasis-color);
    --bs-list-group-action-hover-bg: var(--bs-tertiary-bg);
    --bs-list-group-action-active-color: var(--bs-body-color);
    --bs-list-group-action-active-bg: var(--bs-secondary-bg);
    --bs-list-group-disabled-color: var(--bs-secondary-color);
    --bs-list-group-disabled-bg: var(--bs-body-bg);
    --bs-list-group-active-color: #fff;
    --bs-list-group-active-bg: #0d6efd;
    --bs-list-group-active-border-color: #0d6efd;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    padding-left: 0;
    margin-bottom: 0;
    border-radius: var(--bs-list-group-border-radius)
}

.list-group-numbered {
    list-style-type: none;
    counter-reset: section
}

.list-group-numbered>.list-group-item::before {
    content: counters(section,".") ". ";
    counter-increment: section
}

.list-group-item-action {
    width: 100%;
    color: var(--bs-list-group-action-color);
    text-align: inherit
}

.list-group-item-action:focus,.list-group-item-action:hover {
    z-index: 1;
    color: var(--bs-list-group-action-hover-color);
    text-decoration: none;
    background-color: var(--bs-list-group-action-hover-bg)
}

.list-group-item-action:active {
    color: var(--bs-list-group-action-active-color);
    background-color: var(--bs-list-group-action-active-bg)
}

.list-group-item {
    position: relative;
    display: block;
    padding: var(--bs-list-group-item-padding-y) var(--bs-list-group-item-padding-x);
    color: var(--bs-list-group-color);
    text-decoration: none;
    background-color: var(--bs-list-group-bg);
    border: var(--bs-list-group-border-width) solid var(--bs-list-group-border-color)
}

.list-group-item:first-child {
    border-top-left-radius: inherit;
    border-top-right-radius: inherit
}

.list-group-item:last-child {
    border-bottom-right-radius: inherit;
    border-bottom-left-radius: inherit
}

.list-group-item.disabled,.list-group-item:disabled {
    color: var(--bs-list-group-disabled-color);
    pointer-events: none;
    background-color: var(--bs-list-group-disabled-bg)
}

.list-group-item.active {
    z-index: 2;
    color: var(--bs-list-group-active-color);
    background-color: var(--bs-list-group-active-bg);
    border-color: var(--bs-list-group-active-border-color)
}

.list-group-item+.list-group-item {
    border-top-width: 0
}

.list-group-item+.list-group-item.active {
    margin-top: calc(-1*var(--bs-list-group-border-width));
    border-top-width: var(--bs-list-group-border-width)
}

.list-group-horizontal {
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-direction: row;
    flex-direction: row
}

.list-group-horizontal>.list-group-item:first-child:not(:last-child) {
    border-bottom-left-radius: var(--bs-list-group-border-radius);
    border-top-right-radius: 0
}

.list-group-horizontal>.list-group-item:last-child:not(:first-child) {
    border-top-right-radius: var(--bs-list-group-border-radius);
    border-bottom-left-radius: 0
}

.list-group-horizontal>.list-group-item.active {
    margin-top: 0
}

.list-group-horizontal>.list-group-item+.list-group-item {
    border-top-width: var(--bs-list-group-border-width);
    border-left-width: 0
}

.list-group-horizontal>.list-group-item+.list-group-item.active {
    margin-left: calc(-1*var(--bs-list-group-border-width));
    border-left-width: var(--bs-list-group-border-width)
}

@media (min-width: 576px) {
    .list-group-horizontal-sm {
        -webkit-box-orient:horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .list-group-horizontal-sm>.list-group-item:first-child:not(:last-child) {
        border-bottom-left-radius: var(--bs-list-group-border-radius);
        border-top-right-radius: 0
    }

    .list-group-horizontal-sm>.list-group-item:last-child:not(:first-child) {
        border-top-right-radius: var(--bs-list-group-border-radius);
        border-bottom-left-radius: 0
    }

    .list-group-horizontal-sm>.list-group-item.active {
        margin-top: 0
    }

    .list-group-horizontal-sm>.list-group-item+.list-group-item {
        border-top-width: var(--bs-list-group-border-width);
        border-left-width: 0
    }

    .list-group-horizontal-sm>.list-group-item+.list-group-item.active {
        margin-left: calc(-1*var(--bs-list-group-border-width));
        border-left-width: var(--bs-list-group-border-width)
    }
}

@media (min-width: 768px) {
    .list-group-horizontal-md {
        -webkit-box-orient:horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .list-group-horizontal-md>.list-group-item:first-child:not(:last-child) {
        border-bottom-left-radius: var(--bs-list-group-border-radius);
        border-top-right-radius: 0
    }

    .list-group-horizontal-md>.list-group-item:last-child:not(:first-child) {
        border-top-right-radius: var(--bs-list-group-border-radius);
        border-bottom-left-radius: 0
    }

    .list-group-horizontal-md>.list-group-item.active {
        margin-top: 0
    }

    .list-group-horizontal-md>.list-group-item+.list-group-item {
        border-top-width: var(--bs-list-group-border-width);
        border-left-width: 0
    }

    .list-group-horizontal-md>.list-group-item+.list-group-item.active {
        margin-left: calc(-1*var(--bs-list-group-border-width));
        border-left-width: var(--bs-list-group-border-width)
    }
}

@media (min-width: 992px) {
    .list-group-horizontal-lg {
        -webkit-box-orient:horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .list-group-horizontal-lg>.list-group-item:first-child:not(:last-child) {
        border-bottom-left-radius: var(--bs-list-group-border-radius);
        border-top-right-radius: 0
    }

    .list-group-horizontal-lg>.list-group-item:last-child:not(:first-child) {
        border-top-right-radius: var(--bs-list-group-border-radius);
        border-bottom-left-radius: 0
    }

    .list-group-horizontal-lg>.list-group-item.active {
        margin-top: 0
    }

    .list-group-horizontal-lg>.list-group-item+.list-group-item {
        border-top-width: var(--bs-list-group-border-width);
        border-left-width: 0
    }

    .list-group-horizontal-lg>.list-group-item+.list-group-item.active {
        margin-left: calc(-1*var(--bs-list-group-border-width));
        border-left-width: var(--bs-list-group-border-width)
    }
}

@media (min-width: 1200px) {
    .list-group-horizontal-xl {
        -webkit-box-orient:horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .list-group-horizontal-xl>.list-group-item:first-child:not(:last-child) {
        border-bottom-left-radius: var(--bs-list-group-border-radius);
        border-top-right-radius: 0
    }

    .list-group-horizontal-xl>.list-group-item:last-child:not(:first-child) {
        border-top-right-radius: var(--bs-list-group-border-radius);
        border-bottom-left-radius: 0
    }

    .list-group-horizontal-xl>.list-group-item.active {
        margin-top: 0
    }

    .list-group-horizontal-xl>.list-group-item+.list-group-item {
        border-top-width: var(--bs-list-group-border-width);
        border-left-width: 0
    }

    .list-group-horizontal-xl>.list-group-item+.list-group-item.active {
        margin-left: calc(-1*var(--bs-list-group-border-width));
        border-left-width: var(--bs-list-group-border-width)
    }
}

@media (min-width: 1400px) {
    .list-group-horizontal-xxl {
        -webkit-box-orient:horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row
    }

    .list-group-horizontal-xxl>.list-group-item:first-child:not(:last-child) {
        border-bottom-left-radius: var(--bs-list-group-border-radius);
        border-top-right-radius: 0
    }

    .list-group-horizontal-xxl>.list-group-item:last-child:not(:first-child) {
        border-top-right-radius: var(--bs-list-group-border-radius);
        border-bottom-left-radius: 0
    }

    .list-group-horizontal-xxl>.list-group-item.active {
        margin-top: 0
    }

    .list-group-horizontal-xxl>.list-group-item+.list-group-item {
        border-top-width: var(--bs-list-group-border-width);
        border-left-width: 0
    }

    .list-group-horizontal-xxl>.list-group-item+.list-group-item.active {
        margin-left: calc(-1*var(--bs-list-group-border-width));
        border-left-width: var(--bs-list-group-border-width)
    }
}

.list-group-flush {
    border-radius: 0
}

.list-group-flush>.list-group-item {
    border-width: 0 0 var(--bs-list-group-border-width)
}

.list-group-flush>.list-group-item:last-child {
    border-bottom-width: 0
}

.list-group-item-primary,.list-group-item-secondary {
    --bs-list-group-action-hover-color: var(--bs-emphasis-color);
    --bs-list-group-action-active-color: var(--bs-emphasis-color)
}

.list-group-item-primary {
    --bs-list-group-color: var(--bs-primary-text-emphasis);
    --bs-list-group-bg: var(--bs-primary-bg-subtle);
    --bs-list-group-border-color: var(--bs-primary-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-primary-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-primary-border-subtle);
    --bs-list-group-active-color: var(--bs-primary-bg-subtle);
    --bs-list-group-active-bg: var(--bs-primary-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-primary-text-emphasis)
}

.list-group-item-secondary {
    --bs-list-group-color: var(--bs-secondary-text-emphasis);
    --bs-list-group-bg: var(--bs-secondary-bg-subtle);
    --bs-list-group-border-color: var(--bs-secondary-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-secondary-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-secondary-border-subtle);
    --bs-list-group-active-color: var(--bs-secondary-bg-subtle);
    --bs-list-group-active-bg: var(--bs-secondary-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-secondary-text-emphasis)
}

.list-group-item-success {
    --bs-list-group-color: var(--bs-success-text-emphasis);
    --bs-list-group-bg: var(--bs-success-bg-subtle);
    --bs-list-group-border-color: var(--bs-success-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-success-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-success-border-subtle);
    --bs-list-group-active-color: var(--bs-success-bg-subtle);
    --bs-list-group-active-bg: var(--bs-success-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-success-text-emphasis)
}

.list-group-item-info,.list-group-item-success,.list-group-item-warning {
    --bs-list-group-action-hover-color: var(--bs-emphasis-color);
    --bs-list-group-action-active-color: var(--bs-emphasis-color)
}

.list-group-item-info {
    --bs-list-group-color: var(--bs-info-text-emphasis);
    --bs-list-group-bg: var(--bs-info-bg-subtle);
    --bs-list-group-border-color: var(--bs-info-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-info-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-info-border-subtle);
    --bs-list-group-active-color: var(--bs-info-bg-subtle);
    --bs-list-group-active-bg: var(--bs-info-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-info-text-emphasis)
}

.list-group-item-warning {
    --bs-list-group-color: var(--bs-warning-text-emphasis);
    --bs-list-group-bg: var(--bs-warning-bg-subtle);
    --bs-list-group-border-color: var(--bs-warning-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-warning-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-warning-border-subtle);
    --bs-list-group-active-color: var(--bs-warning-bg-subtle);
    --bs-list-group-active-bg: var(--bs-warning-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-warning-text-emphasis)
}

.list-group-item-danger {
    --bs-list-group-color: var(--bs-danger-text-emphasis);
    --bs-list-group-bg: var(--bs-danger-bg-subtle);
    --bs-list-group-border-color: var(--bs-danger-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-danger-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-danger-border-subtle);
    --bs-list-group-active-color: var(--bs-danger-bg-subtle);
    --bs-list-group-active-bg: var(--bs-danger-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-danger-text-emphasis)
}

.list-group-item-danger,.list-group-item-dark,.list-group-item-light {
    --bs-list-group-action-hover-color: var(--bs-emphasis-color);
    --bs-list-group-action-active-color: var(--bs-emphasis-color)
}

.list-group-item-light {
    --bs-list-group-color: var(--bs-light-text-emphasis);
    --bs-list-group-bg: var(--bs-light-bg-subtle);
    --bs-list-group-border-color: var(--bs-light-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-light-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-light-border-subtle);
    --bs-list-group-active-color: var(--bs-light-bg-subtle);
    --bs-list-group-active-bg: var(--bs-light-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-light-text-emphasis)
}

.list-group-item-dark {
    --bs-list-group-color: var(--bs-dark-text-emphasis);
    --bs-list-group-bg: var(--bs-dark-bg-subtle);
    --bs-list-group-border-color: var(--bs-dark-border-subtle);
    --bs-list-group-action-hover-bg: var(--bs-dark-border-subtle);
    --bs-list-group-action-active-bg: var(--bs-dark-border-subtle);
    --bs-list-group-active-color: var(--bs-dark-bg-subtle);
    --bs-list-group-active-bg: var(--bs-dark-text-emphasis);
    --bs-list-group-active-border-color: var(--bs-dark-text-emphasis)
}

.btn-close,.btn-close:hover {
    color: var(--bs-btn-close-color)
}

.btn-close {
    --bs-btn-close-color: #000;
    --bs-btn-close-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e");
    --bs-btn-close-opacity: 0.5;
    --bs-btn-close-hover-opacity: 0.75;
    --bs-btn-close-focus-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    --bs-btn-close-focus-opacity: 1;
    --bs-btn-close-disabled-opacity: 0.25;
    --bs-btn-close-white-filter: invert(1) grayscale(100%) brightness(200%);
    -webkit-box-sizing: content-box;
    box-sizing: content-box;
    width: 1em;
    height: 1em;
    padding: .25em;
    background: var(--bs-btn-close-bg) center/1em auto no-repeat;
    border: 0;
    border-radius: .375rem;
    opacity: var(--bs-btn-close-opacity)
}

.btn-close:hover {
    text-decoration: none;
    opacity: var(--bs-btn-close-hover-opacity)
}

.btn-close:focus {
    outline: 0;
    -webkit-box-shadow: var(--bs-btn-close-focus-shadow);
    box-shadow: var(--bs-btn-close-focus-shadow);
    opacity: var(--bs-btn-close-focus-opacity)
}

.btn-close.disabled,.btn-close:disabled {
    pointer-events: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    opacity: var(--bs-btn-close-disabled-opacity)
}

.btn-close-white,[data-bs-theme=dark] .btn-close {
    -webkit-filter: var(--bs-btn-close-white-filter);
    filter: var(--bs-btn-close-white-filter)
}

.toast {
    --bs-toast-zindex: 1090;
    --bs-toast-padding-x: 0.75rem;
    --bs-toast-padding-y: 0.5rem;
    --bs-toast-spacing: 1.5rem;
    --bs-toast-max-width: 350px;
    --bs-toast-font-size: 0.875rem;
    --bs-toast-color: ;
    --bs-toast-bg: rgba(var(--bs-body-bg-rgb), 0.85);
    --bs-toast-border-width: var(--bs-border-width);
    --bs-toast-border-color: var(--bs-border-color-translucent);
    --bs-toast-border-radius: var(--bs-border-radius);
    --bs-toast-box-shadow: var(--bs-box-shadow);
    --bs-toast-header-color: var(--bs-secondary-color);
    --bs-toast-header-bg: rgba(var(--bs-body-bg-rgb), 0.85);
    --bs-toast-header-border-color: var(--bs-border-color-translucent);
    width: var(--bs-toast-max-width);
    max-width: 100%;
    font-size: var(--bs-toast-font-size);
    color: var(--bs-toast-color);
    pointer-events: auto;
    background-color: var(--bs-toast-bg);
    background-clip: padding-box;
    border: var(--bs-toast-border-width) solid var(--bs-toast-border-color);
    -webkit-box-shadow: var(--bs-toast-box-shadow);
    box-shadow: var(--bs-toast-box-shadow);
    border-radius: var(--bs-toast-border-radius)
}

.toast.showing {
    opacity: 0
}

.toast:not(.show) {
    display: none
}

.toast-container {
    --bs-toast-zindex: 1090;
    position: absolute;
    z-index: var(--bs-toast-zindex);
    width: -webkit-max-content;
    width: -moz-max-content;
    width: max-content;
    max-width: 100%;
    pointer-events: none
}

.toast-container>:not(:last-child) {
    margin-bottom: var(--bs-toast-spacing)
}

.toast-header {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: var(--bs-toast-padding-y) var(--bs-toast-padding-x);
    color: var(--bs-toast-header-color);
    background-color: var(--bs-toast-header-bg);
    background-clip: padding-box;
    border-bottom: var(--bs-toast-border-width) solid var(--bs-toast-header-border-color);
    border-top-left-radius: calc(var(--bs-toast-border-radius) - var(--bs-toast-border-width));
    border-top-right-radius: calc(var(--bs-toast-border-radius) - var(--bs-toast-border-width))
}

.toast-header .btn-close {
    margin-right: calc(-.5*var(--bs-toast-padding-x));
    margin-left: var(--bs-toast-padding-x)
}

.toast-body {
    padding: var(--bs-toast-padding-x);
    word-wrap: break-word
}

.modal {
    --bs-modal-zindex: 1055;
    --bs-modal-width: 500px;
    --bs-modal-padding: 1rem;
    --bs-modal-margin: 0.5rem;
    --bs-modal-color: ;
    --bs-modal-bg: var(--bs-body-bg);
    --bs-modal-border-color: var(--bs-border-color-translucent);
    --bs-modal-border-width: var(--bs-border-width);
    --bs-modal-border-radius: var(--bs-border-radius-lg);
    --bs-modal-box-shadow: var(--bs-box-shadow-sm);
    --bs-modal-inner-border-radius: calc(var(--bs-border-radius-lg) - (var(--bs-border-width)));
    --bs-modal-header-padding-x: 1rem;
    --bs-modal-header-padding-y: 1rem;
    --bs-modal-header-padding: 1rem 1rem;
    --bs-modal-header-border-color: var(--bs-border-color);
    --bs-modal-header-border-width: var(--bs-border-width);
    --bs-modal-title-line-height: 1.5;
    --bs-modal-footer-gap: 0.5rem;
    --bs-modal-footer-bg: ;
    --bs-modal-footer-border-color: var(--bs-border-color);
    --bs-modal-footer-border-width: var(--bs-border-width);
    position: fixed;
    top: 0;
    left: 0;
    z-index: var(--bs-modal-zindex);
    display: none;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: var(--bs-modal-margin);
    pointer-events: none
}

.modal.fade .modal-dialog {
    -webkit-transition: -webkit-transform .3s ease-out;
    transition: transform .3s ease-out;
    transition: transform .3s ease-out,-webkit-transform .3s ease-out;
    -webkit-transform: translate(0,-50px);
    transform: translate(0,-50px)
}

@media (prefers-reduced-motion:reduce) {
    .modal.fade .modal-dialog {
        -webkit-transition: none;
        transition: none
    }
}

.modal.show .modal-dialog {
    -webkit-transform: none;
    transform: none
}

.modal.modal-static .modal-dialog {
    -webkit-transform: scale(1.02);
    transform: scale(1.02)
}

.modal-dialog-scrollable {
    height: calc(100% - var(--bs-modal-margin)*2)
}

.modal-dialog-scrollable .modal-content {
    max-height: 100%;
    overflow: hidden
}

.modal-dialog-scrollable .modal-body {
    overflow-y: auto
}

.modal-content,.modal-dialog-centered {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex
}

.modal-dialog-centered {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    min-height: calc(100% - var(--bs-modal-margin)*2)
}

.modal-content {
    position: relative;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    width: 100%;
    color: var(--bs-modal-color);
    pointer-events: auto;
    background-color: var(--bs-modal-bg);
    background-clip: padding-box;
    border: var(--bs-modal-border-width) solid var(--bs-modal-border-color);
    border-radius: var(--bs-modal-border-radius);
    outline: 0
}

.modal-backdrop {
    --bs-backdrop-zindex: 1050;
    --bs-backdrop-bg: #000;
    --bs-backdrop-opacity: 0.5;
    position: fixed;
    top: 0;
    left: 0;
    z-index: var(--bs-backdrop-zindex);
    width: 100vw;
    height: 100vh;
    background-color: var(--bs-backdrop-bg)
}

.modal-backdrop.fade {
    opacity: 0
}

.modal-backdrop.show {
    opacity: var(--bs-backdrop-opacity)
}

.modal-header {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: var(--bs-modal-header-padding);
    border-bottom: var(--bs-modal-header-border-width) solid var(--bs-modal-header-border-color);
    border-top-left-radius: var(--bs-modal-inner-border-radius);
    border-top-right-radius: var(--bs-modal-inner-border-radius)
}

.modal-header .btn-close {
    padding: calc(var(--bs-modal-header-padding-y)*.5) calc(var(--bs-modal-header-padding-x)*.5);
    margin: calc(-.5*var(--bs-modal-header-padding-y)) calc(-.5*var(--bs-modal-header-padding-x)) calc(-.5*var(--bs-modal-header-padding-y)) auto
}

.modal-title {
    margin-bottom: 0;
    line-height: var(--bs-modal-title-line-height)
}

.modal-body {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: var(--bs-modal-padding)
}

.modal-footer {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    padding: calc(var(--bs-modal-padding) - var(--bs-modal-footer-gap)*.5);
    background-color: var(--bs-modal-footer-bg);
    border-top: var(--bs-modal-footer-border-width) solid var(--bs-modal-footer-border-color);
    border-bottom-right-radius: var(--bs-modal-inner-border-radius);
    border-bottom-left-radius: var(--bs-modal-inner-border-radius)
}

.modal-footer>* {
    margin: calc(var(--bs-modal-footer-gap)*.5)
}

@media (min-width: 576px) {
    .modal {
        --bs-modal-margin:1.75rem;
        --bs-modal-box-shadow: var(--bs-box-shadow)
    }

    .modal-dialog {
        max-width: var(--bs-modal-width);
        margin-right: auto;
        margin-left: auto
    }

    .modal-sm {
        --bs-modal-width: 300px
    }
}

@media (min-width: 992px) {
    .modal-lg,.modal-xl {
        --bs-modal-width:800px
    }
}

@media (min-width: 1200px) {
    .modal-xl {
        --bs-modal-width:1140px
    }
}

.modal-fullscreen {
    width: 100vw;
    max-width: none;
    height: 100%;
    margin: 0
}

.modal-fullscreen .modal-content {
    height: 100%;
    border: 0;
    border-radius: 0
}

.modal-fullscreen .modal-footer,.modal-fullscreen .modal-header {
    border-radius: 0
}

.modal-fullscreen .modal-body {
    overflow-y: auto
}

@media (max-width: 575.98px) {
    .modal-fullscreen-sm-down {
        width:100vw;
        max-width: none;
        height: 100%;
        margin: 0
    }

    .modal-fullscreen-sm-down .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0
    }

    .modal-fullscreen-sm-down .modal-footer,.modal-fullscreen-sm-down .modal-header {
        border-radius: 0
    }

    .modal-fullscreen-sm-down .modal-body {
        overflow-y: auto
    }
}

@media (max-width: 767.98px) {
    .modal-fullscreen-md-down {
        width:100vw;
        max-width: none;
        height: 100%;
        margin: 0
    }

    .modal-fullscreen-md-down .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0
    }

    .modal-fullscreen-md-down .modal-footer,.modal-fullscreen-md-down .modal-header {
        border-radius: 0
    }

    .modal-fullscreen-md-down .modal-body {
        overflow-y: auto
    }
}

@media (max-width: 991.98px) {
    .modal-fullscreen-lg-down {
        width:100vw;
        max-width: none;
        height: 100%;
        margin: 0
    }

    .modal-fullscreen-lg-down .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0
    }

    .modal-fullscreen-lg-down .modal-footer,.modal-fullscreen-lg-down .modal-header {
        border-radius: 0
    }

    .modal-fullscreen-lg-down .modal-body {
        overflow-y: auto
    }
}

@media (max-width: 1199.98px) {
    .modal-fullscreen-xl-down {
        width:100vw;
        max-width: none;
        height: 100%;
        margin: 0
    }

    .modal-fullscreen-xl-down .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0
    }

    .modal-fullscreen-xl-down .modal-footer,.modal-fullscreen-xl-down .modal-header {
        border-radius: 0
    }

    .modal-fullscreen-xl-down .modal-body {
        overflow-y: auto
    }
}

@media (max-width: 1399.98px) {
    .modal-fullscreen-xxl-down {
        width:100vw;
        max-width: none;
        height: 100%;
        margin: 0
    }

    .modal-fullscreen-xxl-down .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0
    }

    .modal-fullscreen-xxl-down .modal-footer,.modal-fullscreen-xxl-down .modal-header {
        border-radius: 0
    }

    .modal-fullscreen-xxl-down .modal-body {
        overflow-y: auto
    }
}

.tooltip {
    --bs-tooltip-zindex: 1080;
    --bs-tooltip-max-width: 200px;
    --bs-tooltip-padding-x: 0.5rem;
    --bs-tooltip-padding-y: 0.25rem;
    --bs-tooltip-margin: ;
    --bs-tooltip-font-size: 0.875rem;
    --bs-tooltip-color: var(--bs-body-bg);
    --bs-tooltip-bg: var(--bs-emphasis-color);
    --bs-tooltip-border-radius: var(--bs-border-radius);
    --bs-tooltip-opacity: 0.9;
    --bs-tooltip-arrow-width: 0.8rem;
    --bs-tooltip-arrow-height: 0.4rem;
    z-index: var(--bs-tooltip-zindex);
    display: block;
    margin: var(--bs-tooltip-margin);
    font-family: var(--bs-font-sans-serif);
    font-style: normal;
    font-weight: 400;
    line-height: 1.5;
    text-align: left;
    text-align: start;
    text-decoration: none;
    text-shadow: none;
    text-transform: none;
    letter-spacing: normal;
    word-break: normal;
    white-space: normal;
    word-spacing: normal;
    line-break: auto;
    font-size: var(--bs-tooltip-font-size);
    word-wrap: break-word;
    opacity: 0
}

.tooltip.show {
    opacity: var(--bs-tooltip-opacity)
}

.tooltip .tooltip-arrow {
    display: block;
    width: var(--bs-tooltip-arrow-width);
    height: var(--bs-tooltip-arrow-height)
}

.tooltip .tooltip-arrow::before {
    position: absolute;
    content: "";
    border-color: transparent;
    border-style: solid
}

.bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow,.bs-tooltip-top .tooltip-arrow {
    bottom: calc(-1*var(--bs-tooltip-arrow-height))
}

.bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before,.bs-tooltip-top .tooltip-arrow::before {
    top: -1px;
    border-width: var(--bs-tooltip-arrow-height) calc(var(--bs-tooltip-arrow-width)*.5) 0;
    border-top-color: var(--bs-tooltip-bg)
}

.bs-tooltip-auto[data-popper-placement^=right] .tooltip-arrow,.bs-tooltip-end .tooltip-arrow {
    left: calc(-1*var(--bs-tooltip-arrow-height));
    width: var(--bs-tooltip-arrow-height);
    height: var(--bs-tooltip-arrow-width)
}

.bs-tooltip-auto[data-popper-placement^=right] .tooltip-arrow::before,.bs-tooltip-end .tooltip-arrow::before {
    right: -1px;
    border-width: calc(var(--bs-tooltip-arrow-width)*.5) var(--bs-tooltip-arrow-height) calc(var(--bs-tooltip-arrow-width)*.5) 0;
    border-right-color: var(--bs-tooltip-bg)
}

.bs-tooltip-auto[data-popper-placement^=bottom] .tooltip-arrow,.bs-tooltip-bottom .tooltip-arrow {
    top: calc(-1*var(--bs-tooltip-arrow-height))
}

.bs-tooltip-auto[data-popper-placement^=bottom] .tooltip-arrow::before,.bs-tooltip-bottom .tooltip-arrow::before {
    bottom: -1px;
    border-width: 0 calc(var(--bs-tooltip-arrow-width)*.5) var(--bs-tooltip-arrow-height);
    border-bottom-color: var(--bs-tooltip-bg)
}

.bs-tooltip-auto[data-popper-placement^=left] .tooltip-arrow,.bs-tooltip-start .tooltip-arrow {
    right: calc(-1*var(--bs-tooltip-arrow-height));
    width: var(--bs-tooltip-arrow-height);
    height: var(--bs-tooltip-arrow-width)
}

.bs-tooltip-auto[data-popper-placement^=left] .tooltip-arrow::before,.bs-tooltip-start .tooltip-arrow::before {
    left: -1px;
    border-width: calc(var(--bs-tooltip-arrow-width)*.5) 0 calc(var(--bs-tooltip-arrow-width)*.5) var(--bs-tooltip-arrow-height);
    border-left-color: var(--bs-tooltip-bg)
}

.tooltip-inner {
    max-width: var(--bs-tooltip-max-width);
    padding: var(--bs-tooltip-padding-y) var(--bs-tooltip-padding-x);
    color: var(--bs-tooltip-color);
    text-align: center;
    background-color: var(--bs-tooltip-bg);
    border-radius: var(--bs-tooltip-border-radius)
}

.popover {
    --bs-popover-zindex: 1070;
    --bs-popover-max-width: 276px;
    --bs-popover-font-size: 0.875rem;
    --bs-popover-bg: var(--bs-body-bg);
    --bs-popover-border-width: var(--bs-border-width);
    --bs-popover-border-color: var(--bs-border-color-translucent);
    --bs-popover-border-radius: var(--bs-border-radius-lg);
    --bs-popover-inner-border-radius: calc(var(--bs-border-radius-lg) - var(--bs-border-width));
    --bs-popover-box-shadow: var(--bs-box-shadow);
    --bs-popover-header-padding-x: 1rem;
    --bs-popover-header-padding-y: 0.5rem;
    --bs-popover-header-font-size: 1rem;
    --bs-popover-header-color: inherit;
    --bs-popover-header-bg: var(--bs-secondary-bg);
    --bs-popover-body-padding-x: 1rem;
    --bs-popover-body-padding-y: 1rem;
    --bs-popover-body-color: var(--bs-body-color);
    --bs-popover-arrow-width: 1rem;
    --bs-popover-arrow-height: 0.5rem;
    --bs-popover-arrow-border: var(--bs-popover-border-color);
    z-index: var(--bs-popover-zindex);
    display: block;
    max-width: var(--bs-popover-max-width);
    font-family: var(--bs-font-sans-serif);
    font-style: normal;
    font-weight: 400;
    line-height: 1.5;
    text-align: left;
    text-align: start;
    text-decoration: none;
    text-shadow: none;
    text-transform: none;
    letter-spacing: normal;
    word-break: normal;
    white-space: normal;
    word-spacing: normal;
    line-break: auto;
    font-size: var(--bs-popover-font-size);
    word-wrap: break-word;
    background-color: var(--bs-popover-bg);
    background-clip: padding-box;
    border: var(--bs-popover-border-width) solid var(--bs-popover-border-color);
    border-radius: var(--bs-popover-border-radius)
}

.popover .popover-arrow {
    display: block;
    width: var(--bs-popover-arrow-width);
    height: var(--bs-popover-arrow-height)
}

.popover .popover-arrow::after,.popover .popover-arrow::before {
    position: absolute;
    display: block;
    content: "";
    border-color: transparent;
    border-style: solid;
    border-width: 0
}

.bs-popover-auto[data-popper-placement^=top]>.popover-arrow,.bs-popover-top>.popover-arrow {
    bottom: calc(-1*(var(--bs-popover-arrow-height)) - var(--bs-popover-border-width))
}

.bs-popover-auto[data-popper-placement^=top]>.popover-arrow::after,.bs-popover-auto[data-popper-placement^=top]>.popover-arrow::before,.bs-popover-top>.popover-arrow::after,.bs-popover-top>.popover-arrow::before {
    border-width: var(--bs-popover-arrow-height) calc(var(--bs-popover-arrow-width)*.5) 0
}

.bs-popover-auto[data-popper-placement^=top]>.popover-arrow::before,.bs-popover-top>.popover-arrow::before {
    bottom: 0;
    border-top-color: var(--bs-popover-arrow-border)
}

.bs-popover-auto[data-popper-placement^=top]>.popover-arrow::after,.bs-popover-top>.popover-arrow::after {
    bottom: var(--bs-popover-border-width);
    border-top-color: var(--bs-popover-bg)
}

.bs-popover-auto[data-popper-placement^=right]>.popover-arrow,.bs-popover-end>.popover-arrow {
    left: calc(-1*(var(--bs-popover-arrow-height)) - var(--bs-popover-border-width));
    width: var(--bs-popover-arrow-height);
    height: var(--bs-popover-arrow-width)
}

.bs-popover-auto[data-popper-placement^=right]>.popover-arrow::after,.bs-popover-auto[data-popper-placement^=right]>.popover-arrow::before,.bs-popover-end>.popover-arrow::after,.bs-popover-end>.popover-arrow::before {
    border-width: calc(var(--bs-popover-arrow-width)*.5) var(--bs-popover-arrow-height) calc(var(--bs-popover-arrow-width)*.5) 0
}

.bs-popover-auto[data-popper-placement^=right]>.popover-arrow::before,.bs-popover-end>.popover-arrow::before {
    left: 0;
    border-right-color: var(--bs-popover-arrow-border)
}

.bs-popover-auto[data-popper-placement^=right]>.popover-arrow::after,.bs-popover-end>.popover-arrow::after {
    left: var(--bs-popover-border-width);
    border-right-color: var(--bs-popover-bg)
}

.bs-popover-auto[data-popper-placement^=bottom]>.popover-arrow,.bs-popover-bottom>.popover-arrow {
    top: calc(-1*(var(--bs-popover-arrow-height)) - var(--bs-popover-border-width))
}

.bs-popover-auto[data-popper-placement^=bottom]>.popover-arrow::after,.bs-popover-auto[data-popper-placement^=bottom]>.popover-arrow::before,.bs-popover-bottom>.popover-arrow::after,.bs-popover-bottom>.popover-arrow::before {
    border-width: 0 calc(var(--bs-popover-arrow-width)*.5) var(--bs-popover-arrow-height)
}

.bs-popover-auto[data-popper-placement^=bottom]>.popover-arrow::before,.bs-popover-bottom>.popover-arrow::before {
    top: 0;
    border-bottom-color: var(--bs-popover-arrow-border)
}

.bs-popover-auto[data-popper-placement^=bottom]>.popover-arrow::after,.bs-popover-bottom>.popover-arrow::after {
    top: var(--bs-popover-border-width);
    border-bottom-color: var(--bs-popover-bg)
}

.bs-popover-auto[data-popper-placement^=bottom] .popover-header::before,.bs-popover-bottom .popover-header::before {
    position: absolute;
    top: 0;
    left: 50%;
    display: block;
    width: var(--bs-popover-arrow-width);
    margin-left: calc(-.5*var(--bs-popover-arrow-width));
    content: "";
    border-bottom: var(--bs-popover-border-width) solid var(--bs-popover-header-bg)
}

.bs-popover-auto[data-popper-placement^=left]>.popover-arrow,.bs-popover-start>.popover-arrow {
    right: calc(-1*(var(--bs-popover-arrow-height)) - var(--bs-popover-border-width));
    width: var(--bs-popover-arrow-height);
    height: var(--bs-popover-arrow-width)
}

.bs-popover-auto[data-popper-placement^=left]>.popover-arrow::after,.bs-popover-auto[data-popper-placement^=left]>.popover-arrow::before,.bs-popover-start>.popover-arrow::after,.bs-popover-start>.popover-arrow::before {
    border-width: calc(var(--bs-popover-arrow-width)*.5) 0 calc(var(--bs-popover-arrow-width)*.5) var(--bs-popover-arrow-height)
}

.bs-popover-auto[data-popper-placement^=left]>.popover-arrow::before,.bs-popover-start>.popover-arrow::before {
    right: 0;
    border-left-color: var(--bs-popover-arrow-border)
}

.bs-popover-auto[data-popper-placement^=left]>.popover-arrow::after,.bs-popover-start>.popover-arrow::after {
    right: var(--bs-popover-border-width);
    border-left-color: var(--bs-popover-bg)
}

.popover-header {
    padding: var(--bs-popover-header-padding-y) var(--bs-popover-header-padding-x);
    margin-bottom: 0;
    font-size: var(--bs-popover-header-font-size);
    color: var(--bs-popover-header-color);
    background-color: var(--bs-popover-header-bg);
    border-bottom: var(--bs-popover-border-width) solid var(--bs-popover-border-color);
    border-top-left-radius: var(--bs-popover-inner-border-radius);
    border-top-right-radius: var(--bs-popover-inner-border-radius)
}

.popover-header:empty {
    display: none
}

.popover-body {
    padding: var(--bs-popover-body-padding-y) var(--bs-popover-body-padding-x);
    color: var(--bs-popover-body-color)
}

.carousel,.carousel-inner {
    position: relative
}

.carousel.pointer-event {
    -ms-touch-action: pan-y;
    touch-action: pan-y
}

.carousel-inner {
    width: 100%;
    overflow: hidden
}

.carousel-inner::after,.clearfix::after {
    display: block;
    clear: both;
    content: ""
}

.carousel-item {
    position: relative;
    display: none;
    float: left;
    width: 100%;
    margin-right: -100%;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -webkit-transition: -webkit-transform .6s ease-in-out;
    transition: transform .6s ease-in-out;
    transition: transform .6s ease-in-out,-webkit-transform .6s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .carousel-item {
        -webkit-transition: none;
        transition: none
    }
}

.carousel-item-next,.carousel-item-prev,.carousel-item.active,.slick-slide img {
    display: block
}

.active.carousel-item-end,.carousel-item-next:not(.carousel-item-start) {
    -webkit-transform: translateX(100%);
    transform: translateX(100%)
}

.active.carousel-item-start,.carousel-item-prev:not(.carousel-item-end) {
    -webkit-transform: translateX(-100%);
    transform: translateX(-100%)
}

.carousel-fade .carousel-item {
    opacity: 0;
    -webkit-transition-property: opacity;
    transition-property: opacity;
    -webkit-transform: none;
    transform: none
}

.carousel-fade .carousel-item-next.carousel-item-start,.carousel-fade .carousel-item-prev.carousel-item-end,.carousel-fade .carousel-item.active {
    z-index: 1;
    opacity: 1
}

.carousel-fade .active.carousel-item-end,.carousel-fade .active.carousel-item-start {
    z-index: 0;
    opacity: 0;
    -webkit-transition: opacity 0s .6s;
    transition: opacity 0s .6s
}

@media (prefers-reduced-motion:reduce) {
    .carousel-fade .active.carousel-item-end,.carousel-fade .active.carousel-item-start {
        -webkit-transition: none;
        transition: none
    }
}

.carousel-control-next,.carousel-control-prev {
    position: absolute;
    top: 0;
    bottom: 0;
    z-index: 1;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 15%;
    padding: 0;
    color: #fff;
    text-align: center;
    background: 0 0;
    border: 0;
    opacity: .5;
    -webkit-transition: opacity .15s ease;
    transition: opacity .15s ease
}

@media (prefers-reduced-motion:reduce) {
    .carousel-control-next,.carousel-control-prev {
        -webkit-transition: none;
        transition: none
    }
}

.carousel-control-next:focus,.carousel-control-next:hover,.carousel-control-prev:focus,.carousel-control-prev:hover {
    color: #fff;
    text-decoration: none;
    outline: 0;
    opacity: .9
}

.carousel-control-prev {
    left: 0
}

.carousel-control-next {
    right: 0
}

.carousel-control-next-icon,.carousel-control-prev-icon {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    background-repeat: no-repeat;
    background-position: 50%;
    background-size: 100% 100%
}

.carousel-control-prev-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e")
}

.carousel-control-next-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e")
}

.carousel-indicators {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 2;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 0;
    margin-right: 15%;
    margin-bottom: 1rem;
    margin-left: 15%
}

.carousel-indicators [data-bs-target] {
    -webkit-box-sizing: content-box;
    box-sizing: content-box;
    -webkit-box-flex: 0;
    -ms-flex: 0 1 auto;
    flex: 0 1 auto;
    width: 30px;
    height: 3px;
    padding: 0;
    margin-right: 3px;
    margin-left: 3px;
    text-indent: -999px;
    cursor: pointer;
    background-color: #fff;
    background-clip: padding-box;
    border: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    opacity: .5;
    -webkit-transition: opacity .6s ease;
    transition: opacity .6s ease
}

@media (prefers-reduced-motion:reduce) {
    .carousel-indicators [data-bs-target] {
        -webkit-transition: none;
        transition: none
    }
}

.carousel-indicators .active {
    opacity: 1
}

.carousel-caption {
    position: absolute;
    right: 15%;
    bottom: 1.25rem;
    left: 15%;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
    color: #fff;
    text-align: center
}

.carousel-dark .carousel-indicators [data-bs-target],[data-bs-theme=dark] .carousel .carousel-indicators [data-bs-target],[data-bs-theme=dark].carousel .carousel-indicators [data-bs-target] {
    background-color: #000
}

.carousel-dark .carousel-control-next-icon,.carousel-dark .carousel-control-prev-icon,[data-bs-theme=dark] .carousel .carousel-control-next-icon,[data-bs-theme=dark] .carousel .carousel-control-prev-icon,[data-bs-theme=dark].carousel .carousel-control-next-icon,[data-bs-theme=dark].carousel .carousel-control-prev-icon {
    -webkit-filter: invert(1) grayscale(100);
    filter: invert(1) grayscale(100)
}

.carousel-dark .carousel-caption,[data-bs-theme=dark] .carousel .carousel-caption,[data-bs-theme=dark].carousel .carousel-caption {
    color: #000
}

.spinner-border,.spinner-grow {
    display: inline-block;
    width: var(--bs-spinner-width);
    height: var(--bs-spinner-height);
    vertical-align: var(--bs-spinner-vertical-align);
    border-radius: 50%;
    -webkit-animation: var(--bs-spinner-animation-speed) linear infinite var(--bs-spinner-animation-name);
    animation: var(--bs-spinner-animation-speed) linear infinite var(--bs-spinner-animation-name)
}

.spinner-border {
    --bs-spinner-width: 2rem;
    --bs-spinner-height: 2rem;
    --bs-spinner-vertical-align: -0.125em;
    --bs-spinner-border-width: 0.25em;
    --bs-spinner-animation-speed: 0.75s;
    --bs-spinner-animation-name: spinner-border;
    border: var(--bs-spinner-border-width) solid currentcolor;
    border-right-color: transparent
}

.spinner-border-sm {
    --bs-spinner-width: 1rem;
    --bs-spinner-height: 1rem;
    --bs-spinner-border-width: 0.2em
}

.spinner-grow {
    --bs-spinner-width: 2rem;
    --bs-spinner-height: 2rem;
    --bs-spinner-vertical-align: -0.125em;
    --bs-spinner-animation-speed: 0.75s;
    --bs-spinner-animation-name: spinner-grow;
    background-color: currentcolor;
    opacity: 0
}

.spinner-grow-sm {
    --bs-spinner-width: 1rem;
    --bs-spinner-height: 1rem
}

@media (prefers-reduced-motion:reduce) {
    .spinner-border,.spinner-grow {
        --bs-spinner-animation-speed: 1.5s
    }
}

.offcanvas,.offcanvas-lg,.offcanvas-md,.offcanvas-sm,.offcanvas-xl,.offcanvas-xxl {
    --bs-offcanvas-zindex: 1045;
    --bs-offcanvas-width: 400px;
    --bs-offcanvas-height: 30vh;
    --bs-offcanvas-padding-x: 1rem;
    --bs-offcanvas-padding-y: 1rem;
    --bs-offcanvas-color: var(--bs-body-color);
    --bs-offcanvas-bg: var(--bs-body-bg);
    --bs-offcanvas-border-width: var(--bs-border-width);
    --bs-offcanvas-border-color: var(--bs-border-color-translucent);
    --bs-offcanvas-box-shadow: var(--bs-box-shadow-sm);
    --bs-offcanvas-transition: transform 0.3s ease-in-out;
    --bs-offcanvas-title-line-height: 1.5
}

@media (max-width: 575.98px) {
    .offcanvas-sm {
        position:fixed;
        bottom: 0;
        z-index: var(--bs-offcanvas-zindex);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        max-width: 100%;
        color: var(--bs-offcanvas-color);
        visibility: hidden;
        background-color: var(--bs-offcanvas-bg);
        background-clip: padding-box;
        outline: 0;
        -webkit-transition: var(--bs-offcanvas-transition);
        transition: var(--bs-offcanvas-transition)
    }
}

@media (max-width: 575.98px) and (prefers-reduced-motion:reduce) {
    .offcanvas-sm {
        -webkit-transition:none;
        transition: none
    }
}

@media (max-width: 575.98px) {
    .offcanvas-sm.offcanvas-start {
        top:0;
        left: 0;
        width: var(--bs-offcanvas-width);
        border-right: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%)
    }

    .offcanvas-sm.offcanvas-end {
        top: 0;
        right: 0;
        width: var(--bs-offcanvas-width);
        border-left: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(100%);
        transform: translateX(100%)
    }

    .offcanvas-sm.offcanvas-bottom,.offcanvas-sm.offcanvas-top {
        right: 0;
        left: 0;
        height: var(--bs-offcanvas-height);
        max-height: 100%
    }

    .offcanvas-sm.offcanvas-top {
        top: 0;
        border-bottom: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%)
    }

    .offcanvas-sm.offcanvas-bottom {
        border-top: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(100%);
        transform: translateY(100%)
    }

    .offcanvas-sm.show:not(.hiding),.offcanvas-sm.showing {
        -webkit-transform: none;
        transform: none
    }

    .offcanvas-sm.hiding,.offcanvas-sm.show,.offcanvas-sm.showing {
        visibility: visible
    }
}

@media (min-width: 576px) {
    .offcanvas-sm {
        --bs-offcanvas-height:auto;
        --bs-offcanvas-border-width: 0;
        background-color: transparent!important
    }

    .offcanvas-sm .offcanvas-header {
        display: none
    }

    .offcanvas-sm .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible;
        background-color: transparent!important
    }
}

@media (max-width: 767.98px) {
    .offcanvas-md {
        position:fixed;
        bottom: 0;
        z-index: var(--bs-offcanvas-zindex);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        max-width: 100%;
        color: var(--bs-offcanvas-color);
        visibility: hidden;
        background-color: var(--bs-offcanvas-bg);
        background-clip: padding-box;
        outline: 0;
        -webkit-transition: var(--bs-offcanvas-transition);
        transition: var(--bs-offcanvas-transition)
    }
}

@media (max-width: 767.98px) and (prefers-reduced-motion:reduce) {
    .offcanvas-md {
        -webkit-transition:none;
        transition: none
    }
}

@media (max-width: 767.98px) {
    .offcanvas-md.offcanvas-start {
        top:0;
        left: 0;
        width: var(--bs-offcanvas-width);
        border-right: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%)
    }

    .offcanvas-md.offcanvas-end {
        top: 0;
        right: 0;
        width: var(--bs-offcanvas-width);
        border-left: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(100%);
        transform: translateX(100%)
    }

    .offcanvas-md.offcanvas-bottom,.offcanvas-md.offcanvas-top {
        right: 0;
        left: 0;
        height: var(--bs-offcanvas-height);
        max-height: 100%
    }

    .offcanvas-md.offcanvas-top {
        top: 0;
        border-bottom: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%)
    }

    .offcanvas-md.offcanvas-bottom {
        border-top: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(100%);
        transform: translateY(100%)
    }

    .offcanvas-md.show:not(.hiding),.offcanvas-md.showing {
        -webkit-transform: none;
        transform: none
    }

    .offcanvas-md.hiding,.offcanvas-md.show,.offcanvas-md.showing {
        visibility: visible
    }
}

@media (min-width: 768px) {
    .offcanvas-md {
        --bs-offcanvas-height:auto;
        --bs-offcanvas-border-width: 0;
        background-color: transparent!important
    }

    .offcanvas-md .offcanvas-header {
        display: none
    }

    .offcanvas-md .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible;
        background-color: transparent!important
    }
}

@media (max-width: 991.98px) {
    .offcanvas-lg {
        position:fixed;
        bottom: 0;
        z-index: var(--bs-offcanvas-zindex);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        max-width: 100%;
        color: var(--bs-offcanvas-color);
        visibility: hidden;
        background-color: var(--bs-offcanvas-bg);
        background-clip: padding-box;
        outline: 0;
        -webkit-transition: var(--bs-offcanvas-transition);
        transition: var(--bs-offcanvas-transition)
    }
}

@media (max-width: 991.98px) and (prefers-reduced-motion:reduce) {
    .offcanvas-lg {
        -webkit-transition:none;
        transition: none
    }
}

@media (max-width: 991.98px) {
    .offcanvas-lg.offcanvas-start {
        top:0;
        left: 0;
        width: var(--bs-offcanvas-width);
        border-right: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%)
    }

    .offcanvas-lg.offcanvas-end {
        top: 0;
        right: 0;
        width: var(--bs-offcanvas-width);
        border-left: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(100%);
        transform: translateX(100%)
    }

    .offcanvas-lg.offcanvas-bottom,.offcanvas-lg.offcanvas-top {
        right: 0;
        left: 0;
        height: var(--bs-offcanvas-height);
        max-height: 100%
    }

    .offcanvas-lg.offcanvas-top {
        top: 0;
        border-bottom: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%)
    }

    .offcanvas-lg.offcanvas-bottom {
        border-top: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(100%);
        transform: translateY(100%)
    }

    .offcanvas-lg.show:not(.hiding),.offcanvas-lg.showing {
        -webkit-transform: none;
        transform: none
    }

    .offcanvas-lg.hiding,.offcanvas-lg.show,.offcanvas-lg.showing {
        visibility: visible
    }
}

@media (min-width: 992px) {
    .offcanvas-lg {
        --bs-offcanvas-height:auto;
        --bs-offcanvas-border-width: 0;
        background-color: transparent!important
    }

    .offcanvas-lg .offcanvas-header {
        display: none
    }

    .offcanvas-lg .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible;
        background-color: transparent!important
    }
}

@media (max-width: 1199.98px) {
    .offcanvas-xl {
        position:fixed;
        bottom: 0;
        z-index: var(--bs-offcanvas-zindex);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        max-width: 100%;
        color: var(--bs-offcanvas-color);
        visibility: hidden;
        background-color: var(--bs-offcanvas-bg);
        background-clip: padding-box;
        outline: 0;
        -webkit-transition: var(--bs-offcanvas-transition);
        transition: var(--bs-offcanvas-transition)
    }
}

@media (max-width: 1199.98px) and (prefers-reduced-motion:reduce) {
    .offcanvas-xl {
        -webkit-transition:none;
        transition: none
    }
}

@media (max-width: 1199.98px) {
    .offcanvas-xl.offcanvas-start {
        top:0;
        left: 0;
        width: var(--bs-offcanvas-width);
        border-right: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%)
    }

    .offcanvas-xl.offcanvas-end {
        top: 0;
        right: 0;
        width: var(--bs-offcanvas-width);
        border-left: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(100%);
        transform: translateX(100%)
    }

    .offcanvas-xl.offcanvas-bottom,.offcanvas-xl.offcanvas-top {
        right: 0;
        left: 0;
        height: var(--bs-offcanvas-height);
        max-height: 100%
    }

    .offcanvas-xl.offcanvas-top {
        top: 0;
        border-bottom: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%)
    }

    .offcanvas-xl.offcanvas-bottom {
        border-top: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(100%);
        transform: translateY(100%)
    }

    .offcanvas-xl.show:not(.hiding),.offcanvas-xl.showing {
        -webkit-transform: none;
        transform: none
    }

    .offcanvas-xl.hiding,.offcanvas-xl.show,.offcanvas-xl.showing {
        visibility: visible
    }
}

@media (min-width: 1200px) {
    .offcanvas-xl {
        --bs-offcanvas-height:auto;
        --bs-offcanvas-border-width: 0;
        background-color: transparent!important
    }

    .offcanvas-xl .offcanvas-header {
        display: none
    }

    .offcanvas-xl .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible;
        background-color: transparent!important
    }
}

@media (max-width: 1399.98px) {
    .offcanvas-xxl {
        position:fixed;
        bottom: 0;
        z-index: var(--bs-offcanvas-zindex);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        max-width: 100%;
        color: var(--bs-offcanvas-color);
        visibility: hidden;
        background-color: var(--bs-offcanvas-bg);
        background-clip: padding-box;
        outline: 0;
        -webkit-transition: var(--bs-offcanvas-transition);
        transition: var(--bs-offcanvas-transition)
    }
}

@media (max-width: 1399.98px) and (prefers-reduced-motion:reduce) {
    .offcanvas-xxl {
        -webkit-transition:none;
        transition: none
    }
}

@media (max-width: 1399.98px) {
    .offcanvas-xxl.offcanvas-start {
        top:0;
        left: 0;
        width: var(--bs-offcanvas-width);
        border-right: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%)
    }

    .offcanvas-xxl.offcanvas-end {
        top: 0;
        right: 0;
        width: var(--bs-offcanvas-width);
        border-left: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateX(100%);
        transform: translateX(100%)
    }

    .offcanvas-xxl.offcanvas-bottom,.offcanvas-xxl.offcanvas-top {
        right: 0;
        left: 0;
        height: var(--bs-offcanvas-height);
        max-height: 100%
    }

    .offcanvas-xxl.offcanvas-top {
        top: 0;
        border-bottom: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(-100%);
        transform: translateY(-100%)
    }

    .offcanvas-xxl.offcanvas-bottom {
        border-top: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
        -webkit-transform: translateY(100%);
        transform: translateY(100%)
    }

    .offcanvas-xxl.show:not(.hiding),.offcanvas-xxl.showing {
        -webkit-transform: none;
        transform: none
    }

    .offcanvas-xxl.hiding,.offcanvas-xxl.show,.offcanvas-xxl.showing {
        visibility: visible
    }
}

@media (min-width: 1400px) {
    .offcanvas-xxl {
        --bs-offcanvas-height:auto;
        --bs-offcanvas-border-width: 0;
        background-color: transparent!important
    }

    .offcanvas-xxl .offcanvas-header {
        display: none
    }

    .offcanvas-xxl .offcanvas-body {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        padding: 0;
        overflow-y: visible;
        background-color: transparent!important
    }
}

.offcanvas {
    position: fixed;
    bottom: 0;
    z-index: var(--bs-offcanvas-zindex);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    max-width: 100%;
    color: var(--bs-offcanvas-color);
    visibility: hidden;
    background-color: var(--bs-offcanvas-bg);
    background-clip: padding-box;
    outline: 0;
    -webkit-transition: var(--bs-offcanvas-transition);
    transition: var(--bs-offcanvas-transition)
}

@media (prefers-reduced-motion:reduce) {
    .offcanvas {
        -webkit-transition: none;
        transition: none
    }
}

.offcanvas.offcanvas-start {
    top: 0;
    left: 0;
    width: var(--bs-offcanvas-width);
    border-right: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
    -webkit-transform: translateX(-100%);
    transform: translateX(-100%)
}

.offcanvas.offcanvas-end {
    top: 0;
    right: 0;
    width: var(--bs-offcanvas-width);
    border-left: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
    -webkit-transform: translateX(100%);
    transform: translateX(100%)
}

.offcanvas.offcanvas-bottom,.offcanvas.offcanvas-top {
    right: 0;
    left: 0;
    height: var(--bs-offcanvas-height);
    max-height: 100%
}

.offcanvas.offcanvas-top {
    top: 0;
    border-bottom: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
    -webkit-transform: translateY(-100%);
    transform: translateY(-100%)
}

.offcanvas.offcanvas-bottom {
    border-top: var(--bs-offcanvas-border-width) solid var(--bs-offcanvas-border-color);
    -webkit-transform: translateY(100%);
    transform: translateY(100%)
}

.offcanvas.show:not(.hiding),.offcanvas.showing {
    -webkit-transform: none;
    transform: none
}

.offcanvas.hiding,.offcanvas.show,.offcanvas.showing {
    visibility: visible
}

.offcanvas-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000
}

.offcanvas-backdrop.fade {
    opacity: 0
}

.offcanvas-backdrop.show {
    opacity: .5
}

.offcanvas-header {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: var(--bs-offcanvas-padding-y) var(--bs-offcanvas-padding-x)
}

.offcanvas-header .btn-close {
    padding: calc(var(--bs-offcanvas-padding-y)*.5) calc(var(--bs-offcanvas-padding-x)*.5);
    margin: calc(-.5*var(--bs-offcanvas-padding-y)) calc(-.5*var(--bs-offcanvas-padding-x)) calc(-.5*var(--bs-offcanvas-padding-y)) auto
}

.offcanvas-title {
    margin-bottom: 0;
    line-height: var(--bs-offcanvas-title-line-height)
}

.offcanvas-body {
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    padding: var(--bs-offcanvas-padding-y) var(--bs-offcanvas-padding-x);
    overflow-y: auto
}

.placeholder {
    display: inline-block;
    min-height: 1em;
    vertical-align: middle;
    cursor: wait;
    background-color: currentcolor;
    opacity: .5
}

.placeholder.btn::before {
    display: inline-block;
    content: ""
}

.placeholder-xs {
    min-height: .6em
}

.placeholder-sm {
    min-height: .8em
}

.placeholder-lg {
    min-height: 1.2em
}

.placeholder-glow .placeholder {
    -webkit-animation: placeholder-glow 2s ease-in-out infinite;
    animation: placeholder-glow 2s ease-in-out infinite
}

.placeholder-wave {
    -webkit-mask-image: linear-gradient(130deg,#000 55%,rgba(0,0,0,.8) 75%,#000 95%);
    mask-image: linear-gradient(130deg,#000 55%,rgba(0,0,0,.8) 75%,#000 95%);
    -webkit-mask-size: 200% 100%;
    mask-size: 200% 100%;
    -webkit-animation: placeholder-wave 2s linear infinite;
    animation: placeholder-wave 2s linear infinite
}

.text-bg-primary {
    color: #fff!important;
    background-color: RGBA(var(--bs-primary-rgb),var(--bs-bg-opacity, 1))!important
}

.text-bg-secondary {
    color: #fff!important;
    background-color: RGBA(var(--bs-secondary-rgb),var(--bs-bg-opacity, 1))!important
}

.text-bg-success {
    color: #fff!important;
    background-color: RGBA(var(--bs-success-rgb),var(--bs-bg-opacity, 1))!important
}

.text-bg-info {
    color: #000!important;
    background-color: RGBA(var(--bs-info-rgb),var(--bs-bg-opacity, 1))!important
}

.text-bg-warning {
    color: #000!important;
    background-color: RGBA(var(--bs-warning-rgb),var(--bs-bg-opacity, 1))!important
}

.text-bg-danger {
    color: #fff!important;
    background-color: RGBA(var(--bs-danger-rgb),var(--bs-bg-opacity, 1))!important
}

.text-bg-light {
    color: #000!important;
    background-color: RGBA(var(--bs-light-rgb),var(--bs-bg-opacity, 1))!important
}

.text-bg-dark {
    color: #fff!important;
    background-color: RGBA(var(--bs-dark-rgb),var(--bs-bg-opacity, 1))!important
}

.link-primary {
    color: RGBA(var(--bs-primary-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-primary-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-primary:focus,.link-primary:hover {
    color: RGBA(10,88,202,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(10,88,202,var(--bs-link-underline-opacity, 1))!important
}

.link-secondary {
    color: RGBA(var(--bs-secondary-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-secondary-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-secondary:focus,.link-secondary:hover {
    color: RGBA(86,94,100,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(86,94,100,var(--bs-link-underline-opacity, 1))!important
}

.link-success {
    color: RGBA(var(--bs-success-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-success-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-success:focus,.link-success:hover {
    color: RGBA(20,108,67,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(20,108,67,var(--bs-link-underline-opacity, 1))!important
}

.link-info {
    color: RGBA(var(--bs-info-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-info-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-info:focus,.link-info:hover {
    color: RGBA(61,213,243,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(61,213,243,var(--bs-link-underline-opacity, 1))!important
}

.link-warning {
    color: RGBA(var(--bs-warning-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-warning-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-warning:focus,.link-warning:hover {
    color: RGBA(255,205,57,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(255,205,57,var(--bs-link-underline-opacity, 1))!important
}

.link-danger {
    color: RGBA(var(--bs-danger-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-danger-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-danger:focus,.link-danger:hover {
    color: RGBA(176,42,55,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(176,42,55,var(--bs-link-underline-opacity, 1))!important
}

.link-light {
    color: RGBA(var(--bs-light-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-light-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-light:focus,.link-light:hover {
    color: RGBA(249,250,251,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(249,250,251,var(--bs-link-underline-opacity, 1))!important
}

.link-dark {
    color: RGBA(var(--bs-dark-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-dark-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-dark:focus,.link-dark:hover {
    color: RGBA(26,30,33,var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(26,30,33,var(--bs-link-underline-opacity, 1))!important
}

.link-body-emphasis {
    color: RGBA(var(--bs-emphasis-color-rgb),var(--bs-link-opacity, 1))!important;
    text-decoration-color: RGBA(var(--bs-emphasis-color-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-body-emphasis:focus,.link-body-emphasis:hover {
    color: RGBA(var(--bs-emphasis-color-rgb),var(--bs-link-opacity, 0.75))!important;
    text-decoration-color: RGBA(var(--bs-emphasis-color-rgb),var(--bs-link-underline-opacity, 0.75))!important
}

.focus-ring:focus {
    outline: 0;
    -webkit-box-shadow: var(--bs-focus-ring-x, 0) var(--bs-focus-ring-y, 0) var(--bs-focus-ring-blur, 0) var(--bs-focus-ring-width) var(--bs-focus-ring-color);
    box-shadow: var(--bs-focus-ring-x, 0) var(--bs-focus-ring-y, 0) var(--bs-focus-ring-blur, 0) var(--bs-focus-ring-width) var(--bs-focus-ring-color)
}

.icon-link {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    gap: .375rem;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    text-decoration-color: rgba(var(--bs-link-color-rgb),var(--bs-link-opacity, 0.5));
    text-underline-offset: .25em;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden
}

.icon-link>.bi {
    -ms-flex-negative: 0;
    flex-shrink: 0;
    width: 1em;
    height: 1em;
    fill: currentcolor;
    -webkit-transition: .2s ease-in-out transform;
    transition: .2s ease-in-out transform
}

@media (prefers-reduced-motion:reduce) {
    .icon-link>.bi {
        -webkit-transition: none;
        transition: none
    }
}

.icon-link-hover:focus-visible>.bi,.icon-link-hover:hover>.bi {
    -webkit-transform: var(--bs-icon-link-transform, translate3d(0.25em, 0, 0));
    transform: var(--bs-icon-link-transform, translate3d(0.25em, 0, 0))
}

.ratio,.ratio>* {
    position: relative;
    width: 100%
}

.ratio::before {
    display: block;
    padding-top: var(--bs-aspect-ratio);
    content: ""
}

.ratio>* {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%
}

.ratio-1x1 {
    --bs-aspect-ratio: 100%
}

.ratio-4x3 {
    --bs-aspect-ratio: 75%
}

.ratio-16x9 {
    --bs-aspect-ratio: 56.25%
}

.ratio-21x9 {
    --bs-aspect-ratio: 42.8571428571%
}

.fixed-bottom,.fixed-top {
    position: fixed;
    right: 0;
    left: 0;
    z-index: 1030
}

.fixed-top {
    top: 0
}

.fixed-bottom {
    bottom: 0
}

.sticky-bottom,.sticky-top {
    position: sticky;
    z-index: 1020
}

.sticky-top {
    top: 0
}

.sticky-bottom {
    bottom: 0
}

@media (min-width: 576px) {
    .sticky-sm-top {
        position:sticky;
        top: 0;
        z-index: 1020
    }

    .sticky-sm-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1020
    }
}

@media (min-width: 768px) {
    .sticky-md-top {
        position:sticky;
        top: 0;
        z-index: 1020
    }

    .sticky-md-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1020
    }
}

@media (min-width: 992px) {
    .sticky-lg-top {
        position:sticky;
        top: 0;
        z-index: 1020
    }

    .sticky-lg-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1020
    }
}

@media (min-width: 1200px) {
    .sticky-xl-top {
        position:sticky;
        top: 0;
        z-index: 1020
    }

    .sticky-xl-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1020
    }
}

@media (min-width: 1400px) {
    .sticky-xxl-top {
        position:sticky;
        top: 0;
        z-index: 1020
    }

    .sticky-xxl-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1020
    }
}

.hstack,.vstack {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-direction: normal;
    -ms-flex-item-align: stretch;
    align-self: stretch
}

.hstack {
    -webkit-box-orient: horizontal;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center
}

.vstack {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    -webkit-box-orient: vertical;
    -ms-flex-direction: column;
    flex-direction: column
}

.visually-hidden,.visually-hidden-focusable:not(:focus):not(:focus-within) {
    width: 1px!important;
    height: 1px!important;
    padding: 0!important;
    margin: -1px!important;
    overflow: hidden!important;
    clip: rect(0,0,0,0)!important;
    white-space: nowrap!important;
    border: 0!important
}

.visually-hidden-focusable:not(:focus):not(:focus-within):not(caption),.visually-hidden:not(caption) {
    position: absolute!important
}

.stretched-link::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    content: ""
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap
}

.vr {
    display: inline-block;
    -ms-flex-item-align: stretch;
    align-self: stretch;
    width: var(--bs-border-width);
    min-height: 1em;
    background-color: currentcolor;
    opacity: .25
}

.align-baseline {
    vertical-align: baseline!important
}

.align-top {
    vertical-align: top!important
}

.align-middle {
    vertical-align: middle!important
}

.align-bottom {
    vertical-align: bottom!important
}

.align-text-bottom {
    vertical-align: text-bottom!important
}

.align-text-top {
    vertical-align: text-top!important
}

.float-start {
    float: left!important
}

.float-end {
    float: right!important
}

.float-none {
    float: none!important
}

.object-fit-contain {
    -o-object-fit: contain!important;
    object-fit: contain!important
}

.object-fit-cover {
    -o-object-fit: cover!important;
    object-fit: cover!important
}

.object-fit-fill {
    -o-object-fit: fill!important;
    object-fit: fill!important
}

.object-fit-scale {
    -o-object-fit: scale-down!important;
    object-fit: scale-down!important
}

.object-fit-none {
    -o-object-fit: none!important;
    object-fit: none!important
}

.opacity-0 {
    opacity: 0!important
}

.opacity-25 {
    opacity: .25!important
}

.opacity-50 {
    opacity: .5!important
}

.opacity-75 {
    opacity: .75!important
}

.opacity-100 {
    opacity: 1!important
}

.overflow-auto {
    overflow: auto!important
}

.overflow-hidden {
    overflow: hidden!important
}

.overflow-visible {
    overflow: visible!important
}

.overflow-scroll {
    overflow: scroll!important
}

.overflow-x-auto {
    overflow-x: auto!important
}

.overflow-x-hidden {
    overflow-x: hidden!important
}

.overflow-x-visible {
    overflow-x: visible!important
}

.overflow-x-scroll {
    overflow-x: scroll!important
}

.overflow-y-auto {
    overflow-y: auto!important
}

.overflow-y-hidden {
    overflow-y: hidden!important
}

.overflow-y-visible {
    overflow-y: visible!important
}

.overflow-y-scroll {
    overflow-y: scroll!important
}

.d-inline {
    display: inline!important
}

.d-inline-block {
    display: inline-block!important
}

.d-block {
    display: block!important
}

.d-grid {
    display: grid!important
}

.d-inline-grid {
    display: inline-grid!important
}

.d-table {
    display: table!important
}

.d-table-row {
    display: table-row!important
}

.d-table-cell {
    display: table-cell!important
}

.d-flex {
    display: -webkit-box!important;
    display: -ms-flexbox!important;
    display: flex!important
}

.d-inline-flex {
    display: -webkit-inline-box!important;
    display: -ms-inline-flexbox!important;
    display: inline-flex!important
}

.d-none {
    display: none!important
}

.shadow {
    -webkit-box-shadow: var(--bs-box-shadow)!important;
    box-shadow: var(--bs-box-shadow)!important
}

.shadow-sm {
    -webkit-box-shadow: var(--bs-box-shadow-sm)!important;
    box-shadow: var(--bs-box-shadow-sm)!important
}

.shadow-lg {
    -webkit-box-shadow: var(--bs-box-shadow-lg)!important;
    box-shadow: var(--bs-box-shadow-lg)!important
}

.shadow-none {
    -webkit-box-shadow: none!important;
    box-shadow: none!important
}

.focus-ring-primary {
    --bs-focus-ring-color: rgba(var(--bs-primary-rgb), var(--bs-focus-ring-opacity))
}

.focus-ring-secondary {
    --bs-focus-ring-color: rgba(var(--bs-secondary-rgb), var(--bs-focus-ring-opacity))
}

.focus-ring-success {
    --bs-focus-ring-color: rgba(var(--bs-success-rgb), var(--bs-focus-ring-opacity))
}

.focus-ring-info {
    --bs-focus-ring-color: rgba(var(--bs-info-rgb), var(--bs-focus-ring-opacity))
}

.focus-ring-warning {
    --bs-focus-ring-color: rgba(var(--bs-warning-rgb), var(--bs-focus-ring-opacity))
}

.focus-ring-danger {
    --bs-focus-ring-color: rgba(var(--bs-danger-rgb), var(--bs-focus-ring-opacity))
}

.focus-ring-light {
    --bs-focus-ring-color: rgba(var(--bs-light-rgb), var(--bs-focus-ring-opacity))
}

.focus-ring-dark {
    --bs-focus-ring-color: rgba(var(--bs-dark-rgb), var(--bs-focus-ring-opacity))
}

.position-static {
    position: static!important
}

.position-relative {
    position: relative!important
}

.position-absolute {
    position: absolute!important
}

.position-fixed {
    position: fixed!important
}

.position-sticky {
    position: sticky!important
}

.top-0 {
    top: 0!important
}

.top-50 {
    top: 50%!important
}

.top-100 {
    top: 100%!important
}

.bottom-0 {
    bottom: 0!important
}

.bottom-50 {
    bottom: 50%!important
}

.bottom-100 {
    bottom: 100%!important
}

.start-0 {
    left: 0!important
}

.start-50 {
    left: 50%!important
}

.start-100 {
    left: 100%!important
}

.end-0 {
    right: 0!important
}

.end-50 {
    right: 50%!important
}

.end-100 {
    right: 100%!important
}

.translate-middle {
    -webkit-transform: translate(-50%,-50%)!important;
    transform: translate(-50%,-50%)!important
}

.translate-middle-x {
    -webkit-transform: translateX(-50%)!important;
    transform: translateX(-50%)!important
}

.translate-middle-y {
    -webkit-transform: translateY(-50%)!important;
    transform: translateY(-50%)!important
}

.border {
    border: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color)!important
}

.border-0 {
    border: 0!important
}

.border-top {
    border-top: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color)!important
}

.border-top-0 {
    border-top: 0!important
}

.border-end {
    border-right: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color)!important
}

.border-end-0 {
    border-right: 0!important
}

.border-bottom {
    border-bottom: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color)!important
}

.border-bottom-0 {
    border-bottom: 0!important
}

.border-start {
    border-left: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color)!important
}

.border-start-0 {
    border-left: 0!important
}

.border-primary,.border-secondary {
    --bs-border-opacity: 1;
    border-color: rgba(var(--bs-primary-rgb),var(--bs-border-opacity))!important
}

.border-secondary {
    border-color: rgba(var(--bs-secondary-rgb),var(--bs-border-opacity))!important
}

.border-info,.border-success {
    --bs-border-opacity: 1;
    border-color: rgba(var(--bs-success-rgb),var(--bs-border-opacity))!important
}

.border-info {
    border-color: rgba(var(--bs-info-rgb),var(--bs-border-opacity))!important
}

.border-danger,.border-warning {
    --bs-border-opacity: 1;
    border-color: rgba(var(--bs-warning-rgb),var(--bs-border-opacity))!important
}

.border-danger {
    border-color: rgba(var(--bs-danger-rgb),var(--bs-border-opacity))!important
}

.border-dark,.border-light {
    --bs-border-opacity: 1;
    border-color: rgba(var(--bs-light-rgb),var(--bs-border-opacity))!important
}

.border-dark {
    border-color: rgba(var(--bs-dark-rgb),var(--bs-border-opacity))!important
}

.border-black,.border-white {
    --bs-border-opacity: 1;
    border-color: rgba(var(--bs-black-rgb),var(--bs-border-opacity))!important
}

.border-white {
    border-color: rgba(var(--bs-white-rgb),var(--bs-border-opacity))!important
}

.border-primary-subtle {
    border-color: var(--bs-primary-border-subtle)!important
}

.border-secondary-subtle {
    border-color: var(--bs-secondary-border-subtle)!important
}

.border-success-subtle {
    border-color: var(--bs-success-border-subtle)!important
}

.border-info-subtle {
    border-color: var(--bs-info-border-subtle)!important
}

.border-warning-subtle {
    border-color: var(--bs-warning-border-subtle)!important
}

.border-danger-subtle {
    border-color: var(--bs-danger-border-subtle)!important
}

.border-light-subtle {
    border-color: var(--bs-light-border-subtle)!important
}

.border-dark-subtle {
    border-color: var(--bs-dark-border-subtle)!important
}

.border-1 {
    border-width: 1px!important
}

.border-2 {
    border-width: 2px!important
}

.border-3 {
    border-width: 3px!important
}

.border-4 {
    border-width: 4px!important
}

.border-5 {
    border-width: 5px!important
}

.border-opacity-10 {
    --bs-border-opacity: 0.1
}

.border-opacity-25 {
    --bs-border-opacity: 0.25
}

.border-opacity-50 {
    --bs-border-opacity: 0.5
}

.border-opacity-75 {
    --bs-border-opacity: 0.75
}

.border-opacity-100 {
    --bs-border-opacity: 1
}

.w-25 {
    width: 25%!important
}

.w-50 {
    width: 50%!important
}

.w-75 {
    width: 75%!important
}

.w-100 {
    width: 100%!important
}

.w-auto {
    width: auto!important
}

.mw-100 {
    max-width: 100%!important
}

.vw-100 {
    width: 100vw!important
}

.min-vw-100 {
    min-width: 100vw!important
}

.h-25 {
    height: 25%!important
}

.h-50 {
    height: 50%!important
}

.h-75 {
    height: 75%!important
}

.h-100 {
    height: 100%!important
}

.h-auto {
    height: auto!important
}

.mh-100 {
    max-height: 100%!important
}

.vh-100 {
    height: 100vh!important
}

.min-vh-100 {
    min-height: 100vh!important
}

.flex-fill {
    -webkit-box-flex: 1!important;
    -ms-flex: 1 1 auto!important;
    flex: 1 1 auto!important
}

.flex-column,.flex-row {
    -webkit-box-orient: horizontal!important;
    -webkit-box-direction: normal!important;
    -ms-flex-direction: row!important;
    flex-direction: row!important
}

.flex-column {
    -webkit-box-orient: vertical!important;
    -ms-flex-direction: column!important;
    flex-direction: column!important
}

.flex-column-reverse,.flex-row-reverse {
    -webkit-box-orient: horizontal!important;
    -webkit-box-direction: reverse!important;
    -ms-flex-direction: row-reverse!important;
    flex-direction: row-reverse!important
}

.flex-column-reverse {
    -webkit-box-orient: vertical!important;
    -ms-flex-direction: column-reverse!important;
    flex-direction: column-reverse!important
}

.flex-grow-0 {
    -webkit-box-flex: 0!important;
    -ms-flex-positive: 0!important;
    flex-grow: 0!important
}

.flex-grow-1 {
    -webkit-box-flex: 1!important;
    -ms-flex-positive: 1!important;
    flex-grow: 1!important
}

.flex-shrink-0 {
    -ms-flex-negative: 0!important;
    flex-shrink: 0!important
}

.flex-shrink-1 {
    -ms-flex-negative: 1!important;
    flex-shrink: 1!important
}

.flex-wrap {
    -ms-flex-wrap: wrap!important;
    flex-wrap: wrap!important
}

.flex-nowrap {
    -ms-flex-wrap: nowrap!important;
    flex-wrap: nowrap!important
}

.flex-wrap-reverse {
    -ms-flex-wrap: wrap-reverse!important;
    flex-wrap: wrap-reverse!important
}

.justify-content-start {
    -webkit-box-pack: start!important;
    -ms-flex-pack: start!important;
    justify-content: flex-start!important
}

.justify-content-end {
    -webkit-box-pack: end!important;
    -ms-flex-pack: end!important;
    justify-content: flex-end!important
}

.justify-content-center {
    -webkit-box-pack: center!important;
    -ms-flex-pack: center!important;
    justify-content: center!important
}

.justify-content-between {
    -webkit-box-pack: justify!important;
    -ms-flex-pack: justify!important;
    justify-content: space-between!important
}

.justify-content-around {
    -ms-flex-pack: distribute!important;
    justify-content: space-around!important
}

.justify-content-evenly {
    -webkit-box-pack: space-evenly!important;
    -ms-flex-pack: space-evenly!important;
    justify-content: space-evenly!important
}

.align-items-start {
    -webkit-box-align: start!important;
    -ms-flex-align: start!important;
    align-items: flex-start!important
}

.align-items-end {
    -webkit-box-align: end!important;
    -ms-flex-align: end!important;
    align-items: flex-end!important
}

.align-items-center {
    -webkit-box-align: center!important;
    -ms-flex-align: center!important;
    align-items: center!important
}

.align-items-baseline {
    -webkit-box-align: baseline!important;
    -ms-flex-align: baseline!important;
    align-items: baseline!important
}

.align-items-stretch {
    -webkit-box-align: stretch!important;
    -ms-flex-align: stretch!important;
    align-items: stretch!important
}

.align-content-start {
    -ms-flex-line-pack: start!important;
    align-content: flex-start!important
}

.align-content-end {
    -ms-flex-line-pack: end!important;
    align-content: flex-end!important
}

.align-content-center {
    -ms-flex-line-pack: center!important;
    align-content: center!important
}

.align-content-between {
    -ms-flex-line-pack: justify!important;
    align-content: space-between!important
}

.align-content-around {
    -ms-flex-line-pack: distribute!important;
    align-content: space-around!important
}

.align-content-stretch {
    -ms-flex-line-pack: stretch!important;
    align-content: stretch!important
}

.align-self-auto {
    -ms-flex-item-align: auto!important;
    align-self: auto!important
}

.align-self-start {
    -ms-flex-item-align: start!important;
    align-self: flex-start!important
}

.align-self-end {
    -ms-flex-item-align: end!important;
    align-self: flex-end!important
}

.align-self-center {
    -ms-flex-item-align: center!important;
    align-self: center!important
}

.align-self-baseline {
    -ms-flex-item-align: baseline!important;
    align-self: baseline!important
}

.align-self-stretch {
    -ms-flex-item-align: stretch!important;
    align-self: stretch!important
}

.order-first {
    -webkit-box-ordinal-group: 0!important;
    -ms-flex-order: -1!important;
    order: -1!important
}

.order-0 {
    -webkit-box-ordinal-group: 1!important;
    -ms-flex-order: 0!important;
    order: 0!important
}

.order-1 {
    -webkit-box-ordinal-group: 2!important;
    -ms-flex-order: 1!important;
    order: 1!important
}

.order-2 {
    -webkit-box-ordinal-group: 3!important;
    -ms-flex-order: 2!important;
    order: 2!important
}

.order-3 {
    -webkit-box-ordinal-group: 4!important;
    -ms-flex-order: 3!important;
    order: 3!important
}

.order-4 {
    -webkit-box-ordinal-group: 5!important;
    -ms-flex-order: 4!important;
    order: 4!important
}

.order-5 {
    -webkit-box-ordinal-group: 6!important;
    -ms-flex-order: 5!important;
    order: 5!important
}

.order-last {
    -webkit-box-ordinal-group: 7!important;
    -ms-flex-order: 6!important;
    order: 6!important
}

.m-0 {
    margin: 0!important
}

.m-1 {
    margin: .25rem!important
}

.m-2 {
    margin: .5rem!important
}

.m-3 {
    margin: 1rem!important
}

.m-4 {
    margin: 1.5rem!important
}

.m-5 {
    margin: 3rem!important
}

.m-auto {
    margin: auto!important
}

.mx-0 {
    margin-right: 0!important;
    margin-left: 0!important
}

.mx-1 {
    margin-right: .25rem!important;
    margin-left: .25rem!important
}

.mx-2 {
    margin-right: .5rem!important;
    margin-left: .5rem!important
}

.mx-3 {
    margin-right: 1rem!important;
    margin-left: 1rem!important
}

.mx-4 {
    margin-right: 1.5rem!important;
    margin-left: 1.5rem!important
}

.mx-5 {
    margin-right: 3rem!important;
    margin-left: 3rem!important
}

.mx-auto {
    margin-right: auto!important;
    margin-left: auto!important
}

.my-0 {
    margin-top: 0!important;
    margin-bottom: 0!important
}

.my-1 {
    margin-top: .25rem!important;
    margin-bottom: .25rem!important
}

.my-2 {
    margin-top: .5rem!important;
    margin-bottom: .5rem!important
}

.my-3 {
    margin-top: 1rem!important;
    margin-bottom: 1rem!important
}

.my-4 {
    margin-top: 1.5rem!important;
    margin-bottom: 1.5rem!important
}

.my-5 {
    margin-top: 3rem!important;
    margin-bottom: 3rem!important
}

.my-auto {
    margin-top: auto!important;
    margin-bottom: auto!important
}

.mt-0 {
    margin-top: 0!important
}

.mt-1 {
    margin-top: .25rem!important
}

.mt-2 {
    margin-top: .5rem!important
}

.mt-3 {
    margin-top: 1rem!important
}

.mt-4 {
    margin-top: 1.5rem!important
}

.mt-5 {
    margin-top: 3rem!important
}

.mt-auto {
    margin-top: auto!important
}

.me-0 {
    margin-right: 0!important
}

.me-1 {
    margin-right: .25rem!important
}

.me-2 {
    margin-right: .5rem!important
}

.me-3 {
    margin-right: 1rem!important
}

.me-4 {
    margin-right: 1.5rem!important
}

.me-5 {
    margin-right: 3rem!important
}

.me-auto {
    margin-right: auto!important
}

.mb-0 {
    margin-bottom: 0!important
}

.mb-1 {
    margin-bottom: .25rem!important
}

.mb-2 {
    margin-bottom: .5rem!important
}

.mb-3 {
    margin-bottom: 1rem!important
}

.mb-4 {
    margin-bottom: 1.5rem!important
}

.mb-5 {
    margin-bottom: 3rem!important
}

.mb-auto {
    margin-bottom: auto!important
}

.ms-0 {
    margin-left: 0!important
}

.ms-1 {
    margin-left: .25rem!important
}

.ms-2 {
    margin-left: .5rem!important
}

.ms-3 {
    margin-left: 1rem!important
}

.ms-4 {
    margin-left: 1.5rem!important
}

.ms-5 {
    margin-left: 3rem!important
}

.ms-auto {
    margin-left: auto!important
}

.p-0 {
    padding: 0!important
}

.p-1 {
    padding: .25rem!important
}

.p-2 {
    padding: .5rem!important
}

.p-3 {
    padding: 1rem!important
}

.p-4 {
    padding: 1.5rem!important
}

.p-5 {
    padding: 3rem!important
}

.px-0 {
    padding-right: 0!important;
    padding-left: 0!important
}

.px-1 {
    padding-right: .25rem!important;
    padding-left: .25rem!important
}

.px-2 {
    padding-right: .5rem!important;
    padding-left: .5rem!important
}

.px-3 {
    padding-right: 1rem!important;
    padding-left: 1rem!important
}

.px-4 {
    padding-right: 1.5rem!important;
    padding-left: 1.5rem!important
}

.px-5 {
    padding-right: 3rem!important;
    padding-left: 3rem!important
}

.py-0 {
    padding-top: 0!important;
    padding-bottom: 0!important
}

.py-1 {
    padding-top: .25rem!important;
    padding-bottom: .25rem!important
}

.py-2 {
    padding-top: .5rem!important;
    padding-bottom: .5rem!important
}

.py-3 {
    padding-top: 1rem!important;
    padding-bottom: 1rem!important
}

.py-4 {
    padding-top: 1.5rem!important;
    padding-bottom: 1.5rem!important
}

.py-5 {
    padding-top: 3rem!important;
    padding-bottom: 3rem!important
}

.pt-0 {
    padding-top: 0!important
}

.pt-1 {
    padding-top: .25rem!important
}

.pt-2 {
    padding-top: .5rem!important
}

.pt-3 {
    padding-top: 1rem!important
}

.pt-4 {
    padding-top: 1.5rem!important
}

.pt-5 {
    padding-top: 3rem!important
}

.pe-0 {
    padding-right: 0!important
}

.pe-1 {
    padding-right: .25rem!important
}

.pe-2 {
    padding-right: .5rem!important
}

.pe-3 {
    padding-right: 1rem!important
}

.pe-4 {
    padding-right: 1.5rem!important
}

.pe-5 {
    padding-right: 3rem!important
}

.pb-0 {
    padding-bottom: 0!important
}

.pb-1 {
    padding-bottom: .25rem!important
}

.pb-2 {
    padding-bottom: .5rem!important
}

.pb-3 {
    padding-bottom: 1rem!important
}

.pb-4 {
    padding-bottom: 1.5rem!important
}

.pb-5 {
    padding-bottom: 3rem!important
}

.ps-0 {
    padding-left: 0!important
}

.ps-1 {
    padding-left: .25rem!important
}

.ps-2 {
    padding-left: .5rem!important
}

.ps-3 {
    padding-left: 1rem!important
}

.ps-4 {
    padding-left: 1.5rem!important
}

.ps-5 {
    padding-left: 3rem!important
}

.gap-0 {
    gap: 0!important
}

.gap-1 {
    gap: .25rem!important
}

.gap-2 {
    gap: .5rem!important
}

.gap-3 {
    gap: 1rem!important
}

.gap-4 {
    gap: 1.5rem!important
}

.gap-5 {
    gap: 3rem!important
}

.row-gap-0 {
    row-gap: 0!important
}

.row-gap-1 {
    row-gap: .25rem!important
}

.row-gap-2 {
    row-gap: .5rem!important
}

.row-gap-3 {
    row-gap: 1rem!important
}

.row-gap-4 {
    row-gap: 1.5rem!important
}

.row-gap-5 {
    row-gap: 3rem!important
}

.column-gap-0 {
    -webkit-column-gap: 0!important;
    -moz-column-gap: 0!important;
    column-gap: 0!important
}

.column-gap-1 {
    -webkit-column-gap: .25rem!important;
    -moz-column-gap: .25rem!important;
    column-gap: .25rem!important
}

.column-gap-2 {
    -webkit-column-gap: .5rem!important;
    -moz-column-gap: .5rem!important;
    column-gap: .5rem!important
}

.column-gap-3 {
    -webkit-column-gap: 1rem!important;
    -moz-column-gap: 1rem!important;
    column-gap: 1rem!important
}

.column-gap-4 {
    -webkit-column-gap: 1.5rem!important;
    -moz-column-gap: 1.5rem!important;
    column-gap: 1.5rem!important
}

.column-gap-5 {
    -webkit-column-gap: 3rem!important;
    -moz-column-gap: 3rem!important;
    column-gap: 3rem!important
}

.font-monospace {
    font-family: var(--bs-font-monospace)!important
}

.fs-1 {
    font-size: calc(1.375rem + 1.5vw)!important
}

.fs-2 {
    font-size: calc(1.325rem + .9vw)!important
}

.fs-3 {
    font-size: calc(1.3rem + .6vw)!important
}

.fs-4 {
    font-size: calc(1.275rem + .3vw)!important
}

.fs-5 {
    font-size: 1.25rem!important
}

.fs-6 {
    font-size: 1rem!important
}

.fst-italic {
    font-style: italic!important
}

.fst-normal {
    font-style: normal!important
}

.fw-lighter {
    font-weight: lighter!important
}

.fw-light {
    font-weight: 300!important
}

.fw-normal {
    font-weight: 400!important
}

.fw-medium {
    font-weight: 500!important
}

.fw-semibold {
    font-weight: 600!important
}

.fw-bold {
    font-weight: 700!important
}

.fw-bolder {
    font-weight: bolder!important
}

.lh-1 {
    line-height: 1!important
}

.lh-sm {
    line-height: 1.25!important
}

.lh-base {
    line-height: 1.5!important
}

.lh-lg {
    line-height: 2!important
}

.text-start {
    text-align: left!important
}

.text-end {
    text-align: right!important
}

.text-center {
    text-align: center!important
}

.text-decoration-none {
    text-decoration: none!important
}

.text-decoration-underline {
    text-decoration: underline!important
}

.text-decoration-line-through {
    text-decoration: line-through!important
}

.text-lowercase {
    text-transform: lowercase!important
}

.text-uppercase {
    text-transform: uppercase!important
}

.text-capitalize {
    text-transform: capitalize!important
}

.text-wrap {
    white-space: normal!important
}

.text-nowrap {
    white-space: nowrap!important
}

.text-break {
    word-wrap: break-word!important;
    word-break: break-word!important
}

.text-primary {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-primary-rgb),var(--bs-text-opacity))!important
}

.text-secondary,.text-success {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-secondary-rgb),var(--bs-text-opacity))!important
}

.text-success {
    color: rgba(var(--bs-success-rgb),var(--bs-text-opacity))!important
}

.text-info,.text-warning {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-info-rgb),var(--bs-text-opacity))!important
}

.text-warning {
    color: rgba(var(--bs-warning-rgb),var(--bs-text-opacity))!important
}

.text-danger,.text-light {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-danger-rgb),var(--bs-text-opacity))!important
}

.text-light {
    color: rgba(var(--bs-light-rgb),var(--bs-text-opacity))!important
}

.text-black,.text-dark {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-dark-rgb),var(--bs-text-opacity))!important
}

.text-black {
    color: rgba(var(--bs-black-rgb),var(--bs-text-opacity))!important
}

.text-body {
    color: rgba(var(--bs-body-color-rgb),var(--bs-text-opacity))!important
}

.text-muted {
    --bs-text-opacity: 1;
    color: var(--bs-secondary-color)!important
}

.text-black-50,.text-white-50 {
    --bs-text-opacity: 1;
    color: rgba(0,0,0,.5)!important
}

.text-white-50 {
    color: rgba(255,255,255,.5)!important
}

.text-body-secondary {
    --bs-text-opacity: 1;
    color: var(--bs-secondary-color)!important
}

.text-body-tertiary {
    --bs-text-opacity: 1;
    color: var(--bs-tertiary-color)!important
}

.text-body-emphasis {
    --bs-text-opacity: 1;
    color: var(--bs-emphasis-color)!important
}

.text-reset {
    --bs-text-opacity: 1;
    color: inherit!important
}

.text-opacity-25 {
    --bs-text-opacity: 0.25
}

.text-opacity-50 {
    --bs-text-opacity: 0.5
}

.text-opacity-75 {
    --bs-text-opacity: 0.75
}

.text-opacity-100 {
    --bs-text-opacity: 1
}

.text-primary-emphasis {
    color: var(--bs-primary-text-emphasis)!important
}

.text-secondary-emphasis {
    color: var(--bs-secondary-text-emphasis)!important
}

.text-success-emphasis {
    color: var(--bs-success-text-emphasis)!important
}

.text-info-emphasis {
    color: var(--bs-info-text-emphasis)!important
}

.text-warning-emphasis {
    color: var(--bs-warning-text-emphasis)!important
}

.text-danger-emphasis {
    color: var(--bs-danger-text-emphasis)!important
}

.text-light-emphasis {
    color: var(--bs-light-text-emphasis)!important
}

.text-dark-emphasis {
    color: var(--bs-dark-text-emphasis)!important
}

.link-opacity-10,.link-opacity-10-hover:hover {
    --bs-link-opacity: 0.1
}

.link-opacity-25,.link-opacity-25-hover:hover {
    --bs-link-opacity: 0.25
}

.link-opacity-50,.link-opacity-50-hover:hover {
    --bs-link-opacity: 0.5
}

.link-opacity-75,.link-opacity-75-hover:hover {
    --bs-link-opacity: 0.75
}

.link-opacity-100,.link-opacity-100-hover:hover {
    --bs-link-opacity: 1
}

.link-offset-1,.link-offset-1-hover:hover {
    text-underline-offset: .125em!important
}

.link-offset-2,.link-offset-2-hover:hover {
    text-underline-offset: .25em!important
}

.link-offset-3,.link-offset-3-hover:hover {
    text-underline-offset: .375em!important
}

.link-underline-primary,.link-underline-secondary {
    --bs-link-underline-opacity: 1;
    text-decoration-color: rgba(var(--bs-primary-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline-secondary {
    text-decoration-color: rgba(var(--bs-secondary-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline-info,.link-underline-success {
    --bs-link-underline-opacity: 1;
    text-decoration-color: rgba(var(--bs-success-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline-info {
    text-decoration-color: rgba(var(--bs-info-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline-danger,.link-underline-warning {
    --bs-link-underline-opacity: 1;
    text-decoration-color: rgba(var(--bs-warning-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline-danger {
    text-decoration-color: rgba(var(--bs-danger-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline-dark,.link-underline-light {
    --bs-link-underline-opacity: 1;
    text-decoration-color: rgba(var(--bs-light-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline-dark {
    text-decoration-color: rgba(var(--bs-dark-rgb),var(--bs-link-underline-opacity))!important
}

.link-underline {
    --bs-link-underline-opacity: 1;
    text-decoration-color: rgba(var(--bs-link-color-rgb),var(--bs-link-underline-opacity, 1))!important
}

.link-underline-opacity-0,.link-underline-opacity-0-hover:hover {
    --bs-link-underline-opacity: 0
}

.link-underline-opacity-10,.link-underline-opacity-10-hover:hover {
    --bs-link-underline-opacity: 0.1
}

.link-underline-opacity-25,.link-underline-opacity-25-hover:hover {
    --bs-link-underline-opacity: 0.25
}

.link-underline-opacity-50,.link-underline-opacity-50-hover:hover {
    --bs-link-underline-opacity: 0.5
}

.link-underline-opacity-75,.link-underline-opacity-75-hover:hover {
    --bs-link-underline-opacity: 0.75
}

.link-underline-opacity-100,.link-underline-opacity-100-hover:hover {
    --bs-link-underline-opacity: 1
}

.bg-primary {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-primary-rgb),var(--bs-bg-opacity))!important
}

.bg-secondary,.bg-success {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-secondary-rgb),var(--bs-bg-opacity))!important
}

.bg-success {
    background-color: rgba(var(--bs-success-rgb),var(--bs-bg-opacity))!important
}

.bg-info,.bg-warning {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-info-rgb),var(--bs-bg-opacity))!important
}

.bg-warning {
    background-color: rgba(var(--bs-warning-rgb),var(--bs-bg-opacity))!important
}

.bg-danger,.bg-light {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-danger-rgb),var(--bs-bg-opacity))!important
}

.bg-light {
    background-color: rgba(var(--bs-light-rgb),var(--bs-bg-opacity))!important
}

.bg-black,.bg-dark {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-dark-rgb),var(--bs-bg-opacity))!important
}

.bg-black {
    background-color: rgba(var(--bs-black-rgb),var(--bs-bg-opacity))!important
}

.bg-body,.bg-white {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-white-rgb),var(--bs-bg-opacity))!important
}

.bg-body {
    background-color: rgba(var(--bs-body-bg-rgb),var(--bs-bg-opacity))!important
}

.bg-transparent {
    --bs-bg-opacity: 1;
    background-color: transparent!important
}

.bg-body-secondary {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-secondary-bg-rgb),var(--bs-bg-opacity))!important
}

.bg-body-tertiary {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-tertiary-bg-rgb),var(--bs-bg-opacity))!important
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1
}

.bg-opacity-25 {
    --bs-bg-opacity: 0.25
}

.bg-opacity-50 {
    --bs-bg-opacity: 0.5
}

.bg-opacity-75 {
    --bs-bg-opacity: 0.75
}

.bg-opacity-100 {
    --bs-bg-opacity: 1
}

.bg-primary-subtle {
    background-color: var(--bs-primary-bg-subtle)!important
}

.bg-secondary-subtle {
    background-color: var(--bs-secondary-bg-subtle)!important
}

.bg-success-subtle {
    background-color: var(--bs-success-bg-subtle)!important
}

.bg-info-subtle {
    background-color: var(--bs-info-bg-subtle)!important
}

.bg-warning-subtle {
    background-color: var(--bs-warning-bg-subtle)!important
}

.bg-danger-subtle {
    background-color: var(--bs-danger-bg-subtle)!important
}

.bg-light-subtle {
    background-color: var(--bs-light-bg-subtle)!important
}

.bg-dark-subtle {
    background-color: var(--bs-dark-bg-subtle)!important
}

.bg-gradient {
    background-image: var(--bs-gradient)!important
}

.user-select-all {
    -webkit-user-select: all!important;
    -moz-user-select: all!important;
    user-select: all!important
}

.user-select-auto {
    -webkit-user-select: auto!important;
    -moz-user-select: auto!important;
    -ms-user-select: auto!important;
    user-select: auto!important
}

.user-select-none {
    -webkit-user-select: none!important;
    -moz-user-select: none!important;
    -ms-user-select: none!important;
    user-select: none!important
}

.pe-none {
    pointer-events: none!important
}

.pe-auto {
    pointer-events: auto!important
}

.rounded {
    border-radius: var(--bs-border-radius)!important
}

.rounded-0 {
    border-radius: 0!important
}

.rounded-1 {
    border-radius: var(--bs-border-radius-sm)!important
}

.rounded-2 {
    border-radius: var(--bs-border-radius)!important
}

.rounded-3 {
    border-radius: var(--bs-border-radius-lg)!important
}

.rounded-4 {
    border-radius: var(--bs-border-radius-xl)!important
}

.rounded-5 {
    border-radius: var(--bs-border-radius-xxl)!important
}

.rounded-circle {
    border-radius: 50%!important
}

.rounded-pill {
    border-radius: var(--bs-border-radius-pill)!important
}

.rounded-top {
    border-top-left-radius: var(--bs-border-radius)!important;
    border-top-right-radius: var(--bs-border-radius)!important
}

.rounded-top-0 {
    border-top-left-radius: 0!important;
    border-top-right-radius: 0!important
}

.rounded-top-1 {
    border-top-left-radius: var(--bs-border-radius-sm)!important;
    border-top-right-radius: var(--bs-border-radius-sm)!important
}

.rounded-top-2 {
    border-top-left-radius: var(--bs-border-radius)!important;
    border-top-right-radius: var(--bs-border-radius)!important
}

.rounded-top-3 {
    border-top-left-radius: var(--bs-border-radius-lg)!important;
    border-top-right-radius: var(--bs-border-radius-lg)!important
}

.rounded-top-4 {
    border-top-left-radius: var(--bs-border-radius-xl)!important;
    border-top-right-radius: var(--bs-border-radius-xl)!important
}

.rounded-top-5 {
    border-top-left-radius: var(--bs-border-radius-xxl)!important;
    border-top-right-radius: var(--bs-border-radius-xxl)!important
}

.rounded-top-circle {
    border-top-left-radius: 50%!important;
    border-top-right-radius: 50%!important
}

.rounded-top-pill {
    border-top-left-radius: var(--bs-border-radius-pill)!important;
    border-top-right-radius: var(--bs-border-radius-pill)!important
}

.rounded-end {
    border-top-right-radius: var(--bs-border-radius)!important;
    border-bottom-right-radius: var(--bs-border-radius)!important
}

.rounded-end-0 {
    border-top-right-radius: 0!important;
    border-bottom-right-radius: 0!important
}

.rounded-end-1 {
    border-top-right-radius: var(--bs-border-radius-sm)!important;
    border-bottom-right-radius: var(--bs-border-radius-sm)!important
}

.rounded-end-2 {
    border-top-right-radius: var(--bs-border-radius)!important;
    border-bottom-right-radius: var(--bs-border-radius)!important
}

.rounded-end-3 {
    border-top-right-radius: var(--bs-border-radius-lg)!important;
    border-bottom-right-radius: var(--bs-border-radius-lg)!important
}

.rounded-end-4 {
    border-top-right-radius: var(--bs-border-radius-xl)!important;
    border-bottom-right-radius: var(--bs-border-radius-xl)!important
}

.rounded-end-5 {
    border-top-right-radius: var(--bs-border-radius-xxl)!important;
    border-bottom-right-radius: var(--bs-border-radius-xxl)!important
}

.rounded-end-circle {
    border-top-right-radius: 50%!important;
    border-bottom-right-radius: 50%!important
}

.rounded-end-pill {
    border-top-right-radius: var(--bs-border-radius-pill)!important;
    border-bottom-right-radius: var(--bs-border-radius-pill)!important
}

.rounded-bottom {
    border-bottom-right-radius: var(--bs-border-radius)!important;
    border-bottom-left-radius: var(--bs-border-radius)!important
}

.rounded-bottom-0 {
    border-bottom-right-radius: 0!important;
    border-bottom-left-radius: 0!important
}

.rounded-bottom-1 {
    border-bottom-right-radius: var(--bs-border-radius-sm)!important;
    border-bottom-left-radius: var(--bs-border-radius-sm)!important
}

.rounded-bottom-2 {
    border-bottom-right-radius: var(--bs-border-radius)!important;
    border-bottom-left-radius: var(--bs-border-radius)!important
}

.rounded-bottom-3 {
    border-bottom-right-radius: var(--bs-border-radius-lg)!important;
    border-bottom-left-radius: var(--bs-border-radius-lg)!important
}

.rounded-bottom-4 {
    border-bottom-right-radius: var(--bs-border-radius-xl)!important;
    border-bottom-left-radius: var(--bs-border-radius-xl)!important
}

.rounded-bottom-5 {
    border-bottom-right-radius: var(--bs-border-radius-xxl)!important;
    border-bottom-left-radius: var(--bs-border-radius-xxl)!important
}

.rounded-bottom-circle {
    border-bottom-right-radius: 50%!important;
    border-bottom-left-radius: 50%!important
}

.rounded-bottom-pill {
    border-bottom-right-radius: var(--bs-border-radius-pill)!important;
    border-bottom-left-radius: var(--bs-border-radius-pill)!important
}

.rounded-start {
    border-bottom-left-radius: var(--bs-border-radius)!important;
    border-top-left-radius: var(--bs-border-radius)!important
}

.rounded-start-0 {
    border-bottom-left-radius: 0!important;
    border-top-left-radius: 0!important
}

.rounded-start-1 {
    border-bottom-left-radius: var(--bs-border-radius-sm)!important;
    border-top-left-radius: var(--bs-border-radius-sm)!important
}

.rounded-start-2 {
    border-bottom-left-radius: var(--bs-border-radius)!important;
    border-top-left-radius: var(--bs-border-radius)!important
}

.rounded-start-3 {
    border-bottom-left-radius: var(--bs-border-radius-lg)!important;
    border-top-left-radius: var(--bs-border-radius-lg)!important
}

.rounded-start-4 {
    border-bottom-left-radius: var(--bs-border-radius-xl)!important;
    border-top-left-radius: var(--bs-border-radius-xl)!important
}

.rounded-start-5 {
    border-bottom-left-radius: var(--bs-border-radius-xxl)!important;
    border-top-left-radius: var(--bs-border-radius-xxl)!important
}

.rounded-start-circle {
    border-bottom-left-radius: 50%!important;
    border-top-left-radius: 50%!important
}

.rounded-start-pill {
    border-bottom-left-radius: var(--bs-border-radius-pill)!important;
    border-top-left-radius: var(--bs-border-radius-pill)!important
}

.visible {
    visibility: visible!important
}

.invisible {
    visibility: hidden!important
}

.z-n1 {
    z-index: -1!important
}

.z-0 {
    z-index: 0!important
}

.z-1 {
    z-index: 1!important
}

.z-2 {
    z-index: 2!important
}

.z-3 {
    z-index: 3!important
}

@media (min-width: 576px) {
    .float-sm-start {
        float:left!important
    }

    .float-sm-end {
        float: right!important
    }

    .float-sm-none {
        float: none!important
    }

    .object-fit-sm-contain {
        -o-object-fit: contain!important;
        object-fit: contain!important
    }

    .object-fit-sm-cover {
        -o-object-fit: cover!important;
        object-fit: cover!important
    }

    .object-fit-sm-fill {
        -o-object-fit: fill!important;
        object-fit: fill!important
    }

    .object-fit-sm-scale {
        -o-object-fit: scale-down!important;
        object-fit: scale-down!important
    }

    .object-fit-sm-none {
        -o-object-fit: none!important;
        object-fit: none!important
    }

    .d-sm-inline {
        display: inline!important
    }

    .d-sm-inline-block {
        display: inline-block!important
    }

    .d-sm-block {
        display: block!important
    }

    .d-sm-grid {
        display: grid!important
    }

    .d-sm-inline-grid {
        display: inline-grid!important
    }

    .d-sm-table {
        display: table!important
    }

    .d-sm-table-row {
        display: table-row!important
    }

    .d-sm-table-cell {
        display: table-cell!important
    }

    .d-sm-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important
    }

    .d-sm-inline-flex {
        display: -webkit-inline-box!important;
        display: -ms-inline-flexbox!important;
        display: inline-flex!important
    }

    .d-sm-none {
        display: none!important
    }

    .flex-sm-fill {
        -webkit-box-flex: 1!important;
        -ms-flex: 1 1 auto!important;
        flex: 1 1 auto!important
    }

    .flex-sm-column,.flex-sm-row {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: normal!important;
        -ms-flex-direction: row!important;
        flex-direction: row!important
    }

    .flex-sm-column {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column!important;
        flex-direction: column!important
    }

    .flex-sm-column-reverse,.flex-sm-row-reverse {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: reverse!important;
        -ms-flex-direction: row-reverse!important;
        flex-direction: row-reverse!important
    }

    .flex-sm-column-reverse {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column-reverse!important;
        flex-direction: column-reverse!important
    }

    .flex-sm-grow-0 {
        -webkit-box-flex: 0!important;
        -ms-flex-positive: 0!important;
        flex-grow: 0!important
    }

    .flex-sm-grow-1 {
        -webkit-box-flex: 1!important;
        -ms-flex-positive: 1!important;
        flex-grow: 1!important
    }

    .flex-sm-shrink-0 {
        -ms-flex-negative: 0!important;
        flex-shrink: 0!important
    }

    .flex-sm-shrink-1 {
        -ms-flex-negative: 1!important;
        flex-shrink: 1!important
    }

    .flex-sm-wrap {
        -ms-flex-wrap: wrap!important;
        flex-wrap: wrap!important
    }

    .flex-sm-nowrap {
        -ms-flex-wrap: nowrap!important;
        flex-wrap: nowrap!important
    }

    .flex-sm-wrap-reverse {
        -ms-flex-wrap: wrap-reverse!important;
        flex-wrap: wrap-reverse!important
    }

    .justify-content-sm-start {
        -webkit-box-pack: start!important;
        -ms-flex-pack: start!important;
        justify-content: flex-start!important
    }

    .justify-content-sm-end {
        -webkit-box-pack: end!important;
        -ms-flex-pack: end!important;
        justify-content: flex-end!important
    }

    .justify-content-sm-center {
        -webkit-box-pack: center!important;
        -ms-flex-pack: center!important;
        justify-content: center!important
    }

    .justify-content-sm-between {
        -webkit-box-pack: justify!important;
        -ms-flex-pack: justify!important;
        justify-content: space-between!important
    }

    .justify-content-sm-around {
        -ms-flex-pack: distribute!important;
        justify-content: space-around!important
    }

    .justify-content-sm-evenly {
        -webkit-box-pack: space-evenly!important;
        -ms-flex-pack: space-evenly!important;
        justify-content: space-evenly!important
    }

    .align-items-sm-start {
        -webkit-box-align: start!important;
        -ms-flex-align: start!important;
        align-items: flex-start!important
    }

    .align-items-sm-end {
        -webkit-box-align: end!important;
        -ms-flex-align: end!important;
        align-items: flex-end!important
    }

    .align-items-sm-center {
        -webkit-box-align: center!important;
        -ms-flex-align: center!important;
        align-items: center!important
    }

    .align-items-sm-baseline {
        -webkit-box-align: baseline!important;
        -ms-flex-align: baseline!important;
        align-items: baseline!important
    }

    .align-items-sm-stretch {
        -webkit-box-align: stretch!important;
        -ms-flex-align: stretch!important;
        align-items: stretch!important
    }

    .align-content-sm-start {
        -ms-flex-line-pack: start!important;
        align-content: flex-start!important
    }

    .align-content-sm-end {
        -ms-flex-line-pack: end!important;
        align-content: flex-end!important
    }

    .align-content-sm-center {
        -ms-flex-line-pack: center!important;
        align-content: center!important
    }

    .align-content-sm-between {
        -ms-flex-line-pack: justify!important;
        align-content: space-between!important
    }

    .align-content-sm-around {
        -ms-flex-line-pack: distribute!important;
        align-content: space-around!important
    }

    .align-content-sm-stretch {
        -ms-flex-line-pack: stretch!important;
        align-content: stretch!important
    }

    .align-self-sm-auto {
        -ms-flex-item-align: auto!important;
        align-self: auto!important
    }

    .align-self-sm-start {
        -ms-flex-item-align: start!important;
        align-self: flex-start!important
    }

    .align-self-sm-end {
        -ms-flex-item-align: end!important;
        align-self: flex-end!important
    }

    .align-self-sm-center {
        -ms-flex-item-align: center!important;
        align-self: center!important
    }

    .align-self-sm-baseline {
        -ms-flex-item-align: baseline!important;
        align-self: baseline!important
    }

    .align-self-sm-stretch {
        -ms-flex-item-align: stretch!important;
        align-self: stretch!important
    }

    .order-sm-first {
        -webkit-box-ordinal-group: 0!important;
        -ms-flex-order: -1!important;
        order: -1!important
    }

    .order-sm-0 {
        -webkit-box-ordinal-group: 1!important;
        -ms-flex-order: 0!important;
        order: 0!important
    }

    .order-sm-1 {
        -webkit-box-ordinal-group: 2!important;
        -ms-flex-order: 1!important;
        order: 1!important
    }

    .order-sm-2 {
        -webkit-box-ordinal-group: 3!important;
        -ms-flex-order: 2!important;
        order: 2!important
    }

    .order-sm-3 {
        -webkit-box-ordinal-group: 4!important;
        -ms-flex-order: 3!important;
        order: 3!important
    }

    .order-sm-4 {
        -webkit-box-ordinal-group: 5!important;
        -ms-flex-order: 4!important;
        order: 4!important
    }

    .order-sm-5 {
        -webkit-box-ordinal-group: 6!important;
        -ms-flex-order: 5!important;
        order: 5!important
    }

    .order-sm-last {
        -webkit-box-ordinal-group: 7!important;
        -ms-flex-order: 6!important;
        order: 6!important
    }

    .m-sm-0 {
        margin: 0!important
    }

    .m-sm-1 {
        margin: .25rem!important
    }

    .m-sm-2 {
        margin: .5rem!important
    }

    .m-sm-3 {
        margin: 1rem!important
    }

    .m-sm-4 {
        margin: 1.5rem!important
    }

    .m-sm-5 {
        margin: 3rem!important
    }

    .m-sm-auto {
        margin: auto!important
    }

    .mx-sm-0 {
        margin-right: 0!important;
        margin-left: 0!important
    }

    .mx-sm-1 {
        margin-right: .25rem!important;
        margin-left: .25rem!important
    }

    .mx-sm-2 {
        margin-right: .5rem!important;
        margin-left: .5rem!important
    }

    .mx-sm-3 {
        margin-right: 1rem!important;
        margin-left: 1rem!important
    }

    .mx-sm-4 {
        margin-right: 1.5rem!important;
        margin-left: 1.5rem!important
    }

    .mx-sm-5 {
        margin-right: 3rem!important;
        margin-left: 3rem!important
    }

    .mx-sm-auto {
        margin-right: auto!important;
        margin-left: auto!important
    }

    .my-sm-0 {
        margin-top: 0!important;
        margin-bottom: 0!important
    }

    .my-sm-1 {
        margin-top: .25rem!important;
        margin-bottom: .25rem!important
    }

    .my-sm-2 {
        margin-top: .5rem!important;
        margin-bottom: .5rem!important
    }

    .my-sm-3 {
        margin-top: 1rem!important;
        margin-bottom: 1rem!important
    }

    .my-sm-4 {
        margin-top: 1.5rem!important;
        margin-bottom: 1.5rem!important
    }

    .my-sm-5 {
        margin-top: 3rem!important;
        margin-bottom: 3rem!important
    }

    .my-sm-auto {
        margin-top: auto!important;
        margin-bottom: auto!important
    }

    .mt-sm-0 {
        margin-top: 0!important
    }

    .mt-sm-1 {
        margin-top: .25rem!important
    }

    .mt-sm-2 {
        margin-top: .5rem!important
    }

    .mt-sm-3 {
        margin-top: 1rem!important
    }

    .mt-sm-4 {
        margin-top: 1.5rem!important
    }

    .mt-sm-5 {
        margin-top: 3rem!important
    }

    .mt-sm-auto {
        margin-top: auto!important
    }

    .me-sm-0 {
        margin-right: 0!important
    }

    .me-sm-1 {
        margin-right: .25rem!important
    }

    .me-sm-2 {
        margin-right: .5rem!important
    }

    .me-sm-3 {
        margin-right: 1rem!important
    }

    .me-sm-4 {
        margin-right: 1.5rem!important
    }

    .me-sm-5 {
        margin-right: 3rem!important
    }

    .me-sm-auto {
        margin-right: auto!important
    }

    .mb-sm-0 {
        margin-bottom: 0!important
    }

    .mb-sm-1 {
        margin-bottom: .25rem!important
    }

    .mb-sm-2 {
        margin-bottom: .5rem!important
    }

    .mb-sm-3 {
        margin-bottom: 1rem!important
    }

    .mb-sm-4 {
        margin-bottom: 1.5rem!important
    }

    .mb-sm-5 {
        margin-bottom: 3rem!important
    }

    .mb-sm-auto {
        margin-bottom: auto!important
    }

    .ms-sm-0 {
        margin-left: 0!important
    }

    .ms-sm-1 {
        margin-left: .25rem!important
    }

    .ms-sm-2 {
        margin-left: .5rem!important
    }

    .ms-sm-3 {
        margin-left: 1rem!important
    }

    .ms-sm-4 {
        margin-left: 1.5rem!important
    }

    .ms-sm-5 {
        margin-left: 3rem!important
    }

    .ms-sm-auto {
        margin-left: auto!important
    }

    .p-sm-0 {
        padding: 0!important
    }

    .p-sm-1 {
        padding: .25rem!important
    }

    .p-sm-2 {
        padding: .5rem!important
    }

    .p-sm-3 {
        padding: 1rem!important
    }

    .p-sm-4 {
        padding: 1.5rem!important
    }

    .p-sm-5 {
        padding: 3rem!important
    }

    .px-sm-0 {
        padding-right: 0!important;
        padding-left: 0!important
    }

    .px-sm-1 {
        padding-right: .25rem!important;
        padding-left: .25rem!important
    }

    .px-sm-2 {
        padding-right: .5rem!important;
        padding-left: .5rem!important
    }

    .px-sm-3 {
        padding-right: 1rem!important;
        padding-left: 1rem!important
    }

    .px-sm-4 {
        padding-right: 1.5rem!important;
        padding-left: 1.5rem!important
    }

    .px-sm-5 {
        padding-right: 3rem!important;
        padding-left: 3rem!important
    }

    .py-sm-0 {
        padding-top: 0!important;
        padding-bottom: 0!important
    }

    .py-sm-1 {
        padding-top: .25rem!important;
        padding-bottom: .25rem!important
    }

    .py-sm-2 {
        padding-top: .5rem!important;
        padding-bottom: .5rem!important
    }

    .py-sm-3 {
        padding-top: 1rem!important;
        padding-bottom: 1rem!important
    }

    .py-sm-4 {
        padding-top: 1.5rem!important;
        padding-bottom: 1.5rem!important
    }

    .py-sm-5 {
        padding-top: 3rem!important;
        padding-bottom: 3rem!important
    }

    .pt-sm-0 {
        padding-top: 0!important
    }

    .pt-sm-1 {
        padding-top: .25rem!important
    }

    .pt-sm-2 {
        padding-top: .5rem!important
    }

    .pt-sm-3 {
        padding-top: 1rem!important
    }

    .pt-sm-4 {
        padding-top: 1.5rem!important
    }

    .pt-sm-5 {
        padding-top: 3rem!important
    }

    .pe-sm-0 {
        padding-right: 0!important
    }

    .pe-sm-1 {
        padding-right: .25rem!important
    }

    .pe-sm-2 {
        padding-right: .5rem!important
    }

    .pe-sm-3 {
        padding-right: 1rem!important
    }

    .pe-sm-4 {
        padding-right: 1.5rem!important
    }

    .pe-sm-5 {
        padding-right: 3rem!important
    }

    .pb-sm-0 {
        padding-bottom: 0!important
    }

    .pb-sm-1 {
        padding-bottom: .25rem!important
    }

    .pb-sm-2 {
        padding-bottom: .5rem!important
    }

    .pb-sm-3 {
        padding-bottom: 1rem!important
    }

    .pb-sm-4 {
        padding-bottom: 1.5rem!important
    }

    .pb-sm-5 {
        padding-bottom: 3rem!important
    }

    .ps-sm-0 {
        padding-left: 0!important
    }

    .ps-sm-1 {
        padding-left: .25rem!important
    }

    .ps-sm-2 {
        padding-left: .5rem!important
    }

    .ps-sm-3 {
        padding-left: 1rem!important
    }

    .ps-sm-4 {
        padding-left: 1.5rem!important
    }

    .ps-sm-5 {
        padding-left: 3rem!important
    }

    .gap-sm-0 {
        gap: 0!important
    }

    .gap-sm-1 {
        gap: .25rem!important
    }

    .gap-sm-2 {
        gap: .5rem!important
    }

    .gap-sm-3 {
        gap: 1rem!important
    }

    .gap-sm-4 {
        gap: 1.5rem!important
    }

    .gap-sm-5 {
        gap: 3rem!important
    }

    .row-gap-sm-0 {
        row-gap: 0!important
    }

    .row-gap-sm-1 {
        row-gap: .25rem!important
    }

    .row-gap-sm-2 {
        row-gap: .5rem!important
    }

    .row-gap-sm-3 {
        row-gap: 1rem!important
    }

    .row-gap-sm-4 {
        row-gap: 1.5rem!important
    }

    .row-gap-sm-5 {
        row-gap: 3rem!important
    }

    .column-gap-sm-0 {
        -webkit-column-gap: 0!important;
        -moz-column-gap: 0!important;
        column-gap: 0!important
    }

    .column-gap-sm-1 {
        -webkit-column-gap: .25rem!important;
        -moz-column-gap: .25rem!important;
        column-gap: .25rem!important
    }

    .column-gap-sm-2 {
        -webkit-column-gap: .5rem!important;
        -moz-column-gap: .5rem!important;
        column-gap: .5rem!important
    }

    .column-gap-sm-3 {
        -webkit-column-gap: 1rem!important;
        -moz-column-gap: 1rem!important;
        column-gap: 1rem!important
    }

    .column-gap-sm-4 {
        -webkit-column-gap: 1.5rem!important;
        -moz-column-gap: 1.5rem!important;
        column-gap: 1.5rem!important
    }

    .column-gap-sm-5 {
        -webkit-column-gap: 3rem!important;
        -moz-column-gap: 3rem!important;
        column-gap: 3rem!important
    }

    .text-sm-start {
        text-align: left!important
    }

    .text-sm-end {
        text-align: right!important
    }

    .text-sm-center {
        text-align: center!important
    }
}

@media (min-width: 768px) {
    .float-md-start {
        float:left!important
    }

    .float-md-end {
        float: right!important
    }

    .float-md-none {
        float: none!important
    }

    .object-fit-md-contain {
        -o-object-fit: contain!important;
        object-fit: contain!important
    }

    .object-fit-md-cover {
        -o-object-fit: cover!important;
        object-fit: cover!important
    }

    .object-fit-md-fill {
        -o-object-fit: fill!important;
        object-fit: fill!important
    }

    .object-fit-md-scale {
        -o-object-fit: scale-down!important;
        object-fit: scale-down!important
    }

    .object-fit-md-none {
        -o-object-fit: none!important;
        object-fit: none!important
    }

    .d-md-inline {
        display: inline!important
    }

    .d-md-inline-block {
        display: inline-block!important
    }

    .d-md-block {
        display: block!important
    }

    .d-md-grid {
        display: grid!important
    }

    .d-md-inline-grid {
        display: inline-grid!important
    }

    .d-md-table {
        display: table!important
    }

    .d-md-table-row {
        display: table-row!important
    }

    .d-md-table-cell {
        display: table-cell!important
    }

    .d-md-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important
    }

    .d-md-inline-flex {
        display: -webkit-inline-box!important;
        display: -ms-inline-flexbox!important;
        display: inline-flex!important
    }

    .d-md-none {
        display: none!important
    }

    .flex-md-fill {
        -webkit-box-flex: 1!important;
        -ms-flex: 1 1 auto!important;
        flex: 1 1 auto!important
    }

    .flex-md-column,.flex-md-row {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: normal!important;
        -ms-flex-direction: row!important;
        flex-direction: row!important
    }

    .flex-md-column {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column!important;
        flex-direction: column!important
    }

    .flex-md-column-reverse,.flex-md-row-reverse {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: reverse!important;
        -ms-flex-direction: row-reverse!important;
        flex-direction: row-reverse!important
    }

    .flex-md-column-reverse {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column-reverse!important;
        flex-direction: column-reverse!important
    }

    .flex-md-grow-0 {
        -webkit-box-flex: 0!important;
        -ms-flex-positive: 0!important;
        flex-grow: 0!important
    }

    .flex-md-grow-1 {
        -webkit-box-flex: 1!important;
        -ms-flex-positive: 1!important;
        flex-grow: 1!important
    }

    .flex-md-shrink-0 {
        -ms-flex-negative: 0!important;
        flex-shrink: 0!important
    }

    .flex-md-shrink-1 {
        -ms-flex-negative: 1!important;
        flex-shrink: 1!important
    }

    .flex-md-wrap {
        -ms-flex-wrap: wrap!important;
        flex-wrap: wrap!important
    }

    .flex-md-nowrap {
        -ms-flex-wrap: nowrap!important;
        flex-wrap: nowrap!important
    }

    .flex-md-wrap-reverse {
        -ms-flex-wrap: wrap-reverse!important;
        flex-wrap: wrap-reverse!important
    }

    .justify-content-md-start {
        -webkit-box-pack: start!important;
        -ms-flex-pack: start!important;
        justify-content: flex-start!important
    }

    .justify-content-md-end {
        -webkit-box-pack: end!important;
        -ms-flex-pack: end!important;
        justify-content: flex-end!important
    }

    .justify-content-md-center {
        -webkit-box-pack: center!important;
        -ms-flex-pack: center!important;
        justify-content: center!important
    }

    .justify-content-md-between {
        -webkit-box-pack: justify!important;
        -ms-flex-pack: justify!important;
        justify-content: space-between!important
    }

    .justify-content-md-around {
        -ms-flex-pack: distribute!important;
        justify-content: space-around!important
    }

    .justify-content-md-evenly {
        -webkit-box-pack: space-evenly!important;
        -ms-flex-pack: space-evenly!important;
        justify-content: space-evenly!important
    }

    .align-items-md-start {
        -webkit-box-align: start!important;
        -ms-flex-align: start!important;
        align-items: flex-start!important
    }

    .align-items-md-end {
        -webkit-box-align: end!important;
        -ms-flex-align: end!important;
        align-items: flex-end!important
    }

    .align-items-md-center {
        -webkit-box-align: center!important;
        -ms-flex-align: center!important;
        align-items: center!important
    }

    .align-items-md-baseline {
        -webkit-box-align: baseline!important;
        -ms-flex-align: baseline!important;
        align-items: baseline!important
    }

    .align-items-md-stretch {
        -webkit-box-align: stretch!important;
        -ms-flex-align: stretch!important;
        align-items: stretch!important
    }

    .align-content-md-start {
        -ms-flex-line-pack: start!important;
        align-content: flex-start!important
    }

    .align-content-md-end {
        -ms-flex-line-pack: end!important;
        align-content: flex-end!important
    }

    .align-content-md-center {
        -ms-flex-line-pack: center!important;
        align-content: center!important
    }

    .align-content-md-between {
        -ms-flex-line-pack: justify!important;
        align-content: space-between!important
    }

    .align-content-md-around {
        -ms-flex-line-pack: distribute!important;
        align-content: space-around!important
    }

    .align-content-md-stretch {
        -ms-flex-line-pack: stretch!important;
        align-content: stretch!important
    }

    .align-self-md-auto {
        -ms-flex-item-align: auto!important;
        align-self: auto!important
    }

    .align-self-md-start {
        -ms-flex-item-align: start!important;
        align-self: flex-start!important
    }

    .align-self-md-end {
        -ms-flex-item-align: end!important;
        align-self: flex-end!important
    }

    .align-self-md-center {
        -ms-flex-item-align: center!important;
        align-self: center!important
    }

    .align-self-md-baseline {
        -ms-flex-item-align: baseline!important;
        align-self: baseline!important
    }

    .align-self-md-stretch {
        -ms-flex-item-align: stretch!important;
        align-self: stretch!important
    }

    .order-md-first {
        -webkit-box-ordinal-group: 0!important;
        -ms-flex-order: -1!important;
        order: -1!important
    }

    .order-md-0 {
        -webkit-box-ordinal-group: 1!important;
        -ms-flex-order: 0!important;
        order: 0!important
    }

    .order-md-1 {
        -webkit-box-ordinal-group: 2!important;
        -ms-flex-order: 1!important;
        order: 1!important
    }

    .order-md-2 {
        -webkit-box-ordinal-group: 3!important;
        -ms-flex-order: 2!important;
        order: 2!important
    }

    .order-md-3 {
        -webkit-box-ordinal-group: 4!important;
        -ms-flex-order: 3!important;
        order: 3!important
    }

    .order-md-4 {
        -webkit-box-ordinal-group: 5!important;
        -ms-flex-order: 4!important;
        order: 4!important
    }

    .order-md-5 {
        -webkit-box-ordinal-group: 6!important;
        -ms-flex-order: 5!important;
        order: 5!important
    }

    .order-md-last {
        -webkit-box-ordinal-group: 7!important;
        -ms-flex-order: 6!important;
        order: 6!important
    }

    .m-md-0 {
        margin: 0!important
    }

    .m-md-1 {
        margin: .25rem!important
    }

    .m-md-2 {
        margin: .5rem!important
    }

    .m-md-3 {
        margin: 1rem!important
    }

    .m-md-4 {
        margin: 1.5rem!important
    }

    .m-md-5 {
        margin: 3rem!important
    }

    .m-md-auto {
        margin: auto!important
    }

    .mx-md-0 {
        margin-right: 0!important;
        margin-left: 0!important
    }

    .mx-md-1 {
        margin-right: .25rem!important;
        margin-left: .25rem!important
    }

    .mx-md-2 {
        margin-right: .5rem!important;
        margin-left: .5rem!important
    }

    .mx-md-3 {
        margin-right: 1rem!important;
        margin-left: 1rem!important
    }

    .mx-md-4 {
        margin-right: 1.5rem!important;
        margin-left: 1.5rem!important
    }

    .mx-md-5 {
        margin-right: 3rem!important;
        margin-left: 3rem!important
    }

    .mx-md-auto {
        margin-right: auto!important;
        margin-left: auto!important
    }

    .my-md-0 {
        margin-top: 0!important;
        margin-bottom: 0!important
    }

    .my-md-1 {
        margin-top: .25rem!important;
        margin-bottom: .25rem!important
    }

    .my-md-2 {
        margin-top: .5rem!important;
        margin-bottom: .5rem!important
    }

    .my-md-3 {
        margin-top: 1rem!important;
        margin-bottom: 1rem!important
    }

    .my-md-4 {
        margin-top: 1.5rem!important;
        margin-bottom: 1.5rem!important
    }

    .my-md-5 {
        margin-top: 3rem!important;
        margin-bottom: 3rem!important
    }

    .my-md-auto {
        margin-top: auto!important;
        margin-bottom: auto!important
    }

    .mt-md-0 {
        margin-top: 0!important
    }

    .mt-md-1 {
        margin-top: .25rem!important
    }

    .mt-md-2 {
        margin-top: .5rem!important
    }

    .mt-md-3 {
        margin-top: 1rem!important
    }

    .mt-md-4 {
        margin-top: 1.5rem!important
    }

    .mt-md-5 {
        margin-top: 3rem!important
    }

    .mt-md-auto {
        margin-top: auto!important
    }

    .me-md-0 {
        margin-right: 0!important
    }

    .me-md-1 {
        margin-right: .25rem!important
    }

    .me-md-2 {
        margin-right: .5rem!important
    }

    .me-md-3 {
        margin-right: 1rem!important
    }

    .me-md-4 {
        margin-right: 1.5rem!important
    }

    .me-md-5 {
        margin-right: 3rem!important
    }

    .me-md-auto {
        margin-right: auto!important
    }

    .mb-md-0 {
        margin-bottom: 0!important
    }

    .mb-md-1 {
        margin-bottom: .25rem!important
    }

    .mb-md-2 {
        margin-bottom: .5rem!important
    }

    .mb-md-3 {
        margin-bottom: 1rem!important
    }

    .mb-md-4 {
        margin-bottom: 1.5rem!important
    }

    .mb-md-5 {
        margin-bottom: 3rem!important
    }

    .mb-md-auto {
        margin-bottom: auto!important
    }

    .ms-md-0 {
        margin-left: 0!important
    }

    .ms-md-1 {
        margin-left: .25rem!important
    }

    .ms-md-2 {
        margin-left: .5rem!important
    }

    .ms-md-3 {
        margin-left: 1rem!important
    }

    .ms-md-4 {
        margin-left: 1.5rem!important
    }

    .ms-md-5 {
        margin-left: 3rem!important
    }

    .ms-md-auto {
        margin-left: auto!important
    }

    .p-md-0 {
        padding: 0!important
    }

    .p-md-1 {
        padding: .25rem!important
    }

    .p-md-2 {
        padding: .5rem!important
    }

    .p-md-3 {
        padding: 1rem!important
    }

    .p-md-4 {
        padding: 1.5rem!important
    }

    .p-md-5 {
        padding: 3rem!important
    }

    .px-md-0 {
        padding-right: 0!important;
        padding-left: 0!important
    }

    .px-md-1 {
        padding-right: .25rem!important;
        padding-left: .25rem!important
    }

    .px-md-2 {
        padding-right: .5rem!important;
        padding-left: .5rem!important
    }

    .px-md-3 {
        padding-right: 1rem!important;
        padding-left: 1rem!important
    }

    .px-md-4 {
        padding-right: 1.5rem!important;
        padding-left: 1.5rem!important
    }

    .px-md-5 {
        padding-right: 3rem!important;
        padding-left: 3rem!important
    }

    .py-md-0 {
        padding-top: 0!important;
        padding-bottom: 0!important
    }

    .py-md-1 {
        padding-top: .25rem!important;
        padding-bottom: .25rem!important
    }

    .py-md-2 {
        padding-top: .5rem!important;
        padding-bottom: .5rem!important
    }

    .py-md-3 {
        padding-top: 1rem!important;
        padding-bottom: 1rem!important
    }

    .py-md-4 {
        padding-top: 1.5rem!important;
        padding-bottom: 1.5rem!important
    }

    .py-md-5 {
        padding-top: 3rem!important;
        padding-bottom: 3rem!important
    }

    .pt-md-0 {
        padding-top: 0!important
    }

    .pt-md-1 {
        padding-top: .25rem!important
    }

    .pt-md-2 {
        padding-top: .5rem!important
    }

    .pt-md-3 {
        padding-top: 1rem!important
    }

    .pt-md-4 {
        padding-top: 1.5rem!important
    }

    .pt-md-5 {
        padding-top: 3rem!important
    }

    .pe-md-0 {
        padding-right: 0!important
    }

    .pe-md-1 {
        padding-right: .25rem!important
    }

    .pe-md-2 {
        padding-right: .5rem!important
    }

    .pe-md-3 {
        padding-right: 1rem!important
    }

    .pe-md-4 {
        padding-right: 1.5rem!important
    }

    .pe-md-5 {
        padding-right: 3rem!important
    }

    .pb-md-0 {
        padding-bottom: 0!important
    }

    .pb-md-1 {
        padding-bottom: .25rem!important
    }

    .pb-md-2 {
        padding-bottom: .5rem!important
    }

    .pb-md-3 {
        padding-bottom: 1rem!important
    }

    .pb-md-4 {
        padding-bottom: 1.5rem!important
    }

    .pb-md-5 {
        padding-bottom: 3rem!important
    }

    .ps-md-0 {
        padding-left: 0!important
    }

    .ps-md-1 {
        padding-left: .25rem!important
    }

    .ps-md-2 {
        padding-left: .5rem!important
    }

    .ps-md-3 {
        padding-left: 1rem!important
    }

    .ps-md-4 {
        padding-left: 1.5rem!important
    }

    .ps-md-5 {
        padding-left: 3rem!important
    }

    .gap-md-0 {
        gap: 0!important
    }

    .gap-md-1 {
        gap: .25rem!important
    }

    .gap-md-2 {
        gap: .5rem!important
    }

    .gap-md-3 {
        gap: 1rem!important
    }

    .gap-md-4 {
        gap: 1.5rem!important
    }

    .gap-md-5 {
        gap: 3rem!important
    }

    .row-gap-md-0 {
        row-gap: 0!important
    }

    .row-gap-md-1 {
        row-gap: .25rem!important
    }

    .row-gap-md-2 {
        row-gap: .5rem!important
    }

    .row-gap-md-3 {
        row-gap: 1rem!important
    }

    .row-gap-md-4 {
        row-gap: 1.5rem!important
    }

    .row-gap-md-5 {
        row-gap: 3rem!important
    }

    .column-gap-md-0 {
        -webkit-column-gap: 0!important;
        -moz-column-gap: 0!important;
        column-gap: 0!important
    }

    .column-gap-md-1 {
        -webkit-column-gap: .25rem!important;
        -moz-column-gap: .25rem!important;
        column-gap: .25rem!important
    }

    .column-gap-md-2 {
        -webkit-column-gap: .5rem!important;
        -moz-column-gap: .5rem!important;
        column-gap: .5rem!important
    }

    .column-gap-md-3 {
        -webkit-column-gap: 1rem!important;
        -moz-column-gap: 1rem!important;
        column-gap: 1rem!important
    }

    .column-gap-md-4 {
        -webkit-column-gap: 1.5rem!important;
        -moz-column-gap: 1.5rem!important;
        column-gap: 1.5rem!important
    }

    .column-gap-md-5 {
        -webkit-column-gap: 3rem!important;
        -moz-column-gap: 3rem!important;
        column-gap: 3rem!important
    }

    .text-md-start {
        text-align: left!important
    }

    .text-md-end {
        text-align: right!important
    }

    .text-md-center {
        text-align: center!important
    }
}

@media (min-width: 992px) {
    .float-lg-start {
        float:left!important
    }

    .float-lg-end {
        float: right!important
    }

    .float-lg-none {
        float: none!important
    }

    .object-fit-lg-contain {
        -o-object-fit: contain!important;
        object-fit: contain!important
    }

    .object-fit-lg-cover {
        -o-object-fit: cover!important;
        object-fit: cover!important
    }

    .object-fit-lg-fill {
        -o-object-fit: fill!important;
        object-fit: fill!important
    }

    .object-fit-lg-scale {
        -o-object-fit: scale-down!important;
        object-fit: scale-down!important
    }

    .object-fit-lg-none {
        -o-object-fit: none!important;
        object-fit: none!important
    }

    .d-lg-inline {
        display: inline!important
    }

    .d-lg-inline-block {
        display: inline-block!important
    }

    .d-lg-block {
        display: block!important
    }

    .d-lg-grid {
        display: grid!important
    }

    .d-lg-inline-grid {
        display: inline-grid!important
    }

    .d-lg-table {
        display: table!important
    }

    .d-lg-table-row {
        display: table-row!important
    }

    .d-lg-table-cell {
        display: table-cell!important
    }

    .d-lg-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important
    }

    .d-lg-inline-flex {
        display: -webkit-inline-box!important;
        display: -ms-inline-flexbox!important;
        display: inline-flex!important
    }

    .d-lg-none {
        display: none!important
    }

    .flex-lg-fill {
        -webkit-box-flex: 1!important;
        -ms-flex: 1 1 auto!important;
        flex: 1 1 auto!important
    }

    .flex-lg-column,.flex-lg-row {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: normal!important;
        -ms-flex-direction: row!important;
        flex-direction: row!important
    }

    .flex-lg-column {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column!important;
        flex-direction: column!important
    }

    .flex-lg-column-reverse,.flex-lg-row-reverse {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: reverse!important;
        -ms-flex-direction: row-reverse!important;
        flex-direction: row-reverse!important
    }

    .flex-lg-column-reverse {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column-reverse!important;
        flex-direction: column-reverse!important
    }

    .flex-lg-grow-0 {
        -webkit-box-flex: 0!important;
        -ms-flex-positive: 0!important;
        flex-grow: 0!important
    }

    .flex-lg-grow-1 {
        -webkit-box-flex: 1!important;
        -ms-flex-positive: 1!important;
        flex-grow: 1!important
    }

    .flex-lg-shrink-0 {
        -ms-flex-negative: 0!important;
        flex-shrink: 0!important
    }

    .flex-lg-shrink-1 {
        -ms-flex-negative: 1!important;
        flex-shrink: 1!important
    }

    .flex-lg-wrap {
        -ms-flex-wrap: wrap!important;
        flex-wrap: wrap!important
    }

    .flex-lg-nowrap {
        -ms-flex-wrap: nowrap!important;
        flex-wrap: nowrap!important
    }

    .flex-lg-wrap-reverse {
        -ms-flex-wrap: wrap-reverse!important;
        flex-wrap: wrap-reverse!important
    }

    .justify-content-lg-start {
        -webkit-box-pack: start!important;
        -ms-flex-pack: start!important;
        justify-content: flex-start!important
    }

    .justify-content-lg-end {
        -webkit-box-pack: end!important;
        -ms-flex-pack: end!important;
        justify-content: flex-end!important
    }

    .justify-content-lg-center {
        -webkit-box-pack: center!important;
        -ms-flex-pack: center!important;
        justify-content: center!important
    }

    .justify-content-lg-between {
        -webkit-box-pack: justify!important;
        -ms-flex-pack: justify!important;
        justify-content: space-between!important
    }

    .justify-content-lg-around {
        -ms-flex-pack: distribute!important;
        justify-content: space-around!important
    }

    .justify-content-lg-evenly {
        -webkit-box-pack: space-evenly!important;
        -ms-flex-pack: space-evenly!important;
        justify-content: space-evenly!important
    }

    .align-items-lg-start {
        -webkit-box-align: start!important;
        -ms-flex-align: start!important;
        align-items: flex-start!important
    }

    .align-items-lg-end {
        -webkit-box-align: end!important;
        -ms-flex-align: end!important;
        align-items: flex-end!important
    }

    .align-items-lg-center {
        -webkit-box-align: center!important;
        -ms-flex-align: center!important;
        align-items: center!important
    }

    .align-items-lg-baseline {
        -webkit-box-align: baseline!important;
        -ms-flex-align: baseline!important;
        align-items: baseline!important
    }

    .align-items-lg-stretch {
        -webkit-box-align: stretch!important;
        -ms-flex-align: stretch!important;
        align-items: stretch!important
    }

    .align-content-lg-start {
        -ms-flex-line-pack: start!important;
        align-content: flex-start!important
    }

    .align-content-lg-end {
        -ms-flex-line-pack: end!important;
        align-content: flex-end!important
    }

    .align-content-lg-center {
        -ms-flex-line-pack: center!important;
        align-content: center!important
    }

    .align-content-lg-between {
        -ms-flex-line-pack: justify!important;
        align-content: space-between!important
    }

    .align-content-lg-around {
        -ms-flex-line-pack: distribute!important;
        align-content: space-around!important
    }

    .align-content-lg-stretch {
        -ms-flex-line-pack: stretch!important;
        align-content: stretch!important
    }

    .align-self-lg-auto {
        -ms-flex-item-align: auto!important;
        align-self: auto!important
    }

    .align-self-lg-start {
        -ms-flex-item-align: start!important;
        align-self: flex-start!important
    }

    .align-self-lg-end {
        -ms-flex-item-align: end!important;
        align-self: flex-end!important
    }

    .align-self-lg-center {
        -ms-flex-item-align: center!important;
        align-self: center!important
    }

    .align-self-lg-baseline {
        -ms-flex-item-align: baseline!important;
        align-self: baseline!important
    }

    .align-self-lg-stretch {
        -ms-flex-item-align: stretch!important;
        align-self: stretch!important
    }

    .order-lg-first {
        -webkit-box-ordinal-group: 0!important;
        -ms-flex-order: -1!important;
        order: -1!important
    }

    .order-lg-0 {
        -webkit-box-ordinal-group: 1!important;
        -ms-flex-order: 0!important;
        order: 0!important
    }

    .order-lg-1 {
        -webkit-box-ordinal-group: 2!important;
        -ms-flex-order: 1!important;
        order: 1!important
    }

    .order-lg-2 {
        -webkit-box-ordinal-group: 3!important;
        -ms-flex-order: 2!important;
        order: 2!important
    }

    .order-lg-3 {
        -webkit-box-ordinal-group: 4!important;
        -ms-flex-order: 3!important;
        order: 3!important
    }

    .order-lg-4 {
        -webkit-box-ordinal-group: 5!important;
        -ms-flex-order: 4!important;
        order: 4!important
    }

    .order-lg-5 {
        -webkit-box-ordinal-group: 6!important;
        -ms-flex-order: 5!important;
        order: 5!important
    }

    .order-lg-last {
        -webkit-box-ordinal-group: 7!important;
        -ms-flex-order: 6!important;
        order: 6!important
    }

    .m-lg-0 {
        margin: 0!important
    }

    .m-lg-1 {
        margin: .25rem!important
    }

    .m-lg-2 {
        margin: .5rem!important
    }

    .m-lg-3 {
        margin: 1rem!important
    }

    .m-lg-4 {
        margin: 1.5rem!important
    }

    .m-lg-5 {
        margin: 3rem!important
    }

    .m-lg-auto {
        margin: auto!important
    }

    .mx-lg-0 {
        margin-right: 0!important;
        margin-left: 0!important
    }

    .mx-lg-1 {
        margin-right: .25rem!important;
        margin-left: .25rem!important
    }

    .mx-lg-2 {
        margin-right: .5rem!important;
        margin-left: .5rem!important
    }

    .mx-lg-3 {
        margin-right: 1rem!important;
        margin-left: 1rem!important
    }

    .mx-lg-4 {
        margin-right: 1.5rem!important;
        margin-left: 1.5rem!important
    }

    .mx-lg-5 {
        margin-right: 3rem!important;
        margin-left: 3rem!important
    }

    .mx-lg-auto {
        margin-right: auto!important;
        margin-left: auto!important
    }

    .my-lg-0 {
        margin-top: 0!important;
        margin-bottom: 0!important
    }

    .my-lg-1 {
        margin-top: .25rem!important;
        margin-bottom: .25rem!important
    }

    .my-lg-2 {
        margin-top: .5rem!important;
        margin-bottom: .5rem!important
    }

    .my-lg-3 {
        margin-top: 1rem!important;
        margin-bottom: 1rem!important
    }

    .my-lg-4 {
        margin-top: 1.5rem!important;
        margin-bottom: 1.5rem!important
    }

    .my-lg-5 {
        margin-top: 3rem!important;
        margin-bottom: 3rem!important
    }

    .my-lg-auto {
        margin-top: auto!important;
        margin-bottom: auto!important
    }

    .mt-lg-0 {
        margin-top: 0!important
    }

    .mt-lg-1 {
        margin-top: .25rem!important
    }

    .mt-lg-2 {
        margin-top: .5rem!important
    }

    .mt-lg-3 {
        margin-top: 1rem!important
    }

    .mt-lg-4 {
        margin-top: 1.5rem!important
    }

    .mt-lg-5 {
        margin-top: 3rem!important
    }

    .mt-lg-auto {
        margin-top: auto!important
    }

    .me-lg-0 {
        margin-right: 0!important
    }

    .me-lg-1 {
        margin-right: .25rem!important
    }

    .me-lg-2 {
        margin-right: .5rem!important
    }

    .me-lg-3 {
        margin-right: 1rem!important
    }

    .me-lg-4 {
        margin-right: 1.5rem!important
    }

    .me-lg-5 {
        margin-right: 3rem!important
    }

    .me-lg-auto {
        margin-right: auto!important
    }

    .mb-lg-0 {
        margin-bottom: 0!important
    }

    .mb-lg-1 {
        margin-bottom: .25rem!important
    }

    .mb-lg-2 {
        margin-bottom: .5rem!important
    }

    .mb-lg-3 {
        margin-bottom: 1rem!important
    }

    .mb-lg-4 {
        margin-bottom: 1.5rem!important
    }

    .mb-lg-5 {
        margin-bottom: 3rem!important
    }

    .mb-lg-auto {
        margin-bottom: auto!important
    }

    .ms-lg-0 {
        margin-left: 0!important
    }

    .ms-lg-1 {
        margin-left: .25rem!important
    }

    .ms-lg-2 {
        margin-left: .5rem!important
    }

    .ms-lg-3 {
        margin-left: 1rem!important
    }

    .ms-lg-4 {
        margin-left: 1.5rem!important
    }

    .ms-lg-5 {
        margin-left: 3rem!important
    }

    .ms-lg-auto {
        margin-left: auto!important
    }

    .p-lg-0 {
        padding: 0!important
    }

    .p-lg-1 {
        padding: .25rem!important
    }

    .p-lg-2 {
        padding: .5rem!important
    }

    .p-lg-3 {
        padding: 1rem!important
    }

    .p-lg-4 {
        padding: 1.5rem!important
    }

    .p-lg-5 {
        padding: 3rem!important
    }

    .px-lg-0 {
        padding-right: 0!important;
        padding-left: 0!important
    }

    .px-lg-1 {
        padding-right: .25rem!important;
        padding-left: .25rem!important
    }

    .px-lg-2 {
        padding-right: .5rem!important;
        padding-left: .5rem!important
    }

    .px-lg-3 {
        padding-right: 1rem!important;
        padding-left: 1rem!important
    }

    .px-lg-4 {
        padding-right: 1.5rem!important;
        padding-left: 1.5rem!important
    }

    .px-lg-5 {
        padding-right: 3rem!important;
        padding-left: 3rem!important
    }

    .py-lg-0 {
        padding-top: 0!important;
        padding-bottom: 0!important
    }

    .py-lg-1 {
        padding-top: .25rem!important;
        padding-bottom: .25rem!important
    }

    .py-lg-2 {
        padding-top: .5rem!important;
        padding-bottom: .5rem!important
    }

    .py-lg-3 {
        padding-top: 1rem!important;
        padding-bottom: 1rem!important
    }

    .py-lg-4 {
        padding-top: 1.5rem!important;
        padding-bottom: 1.5rem!important
    }

    .py-lg-5 {
        padding-top: 3rem!important;
        padding-bottom: 3rem!important
    }

    .pt-lg-0 {
        padding-top: 0!important
    }

    .pt-lg-1 {
        padding-top: .25rem!important
    }

    .pt-lg-2 {
        padding-top: .5rem!important
    }

    .pt-lg-3 {
        padding-top: 1rem!important
    }

    .pt-lg-4 {
        padding-top: 1.5rem!important
    }

    .pt-lg-5 {
        padding-top: 3rem!important
    }

    .pe-lg-0 {
        padding-right: 0!important
    }

    .pe-lg-1 {
        padding-right: .25rem!important
    }

    .pe-lg-2 {
        padding-right: .5rem!important
    }

    .pe-lg-3 {
        padding-right: 1rem!important
    }

    .pe-lg-4 {
        padding-right: 1.5rem!important
    }

    .pe-lg-5 {
        padding-right: 3rem!important
    }

    .pb-lg-0 {
        padding-bottom: 0!important
    }

    .pb-lg-1 {
        padding-bottom: .25rem!important
    }

    .pb-lg-2 {
        padding-bottom: .5rem!important
    }

    .pb-lg-3 {
        padding-bottom: 1rem!important
    }

    .pb-lg-4 {
        padding-bottom: 1.5rem!important
    }

    .pb-lg-5 {
        padding-bottom: 3rem!important
    }

    .ps-lg-0 {
        padding-left: 0!important
    }

    .ps-lg-1 {
        padding-left: .25rem!important
    }

    .ps-lg-2 {
        padding-left: .5rem!important
    }

    .ps-lg-3 {
        padding-left: 1rem!important
    }

    .ps-lg-4 {
        padding-left: 1.5rem!important
    }

    .ps-lg-5 {
        padding-left: 3rem!important
    }

    .gap-lg-0 {
        gap: 0!important
    }

    .gap-lg-1 {
        gap: .25rem!important
    }

    .gap-lg-2 {
        gap: .5rem!important
    }

    .gap-lg-3 {
        gap: 1rem!important
    }

    .gap-lg-4 {
        gap: 1.5rem!important
    }

    .gap-lg-5 {
        gap: 3rem!important
    }

    .row-gap-lg-0 {
        row-gap: 0!important
    }

    .row-gap-lg-1 {
        row-gap: .25rem!important
    }

    .row-gap-lg-2 {
        row-gap: .5rem!important
    }

    .row-gap-lg-3 {
        row-gap: 1rem!important
    }

    .row-gap-lg-4 {
        row-gap: 1.5rem!important
    }

    .row-gap-lg-5 {
        row-gap: 3rem!important
    }

    .column-gap-lg-0 {
        -webkit-column-gap: 0!important;
        -moz-column-gap: 0!important;
        column-gap: 0!important
    }

    .column-gap-lg-1 {
        -webkit-column-gap: .25rem!important;
        -moz-column-gap: .25rem!important;
        column-gap: .25rem!important
    }

    .column-gap-lg-2 {
        -webkit-column-gap: .5rem!important;
        -moz-column-gap: .5rem!important;
        column-gap: .5rem!important
    }

    .column-gap-lg-3 {
        -webkit-column-gap: 1rem!important;
        -moz-column-gap: 1rem!important;
        column-gap: 1rem!important
    }

    .column-gap-lg-4 {
        -webkit-column-gap: 1.5rem!important;
        -moz-column-gap: 1.5rem!important;
        column-gap: 1.5rem!important
    }

    .column-gap-lg-5 {
        -webkit-column-gap: 3rem!important;
        -moz-column-gap: 3rem!important;
        column-gap: 3rem!important
    }

    .text-lg-start {
        text-align: left!important
    }

    .text-lg-end {
        text-align: right!important
    }

    .text-lg-center {
        text-align: center!important
    }
}

@media (min-width: 1200px) {
    .float-xl-start {
        float:left!important
    }

    .float-xl-end {
        float: right!important
    }

    .float-xl-none {
        float: none!important
    }

    .object-fit-xl-contain {
        -o-object-fit: contain!important;
        object-fit: contain!important
    }

    .object-fit-xl-cover {
        -o-object-fit: cover!important;
        object-fit: cover!important
    }

    .object-fit-xl-fill {
        -o-object-fit: fill!important;
        object-fit: fill!important
    }

    .object-fit-xl-scale {
        -o-object-fit: scale-down!important;
        object-fit: scale-down!important
    }

    .object-fit-xl-none {
        -o-object-fit: none!important;
        object-fit: none!important
    }

    .d-xl-inline {
        display: inline!important
    }

    .d-xl-inline-block {
        display: inline-block!important
    }

    .d-xl-block {
        display: block!important
    }

    .d-xl-grid {
        display: grid!important
    }

    .d-xl-inline-grid {
        display: inline-grid!important
    }

    .d-xl-table {
        display: table!important
    }

    .d-xl-table-row {
        display: table-row!important
    }

    .d-xl-table-cell {
        display: table-cell!important
    }

    .d-xl-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important
    }

    .d-xl-inline-flex {
        display: -webkit-inline-box!important;
        display: -ms-inline-flexbox!important;
        display: inline-flex!important
    }

    .d-xl-none {
        display: none!important
    }

    .flex-xl-fill {
        -webkit-box-flex: 1!important;
        -ms-flex: 1 1 auto!important;
        flex: 1 1 auto!important
    }

    .flex-xl-column,.flex-xl-row {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: normal!important;
        -ms-flex-direction: row!important;
        flex-direction: row!important
    }

    .flex-xl-column {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column!important;
        flex-direction: column!important
    }

    .flex-xl-column-reverse,.flex-xl-row-reverse {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: reverse!important;
        -ms-flex-direction: row-reverse!important;
        flex-direction: row-reverse!important
    }

    .flex-xl-column-reverse {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column-reverse!important;
        flex-direction: column-reverse!important
    }

    .flex-xl-grow-0 {
        -webkit-box-flex: 0!important;
        -ms-flex-positive: 0!important;
        flex-grow: 0!important
    }

    .flex-xl-grow-1 {
        -webkit-box-flex: 1!important;
        -ms-flex-positive: 1!important;
        flex-grow: 1!important
    }

    .flex-xl-shrink-0 {
        -ms-flex-negative: 0!important;
        flex-shrink: 0!important
    }

    .flex-xl-shrink-1 {
        -ms-flex-negative: 1!important;
        flex-shrink: 1!important
    }

    .flex-xl-wrap {
        -ms-flex-wrap: wrap!important;
        flex-wrap: wrap!important
    }

    .flex-xl-nowrap {
        -ms-flex-wrap: nowrap!important;
        flex-wrap: nowrap!important
    }

    .flex-xl-wrap-reverse {
        -ms-flex-wrap: wrap-reverse!important;
        flex-wrap: wrap-reverse!important
    }

    .justify-content-xl-start {
        -webkit-box-pack: start!important;
        -ms-flex-pack: start!important;
        justify-content: flex-start!important
    }

    .justify-content-xl-end {
        -webkit-box-pack: end!important;
        -ms-flex-pack: end!important;
        justify-content: flex-end!important
    }

    .justify-content-xl-center {
        -webkit-box-pack: center!important;
        -ms-flex-pack: center!important;
        justify-content: center!important
    }

    .justify-content-xl-between {
        -webkit-box-pack: justify!important;
        -ms-flex-pack: justify!important;
        justify-content: space-between!important
    }

    .justify-content-xl-around {
        -ms-flex-pack: distribute!important;
        justify-content: space-around!important
    }

    .justify-content-xl-evenly {
        -webkit-box-pack: space-evenly!important;
        -ms-flex-pack: space-evenly!important;
        justify-content: space-evenly!important
    }

    .align-items-xl-start {
        -webkit-box-align: start!important;
        -ms-flex-align: start!important;
        align-items: flex-start!important
    }

    .align-items-xl-end {
        -webkit-box-align: end!important;
        -ms-flex-align: end!important;
        align-items: flex-end!important
    }

    .align-items-xl-center {
        -webkit-box-align: center!important;
        -ms-flex-align: center!important;
        align-items: center!important
    }

    .align-items-xl-baseline {
        -webkit-box-align: baseline!important;
        -ms-flex-align: baseline!important;
        align-items: baseline!important
    }

    .align-items-xl-stretch {
        -webkit-box-align: stretch!important;
        -ms-flex-align: stretch!important;
        align-items: stretch!important
    }

    .align-content-xl-start {
        -ms-flex-line-pack: start!important;
        align-content: flex-start!important
    }

    .align-content-xl-end {
        -ms-flex-line-pack: end!important;
        align-content: flex-end!important
    }

    .align-content-xl-center {
        -ms-flex-line-pack: center!important;
        align-content: center!important
    }

    .align-content-xl-between {
        -ms-flex-line-pack: justify!important;
        align-content: space-between!important
    }

    .align-content-xl-around {
        -ms-flex-line-pack: distribute!important;
        align-content: space-around!important
    }

    .align-content-xl-stretch {
        -ms-flex-line-pack: stretch!important;
        align-content: stretch!important
    }

    .align-self-xl-auto {
        -ms-flex-item-align: auto!important;
        align-self: auto!important
    }

    .align-self-xl-start {
        -ms-flex-item-align: start!important;
        align-self: flex-start!important
    }

    .align-self-xl-end {
        -ms-flex-item-align: end!important;
        align-self: flex-end!important
    }

    .align-self-xl-center {
        -ms-flex-item-align: center!important;
        align-self: center!important
    }

    .align-self-xl-baseline {
        -ms-flex-item-align: baseline!important;
        align-self: baseline!important
    }

    .align-self-xl-stretch {
        -ms-flex-item-align: stretch!important;
        align-self: stretch!important
    }

    .order-xl-first {
        -webkit-box-ordinal-group: 0!important;
        -ms-flex-order: -1!important;
        order: -1!important
    }

    .order-xl-0 {
        -webkit-box-ordinal-group: 1!important;
        -ms-flex-order: 0!important;
        order: 0!important
    }

    .order-xl-1 {
        -webkit-box-ordinal-group: 2!important;
        -ms-flex-order: 1!important;
        order: 1!important
    }

    .order-xl-2 {
        -webkit-box-ordinal-group: 3!important;
        -ms-flex-order: 2!important;
        order: 2!important
    }

    .order-xl-3 {
        -webkit-box-ordinal-group: 4!important;
        -ms-flex-order: 3!important;
        order: 3!important
    }

    .order-xl-4 {
        -webkit-box-ordinal-group: 5!important;
        -ms-flex-order: 4!important;
        order: 4!important
    }

    .order-xl-5 {
        -webkit-box-ordinal-group: 6!important;
        -ms-flex-order: 5!important;
        order: 5!important
    }

    .order-xl-last {
        -webkit-box-ordinal-group: 7!important;
        -ms-flex-order: 6!important;
        order: 6!important
    }

    .m-xl-0 {
        margin: 0!important
    }

    .m-xl-1 {
        margin: .25rem!important
    }

    .m-xl-2 {
        margin: .5rem!important
    }

    .m-xl-3 {
        margin: 1rem!important
    }

    .m-xl-4 {
        margin: 1.5rem!important
    }

    .m-xl-5 {
        margin: 3rem!important
    }

    .m-xl-auto {
        margin: auto!important
    }

    .mx-xl-0 {
        margin-right: 0!important;
        margin-left: 0!important
    }

    .mx-xl-1 {
        margin-right: .25rem!important;
        margin-left: .25rem!important
    }

    .mx-xl-2 {
        margin-right: .5rem!important;
        margin-left: .5rem!important
    }

    .mx-xl-3 {
        margin-right: 1rem!important;
        margin-left: 1rem!important
    }

    .mx-xl-4 {
        margin-right: 1.5rem!important;
        margin-left: 1.5rem!important
    }

    .mx-xl-5 {
        margin-right: 3rem!important;
        margin-left: 3rem!important
    }

    .mx-xl-auto {
        margin-right: auto!important;
        margin-left: auto!important
    }

    .my-xl-0 {
        margin-top: 0!important;
        margin-bottom: 0!important
    }

    .my-xl-1 {
        margin-top: .25rem!important;
        margin-bottom: .25rem!important
    }

    .my-xl-2 {
        margin-top: .5rem!important;
        margin-bottom: .5rem!important
    }

    .my-xl-3 {
        margin-top: 1rem!important;
        margin-bottom: 1rem!important
    }

    .my-xl-4 {
        margin-top: 1.5rem!important;
        margin-bottom: 1.5rem!important
    }

    .my-xl-5 {
        margin-top: 3rem!important;
        margin-bottom: 3rem!important
    }

    .my-xl-auto {
        margin-top: auto!important;
        margin-bottom: auto!important
    }

    .mt-xl-0 {
        margin-top: 0!important
    }

    .mt-xl-1 {
        margin-top: .25rem!important
    }

    .mt-xl-2 {
        margin-top: .5rem!important
    }

    .mt-xl-3 {
        margin-top: 1rem!important
    }

    .mt-xl-4 {
        margin-top: 1.5rem!important
    }

    .mt-xl-5 {
        margin-top: 3rem!important
    }

    .mt-xl-auto {
        margin-top: auto!important
    }

    .me-xl-0 {
        margin-right: 0!important
    }

    .me-xl-1 {
        margin-right: .25rem!important
    }

    .me-xl-2 {
        margin-right: .5rem!important
    }

    .me-xl-3 {
        margin-right: 1rem!important
    }

    .me-xl-4 {
        margin-right: 1.5rem!important
    }

    .me-xl-5 {
        margin-right: 3rem!important
    }

    .me-xl-auto {
        margin-right: auto!important
    }

    .mb-xl-0 {
        margin-bottom: 0!important
    }

    .mb-xl-1 {
        margin-bottom: .25rem!important
    }

    .mb-xl-2 {
        margin-bottom: .5rem!important
    }

    .mb-xl-3 {
        margin-bottom: 1rem!important
    }

    .mb-xl-4 {
        margin-bottom: 1.5rem!important
    }

    .mb-xl-5 {
        margin-bottom: 3rem!important
    }

    .mb-xl-auto {
        margin-bottom: auto!important
    }

    .ms-xl-0 {
        margin-left: 0!important
    }

    .ms-xl-1 {
        margin-left: .25rem!important
    }

    .ms-xl-2 {
        margin-left: .5rem!important
    }

    .ms-xl-3 {
        margin-left: 1rem!important
    }

    .ms-xl-4 {
        margin-left: 1.5rem!important
    }

    .ms-xl-5 {
        margin-left: 3rem!important
    }

    .ms-xl-auto {
        margin-left: auto!important
    }

    .p-xl-0 {
        padding: 0!important
    }

    .p-xl-1 {
        padding: .25rem!important
    }

    .p-xl-2 {
        padding: .5rem!important
    }

    .p-xl-3 {
        padding: 1rem!important
    }

    .p-xl-4 {
        padding: 1.5rem!important
    }

    .p-xl-5 {
        padding: 3rem!important
    }

    .px-xl-0 {
        padding-right: 0!important;
        padding-left: 0!important
    }

    .px-xl-1 {
        padding-right: .25rem!important;
        padding-left: .25rem!important
    }

    .px-xl-2 {
        padding-right: .5rem!important;
        padding-left: .5rem!important
    }

    .px-xl-3 {
        padding-right: 1rem!important;
        padding-left: 1rem!important
    }

    .px-xl-4 {
        padding-right: 1.5rem!important;
        padding-left: 1.5rem!important
    }

    .px-xl-5 {
        padding-right: 3rem!important;
        padding-left: 3rem!important
    }

    .py-xl-0 {
        padding-top: 0!important;
        padding-bottom: 0!important
    }

    .py-xl-1 {
        padding-top: .25rem!important;
        padding-bottom: .25rem!important
    }

    .py-xl-2 {
        padding-top: .5rem!important;
        padding-bottom: .5rem!important
    }

    .py-xl-3 {
        padding-top: 1rem!important;
        padding-bottom: 1rem!important
    }

    .py-xl-4 {
        padding-top: 1.5rem!important;
        padding-bottom: 1.5rem!important
    }

    .py-xl-5 {
        padding-top: 3rem!important;
        padding-bottom: 3rem!important
    }

    .pt-xl-0 {
        padding-top: 0!important
    }

    .pt-xl-1 {
        padding-top: .25rem!important
    }

    .pt-xl-2 {
        padding-top: .5rem!important
    }

    .pt-xl-3 {
        padding-top: 1rem!important
    }

    .pt-xl-4 {
        padding-top: 1.5rem!important
    }

    .pt-xl-5 {
        padding-top: 3rem!important
    }

    .pe-xl-0 {
        padding-right: 0!important
    }

    .pe-xl-1 {
        padding-right: .25rem!important
    }

    .pe-xl-2 {
        padding-right: .5rem!important
    }

    .pe-xl-3 {
        padding-right: 1rem!important
    }

    .pe-xl-4 {
        padding-right: 1.5rem!important
    }

    .pe-xl-5 {
        padding-right: 3rem!important
    }

    .pb-xl-0 {
        padding-bottom: 0!important
    }

    .pb-xl-1 {
        padding-bottom: .25rem!important
    }

    .pb-xl-2 {
        padding-bottom: .5rem!important
    }

    .pb-xl-3 {
        padding-bottom: 1rem!important
    }

    .pb-xl-4 {
        padding-bottom: 1.5rem!important
    }

    .pb-xl-5 {
        padding-bottom: 3rem!important
    }

    .ps-xl-0 {
        padding-left: 0!important
    }

    .ps-xl-1 {
        padding-left: .25rem!important
    }

    .ps-xl-2 {
        padding-left: .5rem!important
    }

    .ps-xl-3 {
        padding-left: 1rem!important
    }

    .ps-xl-4 {
        padding-left: 1.5rem!important
    }

    .ps-xl-5 {
        padding-left: 3rem!important
    }

    .gap-xl-0 {
        gap: 0!important
    }

    .gap-xl-1 {
        gap: .25rem!important
    }

    .gap-xl-2 {
        gap: .5rem!important
    }

    .gap-xl-3 {
        gap: 1rem!important
    }

    .gap-xl-4 {
        gap: 1.5rem!important
    }

    .gap-xl-5 {
        gap: 3rem!important
    }

    .row-gap-xl-0 {
        row-gap: 0!important
    }

    .row-gap-xl-1 {
        row-gap: .25rem!important
    }

    .row-gap-xl-2 {
        row-gap: .5rem!important
    }

    .row-gap-xl-3 {
        row-gap: 1rem!important
    }

    .row-gap-xl-4 {
        row-gap: 1.5rem!important
    }

    .row-gap-xl-5 {
        row-gap: 3rem!important
    }

    .column-gap-xl-0 {
        -webkit-column-gap: 0!important;
        -moz-column-gap: 0!important;
        column-gap: 0!important
    }

    .column-gap-xl-1 {
        -webkit-column-gap: .25rem!important;
        -moz-column-gap: .25rem!important;
        column-gap: .25rem!important
    }

    .column-gap-xl-2 {
        -webkit-column-gap: .5rem!important;
        -moz-column-gap: .5rem!important;
        column-gap: .5rem!important
    }

    .column-gap-xl-3 {
        -webkit-column-gap: 1rem!important;
        -moz-column-gap: 1rem!important;
        column-gap: 1rem!important
    }

    .column-gap-xl-4 {
        -webkit-column-gap: 1.5rem!important;
        -moz-column-gap: 1.5rem!important;
        column-gap: 1.5rem!important
    }

    .column-gap-xl-5 {
        -webkit-column-gap: 3rem!important;
        -moz-column-gap: 3rem!important;
        column-gap: 3rem!important
    }

    .text-xl-start {
        text-align: left!important
    }

    .text-xl-end {
        text-align: right!important
    }

    .text-xl-center {
        text-align: center!important
    }
}

@media (min-width: 1400px) {
    .float-xxl-start {
        float:left!important
    }

    .float-xxl-end {
        float: right!important
    }

    .float-xxl-none {
        float: none!important
    }

    .object-fit-xxl-contain {
        -o-object-fit: contain!important;
        object-fit: contain!important
    }

    .object-fit-xxl-cover {
        -o-object-fit: cover!important;
        object-fit: cover!important
    }

    .object-fit-xxl-fill {
        -o-object-fit: fill!important;
        object-fit: fill!important
    }

    .object-fit-xxl-scale {
        -o-object-fit: scale-down!important;
        object-fit: scale-down!important
    }

    .object-fit-xxl-none {
        -o-object-fit: none!important;
        object-fit: none!important
    }

    .d-xxl-inline {
        display: inline!important
    }

    .d-xxl-inline-block {
        display: inline-block!important
    }

    .d-xxl-block {
        display: block!important
    }

    .d-xxl-grid {
        display: grid!important
    }

    .d-xxl-inline-grid {
        display: inline-grid!important
    }

    .d-xxl-table {
        display: table!important
    }

    .d-xxl-table-row {
        display: table-row!important
    }

    .d-xxl-table-cell {
        display: table-cell!important
    }

    .d-xxl-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important
    }

    .d-xxl-inline-flex {
        display: -webkit-inline-box!important;
        display: -ms-inline-flexbox!important;
        display: inline-flex!important
    }

    .d-xxl-none {
        display: none!important
    }

    .flex-xxl-fill {
        -webkit-box-flex: 1!important;
        -ms-flex: 1 1 auto!important;
        flex: 1 1 auto!important
    }

    .flex-xxl-column,.flex-xxl-row {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: normal!important;
        -ms-flex-direction: row!important;
        flex-direction: row!important
    }

    .flex-xxl-column {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column!important;
        flex-direction: column!important
    }

    .flex-xxl-column-reverse,.flex-xxl-row-reverse {
        -webkit-box-orient: horizontal!important;
        -webkit-box-direction: reverse!important;
        -ms-flex-direction: row-reverse!important;
        flex-direction: row-reverse!important
    }

    .flex-xxl-column-reverse {
        -webkit-box-orient: vertical!important;
        -ms-flex-direction: column-reverse!important;
        flex-direction: column-reverse!important
    }

    .flex-xxl-grow-0 {
        -webkit-box-flex: 0!important;
        -ms-flex-positive: 0!important;
        flex-grow: 0!important
    }

    .flex-xxl-grow-1 {
        -webkit-box-flex: 1!important;
        -ms-flex-positive: 1!important;
        flex-grow: 1!important
    }

    .flex-xxl-shrink-0 {
        -ms-flex-negative: 0!important;
        flex-shrink: 0!important
    }

    .flex-xxl-shrink-1 {
        -ms-flex-negative: 1!important;
        flex-shrink: 1!important
    }

    .flex-xxl-wrap {
        -ms-flex-wrap: wrap!important;
        flex-wrap: wrap!important
    }

    .flex-xxl-nowrap {
        -ms-flex-wrap: nowrap!important;
        flex-wrap: nowrap!important
    }

    .flex-xxl-wrap-reverse {
        -ms-flex-wrap: wrap-reverse!important;
        flex-wrap: wrap-reverse!important
    }

    .justify-content-xxl-start {
        -webkit-box-pack: start!important;
        -ms-flex-pack: start!important;
        justify-content: flex-start!important
    }

    .justify-content-xxl-end {
        -webkit-box-pack: end!important;
        -ms-flex-pack: end!important;
        justify-content: flex-end!important
    }

    .justify-content-xxl-center {
        -webkit-box-pack: center!important;
        -ms-flex-pack: center!important;
        justify-content: center!important
    }

    .justify-content-xxl-between {
        -webkit-box-pack: justify!important;
        -ms-flex-pack: justify!important;
        justify-content: space-between!important
    }

    .justify-content-xxl-around {
        -ms-flex-pack: distribute!important;
        justify-content: space-around!important
    }

    .justify-content-xxl-evenly {
        -webkit-box-pack: space-evenly!important;
        -ms-flex-pack: space-evenly!important;
        justify-content: space-evenly!important
    }

    .align-items-xxl-start {
        -webkit-box-align: start!important;
        -ms-flex-align: start!important;
        align-items: flex-start!important
    }

    .align-items-xxl-end {
        -webkit-box-align: end!important;
        -ms-flex-align: end!important;
        align-items: flex-end!important
    }

    .align-items-xxl-center {
        -webkit-box-align: center!important;
        -ms-flex-align: center!important;
        align-items: center!important
    }

    .align-items-xxl-baseline {
        -webkit-box-align: baseline!important;
        -ms-flex-align: baseline!important;
        align-items: baseline!important
    }

    .align-items-xxl-stretch {
        -webkit-box-align: stretch!important;
        -ms-flex-align: stretch!important;
        align-items: stretch!important
    }

    .align-content-xxl-start {
        -ms-flex-line-pack: start!important;
        align-content: flex-start!important
    }

    .align-content-xxl-end {
        -ms-flex-line-pack: end!important;
        align-content: flex-end!important
    }

    .align-content-xxl-center {
        -ms-flex-line-pack: center!important;
        align-content: center!important
    }

    .align-content-xxl-between {
        -ms-flex-line-pack: justify!important;
        align-content: space-between!important
    }

    .align-content-xxl-around {
        -ms-flex-line-pack: distribute!important;
        align-content: space-around!important
    }

    .align-content-xxl-stretch {
        -ms-flex-line-pack: stretch!important;
        align-content: stretch!important
    }

    .align-self-xxl-auto {
        -ms-flex-item-align: auto!important;
        align-self: auto!important
    }

    .align-self-xxl-start {
        -ms-flex-item-align: start!important;
        align-self: flex-start!important
    }

    .align-self-xxl-end {
        -ms-flex-item-align: end!important;
        align-self: flex-end!important
    }

    .align-self-xxl-center {
        -ms-flex-item-align: center!important;
        align-self: center!important
    }

    .align-self-xxl-baseline {
        -ms-flex-item-align: baseline!important;
        align-self: baseline!important
    }

    .align-self-xxl-stretch {
        -ms-flex-item-align: stretch!important;
        align-self: stretch!important
    }

    .order-xxl-first {
        -webkit-box-ordinal-group: 0!important;
        -ms-flex-order: -1!important;
        order: -1!important
    }

    .order-xxl-0 {
        -webkit-box-ordinal-group: 1!important;
        -ms-flex-order: 0!important;
        order: 0!important
    }

    .order-xxl-1 {
        -webkit-box-ordinal-group: 2!important;
        -ms-flex-order: 1!important;
        order: 1!important
    }

    .order-xxl-2 {
        -webkit-box-ordinal-group: 3!important;
        -ms-flex-order: 2!important;
        order: 2!important
    }

    .order-xxl-3 {
        -webkit-box-ordinal-group: 4!important;
        -ms-flex-order: 3!important;
        order: 3!important
    }

    .order-xxl-4 {
        -webkit-box-ordinal-group: 5!important;
        -ms-flex-order: 4!important;
        order: 4!important
    }

    .order-xxl-5 {
        -webkit-box-ordinal-group: 6!important;
        -ms-flex-order: 5!important;
        order: 5!important
    }

    .order-xxl-last {
        -webkit-box-ordinal-group: 7!important;
        -ms-flex-order: 6!important;
        order: 6!important
    }

    .m-xxl-0 {
        margin: 0!important
    }

    .m-xxl-1 {
        margin: .25rem!important
    }

    .m-xxl-2 {
        margin: .5rem!important
    }

    .m-xxl-3 {
        margin: 1rem!important
    }

    .m-xxl-4 {
        margin: 1.5rem!important
    }

    .m-xxl-5 {
        margin: 3rem!important
    }

    .m-xxl-auto {
        margin: auto!important
    }

    .mx-xxl-0 {
        margin-right: 0!important;
        margin-left: 0!important
    }

    .mx-xxl-1 {
        margin-right: .25rem!important;
        margin-left: .25rem!important
    }

    .mx-xxl-2 {
        margin-right: .5rem!important;
        margin-left: .5rem!important
    }

    .mx-xxl-3 {
        margin-right: 1rem!important;
        margin-left: 1rem!important
    }

    .mx-xxl-4 {
        margin-right: 1.5rem!important;
        margin-left: 1.5rem!important
    }

    .mx-xxl-5 {
        margin-right: 3rem!important;
        margin-left: 3rem!important
    }

    .mx-xxl-auto {
        margin-right: auto!important;
        margin-left: auto!important
    }

    .my-xxl-0 {
        margin-top: 0!important;
        margin-bottom: 0!important
    }

    .my-xxl-1 {
        margin-top: .25rem!important;
        margin-bottom: .25rem!important
    }

    .my-xxl-2 {
        margin-top: .5rem!important;
        margin-bottom: .5rem!important
    }

    .my-xxl-3 {
        margin-top: 1rem!important;
        margin-bottom: 1rem!important
    }

    .my-xxl-4 {
        margin-top: 1.5rem!important;
        margin-bottom: 1.5rem!important
    }

    .my-xxl-5 {
        margin-top: 3rem!important;
        margin-bottom: 3rem!important
    }

    .my-xxl-auto {
        margin-top: auto!important;
        margin-bottom: auto!important
    }

    .mt-xxl-0 {
        margin-top: 0!important
    }

    .mt-xxl-1 {
        margin-top: .25rem!important
    }

    .mt-xxl-2 {
        margin-top: .5rem!important
    }

    .mt-xxl-3 {
        margin-top: 1rem!important
    }

    .mt-xxl-4 {
        margin-top: 1.5rem!important
    }

    .mt-xxl-5 {
        margin-top: 3rem!important
    }

    .mt-xxl-auto {
        margin-top: auto!important
    }

    .me-xxl-0 {
        margin-right: 0!important
    }

    .me-xxl-1 {
        margin-right: .25rem!important
    }

    .me-xxl-2 {
        margin-right: .5rem!important
    }

    .me-xxl-3 {
        margin-right: 1rem!important
    }

    .me-xxl-4 {
        margin-right: 1.5rem!important
    }

    .me-xxl-5 {
        margin-right: 3rem!important
    }

    .me-xxl-auto {
        margin-right: auto!important
    }

    .mb-xxl-0 {
        margin-bottom: 0!important
    }

    .mb-xxl-1 {
        margin-bottom: .25rem!important
    }

    .mb-xxl-2 {
        margin-bottom: .5rem!important
    }

    .mb-xxl-3 {
        margin-bottom: 1rem!important
    }

    .mb-xxl-4 {
        margin-bottom: 1.5rem!important
    }

    .mb-xxl-5 {
        margin-bottom: 3rem!important
    }

    .mb-xxl-auto {
        margin-bottom: auto!important
    }

    .ms-xxl-0 {
        margin-left: 0!important
    }

    .ms-xxl-1 {
        margin-left: .25rem!important
    }

    .ms-xxl-2 {
        margin-left: .5rem!important
    }

    .ms-xxl-3 {
        margin-left: 1rem!important
    }

    .ms-xxl-4 {
        margin-left: 1.5rem!important
    }

    .ms-xxl-5 {
        margin-left: 3rem!important
    }

    .ms-xxl-auto {
        margin-left: auto!important
    }

    .p-xxl-0 {
        padding: 0!important
    }

    .p-xxl-1 {
        padding: .25rem!important
    }

    .p-xxl-2 {
        padding: .5rem!important
    }

    .p-xxl-3 {
        padding: 1rem!important
    }

    .p-xxl-4 {
        padding: 1.5rem!important
    }

    .p-xxl-5 {
        padding: 3rem!important
    }

    .px-xxl-0 {
        padding-right: 0!important;
        padding-left: 0!important
    }

    .px-xxl-1 {
        padding-right: .25rem!important;
        padding-left: .25rem!important
    }

    .px-xxl-2 {
        padding-right: .5rem!important;
        padding-left: .5rem!important
    }

    .px-xxl-3 {
        padding-right: 1rem!important;
        padding-left: 1rem!important
    }

    .px-xxl-4 {
        padding-right: 1.5rem!important;
        padding-left: 1.5rem!important
    }

    .px-xxl-5 {
        padding-right: 3rem!important;
        padding-left: 3rem!important
    }

    .py-xxl-0 {
        padding-top: 0!important;
        padding-bottom: 0!important
    }

    .py-xxl-1 {
        padding-top: .25rem!important;
        padding-bottom: .25rem!important
    }

    .py-xxl-2 {
        padding-top: .5rem!important;
        padding-bottom: .5rem!important
    }

    .py-xxl-3 {
        padding-top: 1rem!important;
        padding-bottom: 1rem!important
    }

    .py-xxl-4 {
        padding-top: 1.5rem!important;
        padding-bottom: 1.5rem!important
    }

    .py-xxl-5 {
        padding-top: 3rem!important;
        padding-bottom: 3rem!important
    }

    .pt-xxl-0 {
        padding-top: 0!important
    }

    .pt-xxl-1 {
        padding-top: .25rem!important
    }

    .pt-xxl-2 {
        padding-top: .5rem!important
    }

    .pt-xxl-3 {
        padding-top: 1rem!important
    }

    .pt-xxl-4 {
        padding-top: 1.5rem!important
    }

    .pt-xxl-5 {
        padding-top: 3rem!important
    }

    .pe-xxl-0 {
        padding-right: 0!important
    }

    .pe-xxl-1 {
        padding-right: .25rem!important
    }

    .pe-xxl-2 {
        padding-right: .5rem!important
    }

    .pe-xxl-3 {
        padding-right: 1rem!important
    }

    .pe-xxl-4 {
        padding-right: 1.5rem!important
    }

    .pe-xxl-5 {
        padding-right: 3rem!important
    }

    .pb-xxl-0 {
        padding-bottom: 0!important
    }

    .pb-xxl-1 {
        padding-bottom: .25rem!important
    }

    .pb-xxl-2 {
        padding-bottom: .5rem!important
    }

    .pb-xxl-3 {
        padding-bottom: 1rem!important
    }

    .pb-xxl-4 {
        padding-bottom: 1.5rem!important
    }

    .pb-xxl-5 {
        padding-bottom: 3rem!important
    }

    .ps-xxl-0 {
        padding-left: 0!important
    }

    .ps-xxl-1 {
        padding-left: .25rem!important
    }

    .ps-xxl-2 {
        padding-left: .5rem!important
    }

    .ps-xxl-3 {
        padding-left: 1rem!important
    }

    .ps-xxl-4 {
        padding-left: 1.5rem!important
    }

    .ps-xxl-5 {
        padding-left: 3rem!important
    }

    .gap-xxl-0 {
        gap: 0!important
    }

    .gap-xxl-1 {
        gap: .25rem!important
    }

    .gap-xxl-2 {
        gap: .5rem!important
    }

    .gap-xxl-3 {
        gap: 1rem!important
    }

    .gap-xxl-4 {
        gap: 1.5rem!important
    }

    .gap-xxl-5 {
        gap: 3rem!important
    }

    .row-gap-xxl-0 {
        row-gap: 0!important
    }

    .row-gap-xxl-1 {
        row-gap: .25rem!important
    }

    .row-gap-xxl-2 {
        row-gap: .5rem!important
    }

    .row-gap-xxl-3 {
        row-gap: 1rem!important
    }

    .row-gap-xxl-4 {
        row-gap: 1.5rem!important
    }

    .row-gap-xxl-5 {
        row-gap: 3rem!important
    }

    .column-gap-xxl-0 {
        -webkit-column-gap: 0!important;
        -moz-column-gap: 0!important;
        column-gap: 0!important
    }

    .column-gap-xxl-1 {
        -webkit-column-gap: .25rem!important;
        -moz-column-gap: .25rem!important;
        column-gap: .25rem!important
    }

    .column-gap-xxl-2 {
        -webkit-column-gap: .5rem!important;
        -moz-column-gap: .5rem!important;
        column-gap: .5rem!important
    }

    .column-gap-xxl-3 {
        -webkit-column-gap: 1rem!important;
        -moz-column-gap: 1rem!important;
        column-gap: 1rem!important
    }

    .column-gap-xxl-4 {
        -webkit-column-gap: 1.5rem!important;
        -moz-column-gap: 1.5rem!important;
        column-gap: 1.5rem!important
    }

    .column-gap-xxl-5 {
        -webkit-column-gap: 3rem!important;
        -moz-column-gap: 3rem!important;
        column-gap: 3rem!important
    }

    .text-xxl-start {
        text-align: left!important
    }

    .text-xxl-end {
        text-align: right!important
    }

    .text-xxl-center {
        text-align: center!important
    }
}

@media (min-width: 1200px) {
    .fs-1 {
        font-size:2.5rem!important
    }

    .fs-2 {
        font-size: 2rem!important
    }

    .fs-3 {
        font-size: 1.75rem!important
    }

    .fs-4 {
        font-size: 1.5rem!important
    }
}

@media print {
    .d-print-inline {
        display: inline!important
    }

    .d-print-inline-block {
        display: inline-block!important
    }

    .d-print-block {
        display: block!important
    }

    .d-print-grid {
        display: grid!important
    }

    .d-print-inline-grid {
        display: inline-grid!important
    }

    .d-print-table {
        display: table!important
    }

    .d-print-table-row {
        display: table-row!important
    }

    .d-print-table-cell {
        display: table-cell!important
    }

    .d-print-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important
    }

    .d-print-inline-flex {
        display: -webkit-inline-box!important;
        display: -ms-inline-flexbox!important;
        display: inline-flex!important
    }

    .d-print-none {
        display: none!important
    }
}

.grecaptcha-badge {
    display: none
}

.z-3 {
}

.cky-btn-revisit-wrapper {
    display: none!important
}

.orange-text {
    color: #ff6319
}

.desc a:hover,.orange-link,.orange-link:hover {
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out
}

.orange-link {
    color: #ff6319;
    display: inline-block;
    font-size: 22px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%
}

@media (prefers-reduced-motion:reduce) {
    .orange-link {
        -webkit-transition: none;
        transition: none
    }
}

.desc a:hover,.orange-link:hover {
    color: #fff
}

@media (prefers-reduced-motion:reduce) {
    .orange-link:hover {
        -webkit-transition: none;
        transition: none
    }
}

.countdown-timer {
    color: #ff6319;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px
}

.country-select .country-list,.iti--inline-dropdown .iti__country-list,.iti__dropdown-content {
    background: rgba(41,41,41,.75)!important;
    -webkit-backdrop-filter: blur(12.5px)!important;
    backdrop-filter: blur(12.5px)!important
}

.lock {
    overflow: hidden;
    width: 100%;
    height: 100%
}

.grey-button {
    background: rgba(143,143,143,.2509803922);
    border-radius: 100px;
    text-decoration: none;
    color: #fff;
    font-family: "RF Dewi";
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 150%;
    display: inline-block;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
    padding: 10px 33.5px
}

@media (prefers-reduced-motion:reduce) {
    .grey-button {
        -webkit-transition: none;
        transition: none
    }
}

.grey-button:hover {
    -webkit-transform: scale(1.1);
    transform: scale(1.1)
}

.grey-button.disabled,.grey-button:disabled {
    background: #8f8f8f;
    cursor: not-allowed;
    pointer-events: none
}

.download-button .disabled:hover,.download-button:disabled:hover,.grey-button.disabled:hover,.grey-button:disabled:hover,.orange-button.disabled:hover,.orange-button:disabled:hover,.white-button.disabled:hover,.white-button:disabled:hover {
    -webkit-transform: none;
    transform: none
}

.orange-button {
    background: #ff6319;
    border-radius: 100px;
    text-decoration: none;
    color: #fff;
    font-family: "RF Dewi";
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    border: 0;
    line-height: 150%;
    display: inline-block;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
    padding: 10px 33.5px
}

@media (prefers-reduced-motion:reduce) {
    .orange-button {
        -webkit-transition: none;
        transition: none
    }
}

.orange-button:hover {
    -webkit-transform: scale(1.1);
    transform: scale(1.1)
}

.orange-button.disabled,.orange-button:disabled {
    background: #8f8f8f;
    cursor: not-allowed;
    pointer-events: none
}

.download-button {
    background: #ff6319;
    position: relative;
    border-radius: 100px;
    text-decoration: none;
    color: #fff;
    font-family: "RF Dewi";
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    border: 0;
    line-height: 150%;
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
    padding: 10px 33.5px
}

@media (prefers-reduced-motion:reduce) {
    .download-button {
        -webkit-transition: none;
        transition: none
    }
}

.download-button::after {
    width: 16.5px;
    height: 15.819px;
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none"><path d="M13.9705 6.99922L13.063 6.07109L9.6393 9.49484V0.0898438H8.36055V9.49484L4.9368 6.07109L4.0293 6.99922L8.99992 11.9698L13.9705 6.99922Z" fill="white"/><path d="M15.9506 11.0625C15.9506 13.0219 14.3625 14.61 12.4031 14.61H5.59687C3.6375 14.61 2.04937 13.0219 2.04937 11.0625H0.75C0.75 13.7231 2.91562 15.9094 5.59687 15.9094H12.4237C15.0844 15.8888 17.25 13.7231 17.25 11.0625H15.9506Z" fill="white"/></svg>');
    margin-left: 10px
}

.download-button:hover {
    -webkit-transform: scale(1.1);
    transform: scale(1.1)
}

.download-button .disabled,.download-button:disabled {
    background: #8f8f8f;
    cursor: not-allowed;
    pointer-events: none
}

.white-button {
    background: #fff;
    border-radius: 100px;
    color: #1f1f1f;
    text-decoration: none;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    border: 0;
    line-height: 150%;
    display: inline-block;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
    padding: 14px 16px
}

@media (prefers-reduced-motion:reduce) {
    .white-button {
        -webkit-transition: none;
        transition: none
    }
}

.white-button:hover {
    -webkit-transform: scale(1.1);
    transform: scale(1.1)
}

.white-button.disabled,.white-button:disabled {
    background: rgba(255,255,255,.5);
    cursor: not-allowed;
    pointer-events: none
}

.burger-menu-button {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: end;
    -ms-flex-align: end;
    align-items: flex-end;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .burger-menu-button {
        -webkit-transition: none;
        transition: none
    }
}

.burger-menu-button .burger-bar {
    width: 18px;
    height: 2px;
    background-color: #fff;
    margin: 2px 0;
    border-radius: 2px;
    -webkit-transition: .3s;
    transition: .3s
}

@media (prefers-reduced-motion:reduce) {
    .burger-menu-button .burger-bar {
        -webkit-transition: none;
        transition: none
    }
}

.burger-menu-button .burger-bar:first-child,.burger-menu-button .burger-bar:last-child {
    width: 18px
}

.burger-menu-button .burger-bar:first-child {
    margin-top: 0
}

.burger-menu-button .burger-bar:last-child {
    margin-bottom: 0
}

.burger-menu-button.active {
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .burger-menu-button.active {
        -webkit-transition: none;
        transition: none
    }
}

.burger-menu-button.active .burger-bar:nth-child(1) {
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    position: relative;
    top: 8px;
    width: 18px;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .burger-menu-button.active .burger-bar:nth-child(1) {
        -webkit-transition: none;
        transition: none
    }
}

.burger-menu-button.active .burger-bar:nth-child(2) {
    opacity: 0;
    -webkit-transform: translateX(18px);
    transform: translateX(18px)
}

.burger-menu-button.active .burger-bar:nth-child(3) {
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    position: relative;
    bottom: 4px;
    width: 18px;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .burger-menu-button.active .burger-bar:nth-child(3) {
        -webkit-transition: none;
        transition: none
    }
}

.h1,h1 {
    font-size: 76px;
    font-style: normal;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -.76px
}

.entry-title.h1,.h2,h1.entry-title,h2 {
    font-size: 60px;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -.6px
}

.h2,h2 {
    font-style: normal
}

.calculators .tabs-wrapper .calculator-form p.price,.h3,h3 {
    font-size: 35px;
    font-style: normal;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -.35px
}

.desc {
    font-size: 22px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%
}

.desc .h1,.desc .h2,.desc .h3,.desc h1,.desc h2,.desc h3 {
    margin-bottom: 25px
}

.desc a {
    color: #ff6319;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out
}

@media (prefers-reduced-motion:reduce) {
    .desc a {
        -webkit-transition: none;
        transition: none
    }
}

@media (prefers-reduced-motion:reduce) {
    .desc a:hover {
        -webkit-transition: none;
        transition: none
    }
}

.desc :last-child {
    margin-bottom: 0
}

.slick-slide.slick-loading img,.success-block {
    display: none
}

.success-animation {
    margin: 50px auto 15px;
    display: block
}

.checkmark,.checkmark__circle {
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #ff6319
}

.checkmark {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: block;
    -webkit-box-shadow: inset 0 0 0 #ff6319;
    box-shadow: inset 0 0 0 #ff6319;
    -webkit-animation: fill .4s ease-in-out .4s forwards,scale .3s ease-in-out .9s both;
    animation: fill .4s ease-in-out .4s forwards,scale .3s ease-in-out .9s both;
    position: relative;
    top: 5px;
    right: 5px;
    margin: 0 auto
}

.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    fill: #292929;
    -webkit-animation: stroke .6s cubic-bezier(.65,0,.45,1) forwards;
    animation: stroke .6s cubic-bezier(.65,0,.45,1) forwards
}

.checkmark__check {
    -webkit-transform-origin: 50% 50%;
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    -webkit-animation: stroke .3s cubic-bezier(.65,0,.45,1) .8s forwards;
    animation: stroke .3s cubic-bezier(.65,0,.45,1) .8s forwards
}

@media (max-width: 575.98px) {
    [class*=col-] {
        padding:0 16px
    }

    .entry-title.h1,.h1,h1,h1.entry-title {
        font-size: 32px;
        font-weight: 600;
        letter-spacing: -.32px
    }

    .h2,.h3,h2,h3 {
        line-height: 110%;
        letter-spacing: -.24px;
        font-size: 24px
    }

    .h3,h3 {
        font-size: 20px;
        letter-spacing: -.2px
    }

    .desc .h1,.desc .h2,.desc .h3,.desc h1,.desc h2,.desc h3 {
        margin-bottom: 16px
    }

    .desc p {
        font-size: 16px;
        line-height: 130%
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .entry-title.h1,.h1,h1,h1.entry-title {
        font-size:32px;
        font-weight: 600;
        letter-spacing: -.32px
    }

    .h2,.h3,h2,h3 {
        line-height: 110%;
        letter-spacing: -.24px;
        font-size: 24px
    }

    .h3,h3 {
        font-size: 20px;
        letter-spacing: -.2px
    }

    .desc .h1,.desc .h2,.desc .h3,.desc h1,.desc h2,.desc h3 {
        margin-bottom: 16px
    }

    .desc p {
        font-size: 16px;
        line-height: 130%
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .entry-title.h1,.h1,h1,h1.entry-title {
        font-size:32px;
        font-weight: 600;
        letter-spacing: -.32px
    }

    .h2,.h3,h2,h3 {
        line-height: 110%;
        letter-spacing: -.24px;
        font-size: 24px
    }

    .h3,h3 {
        font-size: 20px;
        letter-spacing: -.2px
    }

    .desc .h1,.desc .h2,.desc .h3,.desc h1,.desc h2,.desc h3 {
        margin-bottom: 16px
    }

    .desc p {
        font-size: 16px;
        line-height: 130%
    }
}

.slick-list,.slick-slider {
    position: relative;
    display: block
}

.slick-slider {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -ms-touch-action: pan-y;
    touch-action: pan-y;
    -webkit-tap-highlight-color: transparent
}

.slick-list {
    overflow: hidden;
    margin: 0;
    padding: 0
}

.slick-list:focus {
    outline: 0
}

.slick-list.dragging {
    cursor: pointer;
    cursor: hand
}

.slick-slider .slick-list,.slick-slider .slick-track {
    -webkit-transform: translate3d(0,0,0);
    transform: translate3d(0,0,0)
}

.slick-track {
    position: relative;
    left: 0;
    top: 0;
    display: block;
    margin-left: auto;
    margin-right: auto
}

.slick-track:after,.slick-track:before {
    content: "";
    display: table
}

.slick-track:after {
    clear: both
}

.slick-loading .slick-track {
    visibility: hidden
}

.slick-slide {
    float: left;
    height: 100%;
    min-height: 1px;
    display: none
}

[dir=rtl] .slick-slide {
    float: right
}

.shape-border-block,.slick-slide.dragging img {
    pointer-events: none
}

.slick-initialized .slick-slide {
    display: block
}

.slick-loading .slick-slide {
    visibility: hidden
}

.slick-vertical .slick-slide {
    display: block;
    height: auto;
    border: 1px solid transparent
}

.slick-arrow.slick-hidden {
    display: none
}

.shape-border-block .border-start {
    border-left: 1px solid rgba(255,255,255,.05)!important
}

.shape-border-block .border-end {
    border-right: 1px solid rgba(255,255,255,.05)!important
}

.site-header {
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    padding: 28px 0;
    position: fixed;
    z-index: 4;
    width: 100%
}

.site-header.scroll-down,.site-header.scroll-up {
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    -webkit-transform: translateY(-100%);
    transform: translateY(-100%)
}

.site-header.scroll-up {
    background-color: #1f1f1f;
    -webkit-transform: translateY(0);
    transform: translateY(0)
}

.site-header .user-data {
    margin-left: 48px;
    gap: 24px
}

.site-header .user-data .avatar {
    width: 49px;
    height: 49px;
    border-radius: 50%;
    overflow: hidden
}

.site-header .user-data .avatar img {
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: center;
    object-position: center
}

.site-header .user-data .logout-link {
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    font-weight: 500;
    text-decoration: none;
    color: #fff
}

.site-header .user-data .logout-link:hover,footer .footer-wrapper .top-footer .contact-data li a:hover {
    color: #ff6319;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

.site-header .login-buttons,.site-header .main-navigation ul,.site-header .user-data {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center
}

.site-header .login-buttons {
    gap: 15px
}

.site-header .main-navigation ul {
    padding: 0;
    margin: 0
}

.site-header .main-navigation ul li,.site-header .mobile-menu ul li {
    list-style: none
}

.site-header .main-navigation ul li:not(:last-child) {
    margin-right: 15px
}

.site-header .main-navigation ul li a,.site-header .mobile-menu ul li a {
    text-decoration: none;
    font-size: 16px;
    font-style: normal;
    color: #fff;
    font-weight: 600;
    line-height: 150%;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

.site-header .main-navigation ul li.calculator-item a,.site-header .main-navigation ul li.referral-item a,.site-header .mobile-menu ul li.calculator-item a,.site-header .mobile-menu ul li.referral-item a {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    position: relative;
    gap: 10px
}

.site-header .main-navigation ul li.referral-item a::before {
    width: 17px;
    height: 16.946px;
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4173 5.08459C14.4173 4.56558 14.2113 4.06809 13.8442 3.70166C13.4778 3.33458 12.9803 3.12854 12.4613 3.12854C9.15883 3.12854 0.725014 3.12854 0.725014 3.12854C0.365101 3.12854 0.072998 3.42064 0.072998 3.78056V14.8648C0.072998 16.3051 1.24076 17.4729 2.68106 17.4729H13.7653C14.1252 17.4729 14.4173 17.1808 14.4173 16.8209V5.08459ZM1.37703 4.43257V14.8648C1.37703 15.5853 1.96058 16.1688 2.68106 16.1688H13.1133V5.08459C13.1133 4.9118 13.0448 4.74554 12.9223 4.62361C12.8003 4.50103 12.6341 4.43257 12.4613 4.43257H1.37703Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0325 3.12597C17.0273 1.68957 15.8608 0.527683 14.4245 0.527683C10.7973 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H12.4658C12.8251 4.42543 13.1165 4.71623 13.1178 5.07549C13.1283 8.37599 13.1556 16.8229 13.1556 16.8229C13.1563 17.0282 13.2534 17.2212 13.4184 17.3438C13.5827 17.4664 13.7953 17.5042 13.9922 17.4462C13.9922 17.4462 15.0595 17.1326 15.9137 16.4316C16.5592 15.9022 17.0716 15.1641 17.0729 14.2207L17.0325 3.12597ZM15.7285 3.13118C15.7259 2.41266 15.143 1.83171 14.4245 1.83171C10.7973 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H12.4658C13.5436 3.1214 14.4179 3.99315 14.4219 5.07093L14.4571 15.8403C14.6624 15.7288 14.8828 15.5905 15.0869 15.4236C15.4449 15.1296 15.7683 14.7423 15.7689 14.2226L15.7285 3.13118Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5.60673 13.0425C4.92342 12.4902 4.48592 11.6459 4.48592 10.7005C4.48592 9.03912 5.83494 7.69075 7.49562 7.69075C9.15695 7.69075 10.5053 9.03912 10.5053 10.7005C10.5053 11.6459 10.0678 12.4902 9.38451 13.0425C9.10479 13.2687 9.06111 13.6795 9.28736 13.9592C9.51361 14.2389 9.92438 14.2826 10.2041 14.0564C11.1834 13.2655 11.8094 12.0553 11.8094 10.7005C11.8094 8.31929 9.87678 6.38672 7.49562 6.38672C5.11511 6.38672 3.18188 8.31929 3.18188 10.7005C3.18188 12.0553 3.80847 13.2655 4.78715 14.0564C5.06686 14.2826 5.47763 14.2389 5.70388 13.9592C5.93013 13.6795 5.88645 13.2687 5.60673 13.0425Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4885 2.97926C15.9006 2.5698 16.0245 1.95169 15.8028 1.41508C15.5818 0.877816 15.0582 0.527683 14.4773 0.527683C10.8697 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H13.7653V3.77342C13.7653 4.42543 13.7653 4.35697 13.7653 4.2357H14.2249L15.4885 2.97926ZM14.5692 2.0547C14.6064 2.01754 14.6175 1.96081 14.5979 1.91256C14.5777 1.86366 14.5301 1.83171 14.4773 1.83171C10.8697 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H13.496L14.5692 2.0547Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9.80045 14.2017H11.1573C11.5172 14.2017 11.8093 13.909 11.8093 13.5497C11.8093 13.1898 11.5172 12.8977 11.1573 12.8977H9.80045C9.44054 12.8977 9.14844 13.1898 9.14844 13.5497C9.14844 13.909 9.44054 14.2017 9.80045 14.2017Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.8339 14.2017H5.1914C5.55131 14.2017 5.84341 13.909 5.84341 13.5497C5.84341 13.1898 5.55131 12.8977 5.1914 12.8977H3.8339C3.47399 12.8977 3.18188 13.1898 3.18188 13.5497C3.18188 13.909 3.47399 14.2017 3.8339 14.2017Z" fill="white"/></svg>')
}

.site-header .main-navigation ul li.calculator-item a::before {
    width: 17.073px;
    height: 17.019px;
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4059 5.06784C14.4059 4.54661 14.199 4.04698 13.831 3.67898C13.4624 3.31032 12.9627 3.10339 12.4415 3.10339C9.12486 3.10339 0.654816 3.10339 0.654816 3.10339C0.293357 3.10339 0 3.39675 0 3.75821V14.8901C0 16.3366 1.17277 17.5093 2.61926 17.5093H13.7511C14.1132 17.5093 14.4059 17.216 14.4059 16.8545V5.06784ZM1.30963 4.41302V14.8901C1.30963 15.6136 1.89635 16.1997 2.61926 16.1997H13.0963V5.06784C13.0963 4.89431 13.0276 4.72734 12.9045 4.60489C12.782 4.48178 12.6157 4.41302 12.4415 4.41302H1.30963Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0324 3.10075C17.0271 1.65819 15.8563 0.491309 14.4131 0.491309C10.7704 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H12.446C12.8068 4.4058 13.0995 4.69785 13.1008 5.05865C13.1113 8.37333 13.1395 16.8565 13.1395 16.8565C13.1401 17.0627 13.2377 17.2566 13.4027 17.3797C13.5677 17.5028 13.7812 17.5407 13.979 17.4825C13.979 17.4825 15.0509 17.1675 15.9087 16.4636C16.557 15.9319 17.0723 15.1906 17.073 14.2431L17.0324 3.10075ZM15.7227 3.10599C15.7201 2.38438 15.1347 1.80094 14.4131 1.80094C10.7704 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H12.446C13.5284 3.09617 14.4065 3.97165 14.4105 5.05407L14.4458 15.8697C14.6528 15.7577 14.8734 15.6189 15.0784 15.4512C15.4379 15.1559 15.7627 14.7669 15.7633 14.2451L15.7227 3.10599Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4818 2.95342C15.8956 2.54219 16.02 1.92143 15.798 1.38251C15.5754 0.842945 15.0496 0.491309 14.4661 0.491309C10.843 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H13.7511V3.75098C13.7511 4.34032 13.7511 4.28662 13.7511 4.21525H14.2127L15.4818 2.95342ZM14.5585 2.02489C14.5958 1.98756 14.6076 1.93059 14.5873 1.88214C14.567 1.83303 14.5192 1.80094 14.4661 1.80094C10.843 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H13.4813L14.5585 2.02489Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.97217 6.37747V8.99673C3.97217 9.35819 4.26553 9.65155 4.62698 9.65155C4.98844 9.65155 5.2818 9.35819 5.2818 8.99673V6.37747C5.2818 6.01601 4.98844 5.72266 4.62698 5.72266C4.26553 5.72266 3.97217 6.01601 3.97217 6.37747Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.31741 8.34186H5.93668C6.29813 8.34186 6.59149 8.0485 6.59149 7.68704C6.59149 7.32558 6.29813 7.03223 5.93668 7.03223H3.31741C2.95596 7.03223 2.6626 7.32558 2.6626 7.68704C2.6626 8.0485 2.95596 8.34186 3.31741 8.34186Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M8.55594 13.5804H11.1752C11.5367 13.5804 11.83 13.287 11.83 12.9256C11.83 12.5641 11.5367 12.2708 11.1752 12.2708H8.55594C8.19448 12.2708 7.90112 12.5641 7.90112 12.9256C7.90112 13.287 8.19448 13.5804 8.55594 13.5804Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.59608 11.616C6.59608 11.2545 6.30272 10.9612 5.94126 10.9612H3.31741C2.9553 10.9612 2.6626 11.2545 2.6626 11.616V14.2353C2.6626 14.5967 2.9553 14.8901 3.31741 14.8901H5.94126C6.30272 14.8901 6.59608 14.5967 6.59608 14.2353V11.616ZM3.97223 12.2708V13.5804H5.28644V12.2708H3.97223Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M11.8299 6.37747C11.8299 6.01601 11.5365 5.72266 11.1751 5.72266H8.55057C8.18911 5.72266 7.89575 6.01601 7.89575 6.37747V8.99673C7.89575 9.35819 8.18911 9.65155 8.55057 9.65155H11.1751C11.5365 9.65155 11.8299 9.35819 11.8299 8.99673V6.37747ZM9.20538 7.03229V8.34192H10.5203V7.03229H9.20538Z" fill="white"/></svg>')
}

.site-header .mobile-menu {
    background-color: #1f1f1f;
    position: absolute;
    width: 100%;
    height: calc(var(--vh, 1vh)*100 - var(--header-padding-top) - var(--top-offset));
    padding: 0 0 26px;
    bottom: 100%;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

.site-header .mobile-menu .login-buttons {
    margin-left: 0;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center
}

.site-header .mobile-menu .user-data {
    margin: 10px 24px 0;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column
}

.site-header .mobile-menu .user-data .avatar {
    width: 100px;
    height: 100px
}

.site-header .mobile-menu .buy-forever-by-one-click {
    margin-left: 0;
    padding-left: 24px
}

.site-header .mobile-menu.active {
    bottom: auto;
    top: var(--header-padding-top);
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

.site-header .mobile-menu ul {
    padding: 0;
    margin-top: 174px
}

.site-header .mobile-menu ul li a {
    display: block
}

.site-header .mobile-menu ul li:not(:last-child) {
    margin-bottom: 15px
}

.site-header .mobile-menu ul li.referral-item a::before {
    width: 17px;
    height: 16.946px;
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4173 5.08459C14.4173 4.56558 14.2113 4.06809 13.8442 3.70166C13.4778 3.33458 12.9803 3.12854 12.4613 3.12854C9.15883 3.12854 0.725014 3.12854 0.725014 3.12854C0.365101 3.12854 0.072998 3.42064 0.072998 3.78056V14.8648C0.072998 16.3051 1.24076 17.4729 2.68106 17.4729H13.7653C14.1252 17.4729 14.4173 17.1808 14.4173 16.8209V5.08459ZM1.37703 4.43257V14.8648C1.37703 15.5853 1.96058 16.1688 2.68106 16.1688H13.1133V5.08459C13.1133 4.9118 13.0448 4.74554 12.9223 4.62361C12.8003 4.50103 12.6341 4.43257 12.4613 4.43257H1.37703Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0325 3.12597C17.0273 1.68957 15.8608 0.527683 14.4245 0.527683C10.7973 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H12.4658C12.8251 4.42543 13.1165 4.71623 13.1178 5.07549C13.1283 8.37599 13.1556 16.8229 13.1556 16.8229C13.1563 17.0282 13.2534 17.2212 13.4184 17.3438C13.5827 17.4664 13.7953 17.5042 13.9922 17.4462C13.9922 17.4462 15.0595 17.1326 15.9137 16.4316C16.5592 15.9022 17.0716 15.1641 17.0729 14.2207L17.0325 3.12597ZM15.7285 3.13118C15.7259 2.41266 15.143 1.83171 14.4245 1.83171C10.7973 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H12.4658C13.5436 3.1214 14.4179 3.99315 14.4219 5.07093L14.4571 15.8403C14.6624 15.7288 14.8828 15.5905 15.0869 15.4236C15.4449 15.1296 15.7683 14.7423 15.7689 14.2226L15.7285 3.13118Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5.60673 13.0425C4.92342 12.4902 4.48592 11.6459 4.48592 10.7005C4.48592 9.03912 5.83494 7.69075 7.49562 7.69075C9.15695 7.69075 10.5053 9.03912 10.5053 10.7005C10.5053 11.6459 10.0678 12.4902 9.38451 13.0425C9.10479 13.2687 9.06111 13.6795 9.28736 13.9592C9.51361 14.2389 9.92438 14.2826 10.2041 14.0564C11.1834 13.2655 11.8094 12.0553 11.8094 10.7005C11.8094 8.31929 9.87678 6.38672 7.49562 6.38672C5.11511 6.38672 3.18188 8.31929 3.18188 10.7005C3.18188 12.0553 3.80847 13.2655 4.78715 14.0564C5.06686 14.2826 5.47763 14.2389 5.70388 13.9592C5.93013 13.6795 5.88645 13.2687 5.60673 13.0425Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4885 2.97926C15.9006 2.5698 16.0245 1.95169 15.8028 1.41508C15.5818 0.877816 15.0582 0.527683 14.4773 0.527683C10.8697 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H13.7653V3.77342C13.7653 4.42543 13.7653 4.35697 13.7653 4.2357H14.2249L15.4885 2.97926ZM14.5692 2.0547C14.6064 2.01754 14.6175 1.96081 14.5979 1.91256C14.5777 1.86366 14.5301 1.83171 14.4773 1.83171C10.8697 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H13.496L14.5692 2.0547Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9.80045 14.2017H11.1573C11.5172 14.2017 11.8093 13.909 11.8093 13.5497C11.8093 13.1898 11.5172 12.8977 11.1573 12.8977H9.80045C9.44054 12.8977 9.14844 13.1898 9.14844 13.5497C9.14844 13.909 9.44054 14.2017 9.80045 14.2017Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.8339 14.2017H5.1914C5.55131 14.2017 5.84341 13.909 5.84341 13.5497C5.84341 13.1898 5.55131 12.8977 5.1914 12.8977H3.8339C3.47399 12.8977 3.18188 13.1898 3.18188 13.5497C3.18188 13.909 3.47399 14.2017 3.8339 14.2017Z" fill="white"/></svg>')
}

.site-header .mobile-menu ul li.calculator-item a::before {
    width: 17.073px;
    height: 17.019px;
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4059 5.06784C14.4059 4.54661 14.199 4.04698 13.831 3.67898C13.4624 3.31032 12.9627 3.10339 12.4415 3.10339C9.12486 3.10339 0.654816 3.10339 0.654816 3.10339C0.293357 3.10339 0 3.39675 0 3.75821V14.8901C0 16.3366 1.17277 17.5093 2.61926 17.5093H13.7511C14.1132 17.5093 14.4059 17.216 14.4059 16.8545V5.06784ZM1.30963 4.41302V14.8901C1.30963 15.6136 1.89635 16.1997 2.61926 16.1997H13.0963V5.06784C13.0963 4.89431 13.0276 4.72734 12.9045 4.60489C12.782 4.48178 12.6157 4.41302 12.4415 4.41302H1.30963Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0324 3.10075C17.0271 1.65819 15.8563 0.491309 14.4131 0.491309C10.7704 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H12.446C12.8068 4.4058 13.0995 4.69785 13.1008 5.05865C13.1113 8.37333 13.1395 16.8565 13.1395 16.8565C13.1401 17.0627 13.2377 17.2566 13.4027 17.3797C13.5677 17.5028 13.7812 17.5407 13.979 17.4825C13.979 17.4825 15.0509 17.1675 15.9087 16.4636C16.557 15.9319 17.0723 15.1906 17.073 14.2431L17.0324 3.10075ZM15.7227 3.10599C15.7201 2.38438 15.1347 1.80094 14.4131 1.80094C10.7704 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H12.446C13.5284 3.09617 14.4065 3.97165 14.4105 5.05407L14.4458 15.8697C14.6528 15.7577 14.8734 15.6189 15.0784 15.4512C15.4379 15.1559 15.7627 14.7669 15.7633 14.2451L15.7227 3.10599Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4818 2.95342C15.8956 2.54219 16.02 1.92143 15.798 1.38251C15.5754 0.842945 15.0496 0.491309 14.4661 0.491309C10.843 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H13.7511V3.75098C13.7511 4.34032 13.7511 4.28662 13.7511 4.21525H14.2127L15.4818 2.95342ZM14.5585 2.02489C14.5958 1.98756 14.6076 1.93059 14.5873 1.88214C14.567 1.83303 14.5192 1.80094 14.4661 1.80094C10.843 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H13.4813L14.5585 2.02489Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.97217 6.37747V8.99673C3.97217 9.35819 4.26553 9.65155 4.62698 9.65155C4.98844 9.65155 5.2818 9.35819 5.2818 8.99673V6.37747C5.2818 6.01601 4.98844 5.72266 4.62698 5.72266C4.26553 5.72266 3.97217 6.01601 3.97217 6.37747Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.31741 8.34186H5.93668C6.29813 8.34186 6.59149 8.0485 6.59149 7.68704C6.59149 7.32558 6.29813 7.03223 5.93668 7.03223H3.31741C2.95596 7.03223 2.6626 7.32558 2.6626 7.68704C2.6626 8.0485 2.95596 8.34186 3.31741 8.34186Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M8.55594 13.5804H11.1752C11.5367 13.5804 11.83 13.287 11.83 12.9256C11.83 12.5641 11.5367 12.2708 11.1752 12.2708H8.55594C8.19448 12.2708 7.90112 12.5641 7.90112 12.9256C7.90112 13.287 8.19448 13.5804 8.55594 13.5804Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.59608 11.616C6.59608 11.2545 6.30272 10.9612 5.94126 10.9612H3.31741C2.9553 10.9612 2.6626 11.2545 2.6626 11.616V14.2353C2.6626 14.5967 2.9553 14.8901 3.31741 14.8901H5.94126C6.30272 14.8901 6.59608 14.5967 6.59608 14.2353V11.616ZM3.97223 12.2708V13.5804H5.28644V12.2708H3.97223Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M11.8299 6.37747C11.8299 6.01601 11.5365 5.72266 11.1751 5.72266H8.55057C8.18911 5.72266 7.89575 6.01601 7.89575 6.37747V8.99673C7.89575 9.35819 8.18911 9.65155 8.55057 9.65155H11.1751C11.5365 9.65155 11.8299 9.35819 11.8299 8.99673V6.37747ZM9.20538 7.03229V8.34192H10.5203V7.03229H9.20538Z" fill="white"/></svg>')
}

.site-header .mobile-menu .button {
    font-size: 20px;
    font-style: normal;
    font-weight: 600;
    line-height: 20px;
    text-transform: capitalize;
    padding: 20px 32px
}

.site-header a.cart {
    margin-left: 16px;
    position: relative
}

.site-header a.cart span {
    position: absolute;
    border-radius: 50%;
    background: #ff6319;
    width: 20px;
    height: 20px;
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    color: #fff;
    font-size: 12px;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    right: -20px;
    top: -6px
}

.site-header .mobile-toggles,.site-header a.cart span {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center
}

.site-header .mobile-toggles .cart {
    margin-right: 24px
}

@media (max-width: 575.98px) {
    .site-header {
        padding:18px 0
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .site-header {
        padding:18px 0
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .site-header {
        padding:18px 0
    }
}

@media (min-width: 992px) and (max-width:1199.98px) {
    .site-header .main-navigation ul {
        margin-right:24px
    }
}

@media (min-width: 1200px) and (max-width:1399.98px) {
    .site-header .main-navigation ul {
        margin-right:24px
    }
}

@media (min-width: 1400px) {
    .site-header .main-navigation ul {
        margin-right:24px
    }
}

@media (min-width: 576px) and (max-width:767.98px) and (orientation:landscape) {
    .site-header .mobile-menu {
        background-color:#1f1f1f;
        position: absolute;
        width: 100%;
        height: calc(var(--vh, 1vh)*100 - var(--header-padding-top));
        padding: 0 0 26px;
        bottom: 100%;
        -webkit-transition: all .5s ease;
        transition: all .5s ease
    }

    .site-header .mobile-menu .login-buttons {
        margin-left: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .site-header .mobile-menu .user-data {
        margin: 10px 24px 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column
    }

    .site-header .mobile-menu .user-data .avatar {
        width: 100px;
        height: 100px
    }

    .site-header .mobile-menu .buy-forever-by-one-click {
        margin-left: 0;
        padding-left: 24px
    }

    .site-header .mobile-menu.active {
        bottom: auto;
        top: var(--header-padding-top);
        -webkit-transition: all .5s ease;
        transition: all .5s ease
    }

    .site-header .mobile-menu ul {
        padding: 0;
        margin-top: 74px
    }

    .site-header .mobile-menu ul li {
        list-style: none
    }

    .site-header .mobile-menu ul li a {
        text-decoration: none;
        font-size: 16px;
        font-style: normal;
        color: #fff;
        font-weight: 600;
        line-height: 150%;
        -webkit-transition: all .5s ease;
        transition: all .5s ease;
        display: block
    }

    .site-header .mobile-menu ul li:not(:last-child) {
        margin-bottom: 15px
    }

    .site-header .mobile-menu ul li.calculator-item a,.site-header .mobile-menu ul li.referral-item a {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        position: relative;
        gap: 10px
    }

    .site-header .mobile-menu ul li.referral-item a::before {
        width: 17px;
        height: 16.946px;
        content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4173 5.08459C14.4173 4.56558 14.2113 4.06809 13.8442 3.70166C13.4778 3.33458 12.9803 3.12854 12.4613 3.12854C9.15883 3.12854 0.725014 3.12854 0.725014 3.12854C0.365101 3.12854 0.072998 3.42064 0.072998 3.78056V14.8648C0.072998 16.3051 1.24076 17.4729 2.68106 17.4729H13.7653C14.1252 17.4729 14.4173 17.1808 14.4173 16.8209V5.08459ZM1.37703 4.43257V14.8648C1.37703 15.5853 1.96058 16.1688 2.68106 16.1688H13.1133V5.08459C13.1133 4.9118 13.0448 4.74554 12.9223 4.62361C12.8003 4.50103 12.6341 4.43257 12.4613 4.43257H1.37703Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0325 3.12597C17.0273 1.68957 15.8608 0.527683 14.4245 0.527683C10.7973 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H12.4658C12.8251 4.42543 13.1165 4.71623 13.1178 5.07549C13.1283 8.37599 13.1556 16.8229 13.1556 16.8229C13.1563 17.0282 13.2534 17.2212 13.4184 17.3438C13.5827 17.4664 13.7953 17.5042 13.9922 17.4462C13.9922 17.4462 15.0595 17.1326 15.9137 16.4316C16.5592 15.9022 17.0716 15.1641 17.0729 14.2207L17.0325 3.12597ZM15.7285 3.13118C15.7259 2.41266 15.143 1.83171 14.4245 1.83171C10.7973 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H12.4658C13.5436 3.1214 14.4179 3.99315 14.4219 5.07093L14.4571 15.8403C14.6624 15.7288 14.8828 15.5905 15.0869 15.4236C15.4449 15.1296 15.7683 14.7423 15.7689 14.2226L15.7285 3.13118Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5.60673 13.0425C4.92342 12.4902 4.48592 11.6459 4.48592 10.7005C4.48592 9.03912 5.83494 7.69075 7.49562 7.69075C9.15695 7.69075 10.5053 9.03912 10.5053 10.7005C10.5053 11.6459 10.0678 12.4902 9.38451 13.0425C9.10479 13.2687 9.06111 13.6795 9.28736 13.9592C9.51361 14.2389 9.92438 14.2826 10.2041 14.0564C11.1834 13.2655 11.8094 12.0553 11.8094 10.7005C11.8094 8.31929 9.87678 6.38672 7.49562 6.38672C5.11511 6.38672 3.18188 8.31929 3.18188 10.7005C3.18188 12.0553 3.80847 13.2655 4.78715 14.0564C5.06686 14.2826 5.47763 14.2389 5.70388 13.9592C5.93013 13.6795 5.88645 13.2687 5.60673 13.0425Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4885 2.97926C15.9006 2.5698 16.0245 1.95169 15.8028 1.41508C15.5818 0.877816 15.0582 0.527683 14.4773 0.527683C10.8697 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H13.7653V3.77342C13.7653 4.42543 13.7653 4.35697 13.7653 4.2357H14.2249L15.4885 2.97926ZM14.5692 2.0547C14.6064 2.01754 14.6175 1.96081 14.5979 1.91256C14.5777 1.86366 14.5301 1.83171 14.4773 1.83171C10.8697 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H13.496L14.5692 2.0547Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9.80045 14.2017H11.1573C11.5172 14.2017 11.8093 13.909 11.8093 13.5497C11.8093 13.1898 11.5172 12.8977 11.1573 12.8977H9.80045C9.44054 12.8977 9.14844 13.1898 9.14844 13.5497C9.14844 13.909 9.44054 14.2017 9.80045 14.2017Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.8339 14.2017H5.1914C5.55131 14.2017 5.84341 13.909 5.84341 13.5497C5.84341 13.1898 5.55131 12.8977 5.1914 12.8977H3.8339C3.47399 12.8977 3.18188 13.1898 3.18188 13.5497C3.18188 13.909 3.47399 14.2017 3.8339 14.2017Z" fill="white"/></svg>')
    }

    .site-header .mobile-menu ul li.calculator-item a::before {
        width: 17.073px;
        height: 17.019px;
        content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4059 5.06784C14.4059 4.54661 14.199 4.04698 13.831 3.67898C13.4624 3.31032 12.9627 3.10339 12.4415 3.10339C9.12486 3.10339 0.654816 3.10339 0.654816 3.10339C0.293357 3.10339 0 3.39675 0 3.75821V14.8901C0 16.3366 1.17277 17.5093 2.61926 17.5093H13.7511C14.1132 17.5093 14.4059 17.216 14.4059 16.8545V5.06784ZM1.30963 4.41302V14.8901C1.30963 15.6136 1.89635 16.1997 2.61926 16.1997H13.0963V5.06784C13.0963 4.89431 13.0276 4.72734 12.9045 4.60489C12.782 4.48178 12.6157 4.41302 12.4415 4.41302H1.30963Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0324 3.10075C17.0271 1.65819 15.8563 0.491309 14.4131 0.491309C10.7704 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H12.446C12.8068 4.4058 13.0995 4.69785 13.1008 5.05865C13.1113 8.37333 13.1395 16.8565 13.1395 16.8565C13.1401 17.0627 13.2377 17.2566 13.4027 17.3797C13.5677 17.5028 13.7812 17.5407 13.979 17.4825C13.979 17.4825 15.0509 17.1675 15.9087 16.4636C16.557 15.9319 17.0723 15.1906 17.073 14.2431L17.0324 3.10075ZM15.7227 3.10599C15.7201 2.38438 15.1347 1.80094 14.4131 1.80094C10.7704 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H12.446C13.5284 3.09617 14.4065 3.97165 14.4105 5.05407L14.4458 15.8697C14.6528 15.7577 14.8734 15.6189 15.0784 15.4512C15.4379 15.1559 15.7627 14.7669 15.7633 14.2451L15.7227 3.10599Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4818 2.95342C15.8956 2.54219 16.02 1.92143 15.798 1.38251C15.5754 0.842945 15.0496 0.491309 14.4661 0.491309C10.843 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H13.7511V3.75098C13.7511 4.34032 13.7511 4.28662 13.7511 4.21525H14.2127L15.4818 2.95342ZM14.5585 2.02489C14.5958 1.98756 14.6076 1.93059 14.5873 1.88214C14.567 1.83303 14.5192 1.80094 14.4661 1.80094C10.843 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H13.4813L14.5585 2.02489Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.97217 6.37747V8.99673C3.97217 9.35819 4.26553 9.65155 4.62698 9.65155C4.98844 9.65155 5.2818 9.35819 5.2818 8.99673V6.37747C5.2818 6.01601 4.98844 5.72266 4.62698 5.72266C4.26553 5.72266 3.97217 6.01601 3.97217 6.37747Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.31741 8.34186H5.93668C6.29813 8.34186 6.59149 8.0485 6.59149 7.68704C6.59149 7.32558 6.29813 7.03223 5.93668 7.03223H3.31741C2.95596 7.03223 2.6626 7.32558 2.6626 7.68704C2.6626 8.0485 2.95596 8.34186 3.31741 8.34186Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M8.55594 13.5804H11.1752C11.5367 13.5804 11.83 13.287 11.83 12.9256C11.83 12.5641 11.5367 12.2708 11.1752 12.2708H8.55594C8.19448 12.2708 7.90112 12.5641 7.90112 12.9256C7.90112 13.287 8.19448 13.5804 8.55594 13.5804Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.59608 11.616C6.59608 11.2545 6.30272 10.9612 5.94126 10.9612H3.31741C2.9553 10.9612 2.6626 11.2545 2.6626 11.616V14.2353C2.6626 14.5967 2.9553 14.8901 3.31741 14.8901H5.94126C6.30272 14.8901 6.59608 14.5967 6.59608 14.2353V11.616ZM3.97223 12.2708V13.5804H5.28644V12.2708H3.97223Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M11.8299 6.37747C11.8299 6.01601 11.5365 5.72266 11.1751 5.72266H8.55057C8.18911 5.72266 7.89575 6.01601 7.89575 6.37747V8.99673C7.89575 9.35819 8.18911 9.65155 8.55057 9.65155H11.1751C11.5365 9.65155 11.8299 9.35819 11.8299 8.99673V6.37747ZM9.20538 7.03229V8.34192H10.5203V7.03229H9.20538Z" fill="white"/></svg>')
    }

    .site-header .mobile-menu .button {
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 20px;
        text-transform: capitalize;
        padding: 20px 32px
    }

    .site-header a.cart {
        margin-left: 16px;
        position: relative
    }

    .site-header a.cart span {
        position: absolute;
        border-radius: 50%;
        background: #ff6319;
        width: 20px;
        height: 20px;
        display: -webkit-inline-box;
        display: -ms-inline-flexbox;
        display: inline-flex;
        color: #fff;
        font-size: 12px;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        right: -20px;
        top: -6px
    }

    .site-header .mobile-toggles,.site-header a.cart span {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .site-header .mobile-toggles .cart {
        margin-right: 24px
    }
}

@media (min-width: 768px) and (max-width:991.98px) and (orientation:landscape) {
    .site-header .mobile-menu {
        background-color:#1f1f1f;
        position: absolute;
        width: 100%;
        height: calc(var(--vh, 1vh)*100);
        padding: 0 0 26px;
        bottom: 100%;
        -webkit-transition: all .5s ease;
        transition: all .5s ease
    }

    .site-header .mobile-menu .login-buttons {
        margin-left: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .site-header .mobile-menu .user-data {
        margin: 10px 24px 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column
    }

    .site-header .mobile-menu .user-data .avatar {
        width: 100px;
        height: 100px
    }

    .site-header .mobile-menu .buy-forever-by-one-click {
        margin-left: 0;
        padding-left: 24px
    }

    .site-header .mobile-menu.active {
        bottom: auto;
        top: 0;
        -webkit-transition: all .5s ease;
        transition: all .5s ease
    }

    .site-header .mobile-menu ul {
        padding: 0;
        margin-top: 74px
    }

    .site-header .mobile-menu ul li {
        list-style: none
    }

    .site-header .mobile-menu ul li a {
        text-decoration: none;
        font-size: 16px;
        font-style: normal;
        color: #fff;
        font-weight: 600;
        line-height: 150%;
        -webkit-transition: all .5s ease;
        transition: all .5s ease;
        display: block
    }

    .site-header .mobile-menu ul li:not(:last-child) {
        margin-bottom: 15px
    }

    .site-header .mobile-menu ul li.calculator-item a,.site-header .mobile-menu ul li.referral-item a {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        position: relative;
        gap: 10px
    }

    .site-header .mobile-menu ul li.referral-item a::before {
        width: 17px;
        height: 16.946px;
        content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4173 5.08459C14.4173 4.56558 14.2113 4.06809 13.8442 3.70166C13.4778 3.33458 12.9803 3.12854 12.4613 3.12854C9.15883 3.12854 0.725014 3.12854 0.725014 3.12854C0.365101 3.12854 0.072998 3.42064 0.072998 3.78056V14.8648C0.072998 16.3051 1.24076 17.4729 2.68106 17.4729H13.7653C14.1252 17.4729 14.4173 17.1808 14.4173 16.8209V5.08459ZM1.37703 4.43257V14.8648C1.37703 15.5853 1.96058 16.1688 2.68106 16.1688H13.1133V5.08459C13.1133 4.9118 13.0448 4.74554 12.9223 4.62361C12.8003 4.50103 12.6341 4.43257 12.4613 4.43257H1.37703Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0325 3.12597C17.0273 1.68957 15.8608 0.527683 14.4245 0.527683C10.7973 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H12.4658C12.8251 4.42543 13.1165 4.71623 13.1178 5.07549C13.1283 8.37599 13.1556 16.8229 13.1556 16.8229C13.1563 17.0282 13.2534 17.2212 13.4184 17.3438C13.5827 17.4664 13.7953 17.5042 13.9922 17.4462C13.9922 17.4462 15.0595 17.1326 15.9137 16.4316C16.5592 15.9022 17.0716 15.1641 17.0729 14.2207L17.0325 3.12597ZM15.7285 3.13118C15.7259 2.41266 15.143 1.83171 14.4245 1.83171C10.7973 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H12.4658C13.5436 3.1214 14.4179 3.99315 14.4219 5.07093L14.4571 15.8403C14.6624 15.7288 14.8828 15.5905 15.0869 15.4236C15.4449 15.1296 15.7683 14.7423 15.7689 14.2226L15.7285 3.13118Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5.60673 13.0425C4.92342 12.4902 4.48592 11.6459 4.48592 10.7005C4.48592 9.03912 5.83494 7.69075 7.49562 7.69075C9.15695 7.69075 10.5053 9.03912 10.5053 10.7005C10.5053 11.6459 10.0678 12.4902 9.38451 13.0425C9.10479 13.2687 9.06111 13.6795 9.28736 13.9592C9.51361 14.2389 9.92438 14.2826 10.2041 14.0564C11.1834 13.2655 11.8094 12.0553 11.8094 10.7005C11.8094 8.31929 9.87678 6.38672 7.49562 6.38672C5.11511 6.38672 3.18188 8.31929 3.18188 10.7005C3.18188 12.0553 3.80847 13.2655 4.78715 14.0564C5.06686 14.2826 5.47763 14.2389 5.70388 13.9592C5.93013 13.6795 5.88645 13.2687 5.60673 13.0425Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4885 2.97926C15.9006 2.5698 16.0245 1.95169 15.8028 1.41508C15.5818 0.877816 15.0582 0.527683 14.4773 0.527683C10.8697 0.527683 3.3754 0.527683 3.3754 0.527683C2.40586 0.507471 1.65213 1.01474 1.11617 1.65763C0.40091 2.51437 0.0970708 3.59737 0.0970708 3.59737C0.0423015 3.79363 0.0820745 4.00488 0.205305 4.16723C0.328536 4.33024 0.520881 4.42543 0.724962 4.42543H13.7653V3.77342C13.7653 4.42543 13.7653 4.35697 13.7653 4.2357H14.2249L15.4885 2.97926ZM14.5692 2.0547C14.6064 2.01754 14.6175 1.96081 14.5979 1.91256C14.5777 1.86366 14.5301 1.83171 14.4773 1.83171C10.8697 1.83171 3.37541 1.83171 3.36236 1.83171C2.81598 1.81998 2.41499 2.13621 2.11702 2.49286C1.94814 2.69564 1.80926 2.91536 1.69907 3.1214H13.496L14.5692 2.0547Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9.80045 14.2017H11.1573C11.5172 14.2017 11.8093 13.909 11.8093 13.5497C11.8093 13.1898 11.5172 12.8977 11.1573 12.8977H9.80045C9.44054 12.8977 9.14844 13.1898 9.14844 13.5497C9.14844 13.909 9.44054 14.2017 9.80045 14.2017Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.8339 14.2017H5.1914C5.55131 14.2017 5.84341 13.909 5.84341 13.5497C5.84341 13.1898 5.55131 12.8977 5.1914 12.8977H3.8339C3.47399 12.8977 3.18188 13.1898 3.18188 13.5497C3.18188 13.909 3.47399 14.2017 3.8339 14.2017Z" fill="white"/></svg>')
    }

    .site-header .mobile-menu ul li.calculator-item a::before {
        width: 17.073px;
        height: 17.019px;
        content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4059 5.06784C14.4059 4.54661 14.199 4.04698 13.831 3.67898C13.4624 3.31032 12.9627 3.10339 12.4415 3.10339C9.12486 3.10339 0.654816 3.10339 0.654816 3.10339C0.293357 3.10339 0 3.39675 0 3.75821V14.8901C0 16.3366 1.17277 17.5093 2.61926 17.5093H13.7511C14.1132 17.5093 14.4059 17.216 14.4059 16.8545V5.06784ZM1.30963 4.41302V14.8901C1.30963 15.6136 1.89635 16.1997 2.61926 16.1997H13.0963V5.06784C13.0963 4.89431 13.0276 4.72734 12.9045 4.60489C12.782 4.48178 12.6157 4.41302 12.4415 4.41302H1.30963Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M17.0324 3.10075C17.0271 1.65819 15.8563 0.491309 14.4131 0.491309C10.7704 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H12.446C12.8068 4.4058 13.0995 4.69785 13.1008 5.05865C13.1113 8.37333 13.1395 16.8565 13.1395 16.8565C13.1401 17.0627 13.2377 17.2566 13.4027 17.3797C13.5677 17.5028 13.7812 17.5407 13.979 17.4825C13.979 17.4825 15.0509 17.1675 15.9087 16.4636C16.557 15.9319 17.0723 15.1906 17.073 14.2431L17.0324 3.10075ZM15.7227 3.10599C15.7201 2.38438 15.1347 1.80094 14.4131 1.80094C10.7704 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H12.446C13.5284 3.09617 14.4065 3.97165 14.4105 5.05407L14.4458 15.8697C14.6528 15.7577 14.8734 15.6189 15.0784 15.4512C15.4379 15.1559 15.7627 14.7669 15.7633 14.2451L15.7227 3.10599Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M15.4818 2.95342C15.8956 2.54219 16.02 1.92143 15.798 1.38251C15.5754 0.842945 15.0496 0.491309 14.4661 0.491309C10.843 0.491309 3.31724 0.491309 3.31724 0.491309C2.34288 0.47101 1.58657 0.980456 1.04765 1.6261C0.32932 2.48653 0.0241762 3.57418 0.0241762 3.57418C-0.0308284 3.77128 0.00911548 3.98344 0.132876 4.14649C0.256636 4.31019 0.449806 4.4058 0.654764 4.4058H13.7511V3.75098C13.7511 4.34032 13.7511 4.28662 13.7511 4.21525H14.2127L15.4818 2.95342ZM14.5585 2.02489C14.5958 1.98756 14.6076 1.93059 14.5873 1.88214C14.567 1.83303 14.5192 1.80094 14.4661 1.80094C10.843 1.80094 3.31724 1.80094 3.30349 1.80094C2.75476 1.78915 2.35205 2.10674 2.05279 2.46492C1.8832 2.66857 1.74438 2.88924 1.63306 3.09617H13.4813L14.5585 2.02489Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.97217 6.37747V8.99673C3.97217 9.35819 4.26553 9.65155 4.62698 9.65155C4.98844 9.65155 5.2818 9.35819 5.2818 8.99673V6.37747C5.2818 6.01601 4.98844 5.72266 4.62698 5.72266C4.26553 5.72266 3.97217 6.01601 3.97217 6.37747Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3.31741 8.34186H5.93668C6.29813 8.34186 6.59149 8.0485 6.59149 7.68704C6.59149 7.32558 6.29813 7.03223 5.93668 7.03223H3.31741C2.95596 7.03223 2.6626 7.32558 2.6626 7.68704C2.6626 8.0485 2.95596 8.34186 3.31741 8.34186Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M8.55594 13.5804H11.1752C11.5367 13.5804 11.83 13.287 11.83 12.9256C11.83 12.5641 11.5367 12.2708 11.1752 12.2708H8.55594C8.19448 12.2708 7.90112 12.5641 7.90112 12.9256C7.90112 13.287 8.19448 13.5804 8.55594 13.5804Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.59608 11.616C6.59608 11.2545 6.30272 10.9612 5.94126 10.9612H3.31741C2.9553 10.9612 2.6626 11.2545 2.6626 11.616V14.2353C2.6626 14.5967 2.9553 14.8901 3.31741 14.8901H5.94126C6.30272 14.8901 6.59608 14.5967 6.59608 14.2353V11.616ZM3.97223 12.2708V13.5804H5.28644V12.2708H3.97223Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M11.8299 6.37747C11.8299 6.01601 11.5365 5.72266 11.1751 5.72266H8.55057C8.18911 5.72266 7.89575 6.01601 7.89575 6.37747V8.99673C7.89575 9.35819 8.18911 9.65155 8.55057 9.65155H11.1751C11.5365 9.65155 11.8299 9.35819 11.8299 8.99673V6.37747ZM9.20538 7.03229V8.34192H10.5203V7.03229H9.20538Z" fill="white"/></svg>')
    }

    .site-header .mobile-menu .button {
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 20px;
        text-transform: capitalize;
        padding: 20px 32px
    }

    .site-header a.cart {
        margin-left: 16px;
        position: relative
    }

    .site-header a.cart span {
        position: absolute;
        border-radius: 50%;
        background: #ff6319;
        width: 20px;
        height: 20px;
        display: -webkit-inline-box;
        display: -ms-inline-flexbox;
        display: inline-flex;
        color: #fff;
        font-size: 12px;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        right: -20px;
        top: -6px
    }

    .site-header .mobile-toggles,.site-header a.cart span {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .site-header .mobile-toggles .cart {
        margin-right: 24px
    }
}

footer {
    padding-top: 150px
}

footer .footer-wrapper {
    border-radius: 50px 50px 0 0;
    position: relative;
    z-index: 4;
    background: #272727;
    padding: 57px 0 20px
}

.calculators .tabs-wrapper .calculator-form strong,footer .footer-wrapper .large-words .dubadu-footer-word {
    font-size: 304.757px;
    font-style: normal;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -3.748px;
    color: #fff
}

footer .footer-wrapper .top-footer {
    margin-bottom: 44px
}

footer .footer-wrapper .top-footer .left-text {
    margin-top: 20px
}

footer .footer-wrapper .top-footer .social-networks {
    padding: 0;
    gap: 16px;
    margin-bottom: 0;
    margin-top: 20px
}

footer .footer-wrapper .top-footer .social-networks li {
    list-style: none;
    margin-bottom: 0!important
}

footer .footer-wrapper .top-footer .contact-data,footer .footer-wrapper .top-footer .social-networks,footer .footer-wrapper .top-footer .social-networks li a {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center
}

footer .footer-wrapper .top-footer .social-networks li a {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f5f6f8;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

footer .footer-wrapper .top-footer .social-networks li a svg path {
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

div.woocommerce .my-account .account-details .social-networks li a:hover svg path,footer .footer-wrapper .top-footer .social-networks li a:hover svg path {
    fill: #fff;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

div.woocommerce .my-account .account-details .social-networks li a:hover.facebook,footer .footer-wrapper .top-footer .social-networks li a:hover.facebook {
    background-color: #3373e1;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

div.woocommerce .my-account .account-details .social-networks li a:hover.linkedin,footer .footer-wrapper .top-footer .social-networks li a:hover.linkedin {
    background-color: #000;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

div.woocommerce .my-account .account-details .social-networks li a:hover.instagram,footer .footer-wrapper .top-footer .social-networks li a:hover.instagram {
    background: radial-gradient(circle at 30% 107%,#fdf497 0,#fdf497 5%,#fd5949 45%,#d6249f 60%,#285aeb 90%);
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

div.woocommerce .my-account .account-details .social-networks li a:hover.telegram,footer .footer-wrapper .top-footer .social-networks li a:hover.telegram {
    background: #08c;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

footer .footer-wrapper .top-footer .contact-data {
    padding: 0;
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    gap: 30px
}

footer .footer-wrapper .bottom-footer .bottom-footer-menu li,footer .footer-wrapper .top-footer .contact-data li {
    list-style: none
}

footer .footer-wrapper .bottom-footer .bottom-footer-menu li a,footer .footer-wrapper .top-footer .contact-data li a {
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    text-decoration: none;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

footer .footer-wrapper .top-footer .contact-data li a {
    color: #fff
}

footer .footer-wrapper .bottom-footer p {
    color: rgba(255,255,255,.5);
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    margin-bottom: 0
}

footer .footer-wrapper .bottom-footer .bottom-footer-menu {
    padding: 0;
    margin: 0;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    gap: 30px;
    position: relative;
    z-index: 5
}

footer .footer-wrapper .bottom-footer .bottom-footer-menu li a {
    color: rgba(255,255,255,.5)
}

footer .footer-wrapper .bottom-footer .bottom-footer-menu li a:hover {
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    color: #ff6319
}

@media (max-width: 575.98px) {
    footer {
        padding-top:70px
    }

    footer .footer-wrapper {
        border-radius: 20px 20px 0 0;
        padding: 26px 0
    }

    footer .footer-wrapper .large-words .dubadu-footer-word {
        font-size: 72.757px;
        margin-top: 20px
    }

    footer .footer-wrapper .top-footer {
        margin-bottom: 0
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu li a,footer .footer-wrapper .bottom-footer p,footer .footer-wrapper .top-footer .contact-data li a,footer .footer-wrapper .top-footer .left-text {
        font-size: 14px
    }

    footer .footer-wrapper .top-footer .copyright-text {
        font-size: 14px;
        margin-top: 14px;
        color: rgba(255,255,255,.5);
        margin-bottom: 0
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu,footer .footer-wrapper .top-footer .contact-data {
        gap: 14px;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column
    }

    footer .footer-wrapper .top-footer .contact-data {
        -webkit-box-pack: end;
        -ms-flex-pack: end;
        justify-content: flex-end;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        margin-top: 40px
    }

    footer .footer-wrapper .top-footer .contact-data li {
        list-style: none
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu {
        margin: 40px 0 0
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu li a:hover {
        -webkit-transition: all .5s ease;
        transition: all .5s ease;
        color: #ff6319
    }

    .dubadu-large-modal .modal-dialog .modal-content {
        border: 1px solid #d8d8d8;
        background: var(--White, #FFF);
        padding: 80px
    }

    .dubadu-large-modal .modal-dialog .modal-content .btn-close {
        position: absolute;
        left: 24px;
        top: 24px;
        border-radius: 0 1000px 1000px 0;
        border: 1px solid #d8d8d8;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 36px;
        height: 38px
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    footer {
        padding-top:70px
    }

    footer .footer-wrapper {
        border-radius: 20px 20px 0 0;
        padding: 26px 0
    }

    footer .footer-wrapper .large-words .dubadu-footer-word {
        font-size: 124.757px
    }

    footer .footer-wrapper .top-footer {
        margin-bottom: 0
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu li a,footer .footer-wrapper .bottom-footer p,footer .footer-wrapper .top-footer .contact-data li a,footer .footer-wrapper .top-footer .left-text {
        font-size: 14px
    }

    footer .footer-wrapper .top-footer .copyright-text {
        font-size: 14px;
        margin-top: 14px;
        color: rgba(255,255,255,.5);
        margin-bottom: 0
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu,footer .footer-wrapper .top-footer .contact-data {
        gap: 14px;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column
    }

    footer .footer-wrapper .top-footer .contact-data {
        -webkit-box-pack: end;
        -ms-flex-pack: end;
        justify-content: flex-end;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        margin-top: 40px
    }

    footer .footer-wrapper .top-footer .contact-data li {
        list-style: none
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu {
        margin: 40px 0 0
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu li a:hover {
        -webkit-transition: all .5s ease;
        transition: all .5s ease;
        color: #ff6319
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    footer {
        padding-top:70px
    }

    footer .footer-wrapper {
        border-radius: 20px 20px 0 0;
        padding: 26px 0
    }

    footer .footer-wrapper .large-words .dubadu-footer-word {
        font-size: 164.757px
    }

    footer .footer-wrapper .top-footer {
        margin-bottom: 40px
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu li a,footer .footer-wrapper .bottom-footer p,footer .footer-wrapper .top-footer .contact-data li a,footer .footer-wrapper .top-footer .left-text {
        font-size: 14px
    }

    footer .footer-wrapper .top-footer .contact-data {
        gap: 14px
    }

    footer .footer-wrapper .top-footer .contact-data li {
        list-style: none
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu {
        margin: 40px 0 0;
        gap: 14px
    }

    footer .footer-wrapper .bottom-footer .bottom-footer-menu li a:hover {
        -webkit-transition: all .5s ease;
        transition: all .5s ease;
        color: #ff6319
    }
}

@media (min-width: 992px) and (max-width:1199.98px) {
    footer .footer-wrapper .large-words .dubadu-footer-word {
        font-size:224.757px;
        font-style: normal;
        font-weight: 600;
        line-height: 110%;
        letter-spacing: -3.748px;
        color: #fff
    }
}

@media (min-width: 1200px) and (max-width:1399.98px) {
    footer .footer-wrapper .large-words .dubadu-footer-word {
        font-size:264.757px;
        font-style: normal;
        font-weight: 600;
        line-height: 110%;
        letter-spacing: -3.748px;
        color: #fff
    }
}

.main-banner {
    padding-top: 180px
}

.main-banner .buy-forever-by-one-click {
    margin-top: 40px
}

.main-banner .image-section {
    width: 100%;
    margin-top: 96px;
    position: relative;
    border-radius: 50px 50px 0 0;
    overflow: hidden
}

.main-banner .image-section .main-image {
    position: absolute;
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    -o-object-position: center;
    object-position: center;
    -webkit-transform: scaleX(-1);
    transform: scaleX(-1)
}

.main-banner .image-section .wrapper {
    position: relative;
    height: 800px
}

.main-banner .image-section .wrapper .block-1 {
    top: 50px;
    left: 0;
    border-radius: 12px;
    border: 1.233px solid #fff;
    background: rgba(255,255,255,.45);
    -webkit-backdrop-filter: blur(15px);
    backdrop-filter: blur(15px);
    position: absolute;
    padding: 16px;
    max-width: 328px
}

.main-banner .image-section .wrapper .block-1 .title {
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 130%;
    margin-bottom: 0
}

.main-banner .image-section .wrapper .block-1 .description {
    margin-top: 8px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-size: 11.571px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    margin-bottom: 0;
    gap: 6px;
    position: relative
}

.main-banner .image-section .wrapper .block-1 .description::before {
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none"><path d="M11.0625 0.927783C11.0028 0.930253 10.9443 0.943949 10.8902 0.968087C10.8361 0.992224 10.7875 1.02633 10.7472 1.06844C10.7069 1.11056 10.6757 1.15984 10.6555 1.21349C10.6352 1.26714 10.6262 1.3241 10.6289 1.38108C10.6317 1.43806 10.6463 1.49396 10.6717 1.54556C10.6972 1.59716 10.7331 1.64345 10.7773 1.68178C10.8215 1.72011 10.8732 1.74972 10.9295 1.76892C10.9857 1.78811 11.0454 1.79652 11.105 1.79365H13.5339L8.81067 5.58185L5.69491 3.59983C5.61617 3.54839 5.52245 3.52199 5.42709 3.5244C5.33173 3.52681 5.2396 3.5579 5.16382 3.61324L0.178619 7.2932C0.131165 7.32784 0.0913193 7.37107 0.061358 7.42042C0.0313966 7.46977 0.0119062 7.52429 0.00400088 7.58084C-0.00390441 7.63739 -7.00815e-05 7.69485 0.0152848 7.74999C0.0306397 7.80512 0.0572145 7.85681 0.0934908 7.90213C0.129767 7.94745 0.175034 7.98549 0.226707 8.0141C0.278379 8.0427 0.335443 8.06132 0.394641 8.06885C0.453838 8.07638 0.514009 8.07267 0.571715 8.05798C0.629421 8.04329 0.683531 8.01789 0.730956 7.98322L5.46123 4.49266L8.58406 6.48147C8.66568 6.53418 8.76305 6.56005 8.86143 6.55517C8.95982 6.55029 9.05386 6.51493 9.12932 6.45442L14.2774 2.32129V4.82421C14.2765 4.88158 14.2876 4.93853 14.31 4.99177C14.3324 5.04501 14.3657 5.09346 14.4078 5.13432C14.45 5.17518 14.5003 5.20761 14.5557 5.22976C14.6111 5.2519 14.6705 5.26331 14.7306 5.26331C14.7907 5.26331 14.8501 5.2519 14.9055 5.22976C14.9609 5.20761 15.0112 5.17518 15.0533 5.13432C15.0955 5.09346 15.1288 5.04501 15.1512 4.99177C15.1735 4.93853 15.1846 4.88158 15.1838 4.82421V1.36071C15.1838 1.24589 15.136 1.13578 15.051 1.05459C14.9661 0.973402 14.8508 0.927794 14.7306 0.927783H11.105C11.0908 0.927149 11.0767 0.927149 11.0625 0.927783Z" fill="white"/></svg>')
}

.main-banner .image-section .wrapper .block-1 .boxes-wrapper {
    margin-top: 21px;
    position: relative;
    overflow: hidden;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px
}

.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none
}

.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    border-top: 1.5px dashed rgba(255,255,255,.2)
}

.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines-2::before,.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines::after {
    content: "";
    position: absolute;
    top: 25%;
    left: 0;
    right: 0;
    border-top: 1.5px dashed rgba(255,255,255,.2)
}

.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines-2::before {
    top: 50%
}

.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines-2::after,.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines-3::after {
    content: "";
    position: absolute;
    top: 75%;
    left: 0;
    right: 0;
    border-top: 1.5px dashed rgba(255,255,255,.2)
}

.main-banner .image-section .wrapper .block-1 .boxes-wrapper .dashed-lines-3::after {
    top: 99%
}

.main-banner .image-section .wrapper .block-1 .boxes {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: end;
    -ms-flex-align: end;
    align-items: flex-end;
    gap: 7px
}

.main-banner .image-section .wrapper .block-1 .boxes .box {
    border-radius: 8px;
    background: #ff6319;
    width: 33.333%;
    z-index: 3;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center
}

.main-banner .image-section .wrapper .block-1 .boxes .box.box-1 {
    height: 32px;
    text-shadow: 0 4px 4px rgba(0,0,0,.25);
    font-size: 18px;
    font-style: normal;
    font-weight: 800;
    line-height: 110%;
    letter-spacing: -.18px
}

.main-banner .image-section .wrapper .block-1 .boxes .box.box-2,.main-banner .image-section .wrapper .block-1 .boxes .box.box-3 {
    height: 60px;
    text-shadow: 0 4px 4px rgba(0,0,0,.25);
    font-family: "RF Dewi";
    font-size: 22px;
    font-style: normal;
    font-weight: 800;
    line-height: 110%;
    letter-spacing: -.22px
}

.main-banner .image-section .wrapper .block-1 .boxes .box.box-3 {
    height: 120px;
    font-size: 28px;
    letter-spacing: -.28px
}

.main-banner .image-section .wrapper .block-2 {
    border-radius: 13.788px;
    border: 1.379px solid #fff;
    background: rgba(255,255,255,.35);
    -webkit-backdrop-filter: blur(15px);
    backdrop-filter: blur(15px);
    position: absolute;
    bottom: 50px;
    right: 0;
    padding: 26.2px 37.23px
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper {
    border-radius: 8.265px;
    background: #ff6319;
    padding: 14.81px 22.5px
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .title {
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 130%;
    margin-bottom: 0
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .description {
    margin-top: 5px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-size: 11.571px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    margin-bottom: 0;
    gap: 6px
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part {
    margin-top: 35px
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block {
    position: relative;
    height: 40px;
    width: 60%
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1,.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
    width: 39.672px;
    height: 39.672px;
    border: 1.034px solid #ff6319;
    border-radius: 50%;
    overflow: hidden;
    position: absolute
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
    width: 39.672px;
    position: absolute;
    height: 39.672px;
    border: 1.034px solid #ff6319;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    font-size: 11.26px;
    font-style: normal;
    font-weight: 700;
    line-height: 120%;
    color: #ff6319;
    background-color: #fff;
    border-radius: 50%
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 {
    left: 0;
    top: 0
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 img {
    width: 100%;
    height: 100%;
    -o-object-position: right;
    object-position: right;
    -o-object-fit: cover;
    object-fit: cover
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
    left: 30px;
    top: 0
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 img {
    width: 100%;
    height: 100%;
    -o-object-position: left;
    object-position: left;
    -o-object-fit: cover;
    object-fit: cover
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
    left: 60px;
    top: 0
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block {
    position: relative;
    padding-left: 6px;
    width: 40%
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block::before {
    content: "";
    position: absolute;
    left: -6px;
    width: .826px;
    height: 90%;
    top: 5%;
    opacity: .25;
    background: #fff
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .price {
    font-size: 12.918px;
    font-style: normal;
    font-weight: 600;
    line-height: 130%;
    margin-bottom: 2px
}

.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .last-description,.price-increase-after .buy-forever span {
    font-size: 11.265px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%
}

@media (max-width: 575.98px) {
    .main-banner {
        padding-top:90px
    }

    .main-banner .buy-forever-by-one-click {
        margin-top: 30px
    }

    .main-banner .image-section {
        margin-top: 36px;
        border-radius: 24px 24px 0 0
    }

    .main-banner .image-section .wrapper {
        position: relative;
        height: 419px
    }

    .main-banner .image-section .wrapper .block-1 {
        top: 16px;
        left: 0;
        border-radius: 8px;
        padding: 8px;
        max-width: 80%
    }

    .main-banner .image-section .wrapper .block-1 .title {
        font-size: 12px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 0
    }

    .main-banner .image-section .wrapper .block-1 .description {
        margin-top: 8px;
        font-size: 10px;
        line-height: 130%
    }

    .main-banner .image-section .wrapper .block-1 .boxes-wrapper {
        margin-top: 12px;
        position: relative;
        overflow: hidden;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px
    }

    .main-banner .image-section .wrapper .block-1 .boxes {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: end;
        -ms-flex-align: end;
        align-items: flex-end;
        gap: 7px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box {
        border-radius: 4px;
        background: #ff6319;
        width: 33.333%;
        z-index: 3;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-1 {
        height: 17px;
        font-size: 10px;
        letter-spacing: -.1px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-2 {
        height: 38px;
        font-size: 16px;
        letter-spacing: -.16px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-3 {
        height: 76px;
        font-size: 18px;
        letter-spacing: -.18px
    }

    .main-banner .image-section .wrapper .block-2 {
        border-radius: 8px;
        position: absolute;
        bottom: 16px;
        right: 0;
        padding: 8px;
        max-width: 80%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper {
        border-radius: 6px;
        background: #ff6319;
        padding: 8px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .title {
        font-size: 12px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .description {
        margin-top: 5px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        font-size: 10px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin-bottom: 0;
        gap: 6px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part {
        margin-top: 17px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block {
        position: relative;
        height: 40px;
        width: 60%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1,.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
        width: 39.672px;
        height: 39.672px;
        border: 1.034px solid #ff6319;
        border-radius: 50%;
        overflow: hidden;
        position: absolute
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
        width: 39.672px;
        position: absolute;
        height: 39.672px;
        border: 1.034px solid #ff6319;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        font-size: 11.26px;
        font-style: normal;
        font-weight: 700;
        line-height: 120%;
        color: #ff6319;
        background-color: #fff;
        border-radius: 50%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 {
        left: 0;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 img {
        width: 100%;
        height: 100%;
        -o-object-position: right;
        object-position: right;
        -o-object-fit: cover;
        object-fit: cover
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
        left: 30px;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 img {
        width: 100%;
        height: 100%;
        -o-object-position: left;
        object-position: left;
        -o-object-fit: cover;
        object-fit: cover
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
        left: 60px;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block {
        position: relative;
        padding-left: 6px;
        width: 40%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block::before {
        content: "";
        position: absolute;
        left: -6px;
        width: .826px;
        height: 90%;
        top: 5%;
        opacity: .25;
        background: #fff
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .price {
        font-size: 12.918px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 2px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .last-description {
        font-size: 11.265px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .main-banner {
        padding-top:90px
    }

    .main-banner .buy-forever-by-one-click {
        margin-top: 30px
    }

    .main-banner .image-section {
        margin-top: 36px;
        border-radius: 24px 24px 0 0
    }

    .main-banner .image-section .wrapper {
        position: relative;
        height: 419px
    }

    .main-banner .image-section .wrapper .block-1 {
        top: 16px;
        left: 0;
        border-radius: 8px;
        padding: 8px;
        max-width: 80%
    }

    .main-banner .image-section .wrapper .block-1 .title {
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 0
    }

    .main-banner .image-section .wrapper .block-1 .description {
        margin-top: 8px;
        font-size: 11px;
        line-height: 130%
    }

    .main-banner .image-section .wrapper .block-1 .boxes-wrapper {
        margin-top: 12px;
        position: relative;
        overflow: hidden;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px
    }

    .main-banner .image-section .wrapper .block-1 .boxes {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: end;
        -ms-flex-align: end;
        align-items: flex-end;
        gap: 7px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box {
        border-radius: 4px;
        background: #ff6319;
        width: 33.333%;
        z-index: 3;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-1 {
        height: 17px;
        font-size: 10px;
        letter-spacing: -.1px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-2 {
        height: 38px;
        font-size: 16px;
        letter-spacing: -.16px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-3 {
        height: 76px;
        font-size: 18px;
        letter-spacing: -.18px
    }

    .main-banner .image-section .wrapper .block-2 {
        border-radius: 8px;
        position: absolute;
        bottom: 16px;
        right: 0;
        padding: 8px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper {
        border-radius: 6px;
        background: #ff6319;
        padding: 8px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .title {
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .description {
        margin-top: 5px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        font-size: 11.571px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin-bottom: 0;
        gap: 6px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part {
        margin-top: 35px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block {
        position: relative;
        height: 40px;
        width: 60%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1,.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
        width: 39.672px;
        height: 39.672px;
        border: 1.034px solid #ff6319;
        border-radius: 50%;
        overflow: hidden;
        position: absolute
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
        width: 39.672px;
        position: absolute;
        height: 39.672px;
        border: 1.034px solid #ff6319;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        font-size: 11.26px;
        font-style: normal;
        font-weight: 700;
        line-height: 120%;
        color: #ff6319;
        background-color: #fff;
        border-radius: 50%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 {
        left: 0;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 img {
        width: 100%;
        height: 100%;
        -o-object-position: right;
        object-position: right;
        -o-object-fit: cover;
        object-fit: cover
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
        left: 30px;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 img {
        width: 100%;
        height: 100%;
        -o-object-position: left;
        object-position: left;
        -o-object-fit: cover;
        object-fit: cover
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
        left: 60px;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block {
        position: relative;
        padding-left: 6px;
        width: 40%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block::before {
        content: "";
        position: absolute;
        left: -6px;
        width: .826px;
        height: 90%;
        top: 5%;
        opacity: .25;
        background: #fff
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .price {
        font-size: 12.918px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 2px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .last-description {
        font-size: 11.265px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .main-banner {
        padding-top:90px
    }

    .main-banner .buy-forever-by-one-click {
        margin-top: 30px
    }

    .main-banner .image-section {
        margin-top: 36px;
        border-radius: 24px 24px 0 0
    }

    .main-banner .image-section .wrapper {
        position: relative;
        height: 419px
    }

    .main-banner .image-section .wrapper .block-1 {
        top: 16px;
        left: 0;
        border-radius: 8px;
        padding: 8px;
        max-width: 80%
    }

    .main-banner .image-section .wrapper .block-1 .title {
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 0
    }

    .main-banner .image-section .wrapper .block-1 .description {
        margin-top: 8px;
        font-size: 11px;
        line-height: 130%
    }

    .main-banner .image-section .wrapper .block-1 .boxes-wrapper {
        margin-top: 12px;
        position: relative;
        overflow: hidden;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px
    }

    .main-banner .image-section .wrapper .block-1 .boxes {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: end;
        -ms-flex-align: end;
        align-items: flex-end;
        gap: 7px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box {
        border-radius: 4px;
        background: #ff6319;
        width: 33.333%;
        z-index: 3;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-1 {
        height: 17px;
        font-size: 10px;
        letter-spacing: -.1px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-2 {
        height: 38px;
        font-size: 16px;
        letter-spacing: -.16px
    }

    .main-banner .image-section .wrapper .block-1 .boxes .box.box-3 {
        height: 76px;
        font-size: 18px;
        letter-spacing: -.18px
    }

    .main-banner .image-section .wrapper .block-2 {
        border-radius: 8px;
        position: absolute;
        bottom: 16px;
        right: 0;
        padding: 8px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper {
        border-radius: 6px;
        background: #ff6319;
        padding: 8px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .title {
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .description {
        margin-top: 5px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        font-size: 11.571px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin-bottom: 0;
        gap: 6px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part {
        margin-top: 35px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block {
        position: relative;
        height: 40px;
        width: 60%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1,.main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
        width: 39.672px;
        height: 39.672px;
        border: 1.034px solid #ff6319;
        border-radius: 50%;
        overflow: hidden;
        position: absolute
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
        width: 39.672px;
        position: absolute;
        height: 39.672px;
        border: 1.034px solid #ff6319;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        font-size: 11.26px;
        font-style: normal;
        font-weight: 700;
        line-height: 120%;
        color: #ff6319;
        background-color: #fff;
        border-radius: 50%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 {
        left: 0;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-1 img {
        width: 100%;
        height: 100%;
        -o-object-position: right;
        object-position: right;
        -o-object-fit: cover;
        object-fit: cover
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 {
        left: 30px;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-2 img {
        width: 100%;
        height: 100%;
        -o-object-position: left;
        object-position: left;
        -o-object-fit: cover;
        object-fit: cover
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .images-block .circle-3 {
        left: 60px;
        top: 0
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block {
        position: relative;
        padding-left: 6px;
        width: 40%
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block::before {
        content: "";
        position: absolute;
        left: -6px;
        width: .826px;
        height: 90%;
        top: 5%;
        opacity: .25;
        background: #fff
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .price {
        font-size: 12.918px;
        font-style: normal;
        font-weight: 600;
        line-height: 130%;
        margin-bottom: 2px
    }

    .main-banner .image-section .wrapper .block-2 .orange-wrapper .bottom-part .text-block .last-description {
        font-size: 11.265px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%
    }
}

.tab-list {
    padding-top: 125px;
    position: relative
}

.tab-list .shape-1,.tab-list .shape-2 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    pointer-events: none
}

.tab-list .shape-1 {
    left: -25%;
    bottom: -50%
}

.tab-list .shape-2 {
    right: -25%;
    top: 0
}

.calculators ul.tabs,.tab-list .tabs-nav-wrapper ul.tabs {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    gap: 10px;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding: 0;
    margin: 0
}

.calculators ul.tabs li,.tab-list .tabs-nav-wrapper ul.tabs li {
    list-style: none
}

.calculators ul.tabs li a,.tab-list .tabs-nav-wrapper ul.tabs li a {
    color: #fff;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 150%;
    padding: 10px 20px;
    border-radius: 100px;
    border: 1px solid #fff;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

.tab-list .tabs-nav-wrapper ul.tabs li a.active {
    border: 1px solid #ff6319;
    background-color: #ff6319
}

.tab-list .tabs-wrapper {
    margin-top: 41px
}

.tab-list .tabs-wrapper .tab-block {
    margin-bottom: 20px
}

.tab-list .tabs-wrapper .tab-block .item-block {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    height: 100%
}

.tab-list .tabs-wrapper .tab-block .item-block .desc {
    padding: 30px 30px 0
}

.calculators .desc p,.tab-list .tabs-wrapper .tab-block .item-block .desc p {
    color: rgba(255,255,255,.65)
}

.tab-list .tabs-wrapper .tab-block .item-block .image-block {
    margin-top: auto;
    padding: 0 30px 30px
}

.tab-list .tabs-wrapper .tab-block .item-block .image-block img {
    width: 100%;
    height: 200px;
    -o-object-fit: contain;
    object-fit: contain;
    -o-object-position: center;
    object-position: center
}

@media (max-width: 575.98px) {
    .tab-list {
        padding-top:70px
    }

    .tab-list .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        left: -25%;
        bottom: unset;
        top: 25%
    }

    .tab-list .shape-2 {
        display: none
    }

    .tab-list .tabs-nav-wrapper ul.tabs {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        gap: 0;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        padding: 0;
        margin: 30px 0 0;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between
    }

    .tab-list .tabs-nav-wrapper ul.tabs li {
        list-style: none;
        width: calc(50% - 10px)
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:not(:last-child) {
        margin-bottom: 10px;
        margin-left: 10px
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:not(:first-child) {
        margin-left: 10px
    }

    .tab-list .tabs-nav-wrapper ul.tabs li a {
        font-size: 14px;
        line-height: 100%;
        padding: 10px 20px;
        text-align: center
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:last-child {
        width: 100%
    }

    .tab-list .tabs-wrapper {
        margin-top: 41px
    }

    .tab-list .tabs-wrapper .tab-block {
        margin-bottom: 16px
    }

    .tab-list .tabs-wrapper .tab-block .item-block {
        border-radius: 10px
    }

    .tab-list .tabs-wrapper .tab-block .item-block .desc {
        padding: 16px 16px 0
    }

    .tab-list .tabs-wrapper .tab-block .item-block .image-block {
        margin-top: auto;
        padding: 0 30px 30px
    }

    .tab-list .tabs-wrapper .tab-block .item-block .image-block img {
        width: 100%;
        height: 200px;
        -o-object-fit: contain;
        object-fit: contain;
        -o-object-position: center;
        object-position: center
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .tab-list {
        padding-top:70px
    }

    .tab-list .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        left: 0;
        bottom: unset;
        top: 25%
    }

    .tab-list .shape-2 {
        display: none
    }

    .tab-list .tabs-nav-wrapper ul.tabs {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        gap: 10px;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        padding: 0;
        margin: 30px 0 0;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between
    }

    .tab-list .tabs-nav-wrapper ul.tabs li {
        list-style: none;
        width: calc(50% - 10px)
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:not(:last-child) {
        margin-bottom: 10px;
        margin-left: 10px
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:not(:first-child) {
        margin-left: 10px
    }

    .tab-list .tabs-nav-wrapper ul.tabs li a {
        font-size: 14px;
        line-height: 100%;
        padding: 10px 20px;
        text-align: center
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:last-child {
        width: 100%
    }

    .tab-list .tabs-wrapper {
        margin-top: 41px
    }

    .tab-list .tabs-wrapper .tab-block {
        margin-bottom: 16px
    }

    .tab-list .tabs-wrapper .tab-block .item-block {
        border-radius: 10px
    }

    .tab-list .tabs-wrapper .tab-block .item-block .desc {
        padding: 16px 16px 0
    }

    .tab-list .tabs-wrapper .tab-block .item-block .image-block {
        margin-top: auto;
        padding: 0 30px 30px
    }

    .tab-list .tabs-wrapper .tab-block .item-block .image-block img {
        width: 100%;
        height: 200px;
        -o-object-fit: contain;
        object-fit: contain;
        -o-object-position: center;
        object-position: center
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .tab-list {
        padding-top:70px
    }

    .tab-list .shape-1,.tab-list .shape-2 {
        border-radius: 347px;
        width: 347px;
        height: 314px
    }

    .tab-list .shape-1 {
        left: 0;
        bottom: 0
    }

    .tab-list .shape-2 {
        position: absolute;
        right: -25%;
        top: 0
    }

    .tab-list .tabs-nav-wrapper ul.tabs {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        gap: 10px;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        padding: 0;
        margin: 30px 0 0
    }

    .tab-list .tabs-nav-wrapper ul.tabs li {
        list-style: none;
        width: calc(50% - 10px)
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:not(:last-child) {
        margin-bottom: 10px
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:not(:first-child) {
        margin-left: 10px
    }

    .tab-list .tabs-nav-wrapper ul.tabs li a {
        font-size: 14px;
        line-height: 100%;
        padding: 10px 20px;
        text-align: center
    }

    .tab-list .tabs-nav-wrapper ul.tabs li:last-child {
        width: 100%
    }

    .tab-list .tabs-wrapper {
        margin-top: 41px
    }

    .tab-list .tabs-wrapper .tab-block {
        margin-bottom: 16px
    }

    .tab-list .tabs-wrapper .tab-block .item-block {
        border-radius: 10px
    }

    .tab-list .tabs-wrapper .tab-block .item-block .desc {
        padding: 16px 16px 0
    }

    .tab-list .tabs-wrapper .tab-block .item-block .image-block {
        margin-top: auto;
        padding: 0 30px 30px
    }

    .tab-list .tabs-wrapper .tab-block .item-block .image-block img {
        width: 100%;
        height: 200px;
        -o-object-fit: contain;
        object-fit: contain;
        -o-object-position: center;
        object-position: center
    }
}

.price-increase-after {
    margin-top: 130px
}

.price-increase-after .forever-word {
    color: #646464;
    font-size: 48px;
    font-style: normal;
    font-weight: 700;
    line-height: 110%;
    letter-spacing: -.48px
}

.price-increase-after .count-left {
    background: -webkit-gradient(linear,left top,left bottom,from(#f05f1a),to(#473025));
    background: linear-gradient(180deg,#f05f1a 0,#473025 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 200px;
    font-style: normal;
    font-weight: 700;
    line-height: 110%;
    letter-spacing: -2px
}

.price-increase-after .buy-forever {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    border-radius: 100px;
    background: -webkit-gradient(linear,right top,left top,color-stop(.07%,rgba(41,41,41,.75)),color-stop(99.93%,rgba(34,34,34,.75)));
    background: linear-gradient(270deg,rgba(41,41,41,.75) .07%,rgba(34,34,34,.75) 99.93%);
    -webkit-backdrop-filter: blur(2px);
    backdrop-filter: blur(2px);
    padding: 14px 35px;
    margin-top: 35px;
    gap: 40px
}

.price-increase-after .buy-forever span {
    color: rgba(255,255,255,.65);
    font-size: 22px
}

@media (max-width: 575.98px) {
    .price-increase-after {
        margin-top:50px
    }

    .price-increase-after .forever-word {
        font-size: 28px;
        letter-spacing: -.28px
    }

    .price-increase-after .count-left {
        font-size: 120px;
        letter-spacing: -1.2px
    }

    .price-increase-after .buy-forever {
        padding: 10px;
        margin-top: 26px;
        gap: 9px;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        width: 100%;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .price-increase-after {
        margin-top:50px
    }

    .price-increase-after .forever-word {
        font-size: 28px;
        letter-spacing: -.28px
    }

    .price-increase-after .count-left {
        font-size: 120px;
        letter-spacing: -1.2px
    }

    .price-increase-after .buy-forever {
        padding: 10px;
        margin-top: 26px;
        gap: 9px
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .price-increase-after {
        margin-top:50px
    }

    .price-increase-after .forever-word {
        font-size: 28px;
        letter-spacing: -.28px
    }

    .price-increase-after .count-left {
        font-size: 120px;
        letter-spacing: -1.2px
    }

    .price-increase-after .buy-forever {
        padding: 10px;
        margin-top: 26px;
        gap: 9px
    }
}

.calculators {
    padding-top: 150px;
    position: relative
}

.calculators .shape-1 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    right: 25%;
    top: 25%;
    pointer-events: none
}

.calculators ul.tabs li a.active {
    border: 1px solid #ff6319;
    background-color: #ff6319
}

.calculators .tabs-wrapper {
    margin-top: 40px
}

.calculators .tabs-wrapper .calculator-form {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    overflow: hidden
}

.calculators .tabs-wrapper .calculator-form .range-block {
    padding: 30px 30px 0
}

.calculators .tabs-wrapper .calculator-form .line-block {
    padding: 34px 30px;
    border-top: 1px solid #3b3b3b;
    border-bottom: 1px solid #3b3b3b;
    background: rgba(42,42,42,.5)
}

.calculators .tabs-wrapper .calculator-form .no-line-block {
    padding: 34px 30px
}

.calculators .tabs-wrapper .calculator-form strong {
    font-size: 28px;
    letter-spacing: -.28px
}

.calculators .tabs-wrapper .calculator-form .bottom-prices-block {
    border-top: 1px solid rgba(59,59,59,.5);
    background: #2a2a2a
}

.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3:not(:last-child),.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4:not(:last-child) {
    border-right: 1px solid #3b3b3b
}

.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3 .price-wrapper,.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4 .price-wrapper {
    padding: 40px 20px
}

.calculators .tabs-wrapper .calculator-form .bottom-prices-block strong {
    font-size: 22px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    color: rgba(255,255,255,.65);
    margin-bottom: 24px
}

.calculators .tabs-wrapper .calculator-form .bottom-prices-block .price {
    font-size: 35px;
    font-style: normal;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -.35px;
    position: relative;
    display: inline-block
}

.calculators .tabs-wrapper .calculator-form .bottom-prices-block .price::after {
    content: "";
    height: 2px;
    width: 100%;
    left: 0;
    position: absolute;
    background-color: #ff6319;
    bottom: -7px
}

.calculators .tabs-wrapper #calculator-1-forever-count,.calculators .tabs-wrapper #calculator-2-month-income {
    margin-top: 30px;
    margin-bottom: 40px
}

.calculators .tabs-wrapper #calculator-1-forever-count.noUi-base,.calculators .tabs-wrapper #calculator-1-forever-count.noUi-target,.calculators .tabs-wrapper #calculator-2-month-income.noUi-base,.calculators .tabs-wrapper #calculator-2-month-income.noUi-target {
    background: 0 0;
    height: 50px!important;
    border-radius: 4px!important;
    -webkit-box-shadow: none;
    box-shadow: none;
    border: 0
}

.calculators .tabs-wrapper #calculator-1-forever-count.noUi-base .noUi-connects,.calculators .tabs-wrapper #calculator-1-forever-count.noUi-target .noUi-connects,.calculators .tabs-wrapper #calculator-2-month-income.noUi-base .noUi-connects,.calculators .tabs-wrapper #calculator-2-month-income.noUi-target .noUi-connects {
    background-color: #ff6319!important;
    height: 6px
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-handle,.calculators .tabs-wrapper #calculator-2-month-income .noUi-handle {
    width: 13px!important;
    height: 13px!important;
    border-radius: 50%!important;
    background: #ff6319!important;
    border: 0;
    -webkit-box-shadow: none!important;
    box-shadow: none!important;
    right: -6.5px;
    top: -5px
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-handle::after,.calculators .tabs-wrapper #calculator-1-forever-count .noUi-handle::before,.calculators .tabs-wrapper #calculator-2-month-income .noUi-handle::after,.calculators .tabs-wrapper #calculator-2-month-income .noUi-handle::before {
    content: unset
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-pips-horizontal,.calculators .tabs-wrapper #calculator-2-month-income .noUi-pips-horizontal {
    bottom: 0!important
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-value,.calculators .tabs-wrapper #calculator-2-month-income .noUi-value {
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    color: rgba(255,255,255,.65);
    -webkit-box-shadow: none;
    box-shadow: none
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-marker,.calculators .tabs-wrapper #calculator-2-month-income .noUi-marker {
    top: -44px;
    left: -1px;
    background: rgba(255,255,255,.65)!important
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-marker.noUi-marker-large,.calculators .tabs-wrapper #calculator-2-month-income .noUi-marker.noUi-marker-large {
    height: 9px;
    -webkit-transform: translate(-50%,0)!important;
    transform: translate(-50%,0)!important
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-marker.noUi-marker-normal,.calculators .tabs-wrapper #calculator-2-month-income .noUi-marker.noUi-marker-normal {
    display: none
}

.calculators .tabs-wrapper #calculator-1-forever-count .noUi-value.noUi-value-horizontal,.calculators .tabs-wrapper #calculator-2-month-income .noUi-value.noUi-value-horizontal {
    -webkit-transform: translate(-50%,-230%)!important;
    transform: translate(-50%,-230%)!important
}

@media (max-width: 575.98px) {
    .calculators {
        padding-top:70px
    }

    .calculators ul.tabs {
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin: 0 0 20px
    }

    .calculators ul.tabs li {
        list-style: none
    }

    .calculators ul.tabs li a {
        color: #fff;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 150%;
        padding: 10px 20px;
        border-radius: 100px;
        border: 1px solid #fff;
        -webkit-transition: all .5s ease;
        transition: all .5s ease
    }

    .calculators ul.tabs li a.active {
        border: 1px solid #ff6319;
        background-color: #ff6319
    }

    .calculators .tabs-wrapper {
        margin-top: 40px
    }

    .calculators .tabs-wrapper .calculator-form {
        border-radius: 20px;
        background: rgba(41,41,41,.75);
        -webkit-backdrop-filter: blur(12.5px);
        backdrop-filter: blur(12.5px);
        overflow: hidden
    }

    .calculators .tabs-wrapper .calculator-form .range-block {
        padding: 10px 10px 0
    }

    .calculators .tabs-wrapper .calculator-form .line-block,.calculators .tabs-wrapper .calculator-form .no-line-block {
        padding: 16px 10px
    }

    .calculators .tabs-wrapper .calculator-form strong {
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 110%;
        letter-spacing: -.2px;
        color: #fff
    }

    .calculators .tabs-wrapper .calculator-form p {
        margin-bottom: 0
    }

    .calculators .tabs-wrapper .calculator-form p.price {
        font-size: 20px;
        letter-spacing: -.2px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block {
        border-top: 1px solid rgba(59,59,59,.5);
        background: #2a2a2a
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3:not(:last-child),.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4:not(:last-child) {
        border-right: none;
        border-bottom: 1px solid #3b3b3b
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3 .price-wrapper,.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4 .price-wrapper {
        padding: 16px 10px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block strong {
        font-size: 16px;
        margin-bottom: 16px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .price {
        font-size: 30px;
        letter-spacing: -.2px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .price::after {
        content: unset
    }

    .calculators .tabs-wrapper #calculator-1-forever-count,.calculators .tabs-wrapper #calculator-2-month-income {
        margin-left: auto;
        margin-right: auto;
        width: 95%
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .calculators {
        padding-top:70px
    }

    .calculators ul.tabs {
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin: 0 0 20px
    }

    .calculators ul.tabs li {
        list-style: none
    }

    .calculators ul.tabs li a {
        color: #fff;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 150%;
        padding: 10px 20px;
        border-radius: 100px;
        border: 1px solid #fff;
        -webkit-transition: all .5s ease;
        transition: all .5s ease
    }

    .calculators ul.tabs li a.active {
        border: 1px solid #ff6319;
        background-color: #ff6319
    }

    .calculators .tabs-wrapper {
        margin-top: 40px
    }

    .calculators .tabs-wrapper .calculator-form {
        border-radius: 20px;
        background: rgba(41,41,41,.75);
        -webkit-backdrop-filter: blur(12.5px);
        backdrop-filter: blur(12.5px);
        overflow: hidden
    }

    .calculators .tabs-wrapper .calculator-form .range-block {
        padding: 10px 10px 0
    }

    .calculators .tabs-wrapper .calculator-form .line-block,.calculators .tabs-wrapper .calculator-form .no-line-block {
        padding: 16px 10px
    }

    .calculators .tabs-wrapper .calculator-form strong {
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 110%;
        letter-spacing: -.2px;
        color: #fff
    }

    .calculators .tabs-wrapper .calculator-form p {
        margin-bottom: 0
    }

    .calculators .tabs-wrapper .calculator-form p.price {
        font-size: 20px;
        letter-spacing: -.2px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block {
        border-top: 1px solid rgba(59,59,59,.5);
        background: #2a2a2a
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3:not(:last-child),.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4:not(:last-child) {
        border-right: none;
        border-bottom: 1px solid #3b3b3b
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3 .price-wrapper,.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4 .price-wrapper {
        padding: 16px 10px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block strong {
        font-size: 16px;
        margin-bottom: 16px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .price {
        font-size: 30px;
        letter-spacing: -.2px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .price::after {
        content: unset
    }

    .calculators .tabs-wrapper #calculator-1-forever-count,.calculators .tabs-wrapper #calculator-2-month-income {
        margin-left: auto;
        margin-right: auto;
        width: 95%
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .calculators {
        padding-top:70px
    }

    .calculators ul.tabs {
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin: 0 0 20px
    }

    .calculators ul.tabs li {
        list-style: none
    }

    .calculators ul.tabs li a {
        color: #fff;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 150%;
        padding: 10px 20px;
        border-radius: 100px;
        border: 1px solid #fff;
        -webkit-transition: all .5s ease;
        transition: all .5s ease
    }

    .calculators ul.tabs li a.active {
        border: 1px solid #ff6319;
        background-color: #ff6319
    }

    .calculators .tabs-wrapper {
        margin-top: 40px
    }

    .calculators .tabs-wrapper .calculator-form {
        border-radius: 20px;
        background: rgba(41,41,41,.75);
        -webkit-backdrop-filter: blur(12.5px);
        backdrop-filter: blur(12.5px);
        overflow: hidden
    }

    .calculators .tabs-wrapper .calculator-form .range-block {
        padding: 10px 10px 0
    }

    .calculators .tabs-wrapper .calculator-form .line-block,.calculators .tabs-wrapper .calculator-form .no-line-block {
        padding: 16px 10px
    }

    .calculators .tabs-wrapper .calculator-form strong {
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 110%;
        letter-spacing: -.2px;
        color: #fff
    }

    .calculators .tabs-wrapper .calculator-form p {
        margin-bottom: 0
    }

    .calculators .tabs-wrapper .calculator-form p.price {
        font-size: 20px;
        letter-spacing: -.2px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block {
        border-top: 1px solid rgba(59,59,59,.5);
        background: #2a2a2a
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3:not(:last-child),.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4:not(:last-child) {
        border-right: none;
        border-bottom: 1px solid #3b3b3b
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-3 .price-wrapper,.calculators .tabs-wrapper .calculator-form .bottom-prices-block .col-lg-4 .price-wrapper {
        padding: 16px 10px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block strong {
        font-size: 16px;
        margin-bottom: 16px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .price {
        font-size: 30px;
        letter-spacing: -.2px
    }

    .calculators .tabs-wrapper .calculator-form .bottom-prices-block .price::after {
        content: unset
    }

    .calculators .tabs-wrapper #calculator-1-forever-count,.calculators .tabs-wrapper #calculator-2-month-income {
        margin-left: auto;
        margin-right: auto;
        width: 95%
    }
}

.referral-program-block {
    padding-top: 50px;
    position: relative
}

.referral-program-block .shape-1 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    right: -25%;
    top: -25%;
    pointer-events: none
}

.referral-program-block .progress-steps {
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-position: top;
    height: 850px;
    overflow: hidden;
    position: relative
}

.referral-program-block .progress-steps:after {
    position: absolute;
    top: 30%;
    -webkit-transform: scaleX(-1);
    transform: scaleX(-1);
    left: 0;
    pointer-events: none;
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="1473" height="660" viewBox="0 0 1473 660" fill="none"><g filter="url(%23filter0_d_3597_20179)"><path d="M3 622C172.5 630.5 326.5 515.5 368 494.5C509.984 422.653 705.52 445.371 733 373.5C763.238 294.417 1045 367 1098 264C1120.32 220.628 1451 226 1480.5 5" stroke="%23989898" stroke-width="6" stroke-linecap="round"/></g><defs><filter id="filter0_d_3597_20179" x="0" y="-0.000488281" width="1521.5" height="659.449" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset dx="20" dy="16"/><feGaussianBlur stdDeviation="9"/><feComposite in2="hardAlpha" operator="out"/><feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.15 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_3597_20179"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_3597_20179" result="shape"/></filter></defs></svg>')
}

.referral-program-block .progress-steps .d-letter {
    top: -20px
}

.referral-program-block .progress-steps .d-letter .desc {
    left: 18%;
    position: absolute;
    top: 27%;
    width: 80%
}

.referral-program-block .progress-steps .d-letter .desc p {
    color: rgba(255,255,255,.65)
}

.referral-program-block .progress-steps .step {
    width: 30%;
    position: absolute;
    border-radius: 24px;
    background: rgba(41,41,41,.75);
    padding: 48px 32px 0;
    -webkit-box-shadow: 0 24px 34px 0 rgba(0,0,0,.05);
    box-shadow: 0 24px 34px 0 rgba(0,0,0,.05)
}

.referral-program-block .progress-steps .step .dot {
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="72" height="74" viewBox="0 0 72 74" fill="none"><g filter="url(%23filter0_d_3326_17737)"><circle cx="28" cy="18" r="18" fill="%23FF6319"/></g><circle cx="28" cy="18" r="6" fill="white"/><defs><filter id="filter0_d_3326_17737" x="0" y="0" width="72" height="74" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset dx="8" dy="20"/><feGaussianBlur stdDeviation="9"/><feComposite in2="hardAlpha" operator="out"/><feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.15 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_3326_17737"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_3326_17737" result="shape"/></filter></defs></svg>');
    display: inline-block;
    margin-bottom: -3px;
    margin-left: -9px;
    z-index: 3;
    margin-top: 32px;
    position: relative
}

.referral-program-block .progress-steps .step .title {
    font-size: 26px;
    font-style: normal;
    font-weight: 600;
    line-height: 31.2px;
    text-transform: capitalize;
    color: #ff6319;
    margin-bottom: 32px;
    position: relative;
    z-index: 3
}

.referral-program-block .progress-steps .description-2 {
    position: absolute;
    width: 60%;
    left: 0;
    bottom: 0
}

@media (max-width: 575.98px) {
    .referral-program-block {
        padding-top:70px
    }

    .referral-program-block .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        right: -25%;
        bottom: unset;
        top: 25%
    }

    .referral-program-block .progress-steps {
        margin-top: 44px;
        background: 0 0;
        background-size: unset;
        background-repeat: unset;
        background-position: top;
        height: unset;
        position: relative;
        overflow: unset
    }

    .referral-program-block .progress-steps:after {
        content: none
    }

    .referral-program-block .progress-steps .d-letter {
        display: none
    }

    .referral-program-block .progress-steps .step {
        width: 100%;
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        background: 0 0;
        -webkit-box-shadow: none;
        padding: 0 50.5px;
        box-shadow: none;
        border: 0
    }

    .referral-program-block .progress-steps .step .dot {
        position: absolute;
        left: -17px;
        top: -3px;
        margin-top: 0
    }

    .referral-program-block .progress-steps .step .title {
        font-size: 24px;
        line-height: 28px;
        margin-bottom: 12px
    }

    .referral-program-block .progress-steps .step:not(:nth-child(4)) {
        padding-bottom: 64px
    }

    .referral-program-block .progress-steps .step:not(:nth-child(4))::before {
        position: absolute;
        content: "";
        width: 4px;
        height: 100%;
        background: #989898;
        left: 0;
        -webkit-filter: drop-shadow(7px 8px 14px rgba(0,0,0,.15));
        filter: drop-shadow(7px 8px 14px rgba(0,0,0,.15))
    }

    .referral-program-block .progress-steps .description-2 {
        width: 100%;
        margin-top: 44px;
        position: relative
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .referral-program-block {
        padding-top:70px
    }

    .referral-program-block .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        right: -25%;
        bottom: unset;
        top: 25%
    }

    .referral-program-block .progress-steps {
        margin-top: 44px;
        background: 0 0;
        background-size: unset;
        background-repeat: unset;
        background-position: top;
        height: unset;
        position: relative;
        overflow: unset
    }

    .referral-program-block .progress-steps:after {
        content: none
    }

    .referral-program-block .progress-steps .d-letter {
        display: none
    }

    .referral-program-block .progress-steps .step {
        width: 100%;
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        background: 0 0;
        -webkit-box-shadow: none;
        padding: 0 50.5px;
        box-shadow: none;
        border: 0
    }

    .referral-program-block .progress-steps .step .dot {
        position: absolute;
        left: -17px;
        top: -3px;
        margin-top: 0
    }

    .referral-program-block .progress-steps .step .title {
        font-size: 24px;
        line-height: 28px;
        margin-bottom: 12px
    }

    .referral-program-block .progress-steps .step:not(:nth-child(4)) {
        padding-bottom: 64px
    }

    .referral-program-block .progress-steps .step:not(:nth-child(4))::before {
        position: absolute;
        content: "";
        width: 4px;
        height: 100%;
        background: #989898;
        left: 0;
        -webkit-filter: drop-shadow(7px 8px 14px rgba(0,0,0,.15));
        filter: drop-shadow(7px 8px 14px rgba(0,0,0,.15))
    }

    .referral-program-block .progress-steps .description-2 {
        width: 100%;
        margin-top: 44px;
        position: relative
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .referral-program-block {
        padding-top:70px
    }

    .referral-program-block .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        right: -25%;
        bottom: unset;
        top: 25%
    }

    .referral-program-block .progress-steps {
        margin-top: 44px;
        background: 0 0;
        background-size: unset;
        background-repeat: unset;
        background-position: top;
        height: unset;
        position: relative;
        overflow: unset
    }

    .referral-program-block .progress-steps:after {
        content: none
    }

    .referral-program-block .progress-steps .d-letter {
        display: none
    }

    .referral-program-block .progress-steps .step {
        width: 100%;
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        background: 0 0;
        -webkit-box-shadow: none;
        padding: 0 50.5px;
        box-shadow: none;
        border: 0
    }

    .referral-program-block .progress-steps .step .dot {
        position: absolute;
        left: -17px;
        top: -3px;
        margin-top: 0
    }

    .referral-program-block .progress-steps .step .title {
        font-size: 24px;
        line-height: 28px;
        margin-bottom: 12px
    }

    .referral-program-block .progress-steps .step:not(:nth-child(4)) {
        padding-bottom: 64px
    }

    .referral-program-block .progress-steps .step:not(:nth-child(4))::before {
        position: absolute;
        content: "";
        width: 4px;
        height: 100%;
        background: #989898;
        left: 0;
        -webkit-filter: drop-shadow(7px 8px 14px rgba(0,0,0,.15));
        filter: drop-shadow(7px 8px 14px rgba(0,0,0,.15))
    }

    .referral-program-block .progress-steps .description-2 {
        width: 100%;
        margin-top: 44px;
        position: relative
    }
}

@media (min-width: 992px) and (max-width:1199.98px) {
    .referral-program-block .progress-steps .d-letter {
        position:absolute;
        right: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        top: -20px;
        background: 0 0;
        width: 70%
    }

    .referral-program-block .progress-steps .step {
        padding: 32px 24px 0
    }

    .referral-program-block .progress-steps .step:nth-child(2) {
        top: 12.5%;
        left: 0
    }

    .referral-program-block .progress-steps .step:nth-child(3) {
        top: 33.3%;
        left: 34%
    }

    .referral-program-block .progress-steps .step:nth-child(4) {
        top: 47%;
        left: 70%
    }

    .referral-program-block .progress-steps .description-2 {
        bottom: 10%
    }
}

@media (min-width: 1200px) and (max-width:1399.98px) {
    .referral-program-block .progress-steps::after {
        top:32%
    }

    .referral-program-block .progress-steps .d-letter {
        width: 440px;
        height: 459.732px;
        position: absolute;
        right: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        top: -20px
    }

    .referral-program-block .progress-steps .step:nth-child(2) {
        top: 16.5%;
        left: 0
    }

    .referral-program-block .progress-steps .step:nth-child(3) {
        top: 39.3%;
        left: 34%
    }

    .referral-program-block .progress-steps .step:nth-child(4) {
        top: 56.5%;
        left: 70%
    }
}

@media (min-width: 1400px) {
    .referral-program-block .progress-steps .d-letter {
        width:440px;
        height: 459.732px;
        position: absolute;
        right: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        top: -20px
    }

    .referral-program-block .progress-steps .step:nth-child(2) {
        top: 16.5%;
        left: 0
    }

    .referral-program-block .progress-steps .step:nth-child(3) {
        top: 39.3%;
        left: 34%
    }

    .referral-program-block .progress-steps .step:nth-child(4) {
        top: 56.5%;
        left: 70%
    }
}

.red-banner {
    padding: 150px 50px 0;
    position: relative
}

.red-banner .shape-1 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    left: -25%;
    top: -25%;
    pointer-events: none
}

.red-banner .wrapper {
    border-radius: 20px;
    padding: 105px 0;
    background: -webkit-gradient(linear,left top,left bottom,from(#f62d07),to(#ff6319));
    background: linear-gradient(180deg,#f62d07 0,#ff6319 100%);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    text-align: center
}

.red-banner .wrapper .desc-block {
    margin-left: auto;
    margin-right: auto
}

.red-banner .wrapper .button-wrapper {
    margin-top: 75px;
    text-align: center
}

@media (max-width: 575.98px) {
    .red-banner {
        padding:70px 16px 0
    }

    .red-banner .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        left: -25%;
        bottom: unset;
        top: 25%
    }

    .red-banner .wrapper {
        padding: 80px 21px
    }

    .red-banner .wrapper .button-wrapper {
        margin-top: 40px
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .red-banner {
        padding:70px 16px 0
    }

    .red-banner .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        left: -25%;
        bottom: unset;
        top: 25%
    }

    .red-banner .wrapper {
        padding: 80px 21px
    }

    .red-banner .wrapper .button-wrapper {
        margin-top: 40px
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .red-banner {
        padding:70px 16px 0
    }

    .red-banner .shape-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        left: -25%;
        bottom: unset;
        top: 25%
    }

    .red-banner .wrapper {
        padding: 80px 21px
    }

    .red-banner .wrapper .button-wrapper {
        margin-top: 40px
    }
}

.road-map {
    padding-top: 150px
}

.road-map .desc-block {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 44px
}

.road-map .desc-block .pre-title {
    display: inline-block;
    padding: 10px 20px;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    color: #fff;
    border-radius: 100px;
    border: .5px solid var(--White, #FFF)
}

.road-map .desc-block .desc {
    margin-top: 20px
}

.road-map .items-block .item:first-child .box::after {
    content: "";
    position: absolute;
    left: -2px;
    width: 3px;
    height: 97px;
    top: -18px;
    background-color: #ff6319
}

.road-map .items-block .item .box {
    width: calc(50% + .5px);
    position: relative
}

.road-map .items-block .item .box:before {
    content: "";
    width: 17px;
    height: 17px;
    position: absolute;
    border-radius: 50%;
    background-color: #ff6319
}

.road-map .items-block .item .box.left-side {
    padding-right: 35px;
    margin-right: auto;
    text-align: end;
    border-right: 1px dashed rgba(255,255,255,.25)
}

.road-map .items-block .item .box.left-side:before {
    right: -8.5px;
    top: 12px
}

.road-map .items-block .item .box.right-side {
    padding-left: 36px;
    text-align: start;
    border-left: 1px dashed rgba(255,255,255,.25);
    margin-left: auto
}

.road-map .items-block .item .box.right-side:before {
    left: -8.5px;
    top: 12px
}

.road-map .items-block .item .box .number {
    display: inline-block;
    color: #ff6319;
    padding: 10px 20px;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    border-radius: 100px;
    border: .5px solid #ff6319;
    margin-bottom: 20px
}

.road-map .items-block .item .box .desc p {
    opacity: .65
}

@media (max-width: 575.98px) {
    .road-map {
        padding-top:70px
    }

    .road-map .desc-block {
        margin-bottom: 44px
    }

    .road-map .desc-block .pre-title {
        padding: 8px 10px;
        font-size: 12px;
        color: #fff
    }

    .road-map .desc-block .desc {
        margin-top: 14px
    }

    .road-map .items-block .item:not(:last-child) .box {
        padding-bottom: 50px
    }

    .road-map .items-block .item:last-child .box {
        padding-bottom: 25px
    }

    .road-map .items-block .item .box {
        width: 100%
    }

    .road-map .items-block .item .box.left-side,.road-map .items-block .item .box.right-side {
        padding-left: 20px;
        text-align: start;
        border-left: 1px dashed rgba(255,255,255,.25);
        border-right: none;
        margin-left: auto
    }

    .road-map .items-block .item .box.left-side:before,.road-map .items-block .item .box.right-side:before {
        left: -8.5px;
        top: 12px
    }

    .road-map .items-block .item .box .number {
        padding: 8px 10px;
        font-size: 12px
    }

    .road-map .items-block .item .box .desc p {
        opacity: .65
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .road-map {
        padding-top:70px
    }

    .road-map .desc-block {
        margin-bottom: 44px
    }

    .road-map .desc-block .pre-title {
        padding: 8px 10px;
        font-size: 12px;
        color: #fff
    }

    .road-map .desc-block .desc {
        margin-top: 14px
    }

    .road-map .items-block .item:not(:last-child) .box {
        padding-bottom: 50px
    }

    .road-map .items-block .item:last-child .box {
        padding-bottom: 25px
    }

    .road-map .items-block .item .box {
        width: 100%
    }

    .road-map .items-block .item .box.left-side,.road-map .items-block .item .box.right-side {
        padding-left: 20px;
        text-align: start;
        border-left: 1px dashed rgba(255,255,255,.25);
        border-right: none;
        margin-left: auto
    }

    .road-map .items-block .item .box.left-side:before,.road-map .items-block .item .box.right-side:before {
        left: -8.5px;
        top: 12px
    }

    .road-map .items-block .item .box .number {
        padding: 8px 10px;
        font-size: 12px
    }

    .road-map .items-block .item .box .desc p {
        opacity: .65
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .road-map {
        padding-top:70px
    }
}

.about-us {
    padding-top: 170px;
    position: relative
}

.about-us .shape-1,.about-us .shape-2 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    pointer-events: none
}

.about-us .shape-1 {
    left: -25%;
    bottom: -45%
}

.about-us .shape-2 {
    right: -25%;
    top: -25%
}

.about-us .pre-title {
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    display: inline-block;
    border-radius: 100px;
    border: .5px solid #fff;
    padding: 10px 20px;
    margin-bottom: 20px
}

.about-us .pre-title * {
    margin-bottom: 0
}

.about-us .image-wrapper {
    border-radius: 20px;
    background: #272727;
    height: 656px;
    position: relative;
    overflow: hidden
}

.about-us .image-wrapper img {
    width: 70%;
    left: 50%;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
    top: 15%;
    position: absolute
}

@media (max-width: 575.98px) {
    .about-us {
        padding-top:70px;
        position: relative
    }

    .about-us .shape-1,.about-us .shape-2 {
        border-radius: 347px;
        width: 347px;
        height: 314px
    }

    .about-us .shape-1 {
        right: unset;
        bottom: unset;
        top: 15%;
        left: -45%
    }

    .about-us .shape-2 {
        bottom: 0;
        right: -55%;
        top: unset;
        z-index: 4
    }

    .about-us .pre-title {
        font-size: 12px;
        padding: 8px 10px;
        margin-bottom: 14px
    }

    .about-us .image-wrapper {
        height: 308px;
        margin-top: 30px
    }

    .about-us .image-wrapper img {
        width: 70%;
        top: 8%
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .about-us {
        padding-top:70px;
        position: relative
    }

    .about-us .shape-1,.about-us .shape-2 {
        border-radius: 347px;
        width: 347px;
        height: 314px
    }

    .about-us .shape-1 {
        right: unset;
        bottom: unset;
        top: 0;
        left: 0
    }

    .about-us .shape-2 {
        bottom: 0;
        right: -55%;
        top: unset;
        z-index: 4
    }

    .about-us .pre-title {
        font-size: 12px;
        padding: 8px 10px;
        margin-bottom: 14px
    }

    .about-us .image-wrapper {
        height: 308px;
        margin-top: 30px
    }

    .about-us .image-wrapper img {
        width: 40%;
        top: 8%
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .about-us {
        padding-top:70px;
        position: relative
    }

    .about-us .shape-1,.about-us .shape-2 {
        border-radius: 347px;
        width: 347px;
        height: 314px
    }

    .about-us .shape-1 {
        right: unset;
        bottom: unset;
        top: 0;
        left: 0
    }

    .about-us .shape-2 {
        bottom: 0;
        right: -55%;
        top: unset;
        z-index: 4
    }

    .about-us .pre-title {
        font-size: 12px;
        padding: 8px 10px;
        margin-bottom: 14px
    }

    .about-us .image-wrapper {
        height: 406px;
        margin-top: 30px
    }

    .about-us .image-wrapper img {
        width: 40%;
        top: 8%
    }
}

.download-block {
    padding-top: 124px;
    position: relative
}

.download-block .shape-flash-1 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    right: -25%;
    bottom: -50%;
    pointer-events: none
}

.download-block .desc-block {
    margin-left: auto;
    margin-right: auto
}

.download-block .desc-block .desc {
    margin-bottom: 40px
}

.download-block .items-block {
    height: 400px;
    position: relative
}

.download-block .items-block .shape-1,.download-block .items-block .shape-2,.download-block .items-block .shape-3 {
    content: url('data:image/svg+xml,<svg width="956" height="475" viewBox="0 0 956 475" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="478.05" cy="-2.47998" r="475.819" transform="rotate(180 478.05 -2.47998)" stroke="url(%23paint0_linear_73_545)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_545" x1="478.05" y1="-479.548" x2="478.05" y2="474.588" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.375" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
    position: absolute;
    left: 50%;
    bottom: 0;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
    pointer-events: none
}

.download-block .items-block .shape-2,.download-block .items-block .shape-3 {
    content: url('data:image/svg+xml,<svg width="716" height="336" viewBox="0 0 716 336" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="358.05" cy="-22.4796" r="356.552" transform="rotate(180 358.05 -22.4796)" stroke="url(%23paint0_linear_73_544)" stroke-opacity="0.25" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_544" x1="358.05" y1="-380.281" x2="358.05" y2="335.321" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.525" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
    bottom: 142.68px
}

.download-block .items-block .shape-3 {
    content: url('data:image/svg+xml,<svg width="478" height="217" viewBox="0 0 478 217" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="239.05" cy="-22.4802" r="237.285" transform="rotate(180 239.05 -22.4802)" stroke="url(%23paint0_linear_73_546)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_546" x1="239.05" y1="-261.014" x2="239.05" y2="216.054" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.46" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
    bottom: 261.95px
}

.download-block .items-block .desc-block {
    font-size: 14px;
    color: rgba(255,255,255,.65);
    text-align: center;
    font-style: normal;
    font-weight: 400;
    width: 289px;
    padding: 12px 23px;
    line-height: 130%;
    border-radius: 10px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    position: absolute;
    z-index: 3
}

.download-block .items-block .desc-block :last-child,.faqs .faqs-wrapper .accordion .faq .card :last-child {
    margin-bottom: 0
}

@media (max-width: 575.98px) {
    .download-block {
        padding-top:70px
    }

    .download-block .shape-flash-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        bottom: -25%;
        right: -35%;
        top: unset;
        z-index: 4
    }

    .download-block .desc-block .desc {
        margin-bottom: 30px
    }

    .download-block .items-block {
        height: 320px;
        position: relative
    }

    .download-block .items-block .shape-1,.download-block .items-block .shape-2,.download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="340" height="297" viewBox="0 0 340 297" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M-128.374 -1.55264C-128.374 -165.675 4.58368 -298.721 168.594 -298.721C332.604 -298.721 465.561 -165.675 465.561 -1.55264C465.561 162.57 332.604 295.615 168.594 295.615C4.58368 295.615 -128.374 162.57 -128.374 -1.55264Z" stroke="url(%23paint0_linear_94_1624)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_94_1624" x1="168.594" y1="296.864" x2="168.594" y2="-299.97" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.375" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        position: absolute;
        left: 50%;
        bottom: 0;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%)
    }

    .download-block .items-block .shape-2,.download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="340" height="210" viewBox="0 0 340 210" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M-53.82 -14.0632C-53.82 -136.983 45.7587 -236.627 168.594 -236.627C291.428 -236.627 391.007 -136.983 391.007 -14.0632C391.007 108.856 291.428 208.501 168.594 208.501C45.7587 208.501 -53.82 108.856 -53.82 -14.0632Z" stroke="url(%23paint0_linear_94_1623)" stroke-opacity="0.25" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_94_1623" x1="168.594" y1="209.75" x2="168.594" y2="-237.876" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.525" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        bottom: 89.25px
    }

    .download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="299" height="136" viewBox="0 0 299 136" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.73462 -14.062C1.73462 -95.7786 67.9343 -162.022 149.594 -162.022C231.254 -162.022 297.454 -95.7786 297.454 -14.062C297.454 67.6546 231.254 133.898 149.594 133.898C67.9343 133.898 1.73462 67.6546 1.73462 -14.062Z" stroke="url(%23paint0_linear_94_1625)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_94_1625" x1="149.594" y1="135.146" x2="149.594" y2="-163.27" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.46" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        bottom: 163.85px
    }

    .download-block .items-block .desc-block {
        font-size: 12px;
        width: 213px;
        padding: 10px
    }

    .download-block .items-block .desc-block.desc-block-1 {
        top: 30px;
        left: 0
    }

    .download-block .items-block .desc-block.desc-block-2 {
        left: 0;
        bottom: 0
    }

    .download-block .items-block .desc-block.desc-block-3 {
        right: 0;
        bottom: 104px
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .download-block {
        padding-top:70px
    }

    .download-block .shape-flash-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        bottom: 0;
        right: -15%;
        top: unset;
        z-index: 4
    }

    .download-block .desc-block .desc {
        margin-bottom: 30px
    }

    .download-block .items-block {
        height: 320px;
        position: relative
    }

    .download-block .items-block .shape-1,.download-block .items-block .shape-2,.download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="956" height="475" viewBox="0 0 956 475" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="478.05" cy="-2.47998" r="475.819" transform="rotate(180 478.05 -2.47998)" stroke="url(%23paint0_linear_73_545)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_545" x1="478.05" y1="-479.548" x2="478.05" y2="474.588" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.375" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        position: absolute;
        left: 50%;
        bottom: 0;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%)
    }

    .download-block .items-block .shape-2,.download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="716" height="336" viewBox="0 0 716 336" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="358.05" cy="-22.4796" r="356.552" transform="rotate(180 358.05 -22.4796)" stroke="url(%23paint0_linear_73_544)" stroke-opacity="0.25" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_544" x1="358.05" y1="-380.281" x2="358.05" y2="335.321" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.525" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        bottom: 142.68px
    }

    .download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="478" height="217" viewBox="0 0 478 217" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="239.05" cy="-22.4802" r="237.285" transform="rotate(180 239.05 -22.4802)" stroke="url(%23paint0_linear_73_546)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_546" x1="239.05" y1="-261.014" x2="239.05" y2="216.054" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.46" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        bottom: 261.95px
    }

    .download-block .items-block .desc-block {
        font-size: 12px;
        width: 213px;
        padding: 10px
    }

    .download-block .items-block .desc-block.desc-block-1 {
        top: 63px;
        left: 0
    }

    .download-block .items-block .desc-block.desc-block-2 {
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        bottom: 0
    }

    .download-block .items-block .desc-block.desc-block-3 {
        right: 0;
        bottom: 127px
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .download-block {
        padding-top:70px
    }

    .download-block .shape-flash-1 {
        border-radius: 347px;
        width: 347px;
        height: 314px;
        bottom: 0;
        right: -25%;
        top: unset;
        z-index: 4
    }

    .download-block .desc-block .desc {
        margin-bottom: 30px
    }

    .download-block .items-block {
        height: 320px;
        position: relative
    }

    .download-block .items-block .shape-1,.download-block .items-block .shape-2,.download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="956" height="475" viewBox="0 0 956 475" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="478.05" cy="-2.47998" r="475.819" transform="rotate(180 478.05 -2.47998)" stroke="url(%23paint0_linear_73_545)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_545" x1="478.05" y1="-479.548" x2="478.05" y2="474.588" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.375" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        position: absolute;
        left: 50%;
        bottom: 0;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%)
    }

    .download-block .items-block .shape-2,.download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="716" height="336" viewBox="0 0 716 336" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="358.05" cy="-22.4796" r="356.552" transform="rotate(180 358.05 -22.4796)" stroke="url(%23paint0_linear_73_544)" stroke-opacity="0.25" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_544" x1="358.05" y1="-380.281" x2="358.05" y2="335.321" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.525" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        bottom: 142.68px
    }

    .download-block .items-block .shape-3 {
        content: url('data:image/svg+xml,<svg width="478" height="217" viewBox="0 0 478 217" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="239.05" cy="-22.4802" r="237.285" transform="rotate(180 239.05 -22.4802)" stroke="url(%23paint0_linear_73_546)" stroke-width="2.49774"/><defs><linearGradient id="paint0_linear_73_546" x1="239.05" y1="-261.014" x2="239.05" y2="216.054" gradientUnits="userSpaceOnUse"><stop stop-color="%23FF6319"/><stop offset="0.46" stop-color="%23272727" stop-opacity="0"/></linearGradient></defs></svg>');
        bottom: 261.95px
    }

    .download-block .items-block .desc-block {
        font-size: 12px;
        width: 213px;
        padding: 10px
    }

    .download-block .items-block .desc-block.desc-block-1 {
        top: 63px;
        left: 0
    }

    .download-block .items-block .desc-block.desc-block-2 {
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        bottom: 0
    }

    .download-block .items-block .desc-block.desc-block-3 {
        right: 0;
        bottom: 127px
    }
}

@media (min-width: 992px) and (max-width:1199.98px) {
    .download-block .items-block .desc-block.desc-block-1 {
        top:63px;
        left: 0
    }

    .download-block .items-block .desc-block.desc-block-2 {
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        bottom: 0
    }

    .download-block .items-block .desc-block.desc-block-3 {
        right: 0;
        bottom: 127px
    }
}

@media (min-width: 1200px) and (max-width:1399.98px) {
    .download-block .items-block .desc-block.desc-block-1 {
        top:63px;
        left: 119px
    }

    .download-block .items-block .desc-block.desc-block-2 {
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        bottom: 0
    }

    .download-block .items-block .desc-block.desc-block-3 {
        right: 131px;
        bottom: 127px
    }
}

@media (min-width: 1400px) {
    .download-block .items-block .desc-block.desc-block-1 {
        top:63px;
        left: 119px
    }

    .download-block .items-block .desc-block.desc-block-2 {
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        bottom: 0
    }

    .download-block .items-block .desc-block.desc-block-3 {
        right: 131px;
        bottom: 127px
    }
}

.faqs {
    padding-top: 150px;
    position: relative
}

.faqs .shape-1 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    left: -25%;
    top: 25%;
    pointer-events: none
}

.faqs .pre-title {
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%;
    display: inline-block;
    border-radius: 100px;
    border: .5px solid #fff;
    padding: 10px 20px;
    margin-bottom: 20px
}

.benefits-block .items .item-block.item-block-4.half-block .item .desc,.faqs .pre-title * {
    margin-bottom: 0
}

.faqs .faqs-wrapper {
    margin-top: 37px;
    margin-left: auto;
    margin-right: auto
}

.faqs .faqs-wrapper .accordion {
    background-color: unset
}

.faqs .faqs-wrapper .accordion .count {
    font-size: 35px;
    font-style: normal;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -.35px;
    color: #fff;
    opacity: .7;
    margin-right: 42px
}

.faqs .faqs-wrapper .accordion .h3,.faqs .faqs-wrapper .accordion h3 {
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    position: relative;
    width: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    text-transform: capitalize;
    color: #fff;
    margin-bottom: 0
}

.faqs .faqs-wrapper .accordion .collapsed.h3::after,.faqs .faqs-wrapper .accordion h3.collapsed::after {
    content: url('data:image/svg+xml,<svg width="61" height="30" viewBox="0 0 61 30" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="61" height="30" rx="15" fill="%23FF6319"/><path d="M37.47 14.12V16.675H31.975V21.995H29.42V16.675H23.925V14.12H29.42V8.8H31.975V14.12H37.47Z" fill="white"/></svg>')
}

.faqs .faqs-wrapper .accordion .h3:after,.faqs .faqs-wrapper .accordion h3:after {
    display: inline;
    content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="61" height="30" viewBox="0 0 61 30" fill="none"><rect width="61" height="30" rx="15" fill="%23FF6319"/><path d="M36.3897 19.2831L34.583 21.0897L30.6975 17.2042L26.9357 20.966L25.129 19.1593L28.8908 15.3975L25.0053 11.5119L26.8119 9.70529L30.6975 13.5908L34.4593 9.82903L36.2659 11.6357L32.5041 15.3975L36.3897 19.2831Z" fill="white"/></svg>')
}

.faqs .faqs-wrapper .accordion .faq {
    padding: 14px 0;
    background-color: unset;
    border-top: none;
    border-bottom: .556px solid #d8dad8;
    border-left: none;
    border-right: none;
    border-radius: 0
}

.faqs .faqs-wrapper .accordion .faq .card {
    background: unset;
    border: 0;
    padding: 14px 0 0;
    color: rgba(255,255,255,.65)
}

.faqs .faqs-wrapper .accordion .faq .card :not(li) {
    margin-bottom: 1.5rem
}

.faqs .faqs-wrapper .accordion .faq .card ul {
    padding: 0
}

.faqs .faqs-wrapper .accordion .faq:hover .h3,.faqs .faqs-wrapper .accordion .faq:hover h3 {
    color: #ff6319;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

@media (max-width: 575.98px) {
    .faqs .shape-1 {
        border-radius:347px;
        width: 347px;
        height: 314px;
        left: -25%;
        bottom: unset;
        top: 25%
    }

    .faqs .faqs-wrapper {
        margin-top: 42px
    }

    .faqs .faqs-wrapper .accordion .count {
        font-size: 20px;
        letter-spacing: -.2px;
        margin-right: 10px
    }

    .faqs .faqs-wrapper .accordion .faq {
        padding: 12px 0
    }

    .faqs .faqs-wrapper .accordion .faq .card {
        padding: 8px 0 0
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .faqs .shape-1 {
        border-radius:347px;
        width: 347px;
        height: 314px;
        left: -25%;
        bottom: unset;
        top: 25%
    }

    .faqs .faqs-wrapper {
        margin-top: 42px
    }

    .faqs .faqs-wrapper .accordion .count {
        font-size: 20px;
        letter-spacing: -.2px;
        margin-right: 10px
    }

    .faqs .faqs-wrapper .accordion .faq {
        padding: 12px 0
    }

    .faqs .faqs-wrapper .accordion .faq .card {
        padding: 8px 0 0
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .faqs .shape-1 {
        border-radius:347px;
        width: 347px;
        height: 314px;
        left: -25%;
        bottom: unset;
        top: 25%
    }

    .faqs .faqs-wrapper {
        margin-top: 42px
    }

    .faqs .faqs-wrapper .accordion .count {
        font-size: 20px;
        letter-spacing: -.2px;
        margin-right: 10px
    }

    .faqs .faqs-wrapper .accordion .faq {
        padding: 12px 0
    }

    .faqs .faqs-wrapper .accordion .faq .card {
        padding: 8px 0 0
    }
}

.benefits-block {
    padding-top: 120px;
    position: relative
}

.benefits-block .shape-1 {
    position: absolute;
    border-radius: 909px;
    background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
    width: 909px;
    height: 824px;
    right: -25%;
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    pointer-events: none
}

.benefits-block .items {
    margin-top: 40px
}

.benefits-block .items .item-block,.benefits-block .items .item-block .item {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column
}

.benefits-block .items .item-block {
    margin-bottom: 24px
}

.benefits-block .items .item-block.item-block-4.half-block .item {
    padding: 50px 50px 0;
    position: relative
}

.benefits-block .items .item-block.item-block-4.half-block .item::after {
    content: "";
    border-radius: 0 0 20px 20px;
    background: -webkit-gradient(linear,left top,left bottom,from(rgba(39,39,39,0)),color-stop(98.5%,#272727));
    background: linear-gradient(180deg,rgba(39,39,39,0) 0,#272727 98.5%);
    width: 100%;
    height: 40%;
    position: absolute;
    bottom: 0;
    left: 0
}

.benefits-block .items .item-block .item {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    height: 100%
}

.benefits-block .items .item-block .item .desc p {
    opacity: .65
}

.benefits-block .items .item-block.half-block .item {
    padding: 50px
}

.benefits-block .items .item-block.half-block .item .desc {
    margin-bottom: 50px
}

.benefits-block .items .item-block.half-block .item .image-wrapper {
    margin-top: auto
}

.benefits-block .items .item-block.half-block .item .image-wrapper img {
    width: 100%;
    height: auto;
    -o-object-fit: contain;
    object-fit: contain;
    -o-object-position: center;
    object-position: center
}

.benefits-block .items .item-block.full-block {
    position: relative;
    overflow: hidden
}

.benefits-block .items .item-block.full-block .item .desc {
    padding: 0 50px
}

.benefits-block .items .item-block.full-block .item .image-wrapper {
    position: relative;
    height: 544px
}

.benefits-block .items .item-block.full-block .item .image-wrapper img {
    position: absolute;
    height: 544px;
    width: 100%;
    -o-object-fit: contain;
    object-fit: contain;
    -o-object-position: top;
    object-position: top
}

@media (max-width: 575.98px) {
    .benefits-block {
        padding-top:70px
    }

    .benefits-block .shape-1 {
        position: absolute;
        background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
        border-radius: 347px;
        width: 347px;
        height: 314px;
        right: -25%;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        pointer-events: none
    }

    .benefits-block .items {
        margin-top: 30px
    }

    .benefits-block .items .item-block.half-block .item,.benefits-block .items .item-block.item-block-4.half-block .item {
        padding: 16px
    }

    .benefits-block .items .item-block.item-block-4.half-block .item .desc {
        margin-bottom: 0
    }

    .benefits-block .items .item-block.item-block-4.half-block .item::after {
        height: 30%
    }

    .benefits-block .items .item-block .item {
        border-radius: 18px
    }

    .benefits-block .items .item-block.half-block .item .desc {
        margin-bottom: 16px
    }

    .benefits-block .items .item-block.full-block .item .desc {
        padding: 16px
    }

    .benefits-block .items .item-block.full-block .item .image-wrapper,.benefits-block .items .item-block.full-block .item .image-wrapper img {
        height: 272px
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .benefits-block {
        padding-top:70px
    }

    .benefits-block .shape-1 {
        position: absolute;
        background: radial-gradient(50% 50% at 50% 50%,rgba(255,99,25,.25) 0,rgba(255,99,25,0) 100%);
        border-radius: 347px;
        width: 347px;
        height: 314px;
        right: -25%;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        pointer-events: none
    }

    .benefits-block .items {
        margin-top: 30px
    }

    .benefits-block .items .item-block.half-block .item,.benefits-block .items .item-block.item-block-4.half-block .item {
        padding: 16px
    }

    .benefits-block .items .item-block.item-block-4.half-block .item .desc {
        margin-bottom: 0
    }

    .benefits-block .items .item-block.item-block-4.half-block .item::after {
        height: 30%
    }

    .benefits-block .items .item-block .item {
        border-radius: 18px
    }

    .benefits-block .items .item-block.half-block .item .desc {
        margin-bottom: 16px
    }

    .benefits-block .items .item-block.full-block .item .desc {
        padding: 16px
    }

    .benefits-block .items .item-block.full-block .item .image-wrapper,.benefits-block .items .item-block.full-block .item .image-wrapper img {
        height: 272px
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .benefits-block {
        padding-top:70px
    }

    .benefits-block .items {
        margin-top: 30px
    }

    .benefits-block .items .item-block.half-block .item,.benefits-block .items .item-block.item-block-4.half-block .item {
        padding: 16px
    }

    .benefits-block .items .item-block.item-block-4.half-block .item .desc {
        margin-bottom: 0
    }

    .benefits-block .items .item-block.item-block-4.half-block .item::after {
        height: 30%
    }

    .benefits-block .items .item-block .item {
        border-radius: 18px
    }

    .benefits-block .items .item-block.half-block .item .desc {
        margin-bottom: 16px
    }

    .benefits-block .items .item-block.full-block .item .desc {
        padding: 16px
    }

    .benefits-block .items .item-block.full-block .item .image-wrapper,.benefits-block .items .item-block.full-block .item .image-wrapper img {
        height: 372px
    }
}

.dubadu-large-modal .modal-dialog .modal-content,.withdraw-modal .modal-dialog .modal-content {
    border: 1px solid #d8d8d8;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    padding: 80px
}

.dubadu-large-modal .modal-dialog .modal-content .btn-close,.withdraw-modal .modal-dialog .modal-content .btn-close {
    position: absolute;
    left: 24px;
    top: 24px;
    border-radius: 0 1000px 1000px 0;
    border: 1px solid #d8d8d8;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 36px;
    height: 38px;
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="%23f8f8f8"><path d="M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z"/></svg>') center/1em auto no-repeat
}

.dubadu-large-modal .modal-dialog .modal-content .btn-close:hover,.withdraw-modal .modal-dialog .modal-content .btn-close:hover {
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    -webkit-transform: scale(1.1);
    transform: scale(1.1)
}

.withdraw-modal .invest-price {
    font-size: 24px;
    font-style: normal;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -.35px;
    position: relative;
    display: inline-block
}

.withdraw-modal .invest-price::after,div.woocommerce .my-account .white-shadow-box .invest-price::after {
    content: "";
    height: 2px;
    width: 100%;
    left: 0;
    position: absolute;
    background-color: #ff6319;
    bottom: -7px
}

.withdraw-modal .modal-dialog .modal-content {
    padding: 32px
}

.auth-pages form,.dubadu-modal .modal-dialog .modal-content form,.lost_reset_password form,.withdraw-modal .modal-dialog .modal-content form {
    margin-top: 32px
}

.withdraw-modal .modal-dialog .modal-content form .country-select,.withdraw-modal .modal-dialog .modal-content form .iti,div.woocommerce .my-account form .country-select,div.woocommerce .my-account form .iti {
    display: block
}

.auth-pages form label.error,.lost_reset_password form label.error,.withdraw-modal .modal-dialog .modal-content form label.error,div.woocommerce .my-account form label.error {
    color: #dc3232
}

.withdraw-modal .modal-dialog .modal-content form .form-group {
    margin-bottom: 8px
}

.dubadu-modal .modal-dialog .modal-content form p:last-of-type,.withdraw-modal .modal-dialog .modal-content form .form-group:last-of-type {
    margin-bottom: 0
}

.auth-pages form label,.dubadu-modal .modal-dialog .modal-content form label,.lost_reset_password form label,.withdraw-modal .modal-dialog .modal-content form label,div.woocommerce .my-account .white-shadow-box label,div.woocommerce .my-account form label,div.woocommerce form.woocommerce-ResetPassword label {
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 28px;
    color: #f8f8f8;
    margin-bottom: 12px
}

.withdraw-modal .modal-dialog .modal-content form input {
    border-radius: 8px 0 0 8px;
    border: 1px solid var(--Disabled, #989898);
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    color: #fff;
    padding: 8px 10px;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    width: 100%;
    line-height: 28px
}

.withdraw-modal .modal-dialog .modal-content form input::-webkit-input-placeholder {
    color: #989898
}

.withdraw-modal .modal-dialog .modal-content form input::-moz-placeholder {
    color: #989898
}

.withdraw-modal .modal-dialog .modal-content form input:-ms-input-placeholder {
    color: #989898
}

.withdraw-modal .modal-dialog .modal-content form input::-ms-input-placeholder {
    color: #989898
}

.withdraw-modal .modal-dialog .modal-content form input::placeholder {
    color: #989898
}

.withdraw-modal .modal-dialog .modal-content form input:focus-visible {
    outline: 0;
    border: 1px solid #ff6319
}

.withdraw-modal .modal-dialog .modal-content form input:focus {
    background-color: unset!important;
    outline: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    color: #fff;
    border: 1px solid #ff6319!important
}

.dubadu-modal .modal-dialog {
    max-width: 686px;
    width: 686px
}

.dubadu-modal .modal-dialog .modal-content {
    padding: 80px 140px 120px 40px;
    position: relative;
    border-radius: 0 1000px 1000px 0;
    border: 1px solid #d8d8d8;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px)
}

.dubadu-modal .modal-dialog .modal-content .login-buttons {
    gap: 32px;
    margin-top: 64px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center
}

.dubadu-modal .modal-dialog .modal-content .modal-body {
    padding: 0!important
}

.dubadu-modal .modal-dialog .modal-content .btn-close,.dubadu-modal .modal-dialog .modal-content .checkmark {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center
}

.dubadu-modal .modal-dialog .modal-content .btn-close {
    position: absolute;
    left: 24px;
    top: 24px;
    border-radius: 0 1000px 1000px 0;
    border: 1px solid #d8d8d8;
    width: 36px;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="%23f8f8f8"><path d="M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z"/></svg>') center/1em auto no-repeat;
    height: 38px
}

.dubadu-modal .modal-dialog .modal-content .checkmark {
    margin: 64px auto;
    width: 96px;
    height: 100px;
    border-radius: 0 100px 100px 0;
    border: 2px solid #88ef8c;
    background: #b3ffb6
}

.dubadu-modal .modal-dialog .modal-content form .wpcf7-spinner,div.woocommerce button[name=update_cart] {
    display: none
}

.dubadu-modal .modal-dialog .modal-content form p {
    margin-bottom: 32px
}

.dubadu-modal .modal-dialog .modal-content form .intl-tel-input {
    width: 100%;
    border-radius: 8px 0 0 8px
}

.dubadu-modal .modal-dialog .modal-content form .intl-tel-input input {
    padding-left: 45px!important
}

.dubadu-modal .modal-dialog .modal-content form input,.referral-program input,div.woocommerce .my-account form input,div.woocommerce form.woocommerce-ResetPassword input {
    border-radius: 8px 0 0 8px;
    border: 1px solid var(--Disabled, #989898);
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    color: #fff;
    padding: 16px 20px;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    width: 100%;
    line-height: 28px
}

.dubadu-modal .modal-dialog .modal-content form input::-webkit-input-placeholder,div.woocommerce form.woocommerce-ResetPassword input::-webkit-input-placeholder,div.woocommerce table.woocommerce-cart-form__contents .quantity input::-webkit-input-placeholder {
    color: #989898
}

.dubadu-modal .modal-dialog .modal-content form input::-moz-placeholder,div.woocommerce form.woocommerce-ResetPassword input::-moz-placeholder,div.woocommerce table.woocommerce-cart-form__contents .quantity input::-moz-placeholder {
    color: #989898
}

.dubadu-modal .modal-dialog .modal-content form input:-ms-input-placeholder {
    color: #989898
}

.dubadu-modal .modal-dialog .modal-content form input::-ms-input-placeholder,div.woocommerce form.woocommerce-ResetPassword input::-ms-input-placeholder,div.woocommerce table.woocommerce-cart-form__contents .quantity input::-ms-input-placeholder {
    color: #989898
}

.dubadu-modal .modal-dialog .modal-content form input::placeholder,div.woocommerce form.woocommerce-ResetPassword input::placeholder,div.woocommerce table.woocommerce-cart-form__contents .quantity input::placeholder {
    color: #989898
}

.dubadu-modal .modal-dialog .modal-content form input:focus-visible {
    outline: 0;
    border: 1px solid #ff6319
}

.dubadu-modal .modal-dialog .modal-content form input:focus {
    background-color: unset!important;
    outline: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    color: #fff;
    border: 1px solid #ff6319!important
}

.dubadu-modal .modal-dialog .modal-content form input[type=submit] {
    border-radius: 0 100px 100px 0;
    padding: 21.5px 32px;
    border: 0;
    width: 100%;
    margin-top: 32px;
    background-color: #ff6319;
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    text-transform: capitalize;
    color: #fff
}

.dubadu-modal .modal-dialog .modal-content form input[type=submit]:hover {
    border-radius: 0 100px 100px 0;
    background: #e55917;
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    -webkit-box-shadow: 2px 3px 6px 0 rgba(255,99,25,.2),-5px -4px 10px 0 #d14300 inset;
    box-shadow: 2px 3px 6px 0 rgba(255,99,25,.2),-5px -4px 10px 0 #d14300 inset
}

@media (max-width: 575.98px) {
    .dubadu-large-modal .modal-dialog .modal-content {
        padding:70px 10px 20px
    }

    .dubadu-large-modal .modal-dialog .modal-content .btn-close,.dubadu-modal .modal-dialog .modal-content .btn-close {
        position: absolute;
        left: unset;
        right: 17px;
        top: 24px;
        border-radius: 0 25px 25px 0;
        border: 1px solid #c7c7c7;
        width: 36px;
        height: 38px
    }

    .dubadu-modal .modal-dialog {
        max-width: unset;
        width: unset
    }

    .dubadu-modal .modal-dialog .modal-content {
        border-radius: 25px;
        padding: 84px 19px 64px;
        position: relative
    }

    .dubadu-modal .modal-dialog .modal-content .checkmark {
        margin: 64px auto;
        width: 96px;
        height: 100px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        border-radius: 0 100px 100px 0;
        border: 2px solid #88ef8c;
        background: #b3ffb6
    }
}

@media (min-width: 576px) and (max-width:767.98px) {
    .dubadu-large-modal .modal-dialog .modal-content {
        padding:70px 10px 20px
    }

    .dubadu-large-modal .modal-dialog .modal-content .btn-close,.dubadu-modal .modal-dialog .modal-content .btn-close {
        position: absolute;
        left: unset;
        right: 17px;
        top: 24px;
        border-radius: 0 25px 25px 0;
        border: 1px solid #c7c7c7;
        width: 36px;
        height: 38px
    }

    .dubadu-modal .modal-dialog {
        max-width: unset;
        width: unset
    }

    .dubadu-modal .modal-dialog .modal-content {
        border-radius: 25px;
        padding: 84px 19px 64px;
        position: relative
    }

    .dubadu-modal .modal-dialog .modal-content .checkmark {
        margin: 64px auto;
        width: 96px;
        height: 100px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        border-radius: 0 100px 100px 0;
        border: 2px solid #88ef8c;
        background: #b3ffb6
    }
}

@media (min-width: 768px) and (max-width:991.98px) {
    .dubadu-large-modal .modal-dialog .modal-content {
        padding:70px 10px 20px
    }

    .dubadu-large-modal .modal-dialog .modal-content .btn-close {
        position: absolute;
        left: unset;
        right: 17px;
        top: 24px;
        border-radius: 0 25px 25px 0;
        border: 1px solid #c7c7c7;
        width: 36px;
        height: 38px
    }
}

div.woocommerce {
    margin-top: 50px
}

div.woocommerce .cart-collaterals,div.woocommerce .woocommerce-checkout-review-order-table,div.woocommerce table.woocommerce-cart-form__contents,div.woocommerce table.woocommerce-cart-form__contents .quantity input {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    padding: 24px
}

div.woocommerce table.woocommerce-cart-form__contents a {
    color: #fff
}

div.woocommerce table.woocommerce-cart-form__contents .quantity input {
    border-radius: 8px 0 0 8px;
    border: 1px solid var(--Disabled, #989898);
    color: #fff;
    padding: 6px 10px;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    width: 100px;
    line-height: 28px
}

div.woocommerce form.woocommerce-ResetPassword input:disabled,div.woocommerce table.woocommerce-cart-form__contents .quantity input:disabled {
    opacity: .5
}

div.woocommerce form.woocommerce-ResetPassword input:-ms-input-placeholder,div.woocommerce table.woocommerce-cart-form__contents .quantity input:-ms-input-placeholder {
    color: #989898
}

div.woocommerce table.woocommerce-cart-form__contents .quantity input:focus-visible {
    background-color: none;
    outline: 0;
    border: 1px solid #ff6319
}

div.woocommerce table.woocommerce-cart-form__contents .quantity input:focus {
    background-color: none;
    outline: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    color: #fff;
    border: 1px solid #ff6319
}

div.woocommerce #payment #place_order {
    float: unset
}

div.woocommerce .your-products .product {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    -webkit-box-shadow: 2px 3px 24px 0 rgba(2,7,14,.04);
    box-shadow: 2px 3px 24px 0 rgba(2,7,14,.04);
    padding: 24px
}

div.woocommerce .your-products .product .product-image {
    width: 150px;
    height: 150px;
    border-radius: 0 100px 100px 0;
    overflow: hidden;
    margin-bottom: 24px;
    margin-left: auto;
    margin-right: auto
}

div.woocommerce .your-products .product .product-image img {
    width: 100%;
    height: 100%;
    -o-object-position: center;
    object-position: center;
    -o-object-fit: cover;
    object-fit: cover
}

div.woocommerce .your-products .product .price,div.woocommerce ul.products li.product a .price {
    display: block;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    color: #ff6319
}

div.woocommerce .your-products .product:not(:last-child),div.woocommerce ul.products li.product:not(:last-child) {
    margin-bottom: 32px
}

div.woocommerce form.woocommerce-ResetPassword input:focus-visible {
    outline: 0;
    border: 1px solid #ff6319
}

div.woocommerce ul.products li.product {
    border-radius: 24px;
    border: 1px solid #e8e8e8;
    background: var(--White, #FFF);
    -webkit-box-shadow: 2px 3px 24px 0 rgba(2,7,14,.04);
    box-shadow: 2px 3px 24px 0 rgba(2,7,14,.04);
    padding: 24px;
    text-align: center
}

div.woocommerce ul.products li.product a img {
    width: 150px;
    height: 150px;
    -o-object-fit: cover;
    object-fit: cover;
    margin: auto;
    -o-object-position: center;
    object-position: center
}

div.woocommerce ul.products li.product .add_to_cart_button {
    font-size: 18px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
    color: #fff;
    padding: 16px 32px;
    border-radius: 0 30px 30px 0;
    border: 1px solid #ff6319;
    text-decoration: none;
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    display: inline-block;
    background: #ff6319
}

div.woocommerce ul.products li.product .add_to_cart_button:hover {
    color: #fff;
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    background-color: #f8f8f8;
    border: 1px solid #fff
}

div.woocommerce ul.products li.product .added_to_cart {
    color: #ff6319;
    display: block;
    width: 100%
}

div.woocommerce .woocommerce-checkout-payment#payment {
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    padding: 24px
}

div.woocommerce .woocommerce-checkout-payment#payment .wc_payment_methods {
    border-bottom: none
}

div.woocommerce .woocommerce-checkout-payment#payment div.payment_box {
    background-color: unset;
    font-weight: 500;
    border: 2px solid #ff6319;
    -webkit-box-shadow: 0 8px 12px 0 rgba(255,99,25,.06);
    box-shadow: 0 8px 12px 0 rgba(255,99,25,.06);
    border-radius: 20px;
    color: #f8f8f8
}

div.woocommerce .woocommerce-checkout-payment#payment div.payment_box::before {
    top: -13px;
    border: 1em solid #ff6319;
    border-right-color: transparent;
    border-left-color: transparent;
    border-top-color: transparent
}

div.woocommerce .cart-collaterals .cart_totals {
    float: unset;
    width: 100%
}

div.woocommerce .shop_table .actions {
    display: none
}

div.woocommerce .my-account .account-details,div.woocommerce .my-account .white-shadow-box {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    padding: 24px
}

div.woocommerce .my-account .account-details .save-photo-block {
    margin-top: 24px;
    display: none
}

div.woocommerce .my-account .account-details .save-photo-block .buttons {
    gap: 16px
}

div.woocommerce .my-account .account-details .save-photo-block .buttons .orange-button {
    padding: 12px 32px
}

div.woocommerce .my-account .account-details .social-networks {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    padding: 0;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    margin-top: 24px;
    margin-bottom: 24px;
    gap: 16px
}

div.woocommerce .my-account .account-details .social-networks li {
    list-style: none;
    margin-right: 16px;
    margin-bottom: 0!important;
    position: relative
}

div.woocommerce .my-account .account-details .social-networks li .subscribed {
    position: absolute;
    bottom: -4px;
    right: -3px;
    border-radius: 0 100px 100px 0;
    border: 2px solid var(--Dubadu-Yes, #07B80E);
    background: var(--Dubadu-Yes, #07B80E);
    width: 13px;
    height: 14px;
    display: none
}

div.woocommerce .my-account .account-details .social-networks li .subscribed.active {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex
}

div.woocommerce .my-account .account-details .social-networks li a {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f5f6f8;
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

div.woocommerce .my-account .account-details .social-networks li a svg path {
    -webkit-transition: all .5s ease;
    transition: all .5s ease
}

div.woocommerce .my-account .white-shadow-box .form-group,div.woocommerce .my-account form .form-group {
    margin-bottom: 32px
}

div.woocommerce .my-account .white-shadow-box .form-group:last-of-type {
    margin-bottom: 0
}

.referral-program .card .card-body .price,.referral-program .card .card-header .price,div.woocommerce .my-account .white-shadow-box .invest-price {
    font-size: 24px;
    font-style: normal;
    font-weight: 600;
    line-height: 110%;
    letter-spacing: -.35px;
    position: relative;
    display: inline-block
}

div.woocommerce .my-account form:not(.change-photo-form) {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    -webkit-box-shadow: 2px 3px 24px 0 rgba(2,7,14,.04);
    box-shadow: 2px 3px 24px 0 rgba(2,7,14,.04);
    padding: 24px
}

div.woocommerce .my-account form.change-photo-form .circle {
    width: 200px;
    height: 200px;
    margin: auto;
    border-radius: 50%;
    position: relative
}

div.woocommerce .my-account form.change-photo-form .circle img {
    border-radius: 50%;
    width: 100%;
    height: 100%;
    -o-object-position: center;
    object-position: center;
    -o-object-fit: cover;
    object-fit: cover
}

div.woocommerce .my-account form.change-photo-form .circle .upload-button {
    cursor: pointer;
    right: 0;
    position: absolute;
    top: 0
}

.auth-pages form .form-group:last-of-type,.lost_reset_password form .form-group:last-of-type,div.woocommerce .my-account form .form-group:last-of-type {
    margin-bottom: 0
}

div.woocommerce .my-account form input:disabled {
    opacity: .5
}

.auth-pages form input::-webkit-input-placeholder,.lost_reset_password form input::-webkit-input-placeholder,.referral-program input::-webkit-input-placeholder,div.woocommerce .my-account form input::-webkit-input-placeholder {
    color: #989898
}

.auth-pages form input::-moz-placeholder,.lost_reset_password form input::-moz-placeholder,.referral-program input::-moz-placeholder,div.woocommerce .my-account form input::-moz-placeholder {
    color: #989898
}

.auth-pages form input:-ms-input-placeholder,.lost_reset_password form input:-ms-input-placeholder,.referral-program input:-ms-input-placeholder,div.woocommerce .my-account form input:-ms-input-placeholder {
    color: #989898
}

.auth-pages form input::-ms-input-placeholder,.lost_reset_password form input::-ms-input-placeholder,.referral-program input::-ms-input-placeholder,div.woocommerce .my-account form input::-ms-input-placeholder {
    color: #989898
}

.auth-pages form input::placeholder,.lost_reset_password form input::placeholder,.referral-program input::placeholder,div.woocommerce .my-account form input::placeholder {
    color: #989898
}

div.woocommerce .my-account form input:focus-visible {
    background-color: none;
    outline: 0;
    border: 1px solid #ff6319
}

div.woocommerce .my-account form input:focus {
    background-color: none;
    outline: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    color: #fff;
    border: 1px solid #ff6319
}

.site-main {
    padding: 200px 0 50px
}

.referral-program .card {
    border-radius: 20px;
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    padding: 16px 24px;
    color: #fff
}

.referral-program .card .card-body,.referral-program .card .card-header {
    padding: 8px 0
}

.referral-program .card .card-body .price::after,.referral-program .card .card-header .price::after {
    content: "";
    height: 2px;
    width: 100%;
    left: 0;
    position: absolute;
    background-color: #ff6319;
    bottom: -7px
}

.referral-program .card .card-header {
    border-bottom: none
}

.auth-pages form input:focus-visible,.lost_reset_password form input:focus-visible,.referral-program input:focus-visible {
    outline: 0;
    border: 1px solid #ff6319
}

.auth-pages form input:focus,.lost_reset_password form input:focus,.referral-program input:focus {
    background-color: unset!important;
    outline: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    color: #fff;
    border: 1px solid #ff6319!important
}

.auth-pages form .country-select,.auth-pages form .iti,.lost_reset_password form .country-select,.lost_reset_password form .iti {
    display: block
}

.auth-pages form .form-group,.lost_reset_password form .form-group {
    margin-bottom: 32px
}

.auth-pages form input,.lost_reset_password form input {
    border-radius: 8px 0 0 8px;
    border: 1px solid var(--Disabled, #989898);
    background: rgba(41,41,41,.75);
    -webkit-backdrop-filter: blur(12.5px);
    backdrop-filter: blur(12.5px);
    color: #fff;
    padding: 16px 20px;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    width: 100%;
    line-height: 28px
}

.withdraw-modal form .fop-block {
    display: none
}
</style>




		</div>




<script>
/*! For license information please see app.js.LICENSE.txt */
( () => {
    var t = {
        11: function(t, e, i) {
            t.exports = function(t, e, i, n) {
                "use strict";
                return class extends i {
                    constructor(e, i) {
                        super(),
                        (e = n.getElement(e)) && (this._element = e,
                        this._config = this._getConfig(i),
                        t.set(this._element, this.constructor.DATA_KEY, this))
                    }
                    dispose() {
                        t.remove(this._element, this.constructor.DATA_KEY),
                        e.off(this._element, this.constructor.EVENT_KEY);
                        for (const t of Object.getOwnPropertyNames(this))
                            this[t] = null
                    }
                    _queueCallback(t, e, i=!0) {
                        n.executeAfterTransition(t, e, i)
                    }
                    _getConfig(t) {
                        return t = this._mergeConfigObj(t, this._element),
                        t = this._configAfterMerge(t),
                        this._typeCheckConfig(t),
                        t
                    }
                    static getInstance(e) {
                        return t.get(n.getElement(e), this.DATA_KEY)
                    }
                    static getOrCreateInstance(t, e={}) {
                        return this.getInstance(t) || new this(t,"object" == typeof e ? e : null)
                    }
                    static get VERSION() {
                        return "5.3.3"
                    }
                    static get DATA_KEY() {
                        return `bs.${this.NAME}`
                    }
                    static get EVENT_KEY() {
                        return `.${this.DATA_KEY}`
                    }
                    static eventName(t) {
                        return `${t}${this.EVENT_KEY}`
                    }
                }
            }(i(269), i(956), i(105), i(35))
        },
        647: function(t, e, i) {
            t.exports = function(t, e, i, n) {
                "use strict";
                const s = ".bs.collapse"
                  , o = `show${s}`
                  , r = `shown${s}`
                  , a = `hide${s}`
                  , l = `hidden${s}`
                  , d = `click${s}.data-api`
                  , c = "show"
                  , u = "collapse"
                  , h = "collapsing"
                  , p = `:scope .${u} .${u}`
                  , f = '[data-bs-toggle="collapse"]'
                  , m = {
                    parent: null,
                    toggle: !0
                }
                  , g = {
                    parent: "(null|element)",
                    toggle: "boolean"
                };
                class v extends t {
                    constructor(t, e) {
                        super(t, e),
                        this._isTransitioning = !1,
                        this._triggerArray = [];
                        const n = i.find(f);
                        for (const t of n) {
                            const e = i.getSelectorFromElement(t)
                              , n = i.find(e).filter((t => t === this._element));
                            null !== e && n.length && this._triggerArray.push(t)
                        }
                        this._initializeChildren(),
                        this._config.parent || this._addAriaAndCollapsedClass(this._triggerArray, this._isShown()),
                        this._config.toggle && this.toggle()
                    }
                    static get Default() {
                        return m
                    }
                    static get DefaultType() {
                        return g
                    }
                    static get NAME() {
                        return "collapse"
                    }
                    toggle() {
                        this._isShown() ? this.hide() : this.show()
                    }
                    show() {
                        if (this._isTransitioning || this._isShown())
                            return;
                        let t = [];
                        if (this._config.parent && (t = this._getFirstLevelChildren(".collapse.show, .collapse.collapsing").filter((t => t !== this._element)).map((t => v.getOrCreateInstance(t, {
                            toggle: !1
                        })))),
                        t.length && t[0]._isTransitioning)
                            return;
                        if (e.trigger(this._element, o).defaultPrevented)
                            return;
                        for (const e of t)
                            e.hide();
                        const i = this._getDimension();
                        this._element.classList.remove(u),
                        this._element.classList.add(h),
                        this._element.style[i] = 0,
                        this._addAriaAndCollapsedClass(this._triggerArray, !0),
                        this._isTransitioning = !0;
                        const n = `scroll${i[0].toUpperCase() + i.slice(1)}`;
                        this._queueCallback(( () => {
                            this._isTransitioning = !1,
                            this._element.classList.remove(h),
                            this._element.classList.add(u, c),
                            this._element.style[i] = "",
                            e.trigger(this._element, r)
                        }
                        ), this._element, !0),
                        this._element.style[i] = `${this._element[n]}px`
                    }
                    hide() {
                        if (this._isTransitioning || !this._isShown())
                            return;
                        if (e.trigger(this._element, a).defaultPrevented)
                            return;
                        const t = this._getDimension();
                        this._element.style[t] = `${this._element.getBoundingClientRect()[t]}px`,
                        n.reflow(this._element),
                        this._element.classList.add(h),
                        this._element.classList.remove(u, c);
                        for (const t of this._triggerArray) {
                            const e = i.getElementFromSelector(t);
                            e && !this._isShown(e) && this._addAriaAndCollapsedClass([t], !1)
                        }
                        this._isTransitioning = !0;
                        this._element.style[t] = "",
                        this._queueCallback(( () => {
                            this._isTransitioning = !1,
                            this._element.classList.remove(h),
                            this._element.classList.add(u),
                            e.trigger(this._element, l)
                        }
                        ), this._element, !0)
                    }
                    _isShown(t=this._element) {
                        return t.classList.contains(c)
                    }
                    _configAfterMerge(t) {
                        return t.toggle = Boolean(t.toggle),
                        t.parent = n.getElement(t.parent),
                        t
                    }
                    _getDimension() {
                        return this._element.classList.contains("collapse-horizontal") ? "width" : "height"
                    }
                    _initializeChildren() {
                        if (!this._config.parent)
                            return;
                        const t = this._getFirstLevelChildren(f);
                        for (const e of t) {
                            const t = i.getElementFromSelector(e);
                            t && this._addAriaAndCollapsedClass([e], this._isShown(t))
                        }
                    }
                    _getFirstLevelChildren(t) {
                        const e = i.find(p, this._config.parent);
                        return i.find(t, this._config.parent).filter((t => !e.includes(t)))
                    }
                    _addAriaAndCollapsedClass(t, e) {
                        if (t.length)
                            for (const i of t)
                                i.classList.toggle("collapsed", !e),
                                i.setAttribute("aria-expanded", e)
                    }
                    static jQueryInterface(t) {
                        const e = {};
                        return "string" == typeof t && /show|hide/.test(t) && (e.toggle = !1),
                        this.each((function() {
                            const i = v.getOrCreateInstance(this, e);
                            if ("string" == typeof t) {
                                if (void 0 === i[t])
                                    throw new TypeError(`No method named "${t}"`);
                                i[t]()
                            }
                        }
                        ))
                    }
                }
                return e.on(document, d, f, (function(t) {
                    ("A" === t.target.tagName || t.delegateTarget && "A" === t.delegateTarget.tagName) && t.preventDefault();
                    for (const t of i.getMultipleElementsFromSelector(this))
                        v.getOrCreateInstance(t, {
                            toggle: !1
                        }).toggle()
                }
                )),
                n.defineJQueryPlugin(v),
                v
            }(i(11), i(956), i(411), i(35))
        },
        269: function(t) {
            t.exports = function() {
                "use strict";
                const t = new Map;
                return {
                    set(e, i, n) {
                        t.has(e) || t.set(e, new Map);
                        const s = t.get(e);
                        s.has(i) || 0 === s.size ? s.set(i, n) : console.error(`Bootstrap doesn't allow more than one instance per element. Bound instance: ${Array.from(s.keys())[0]}.`)
                    },
                    get: (e, i) => t.has(e) && t.get(e).get(i) || null,
                    remove(e, i) {
                        if (!t.has(e))
                            return;
                        const n = t.get(e);
                        n.delete(i),
                        0 === n.size && t.delete(e)
                    }
                }
            }()
        },
        956: function(t, e, i) {
            t.exports = function(t) {
                "use strict";
                const e = /[^.]*(?=\..*)\.|.*/
                  , i = /\..*/
                  , n = /::\d+$/
                  , s = {};
                let o = 1;
                const r = {
                    mouseenter: "mouseover",
                    mouseleave: "mouseout"
                }
                  , a = new Set(["click", "dblclick", "mouseup", "mousedown", "contextmenu", "mousewheel", "DOMMouseScroll", "mouseover", "mouseout", "mousemove", "selectstart", "selectend", "keydown", "keypress", "keyup", "orientationchange", "touchstart", "touchmove", "touchend", "touchcancel", "pointerdown", "pointermove", "pointerup", "pointerleave", "pointercancel", "gesturestart", "gesturechange", "gestureend", "focus", "blur", "change", "reset", "select", "submit", "focusin", "focusout", "load", "unload", "beforeunload", "resize", "move", "DOMContentLoaded", "readystatechange", "error", "abort", "scroll"]);
                function l(t, e) {
                    return e && `${e}::${o++}` || t.uidEvent || o++
                }
                function d(t) {
                    const e = l(t);
                    return t.uidEvent = e,
                    s[e] = s[e] || {},
                    s[e]
                }
                function c(t, e, i=null) {
                    return Object.values(t).find((t => t.callable === e && t.delegationSelector === i))
                }
                function u(t, e, i) {
                    const n = "string" == typeof e
                      , s = n ? i : e || i;
                    let o = m(t);
                    return a.has(o) || (o = t),
                    [n, s, o]
                }
                function h(t, i, n, s, o) {
                    if ("string" != typeof i || !t)
                        return;
                    let[a,h,p] = u(i, n, s);
                    if (i in r) {
                        const t = t => function(e) {
                            if (!e.relatedTarget || e.relatedTarget !== e.delegateTarget && !e.delegateTarget.contains(e.relatedTarget))
                                return t.call(this, e)
                        }
                        ;
                        h = t(h)
                    }
                    const f = d(t)
                      , m = f[p] || (f[p] = {})
                      , y = c(m, h, a ? n : null);
                    if (y)
                        return void (y.oneOff = y.oneOff && o);
                    const b = l(h, i.replace(e, ""))
                      , w = a ? function(t, e, i) {
                        return function n(s) {
                            const o = t.querySelectorAll(e);
                            for (let {target: r} = s; r && r !== this; r = r.parentNode)
                                for (const a of o)
                                    if (a === r)
                                        return v(s, {
                                            delegateTarget: r
                                        }),
                                        n.oneOff && g.off(t, s.type, e, i),
                                        i.apply(r, [s])
                        }
                    }(t, n, h) : function(t, e) {
                        return function i(n) {
                            return v(n, {
                                delegateTarget: t
                            }),
                            i.oneOff && g.off(t, n.type, e),
                            e.apply(t, [n])
                        }
                    }(t, h);
                    w.delegationSelector = a ? n : null,
                    w.callable = h,
                    w.oneOff = o,
                    w.uidEvent = b,
                    m[b] = w,
                    t.addEventListener(p, w, a)
                }
                function p(t, e, i, n, s) {
                    const o = c(e[i], n, s);
                    o && (t.removeEventListener(i, o, Boolean(s)),
                    delete e[i][o.uidEvent])
                }
                function f(t, e, i, n) {
                    const s = e[i] || {};
                    for (const [o,r] of Object.entries(s))
                        o.includes(n) && p(t, e, i, r.callable, r.delegationSelector)
                }
                function m(t) {
                    return t = t.replace(i, ""),
                    r[t] || t
                }
                const g = {
                    on(t, e, i, n) {
                        h(t, e, i, n, !1)
                    },
                    one(t, e, i, n) {
                        h(t, e, i, n, !0)
                    },
                    off(t, e, i, s) {
                        if ("string" != typeof e || !t)
                            return;
                        const [o,r,a] = u(e, i, s)
                          , l = a !== e
                          , c = d(t)
                          , h = c[a] || {}
                          , m = e.startsWith(".");
                        if (void 0 === r) {
                            if (m)
                                for (const i of Object.keys(c))
                                    f(t, c, i, e.slice(1));
                            for (const [i,s] of Object.entries(h)) {
                                const o = i.replace(n, "");
                                l && !e.includes(o) || p(t, c, a, s.callable, s.delegationSelector)
                            }
                        } else {
                            if (!Object.keys(h).length)
                                return;
                            p(t, c, a, r, o ? i : null)
                        }
                    },
                    trigger(e, i, n) {
                        if ("string" != typeof i || !e)
                            return null;
                        const s = t.getjQuery();
                        let o = null
                          , r = !0
                          , a = !0
                          , l = !1;
                        i !== m(i) && s && (o = s.Event(i, n),
                        s(e).trigger(o),
                        r = !o.isPropagationStopped(),
                        a = !o.isImmediatePropagationStopped(),
                        l = o.isDefaultPrevented());
                        const d = v(new Event(i,{
                            bubbles: r,
                            cancelable: !0
                        }), n);
                        return l && d.preventDefault(),
                        a && e.dispatchEvent(d),
                        d.defaultPrevented && o && o.preventDefault(),
                        d
                    }
                };
                function v(t, e={}) {
                    for (const [i,n] of Object.entries(e))
                        try {
                            t[i] = n
                        } catch (e) {
                            Object.defineProperty(t, i, {
                                configurable: !0,
                                get: () => n
                            })
                        }
                    return t
                }
                return g
            }(i(35))
        },
        333: function(t) {
            t.exports = function() {
                "use strict";
                function t(t) {
                    if ("true" === t)
                        return !0;
                    if ("false" === t)
                        return !1;
                    if (t === Number(t).toString())
                        return Number(t);
                    if ("" === t || "null" === t)
                        return null;
                    if ("string" != typeof t)
                        return t;
                    try {
                        return JSON.parse(decodeURIComponent(t))
                    } catch (e) {
                        return t
                    }
                }
                function e(t) {
                    return t.replace(/[A-Z]/g, (t => `-${t.toLowerCase()}`))
                }
                return {
                    setDataAttribute(t, i, n) {
                        t.setAttribute(`data-bs-${e(i)}`, n)
                    },
                    removeDataAttribute(t, i) {
                        t.removeAttribute(`data-bs-${e(i)}`)
                    },
                    getDataAttributes(e) {
                        if (!e)
                            return {};
                        const i = {}
                          , n = Object.keys(e.dataset).filter((t => t.startsWith("bs") && !t.startsWith("bsConfig")));
                        for (const s of n) {
                            let n = s.replace(/^bs/, "");
                            n = n.charAt(0).toLowerCase() + n.slice(1, n.length),
                            i[n] = t(e.dataset[s])
                        }
                        return i
                    },
                    getDataAttribute: (i, n) => t(i.getAttribute(`data-bs-${e(n)}`))
                }
            }()
        },
        411: function(t, e, i) {
            t.exports = function(t) {
                "use strict";
                const e = e => {
                    let i = e.getAttribute("data-bs-target");
                    if (!i || "#" === i) {
                        let t = e.getAttribute("href");
                        if (!t || !t.includes("#") && !t.startsWith("."))
                            return null;
                        t.includes("#") && !t.startsWith("#") && (t = `#${t.split("#")[1]}`),
                        i = t && "#" !== t ? t.trim() : null
                    }
                    return i ? i.split(",").map((e => t.parseSelector(e))).join(",") : null
                }
                  , i = {
                    find: (t, e=document.documentElement) => [].concat(...Element.prototype.querySelectorAll.call(e, t)),
                    findOne: (t, e=document.documentElement) => Element.prototype.querySelector.call(e, t),
                    children: (t, e) => [].concat(...t.children).filter((t => t.matches(e))),
                    parents(t, e) {
                        const i = [];
                        let n = t.parentNode.closest(e);
                        for (; n; )
                            i.push(n),
                            n = n.parentNode.closest(e);
                        return i
                    },
                    prev(t, e) {
                        let i = t.previousElementSibling;
                        for (; i; ) {
                            if (i.matches(e))
                                return [i];
                            i = i.previousElementSibling
                        }
                        return []
                    },
                    next(t, e) {
                        let i = t.nextElementSibling;
                        for (; i; ) {
                            if (i.matches(e))
                                return [i];
                            i = i.nextElementSibling
                        }
                        return []
                    },
                    focusableChildren(e) {
                        const i = ["a", "button", "input", "textarea", "select", "details", "[tabindex]", '[contenteditable="true"]'].map((t => `${t}:not([tabindex^="-"])`)).join(",");
                        return this.find(i, e).filter((e => !t.isDisabled(e) && t.isVisible(e)))
                    },
                    getSelectorFromElement(t) {
                        const n = e(t);
                        return n && i.findOne(n) ? n : null
                    },
                    getElementFromSelector(t) {
                        const n = e(t);
                        return n ? i.findOne(n) : null
                    },
                    getMultipleElementsFromSelector(t) {
                        const n = e(t);
                        return n ? i.find(n) : []
                    }
                };
                return i
            }(i(35))
        },
        635: function(t, e, i) {
            t.exports = function(t, e, i, n, s, o, r, a) {
                "use strict";
                const l = ".bs.modal"
                  , d = `hide${l}`
                  , c = `hidePrevented${l}`
                  , u = `hidden${l}`
                  , h = `show${l}`
                  , p = `shown${l}`
                  , f = `resize${l}`
                  , m = `click.dismiss${l}`
                  , g = `mousedown.dismiss${l}`
                  , v = `keydown.dismiss${l}`
                  , y = `click${l}.data-api`
                  , b = "modal-open"
                  , w = "show"
                  , k = "modal-static"
                  , C = {
                    backdrop: !0,
                    focus: !0,
                    keyboard: !0
                }
                  , _ = {
                    backdrop: "(boolean|string)",
                    focus: "boolean",
                    keyboard: "boolean"
                };
                class S extends t {
                    constructor(t, e) {
                        super(t, e),
                        this._dialog = i.findOne(".modal-dialog", this._element),
                        this._backdrop = this._initializeBackDrop(),
                        this._focustrap = this._initializeFocusTrap(),
                        this._isShown = !1,
                        this._isTransitioning = !1,
                        this._scrollBar = new a,
                        this._addEventListeners()
                    }
                    static get Default() {
                        return C
                    }
                    static get DefaultType() {
                        return _
                    }
                    static get NAME() {
                        return "modal"
                    }
                    toggle(t) {
                        return this._isShown ? this.hide() : this.show(t)
                    }
                    show(t) {
                        this._isShown || this._isTransitioning || e.trigger(this._element, h, {
                            relatedTarget: t
                        }).defaultPrevented || (this._isShown = !0,
                        this._isTransitioning = !0,
                        this._scrollBar.hide(),
                        document.body.classList.add(b),
                        this._adjustDialog(),
                        this._backdrop.show(( () => this._showElement(t))))
                    }
                    hide() {
                        this._isShown && !this._isTransitioning && (e.trigger(this._element, d).defaultPrevented || (this._isShown = !1,
                        this._isTransitioning = !0,
                        this._focustrap.deactivate(),
                        this._element.classList.remove(w),
                        this._queueCallback(( () => this._hideModal()), this._element, this._isAnimated())))
                    }
                    dispose() {
                        e.off(window, l),
                        e.off(this._dialog, l),
                        this._backdrop.dispose(),
                        this._focustrap.deactivate(),
                        super.dispose()
                    }
                    handleUpdate() {
                        this._adjustDialog()
                    }
                    _initializeBackDrop() {
                        return new n({
                            isVisible: Boolean(this._config.backdrop),
                            isAnimated: this._isAnimated()
                        })
                    }
                    _initializeFocusTrap() {
                        return new o({
                            trapElement: this._element
                        })
                    }
                    _showElement(t) {
                        document.body.contains(this._element) || document.body.append(this._element),
                        this._element.style.display = "block",
                        this._element.removeAttribute("aria-hidden"),
                        this._element.setAttribute("aria-modal", !0),
                        this._element.setAttribute("role", "dialog"),
                        this._element.scrollTop = 0;
                        const n = i.findOne(".modal-body", this._dialog);
                        n && (n.scrollTop = 0),
                        r.reflow(this._element),
                        this._element.classList.add(w);
                        this._queueCallback(( () => {
                            this._config.focus && this._focustrap.activate(),
                            this._isTransitioning = !1,
                            e.trigger(this._element, p, {
                                relatedTarget: t
                            })
                        }
                        ), this._dialog, this._isAnimated())
                    }
                    _addEventListeners() {
                        e.on(this._element, v, (t => {
                            "Escape" === t.key && (this._config.keyboard ? this.hide() : this._triggerBackdropTransition())
                        }
                        )),
                        e.on(window, f, ( () => {
                            this._isShown && !this._isTransitioning && this._adjustDialog()
                        }
                        )),
                        e.on(this._element, g, (t => {
                            e.one(this._element, m, (e => {
                                this._element === t.target && this._element === e.target && ("static" !== this._config.backdrop ? this._config.backdrop && this.hide() : this._triggerBackdropTransition())
                            }
                            ))
                        }
                        ))
                    }
                    _hideModal() {
                        this._element.style.display = "none",
                        this._element.setAttribute("aria-hidden", !0),
                        this._element.removeAttribute("aria-modal"),
                        this._element.removeAttribute("role"),
                        this._isTransitioning = !1,
                        this._backdrop.hide(( () => {
                            document.body.classList.remove(b),
                            this._resetAdjustments(),
                            this._scrollBar.reset(),
                            e.trigger(this._element, u)
                        }
                        ))
                    }
                    _isAnimated() {
                        return this._element.classList.contains("fade")
                    }
                    _triggerBackdropTransition() {
                        if (e.trigger(this._element, c).defaultPrevented)
                            return;
                        const t = this._element.scrollHeight > document.documentElement.clientHeight
                          , i = this._element.style.overflowY;
                        "hidden" === i || this._element.classList.contains(k) || (t || (this._element.style.overflowY = "hidden"),
                        this._element.classList.add(k),
                        this._queueCallback(( () => {
                            this._element.classList.remove(k),
                            this._queueCallback(( () => {
                                this._element.style.overflowY = i
                            }
                            ), this._dialog)
                        }
                        ), this._dialog),
                        this._element.focus())
                    }
                    _adjustDialog() {
                        const t = this._element.scrollHeight > document.documentElement.clientHeight
                          , e = this._scrollBar.getWidth()
                          , i = e > 0;
                        if (i && !t) {
                            const t = r.isRTL() ? "paddingLeft" : "paddingRight";
                            this._element.style[t] = `${e}px`
                        }
                        if (!i && t) {
                            const t = r.isRTL() ? "paddingRight" : "paddingLeft";
                            this._element.style[t] = `${e}px`
                        }
                    }
                    _resetAdjustments() {
                        this._element.style.paddingLeft = "",
                        this._element.style.paddingRight = ""
                    }
                    static jQueryInterface(t, e) {
                        return this.each((function() {
                            const i = S.getOrCreateInstance(this, t);
                            if ("string" == typeof t) {
                                if (void 0 === i[t])
                                    throw new TypeError(`No method named "${t}"`);
                                i[t](e)
                            }
                        }
                        ))
                    }
                }
                return e.on(document, y, '[data-bs-toggle="modal"]', (function(t) {
                    const n = i.getElementFromSelector(this);
                    ["A", "AREA"].includes(this.tagName) && t.preventDefault(),
                    e.one(n, h, (t => {
                        t.defaultPrevented || e.one(n, u, ( () => {
                            r.isVisible(this) && this.focus()
                        }
                        ))
                    }
                    ));
                    const s = i.findOne(".modal.show");
                    s && S.getInstance(s).hide(),
                    S.getOrCreateInstance(n).toggle(this)
                }
                )),
                s.enableDismissTrigger(S),
                r.defineJQueryPlugin(S),
                S
            }(i(11), i(956), i(411), i(877), i(248), i(936), i(35), i(673))
        },
        13: function(t, e, i) {
            t.exports = function(t, e, i, n) {
                "use strict";
                const s = ".bs.tab"
                  , o = `hide${s}`
                  , r = `hidden${s}`
                  , a = `show${s}`
                  , l = `shown${s}`
                  , d = `click${s}`
                  , c = `keydown${s}`
                  , u = `load${s}`
                  , h = "ArrowLeft"
                  , p = "ArrowRight"
                  , f = "ArrowUp"
                  , m = "ArrowDown"
                  , g = "Home"
                  , v = "End"
                  , y = "active"
                  , b = "fade"
                  , w = "show"
                  , k = ".dropdown-toggle"
                  , C = `:not(${k})`
                  , _ = '[data-bs-toggle="tab"], [data-bs-toggle="pill"], [data-bs-toggle="list"]'
                  , S = `.nav-link${C}, .list-group-item${C}, [role="tab"]${C}, ${_}`
                  , T = `.${y}[data-bs-toggle="tab"], .${y}[data-bs-toggle="pill"], .${y}[data-bs-toggle="list"]`;
                class x extends t {
                    constructor(t) {
                        super(t),
                        this._parent = this._element.closest('.list-group, .nav, [role="tablist"]'),
                        this._parent && (this._setInitialAttributes(this._parent, this._getChildren()),
                        e.on(this._element, c, (t => this._keydown(t))))
                    }
                    static get NAME() {
                        return "tab"
                    }
                    show() {
                        const t = this._element;
                        if (this._elemIsActive(t))
                            return;
                        const i = this._getActiveElem()
                          , n = i ? e.trigger(i, o, {
                            relatedTarget: t
                        }) : null;
                        e.trigger(t, a, {
                            relatedTarget: i
                        }).defaultPrevented || n && n.defaultPrevented || (this._deactivate(i, t),
                        this._activate(t, i))
                    }
                    _activate(t, n) {
                        if (!t)
                            return;
                        t.classList.add(y),
                        this._activate(i.getElementFromSelector(t));
                        this._queueCallback(( () => {
                            "tab" === t.getAttribute("role") ? (t.removeAttribute("tabindex"),
                            t.setAttribute("aria-selected", !0),
                            this._toggleDropDown(t, !0),
                            e.trigger(t, l, {
                                relatedTarget: n
                            })) : t.classList.add(w)
                        }
                        ), t, t.classList.contains(b))
                    }
                    _deactivate(t, n) {
                        if (!t)
                            return;
                        t.classList.remove(y),
                        t.blur(),
                        this._deactivate(i.getElementFromSelector(t));
                        this._queueCallback(( () => {
                            "tab" === t.getAttribute("role") ? (t.setAttribute("aria-selected", !1),
                            t.setAttribute("tabindex", "-1"),
                            this._toggleDropDown(t, !1),
                            e.trigger(t, r, {
                                relatedTarget: n
                            })) : t.classList.remove(w)
                        }
                        ), t, t.classList.contains(b))
                    }
                    _keydown(t) {
                        if (![h, p, f, m, g, v].includes(t.key))
                            return;
                        t.stopPropagation(),
                        t.preventDefault();
                        const e = this._getChildren().filter((t => !n.isDisabled(t)));
                        let i;
                        if ([g, v].includes(t.key))
                            i = e[t.key === g ? 0 : e.length - 1];
                        else {
                            const s = [p, m].includes(t.key);
                            i = n.getNextActiveElement(e, t.target, s, !0)
                        }
                        i && (i.focus({
                            preventScroll: !0
                        }),
                        x.getOrCreateInstance(i).show())
                    }
                    _getChildren() {
                        return i.find(S, this._parent)
                    }
                    _getActiveElem() {
                        return this._getChildren().find((t => this._elemIsActive(t))) || null
                    }
                    _setInitialAttributes(t, e) {
                        this._setAttributeIfNotExists(t, "role", "tablist");
                        for (const t of e)
                            this._setInitialAttributesOnChild(t)
                    }
                    _setInitialAttributesOnChild(t) {
                        t = this._getInnerElement(t);
                        const e = this._elemIsActive(t)
                          , i = this._getOuterElement(t);
                        t.setAttribute("aria-selected", e),
                        i !== t && this._setAttributeIfNotExists(i, "role", "presentation"),
                        e || t.setAttribute("tabindex", "-1"),
                        this._setAttributeIfNotExists(t, "role", "tab"),
                        this._setInitialAttributesOnTargetPanel(t)
                    }
                    _setInitialAttributesOnTargetPanel(t) {
                        const e = i.getElementFromSelector(t);
                        e && (this._setAttributeIfNotExists(e, "role", "tabpanel"),
                        t.id && this._setAttributeIfNotExists(e, "aria-labelledby", `${t.id}`))
                    }
                    _toggleDropDown(t, e) {
                        const n = this._getOuterElement(t);
                        if (!n.classList.contains("dropdown"))
                            return;
                        const s = (t, s) => {
                            const o = i.findOne(t, n);
                            o && o.classList.toggle(s, e)
                        }
                        ;
                        s(k, y),
                        s(".dropdown-menu", w),
                        n.setAttribute("aria-expanded", e)
                    }
                    _setAttributeIfNotExists(t, e, i) {
                        t.hasAttribute(e) || t.setAttribute(e, i)
                    }
                    _elemIsActive(t) {
                        return t.classList.contains(y)
                    }
                    _getInnerElement(t) {
                        return t.matches(S) ? t : i.findOne(S, t)
                    }
                    _getOuterElement(t) {
                        return t.closest(".nav-item, .list-group-item") || t
                    }
                    static jQueryInterface(t) {
                        return this.each((function() {
                            const e = x.getOrCreateInstance(this);
                            if ("string" == typeof t) {
                                if (void 0 === e[t] || t.startsWith("_") || "constructor" === t)
                                    throw new TypeError(`No method named "${t}"`);
                                e[t]()
                            }
                        }
                        ))
                    }
                }
                return e.on(document, d, _, (function(t) {
                    ["A", "AREA"].includes(this.tagName) && t.preventDefault(),
                    n.isDisabled(this) || x.getOrCreateInstance(this).show()
                }
                )),
                e.on(window, u, ( () => {
                    for (const t of i.find(T))
                        x.getOrCreateInstance(t)
                }
                )),
                n.defineJQueryPlugin(x),
                x
            }(i(11), i(956), i(411), i(35))
        },
        877: function(t, e, i) {
            t.exports = function(t, e, i) {
                "use strict";
                const n = "backdrop"
                  , s = "show"
                  , o = `mousedown.bs.${n}`
                  , r = {
                    className: "modal-backdrop",
                    clickCallback: null,
                    isAnimated: !1,
                    isVisible: !0,
                    rootElement: "body"
                }
                  , a = {
                    className: "string",
                    clickCallback: "(function|null)",
                    isAnimated: "boolean",
                    isVisible: "boolean",
                    rootElement: "(element|string)"
                };
                return class extends e {
                    constructor(t) {
                        super(),
                        this._config = this._getConfig(t),
                        this._isAppended = !1,
                        this._element = null
                    }
                    static get Default() {
                        return r
                    }
                    static get DefaultType() {
                        return a
                    }
                    static get NAME() {
                        return n
                    }
                    show(t) {
                        if (!this._config.isVisible)
                            return void i.execute(t);
                        this._append();
                        const e = this._getElement();
                        this._config.isAnimated && i.reflow(e),
                        e.classList.add(s),
                        this._emulateAnimation(( () => {
                            i.execute(t)
                        }
                        ))
                    }
                    hide(t) {
                        this._config.isVisible ? (this._getElement().classList.remove(s),
                        this._emulateAnimation(( () => {
                            this.dispose(),
                            i.execute(t)
                        }
                        ))) : i.execute(t)
                    }
                    dispose() {
                        this._isAppended && (t.off(this._element, o),
                        this._element.remove(),
                        this._isAppended = !1)
                    }
                    _getElement() {
                        if (!this._element) {
                            const t = document.createElement("div");
                            t.className = this._config.className,
                            this._config.isAnimated && t.classList.add("fade"),
                            this._element = t
                        }
                        return this._element
                    }
                    _configAfterMerge(t) {
                        return t.rootElement = i.getElement(t.rootElement),
                        t
                    }
                    _append() {
                        if (this._isAppended)
                            return;
                        const e = this._getElement();
                        this._config.rootElement.append(e),
                        t.on(e, o, ( () => {
                            i.execute(this._config.clickCallback)
                        }
                        )),
                        this._isAppended = !0
                    }
                    _emulateAnimation(t) {
                        i.executeAfterTransition(t, this._getElement(), this._config.isAnimated)
                    }
                }
            }(i(956), i(105), i(35))
        },
        248: function(t, e, i) {
            !function(t, e, i, n) {
                "use strict";
                t.enableDismissTrigger = (t, s="hide") => {
                    const o = `click.dismiss${t.EVENT_KEY}`
                      , r = t.NAME;
                    e.on(document, o, `[data-bs-dismiss="${r}"]`, (function(e) {
                        if (["A", "AREA"].includes(this.tagName) && e.preventDefault(),
                        n.isDisabled(this))
                            return;
                        const o = i.getElementFromSelector(this) || this.closest(`.${r}`);
                        t.getOrCreateInstance(o)[s]()
                    }
                    ))
                }
                ,
                Object.defineProperty(t, Symbol.toStringTag, {
                    value: "Module"
                })
            }(e, i(956), i(411), i(35))
        },
        105: function(t, e, i) {
            t.exports = function(t, e) {
                "use strict";
                return class {
                    static get Default() {
                        return {}
                    }
                    static get DefaultType() {
                        return {}
                    }
                    static get NAME() {
                        throw new Error('You have to implement the static method "NAME", for each component!')
                    }
                    _getConfig(t) {
                        return t = this._mergeConfigObj(t),
                        t = this._configAfterMerge(t),
                        this._typeCheckConfig(t),
                        t
                    }
                    _configAfterMerge(t) {
                        return t
                    }
                    _mergeConfigObj(i, n) {
                        const s = e.isElement(n) ? t.getDataAttribute(n, "config") : {};
                        return {
                            ...this.constructor.Default,
                            ..."object" == typeof s ? s : {},
                            ...e.isElement(n) ? t.getDataAttributes(n) : {},
                            ..."object" == typeof i ? i : {}
                        }
                    }
                    _typeCheckConfig(t, i=this.constructor.DefaultType) {
                        for (const [n,s] of Object.entries(i)) {
                            const i = t[n]
                              , o = e.isElement(i) ? "element" : e.toType(i);
                            if (!new RegExp(s).test(o))
                                throw new TypeError(`${this.constructor.NAME.toUpperCase()}: Option "${n}" provided type "${o}" but expected type "${s}".`)
                        }
                    }
                }
            }(i(333), i(35))
        },
        936: function(t, e, i) {
            t.exports = function(t, e, i) {
                "use strict";
                const n = ".bs.focustrap"
                  , s = `focusin${n}`
                  , o = `keydown.tab${n}`
                  , r = "backward"
                  , a = {
                    autofocus: !0,
                    trapElement: null
                }
                  , l = {
                    autofocus: "boolean",
                    trapElement: "element"
                };
                return class extends i {
                    constructor(t) {
                        super(),
                        this._config = this._getConfig(t),
                        this._isActive = !1,
                        this._lastTabNavDirection = null
                    }
                    static get Default() {
                        return a
                    }
                    static get DefaultType() {
                        return l
                    }
                    static get NAME() {
                        return "focustrap"
                    }
                    activate() {
                        this._isActive || (this._config.autofocus && this._config.trapElement.focus(),
                        t.off(document, n),
                        t.on(document, s, (t => this._handleFocusin(t))),
                        t.on(document, o, (t => this._handleKeydown(t))),
                        this._isActive = !0)
                    }
                    deactivate() {
                        this._isActive && (this._isActive = !1,
                        t.off(document, n))
                    }
                    _handleFocusin(t) {
                        const {trapElement: i} = this._config;
                        if (t.target === document || t.target === i || i.contains(t.target))
                            return;
                        const n = e.focusableChildren(i);
                        0 === n.length ? i.focus() : this._lastTabNavDirection === r ? n[n.length - 1].focus() : n[0].focus()
                    }
                    _handleKeydown(t) {
                        "Tab" === t.key && (this._lastTabNavDirection = t.shiftKey ? r : "forward")
                    }
                }
            }(i(956), i(411), i(105))
        },
        35: function(t, e) {
            !function(t) {
                "use strict";
                const e = "transitionend"
                  , i = t => (t && window.CSS && window.CSS.escape && (t = t.replace(/#([^\s"#']+)/g, ( (t, e) => `#${CSS.escape(e)}`))),
                t)
                  , n = t => {
                    if (!t)
                        return 0;
                    let {transitionDuration: e, transitionDelay: i} = window.getComputedStyle(t);
                    const n = Number.parseFloat(e)
                      , s = Number.parseFloat(i);
                    return n || s ? (e = e.split(",")[0],
                    i = i.split(",")[0],
                    1e3 * (Number.parseFloat(e) + Number.parseFloat(i))) : 0
                }
                  , s = t => {
                    t.dispatchEvent(new Event(e))
                }
                  , o = t => !(!t || "object" != typeof t) && (void 0 !== t.jquery && (t = t[0]),
                void 0 !== t.nodeType)
                  , r = t => {
                    if (!document.documentElement.attachShadow)
                        return null;
                    if ("function" == typeof t.getRootNode) {
                        const e = t.getRootNode();
                        return e instanceof ShadowRoot ? e : null
                    }
                    return t instanceof ShadowRoot ? t : t.parentNode ? r(t.parentNode) : null
                }
                  , a = () => window.jQuery && !document.body.hasAttribute("data-bs-no-jquery") ? window.jQuery : null
                  , l = []
                  , d = t => {
                    "loading" === document.readyState ? (l.length || document.addEventListener("DOMContentLoaded", ( () => {
                        for (const t of l)
                            t()
                    }
                    )),
                    l.push(t)) : t()
                }
                  , c = (t, e=[], i=t) => "function" == typeof t ? t(...e) : i;
                t.defineJQueryPlugin = t => {
                    d(( () => {
                        const e = a();
                        if (e) {
                            const i = t.NAME
                              , n = e.fn[i];
                            e.fn[i] = t.jQueryInterface,
                            e.fn[i].Constructor = t,
                            e.fn[i].noConflict = () => (e.fn[i] = n,
                            t.jQueryInterface)
                        }
                    }
                    ))
                }
                ,
                t.execute = c,
                t.executeAfterTransition = (t, i, o=!0) => {
                    if (!o)
                        return void c(t);
                    const r = n(i) + 5;
                    let a = !1;
                    const l = ({target: n}) => {
                        n === i && (a = !0,
                        i.removeEventListener(e, l),
                        c(t))
                    }
                    ;
                    i.addEventListener(e, l),
                    setTimeout(( () => {
                        a || s(i)
                    }
                    ), r)
                }
                ,
                t.findShadowRoot = r,
                t.getElement = t => o(t) ? t.jquery ? t[0] : t : "string" == typeof t && t.length > 0 ? document.querySelector(i(t)) : null,
                t.getNextActiveElement = (t, e, i, n) => {
                    const s = t.length;
                    let o = t.indexOf(e);
                    return -1 === o ? !i && n ? t[s - 1] : t[0] : (o += i ? 1 : -1,
                    n && (o = (o + s) % s),
                    t[Math.max(0, Math.min(o, s - 1))])
                }
                ,
                t.getTransitionDurationFromElement = n,
                t.getUID = t => {
                    do {
                        t += Math.floor(1e6 * Math.random())
                    } while (document.getElementById(t));
                    return t
                }
                ,
                t.getjQuery = a,
                t.isDisabled = t => !t || t.nodeType !== Node.ELEMENT_NODE || !!t.classList.contains("disabled") || (void 0 !== t.disabled ? t.disabled : t.hasAttribute("disabled") && "false" !== t.getAttribute("disabled")),
                t.isElement = o,
                t.isRTL = () => "rtl" === document.documentElement.dir,
                t.isVisible = t => {
                    if (!o(t) || 0 === t.getClientRects().length)
                        return !1;
                    const e = "visible" === getComputedStyle(t).getPropertyValue("visibility")
                      , i = t.closest("details:not([open])");
                    if (!i)
                        return e;
                    if (i !== t) {
                        const e = t.closest("summary");
                        if (e && e.parentNode !== i)
                            return !1;
                        if (null === e)
                            return !1
                    }
                    return e
                }
                ,
                t.noop = () => {}
                ,
                t.onDOMContentLoaded = d,
                t.parseSelector = i,
                t.reflow = t => {
                    t.offsetHeight
                }
                ,
                t.toType = t => null == t ? `${t}` : Object.prototype.toString.call(t).match(/\s([a-z]+)/i)[1].toLowerCase(),
                t.triggerTransitionEnd = s,
                Object.defineProperty(t, Symbol.toStringTag, {
                    value: "Module"
                })
            }(e)
        },
        673: function(t, e, i) {
            t.exports = function(t, e, i) {
                "use strict";
                const n = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top"
                  , s = ".sticky-top"
                  , o = "padding-right"
                  , r = "margin-right";
                return class {
                    constructor() {
                        this._element = document.body
                    }
                    getWidth() {
                        const t = document.documentElement.clientWidth;
                        return Math.abs(window.innerWidth - t)
                    }
                    hide() {
                        const t = this.getWidth();
                        this._disableOverFlow(),
                        this._setElementAttributes(this._element, o, (e => e + t)),
                        this._setElementAttributes(n, o, (e => e + t)),
                        this._setElementAttributes(s, r, (e => e - t))
                    }
                    reset() {
                        this._resetElementAttributes(this._element, "overflow"),
                        this._resetElementAttributes(this._element, o),
                        this._resetElementAttributes(n, o),
                        this._resetElementAttributes(s, r)
                    }
                    isOverflowing() {
                        return this.getWidth() > 0
                    }
                    _disableOverFlow() {
                        this._saveInitialAttribute(this._element, "overflow"),
                        this._element.style.overflow = "hidden"
                    }
                    _setElementAttributes(t, e, i) {
                        const n = this.getWidth();
                        this._applyManipulationCallback(t, (t => {
                            if (t !== this._element && window.innerWidth > t.clientWidth + n)
                                return;
                            this._saveInitialAttribute(t, e);
                            const s = window.getComputedStyle(t).getPropertyValue(e);
                            t.style.setProperty(e, `${i(Number.parseFloat(s))}px`)
                        }
                        ))
                    }
                    _saveInitialAttribute(e, i) {
                        const n = e.style.getPropertyValue(i);
                        n && t.setDataAttribute(e, i, n)
                    }
                    _resetElementAttributes(e, i) {
                        this._applyManipulationCallback(e, (e => {
                            const n = t.getDataAttribute(e, i);
                            null !== n ? (t.removeDataAttribute(e, i),
                            e.style.setProperty(i, n)) : e.style.removeProperty(i)
                        }
                        ))
                    }
                    _applyManipulationCallback(t, n) {
                        if (i.isElement(t))
                            n(t);
                        else
                            for (const i of e.find(t, this._element))
                                n(i)
                    }
                }
            }(i(333), i(411), i(35))
        },
        17: (t, e, i) => {
            var n, s;
            n = [i(669)],
            void 0 === (s = function(t) {
                !function(t, e, i, n) {
                    "use strict";
                    var s = "countrySelect"
                      , o = 1
                      , r = {
                        defaultCountry: "",
                        defaultStyling: "inside",
                        excludeCountries: [],
                        onlyCountries: [],
                        preferredCountries: ["us", "gb"],
                        localizedCountries: null,
                        responsiveDropdown: t(e).width() < 768
                    }
                      , a = 38
                      , l = 40
                      , d = 13
                      , c = 27
                      , u = 8
                      , h = 32
                      , p = 65
                      , f = 90;
                    function m(e, i) {
                        this.element = e,
                        this.options = t.extend({}, r, i),
                        this._defaults = r,
                        this.ns = "." + s + o++,
                        this._name = s,
                        this.init()
                    }
                    t(e).on("load", (function() {}
                    )),
                    m.prototype = {
                        init: function() {
                            return this._processCountryData(),
                            this._generateMarkup(),
                            this._setInitialState(),
                            this._initListeners(),
                            this.autoCountryDeferred = new t.Deferred,
                            this._initAutoCountry(),
                            this.typedLetters = "",
                            this.autoCountryDeferred
                        },
                        _processCountryData: function() {
                            this._setInstanceCountryData(),
                            this._setPreferredCountries(),
                            this.options.localizedCountries && this._translateCountriesByLocale(),
                            (this.options.onlyCountries.length || this.options.localizedCountries) && this.countries.sort(this._countryNameSort)
                        },
                        _setInstanceCountryData: function() {
                            var e = this;
                            if (this.options.onlyCountries.length) {
                                var i = [];
                                t.each(this.options.onlyCountries, (function(t, n) {
                                    var s = e._getCountryData(n, !0);
                                    s && i.push(s)
                                }
                                )),
                                this.countries = i
                            } else if (this.options.excludeCountries.length) {
                                var n = this.options.excludeCountries.map((function(t) {
                                    return t.toLowerCase()
                                }
                                ));
                                this.countries = g.filter((function(t) {
                                    return -1 === n.indexOf(t.iso2)
                                }
                                ))
                            } else
                                this.countries = g
                        },
                        _setPreferredCountries: function() {
                            var e = this;
                            this.preferredCountries = [],
                            t.each(this.options.preferredCountries, (function(t, i) {
                                var n = e._getCountryData(i, !1);
                                n && e.preferredCountries.push(n)
                            }
                            ))
                        },
                        _translateCountriesByLocale() {
                            for (let t = 0; t < this.countries.length; t++) {
                                const e = this.countries[t].iso2.toLowerCase();
                                this.options.localizedCountries.hasOwnProperty(e) && (this.countries[t].name = this.options.localizedCountries[e])
                            }
                        },
                        _countryNameSort: (t, e) => t.name.localeCompare(e.name),
                        _generateMarkup: function() {
                            this.countryInput = t(this.element);
                            var i = "country-select";
                            this.options.defaultStyling && (i += " " + this.options.defaultStyling),
                            this.countryInput.wrap(t("<div>", {
                                class: i
                            }));
                            var n = t("<div>", {
                                class: "flag-dropdown"
                            }).insertAfter(this.countryInput)
                              , s = t("<div>", {
                                class: "selected-flag"
                            }).appendTo(n);
                            this.selectedFlagInner = t("<div>", {
                                class: "flag"
                            }).appendTo(s),
                            t("<div>", {
                                class: "arrow"
                            }).appendTo(s),
                            this.countryList = t("<ul>", {
                                class: "country-list v-hide"
                            }).appendTo(n),
                            this.preferredCountries.length && (this._appendListItems(this.preferredCountries, "preferred"),
                            t("<li>", {
                                class: "divider"
                            }).appendTo(this.countryList)),
                            this._appendListItems(this.countries, ""),
                            this.countryCodeInput = t("#" + this.countryInput.attr("id") + "_code"),
                            this.countryCodeInput || (this.countryCodeInput = t('<input type="hidden" id="' + this.countryInput.attr("id") + '_code" name="' + this.countryInput.attr("name") + '_code" value="" />'),
                            this.countryCodeInput.insertAfter(this.countryInput)),
                            this.dropdownHeight = this.countryList.outerHeight(),
                            this.options.responsiveDropdown && t(e).resize((function() {
                                t(".country-select").each((function() {
                                    var e = this.offsetWidth;
                                    t(this).find(".country-list").css("width", e + "px")
                                }
                                ))
                            }
                            )).resize(),
                            this.countryList.removeClass("v-hide").addClass("hide"),
                            this.countryListItems = this.countryList.children(".country")
                        },
                        _appendListItems: function(e, i) {
                            var n = "";
                            t.each(e, (function(t, e) {
                                n += '<li class="country ' + i + '" data-country-code="' + e.iso2 + '">',
                                n += '<div class="flag ' + e.iso2 + '"></div>',
                                n += '<span class="country-name">' + e.name + "</span>",
                                n += "</li>"
                            }
                            )),
                            this.countryList.append(n)
                        },
                        _setInitialState: function() {
                            var t = !1;
                            this.countryInput.val() && (t = this._updateFlagFromInputVal());
                            var e, i = this.countryCodeInput.val();
                            i && this.selectCountry(i),
                            t || (this.options.defaultCountry && (e = this._getCountryData(this.options.defaultCountry, !1)) || (e = this.preferredCountries.length ? this.preferredCountries[0] : this.countries[0]),
                            this.defaultCountry = e.iso2)
                        },
                        _initListeners: function() {
                            var t = this;
                            this.countryInput.on("keyup" + this.ns, (function() {
                                t._updateFlagFromInputVal()
                            }
                            )),
                            this.selectedFlagInner.parent().on("click" + this.ns, (function(e) {
                                t.countryList.hasClass("hide") && !t.countryInput.prop("disabled") && t._showDropdown()
                            }
                            )),
                            this.countryInput.on("blur" + this.ns, (function() {
                                t.countryInput.val() != t.getSelectedCountryData().name && t.setCountry(t.countryInput.val()),
                                t.countryInput.val(t.getSelectedCountryData().name)
                            }
                            ))
                        },
                        _initAutoCountry: function() {
                            "auto" === this.options.initialCountry ? this._loadAutoCountry() : (this.defaultCountry && this.selectCountry(this.defaultCountry),
                            this.autoCountryDeferred.resolve())
                        },
                        _loadAutoCountry: function() {
                            t.fn[s].autoCountry ? this.handleAutoCountry() : t.fn[s].startedLoadingAutoCountry || (t.fn[s].startedLoadingAutoCountry = !0,
                            "function" == typeof this.options.geoIpLookup && this.options.geoIpLookup((function(e) {
                                t.fn[s].autoCountry = e.toLowerCase(),
                                setTimeout((function() {
                                    t(".country-select input").countrySelect("handleAutoCountry")
                                }
                                ))
                            }
                            )))
                        },
                        _focus: function() {
                            this.countryInput.focus();
                            var t = this.countryInput[0];
                            if (t.setSelectionRange) {
                                var e = this.countryInput.val().length;
                                t.setSelectionRange(e, e)
                            }
                        },
                        _showDropdown: function() {
                            this._setDropdownPosition();
                            var t = this.countryList.children(".active");
                            this._highlightListItem(t),
                            this.countryList.removeClass("hide"),
                            this._scrollTo(t),
                            this._bindDropdownListeners(),
                            this.selectedFlagInner.parent().children(".arrow").addClass("up")
                        },
                        _setDropdownPosition: function() {
                            var i = this.countryInput.offset().top
                              , n = t(e).scrollTop()
                              , s = i + this.countryInput.outerHeight() + this.dropdownHeight < n + t(e).height()
                              , o = i - this.dropdownHeight > n
                              , r = !s && o ? "-" + (this.dropdownHeight - 1) + "px" : "";
                            this.countryList.css("top", r)
                        },
                        _bindDropdownListeners: function() {
                            var e = this;
                            this.countryList.on("mouseover" + this.ns, ".country", (function(i) {
                                e._highlightListItem(t(this))
                            }
                            )),
                            this.countryList.on("click" + this.ns, ".country", (function(i) {
                                e._selectListItem(t(this))
                            }
                            ));
                            var n = !0;
                            t("html").on("click" + this.ns, (function(t) {
                                t.preventDefault(),
                                n || e._closeDropdown(),
                                n = !1
                            }
                            )),
                            t(i).on("keydown" + this.ns, (function(t) {
                                t.preventDefault(),
                                t.which == a || t.which == l ? e._handleUpDownKey(t.which) : t.which == d ? e._handleEnterKey() : t.which == c ? e._closeDropdown() : t.which >= p && t.which <= f || t.which === h ? (e.typedLetters += String.fromCharCode(t.which),
                                e._filterCountries(e.typedLetters)) : t.which === u && (e.typedLetters = e.typedLetters.slice(0, -1),
                                e._filterCountries(e.typedLetters))
                            }
                            ))
                        },
                        _handleUpDownKey: function(t) {
                            var e = this.countryList.children(".highlight").first()
                              , i = t == a ? e.prev() : e.next();
                            i.length && (i.hasClass("divider") && (i = t == a ? i.prev() : i.next()),
                            this._highlightListItem(i),
                            this._scrollTo(i))
                        },
                        _handleEnterKey: function() {
                            var t = this.countryList.children(".highlight").first();
                            t.length && this._selectListItem(t)
                        },
                        _filterCountries: function(e) {
                            var i = this.countryListItems.filter((function() {
                                return 0 === t(this).text().toUpperCase().indexOf(e) && !t(this).hasClass("preferred")
                            }
                            ));
                            if (i.length) {
                                var n, s = i.filter(".highlight").first();
                                n = s && s.next() && 0 === s.next().text().toUpperCase().indexOf(e) ? s.next() : i.first(),
                                this._highlightListItem(n),
                                this._scrollTo(n)
                            }
                        },
                        _updateFlagFromInputVal: function() {
                            var e = this
                              , i = this.countryInput.val().replace(/(?=[() ])/g, "\\");
                            if (i) {
                                var n = []
                                  , s = new RegExp(i,"i");
                                if (i.length <= 2)
                                    for (var o = 0; o < this.countries.length; o++)
                                        this.countries[o].iso2.match(s) && n.push(this.countries[o].iso2);
                                if (0 == n.length)
                                    for (o = 0; o < this.countries.length; o++)
                                        this.countries[o].name.match(s) && n.push(this.countries[o].iso2);
                                var r = !1;
                                return t.each(n, (function(t, i) {
                                    e.selectedFlagInner.hasClass(i) && (r = !0)
                                }
                                )),
                                r || (this._selectFlag(n[0]),
                                this.countryCodeInput.val(n[0]).trigger("change")),
                                !0
                            }
                            return !1
                        },
                        _highlightListItem: function(t) {
                            this.countryListItems.removeClass("highlight"),
                            t.addClass("highlight")
                        },
                        _getCountryData: function(t, e) {
                            for (var i = e ? g : this.countries, n = 0; n < i.length; n++)
                                if (i[n].iso2 == t)
                                    return i[n];
                            return null
                        },
                        _selectFlag: function(t) {
                            if (!t)
                                return !1;
                            this.selectedFlagInner.attr("class", "flag " + t);
                            var e = this._getCountryData(t);
                            this.selectedFlagInner.parent().attr("title", e.name);
                            var i = this.countryListItems.children(".flag." + t).first().parent();
                            this.countryListItems.removeClass("active"),
                            i.addClass("active")
                        },
                        _selectListItem: function(t) {
                            var e = t.attr("data-country-code");
                            this._selectFlag(e),
                            this._closeDropdown(),
                            this._updateName(e),
                            this.countryInput.trigger("change"),
                            this.countryCodeInput.trigger("change"),
                            this._focus()
                        },
                        _closeDropdown: function() {
                            this.countryList.addClass("hide"),
                            this.selectedFlagInner.parent().children(".arrow").removeClass("up"),
                            t(i).off("keydown" + this.ns),
                            t("html").off("click" + this.ns),
                            this.countryList.off(this.ns),
                            this.typedLetters = ""
                        },
                        _scrollTo: function(t) {
                            if (t && t.offset()) {
                                var e = this.countryList
                                  , i = e.height()
                                  , n = e.offset().top
                                  , s = n + i
                                  , o = t.outerHeight()
                                  , r = t.offset().top
                                  , a = r + o
                                  , l = r - n + e.scrollTop();
                                if (r < n)
                                    e.scrollTop(l);
                                else if (a > s) {
                                    var d = i - o;
                                    e.scrollTop(l - d)
                                }
                            }
                        },
                        _updateName: function(t) {
                            this.countryCodeInput.val(t).trigger("change"),
                            this.countryInput.val(this._getCountryData(t).name)
                        },
                        handleAutoCountry: function() {
                            "auto" === this.options.initialCountry && (this.defaultCountry = t.fn[s].autoCountry,
                            this.countryInput.val() || this.selectCountry(this.defaultCountry),
                            this.autoCountryDeferred.resolve())
                        },
                        getSelectedCountryData: function() {
                            var t = this.selectedFlagInner.attr("class").split(" ")[1];
                            return this._getCountryData(t)
                        },
                        selectCountry: function(t) {
                            t = t.toLowerCase(),
                            this.selectedFlagInner.hasClass(t) || (this._selectFlag(t),
                            this._updateName(t))
                        },
                        setCountry: function(t) {
                            this.countryInput.val(t),
                            this._updateFlagFromInputVal()
                        },
                        destroy: function() {
                            this.countryInput.off(this.ns),
                            this.selectedFlagInner.parent().off(this.ns),
                            this.countryInput.parent().before(this.countryInput).remove()
                        }
                    },
                    t.fn[s] = function(e) {
                        var i, o = arguments;
                        return e === n || "object" == typeof e ? this.each((function() {
                            t.data(this, "plugin_" + s) || t.data(this, "plugin_" + s, new m(this,e))
                        }
                        )) : "string" == typeof e && "_" !== e[0] && "init" !== e ? (this.each((function() {
                            var n = t.data(this, "plugin_" + s);
                            n instanceof m && "function" == typeof n[e] && (i = n[e].apply(n, Array.prototype.slice.call(o, 1))),
                            "destroy" === e && t.data(this, "plugin_" + s, null)
                        }
                        )),
                        i !== n ? i : this) : void 0
                    }
                    ,
                    t.fn[s].getCountryData = function() {
                        return g
                    }
                    ,
                    t.fn[s].setCountryData = function(t) {
                        g = t
                    }
                    ;
                    var g = t.each([{
                        n: "Afghanistan (‫افغانستان‬‎)",
                        i: "af"
                    }, {
                        n: "Åland Islands (Åland)",
                        i: "ax"
                    }, {
                        n: "Albania (Shqipëri)",
                        i: "al"
                    }, {
                        n: "Algeria (‫الجزائر‬‎)",
                        i: "dz"
                    }, {
                        n: "American Samoa",
                        i: "as"
                    }, {
                        n: "Andorra",
                        i: "ad"
                    }, {
                        n: "Angola",
                        i: "ao"
                    }, {
                        n: "Anguilla",
                        i: "ai"
                    }, {
                        n: "Antarctica",
                        i: "aq"
                    }, {
                        n: "Antigua and Barbuda",
                        i: "ag"
                    }, {
                        n: "Argentina",
                        i: "ar"
                    }, {
                        n: "Armenia (Հայաստան)",
                        i: "am"
                    }, {
                        n: "Aruba",
                        i: "aw"
                    }, {
                        n: "Australia",
                        i: "au"
                    }, {
                        n: "Austria (Österreich)",
                        i: "at"
                    }, {
                        n: "Azerbaijan (Azərbaycan)",
                        i: "az"
                    }, {
                        n: "Bahamas",
                        i: "bs"
                    }, {
                        n: "Bahrain (‫البحرين‬‎)",
                        i: "bh"
                    }, {
                        n: "Bangladesh (বাংলাদেশ)",
                        i: "bd"
                    }, {
                        n: "Barbados",
                        i: "bb"
                    }, {
                        n: "Belarus (Беларусь)",
                        i: "by"
                    }, {
                        n: "Belgium (België)",
                        i: "be"
                    }, {
                        n: "Belize",
                        i: "bz"
                    }, {
                        n: "Benin (Bénin)",
                        i: "bj"
                    }, {
                        n: "Bermuda",
                        i: "bm"
                    }, {
                        n: "Bhutan (འབྲུག)",
                        i: "bt"
                    }, {
                        n: "Bolivia",
                        i: "bo"
                    }, {
                        n: "Bosnia and Herzegovina (Босна и Херцеговина)",
                        i: "ba"
                    }, {
                        n: "Botswana",
                        i: "bw"
                    }, {
                        n: "Bouvet Island (Bouvetøya)",
                        i: "bv"
                    }, {
                        n: "Brazil (Brasil)",
                        i: "br"
                    }, {
                        n: "British Indian Ocean Territory",
                        i: "io"
                    }, {
                        n: "British Virgin Islands",
                        i: "vg"
                    }, {
                        n: "Brunei",
                        i: "bn"
                    }, {
                        n: "Bulgaria (България)",
                        i: "bg"
                    }, {
                        n: "Burkina Faso",
                        i: "bf"
                    }, {
                        n: "Burundi (Uburundi)",
                        i: "bi"
                    }, {
                        n: "Cambodia (កម្ពុជា)",
                        i: "kh"
                    }, {
                        n: "Cameroon (Cameroun)",
                        i: "cm"
                    }, {
                        n: "Canada",
                        i: "ca"
                    }, {
                        n: "Cape Verde (Kabu Verdi)",
                        i: "cv"
                    }, {
                        n: "Caribbean Netherlands",
                        i: "bq"
                    }, {
                        n: "Cayman Islands",
                        i: "ky"
                    }, {
                        n: "Central African Republic (République Centrafricaine)",
                        i: "cf"
                    }, {
                        n: "Chad (Tchad)",
                        i: "td"
                    }, {
                        n: "Chile",
                        i: "cl"
                    }, {
                        n: "China (中国)",
                        i: "cn"
                    }, {
                        n: "Christmas Island",
                        i: "cx"
                    }, {
                        n: "Cocos (Keeling) Islands (Kepulauan Cocos (Keeling))",
                        i: "cc"
                    }, {
                        n: "Colombia",
                        i: "co"
                    }, {
                        n: "Comoros (‫جزر القمر‬‎)",
                        i: "km"
                    }, {
                        n: "Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)",
                        i: "cd"
                    }, {
                        n: "Congo (Republic) (Congo-Brazzaville)",
                        i: "cg"
                    }, {
                        n: "Cook Islands",
                        i: "ck"
                    }, {
                        n: "Costa Rica",
                        i: "cr"
                    }, {
                        n: "Côte d’Ivoire",
                        i: "ci"
                    }, {
                        n: "Croatia (Hrvatska)",
                        i: "hr"
                    }, {
                        n: "Cuba",
                        i: "cu"
                    }, {
                        n: "Curaçao",
                        i: "cw"
                    }, {
                        n: "Cyprus (Κύπρος)",
                        i: "cy"
                    }, {
                        n: "Czech Republic (Česká republika)",
                        i: "cz"
                    }, {
                        n: "Denmark (Danmark)",
                        i: "dk"
                    }, {
                        n: "Djibouti",
                        i: "dj"
                    }, {
                        n: "Dominica",
                        i: "dm"
                    }, {
                        n: "Dominican Republic (República Dominicana)",
                        i: "do"
                    }, {
                        n: "Ecuador",
                        i: "ec"
                    }, {
                        n: "Egypt (‫مصر‬‎)",
                        i: "eg"
                    }, {
                        n: "El Salvador",
                        i: "sv"
                    }, {
                        n: "Equatorial Guinea (Guinea Ecuatorial)",
                        i: "gq"
                    }, {
                        n: "Eritrea",
                        i: "er"
                    }, {
                        n: "Estonia (Eesti)",
                        i: "ee"
                    }, {
                        n: "Ethiopia",
                        i: "et"
                    }, {
                        n: "Falkland Islands (Islas Malvinas)",
                        i: "fk"
                    }, {
                        n: "Faroe Islands (Føroyar)",
                        i: "fo"
                    }, {
                        n: "Fiji",
                        i: "fj"
                    }, {
                        n: "Finland (Suomi)",
                        i: "fi"
                    }, {
                        n: "France",
                        i: "fr"
                    }, {
                        n: "French Guiana (Guyane française)",
                        i: "gf"
                    }, {
                        n: "French Polynesia (Polynésie française)",
                        i: "pf"
                    }, {
                        n: "French Southern Territories (Terres australes françaises)",
                        i: "tf"
                    }, {
                        n: "Gabon",
                        i: "ga"
                    }, {
                        n: "Gambia",
                        i: "gm"
                    }, {
                        n: "Georgia (საქართველო)",
                        i: "ge"
                    }, {
                        n: "Germany (Deutschland)",
                        i: "de"
                    }, {
                        n: "Ghana (Gaana)",
                        i: "gh"
                    }, {
                        n: "Gibraltar",
                        i: "gi"
                    }, {
                        n: "Greece (Ελλάδα)",
                        i: "gr"
                    }, {
                        n: "Greenland (Kalaallit Nunaat)",
                        i: "gl"
                    }, {
                        n: "Grenada",
                        i: "gd"
                    }, {
                        n: "Guadeloupe",
                        i: "gp"
                    }, {
                        n: "Guam",
                        i: "gu"
                    }, {
                        n: "Guatemala",
                        i: "gt"
                    }, {
                        n: "Guernsey",
                        i: "gg"
                    }, {
                        n: "Guinea (Guinée)",
                        i: "gn"
                    }, {
                        n: "Guinea-Bissau (Guiné Bissau)",
                        i: "gw"
                    }, {
                        n: "Guyana",
                        i: "gy"
                    }, {
                        n: "Haiti",
                        i: "ht"
                    }, {
                        n: "Heard Island and Mcdonald Islands",
                        i: "hm"
                    }, {
                        n: "Honduras",
                        i: "hn"
                    }, {
                        n: "Hong Kong (香港)",
                        i: "hk"
                    }, {
                        n: "Hungary (Magyarország)",
                        i: "hu"
                    }, {
                        n: "Iceland (Ísland)",
                        i: "is"
                    }, {
                        n: "India (भारत)",
                        i: "in"
                    }, {
                        n: "Indonesia",
                        i: "id"
                    }, {
                        n: "Iran (‫ایران‬‎)",
                        i: "ir"
                    }, {
                        n: "Iraq (‫العراق‬‎)",
                        i: "iq"
                    }, {
                        n: "Ireland",
                        i: "ie"
                    }, {
                        n: "Isle of Man",
                        i: "im"
                    }, {
                        n: "Israel (‫ישראל‬‎)",
                        i: "il"
                    }, {
                        n: "Italy (Italia)",
                        i: "it"
                    }, {
                        n: "Jamaica",
                        i: "jm"
                    }, {
                        n: "Japan (日本)",
                        i: "jp"
                    }, {
                        n: "Jersey",
                        i: "je"
                    }, {
                        n: "Jordan (‫الأردن‬‎)",
                        i: "jo"
                    }, {
                        n: "Kazakhstan (Казахстан)",
                        i: "kz"
                    }, {
                        n: "Kenya",
                        i: "ke"
                    }, {
                        n: "Kiribati",
                        i: "ki"
                    }, {
                        n: "Kosovo (Kosovë)",
                        i: "xk"
                    }, {
                        n: "Kuwait (‫الكويت‬‎)",
                        i: "kw"
                    }, {
                        n: "Kyrgyzstan (Кыргызстан)",
                        i: "kg"
                    }, {
                        n: "Laos (ລາວ)",
                        i: "la"
                    }, {
                        n: "Latvia (Latvija)",
                        i: "lv"
                    }, {
                        n: "Lebanon (‫لبنان‬‎)",
                        i: "lb"
                    }, {
                        n: "Lesotho",
                        i: "ls"
                    }, {
                        n: "Liberia",
                        i: "lr"
                    }, {
                        n: "Libya (‫ليبيا‬‎)",
                        i: "ly"
                    }, {
                        n: "Liechtenstein",
                        i: "li"
                    }, {
                        n: "Lithuania (Lietuva)",
                        i: "lt"
                    }, {
                        n: "Luxembourg",
                        i: "lu"
                    }, {
                        n: "Macau (澳門)",
                        i: "mo"
                    }, {
                        n: "Macedonia (FYROM) (Македонија)",
                        i: "mk"
                    }, {
                        n: "Madagascar (Madagasikara)",
                        i: "mg"
                    }, {
                        n: "Malawi",
                        i: "mw"
                    }, {
                        n: "Malaysia",
                        i: "my"
                    }, {
                        n: "Maldives",
                        i: "mv"
                    }, {
                        n: "Mali",
                        i: "ml"
                    }, {
                        n: "Malta",
                        i: "mt"
                    }, {
                        n: "Marshall Islands",
                        i: "mh"
                    }, {
                        n: "Martinique",
                        i: "mq"
                    }, {
                        n: "Mauritania (‫موريتانيا‬‎)",
                        i: "mr"
                    }, {
                        n: "Mauritius (Moris)",
                        i: "mu"
                    }, {
                        n: "Mayotte",
                        i: "yt"
                    }, {
                        n: "Mexico (México)",
                        i: "mx"
                    }, {
                        n: "Micronesia",
                        i: "fm"
                    }, {
                        n: "Moldova (Republica Moldova)",
                        i: "md"
                    }, {
                        n: "Monaco",
                        i: "mc"
                    }, {
                        n: "Mongolia (Монгол)",
                        i: "mn"
                    }, {
                        n: "Montenegro (Crna Gora)",
                        i: "me"
                    }, {
                        n: "Montserrat",
                        i: "ms"
                    }, {
                        n: "Morocco (‫المغرب‬‎)",
                        i: "ma"
                    }, {
                        n: "Mozambique (Moçambique)",
                        i: "mz"
                    }, {
                        n: "Myanmar (Burma) (မြန်မာ)",
                        i: "mm"
                    }, {
                        n: "Namibia (Namibië)",
                        i: "na"
                    }, {
                        n: "Nauru",
                        i: "nr"
                    }, {
                        n: "Nepal (नेपाल)",
                        i: "np"
                    }, {
                        n: "Netherlands (Nederland)",
                        i: "nl"
                    }, {
                        n: "New Caledonia (Nouvelle-Calédonie)",
                        i: "nc"
                    }, {
                        n: "New Zealand",
                        i: "nz"
                    }, {
                        n: "Nicaragua",
                        i: "ni"
                    }, {
                        n: "Niger (Nijar)",
                        i: "ne"
                    }, {
                        n: "Nigeria",
                        i: "ng"
                    }, {
                        n: "Niue",
                        i: "nu"
                    }, {
                        n: "Norfolk Island",
                        i: "nf"
                    }, {
                        n: "North Korea (조선 민주주의 인민 공화국)",
                        i: "kp"
                    }, {
                        n: "Northern Mariana Islands",
                        i: "mp"
                    }, {
                        n: "Norway (Norge)",
                        i: "no"
                    }, {
                        n: "Oman (‫عُمان‬‎)",
                        i: "om"
                    }, {
                        n: "Pakistan (‫پاکستان‬‎)",
                        i: "pk"
                    }, {
                        n: "Palau",
                        i: "pw"
                    }, {
                        n: "Palestine (‫فلسطين‬‎)",
                        i: "ps"
                    }, {
                        n: "Panama (Panamá)",
                        i: "pa"
                    }, {
                        n: "Papua New Guinea",
                        i: "pg"
                    }, {
                        n: "Paraguay",
                        i: "py"
                    }, {
                        n: "Peru (Perú)",
                        i: "pe"
                    }, {
                        n: "Philippines",
                        i: "ph"
                    }, {
                        n: "Pitcairn Islands",
                        i: "pn"
                    }, {
                        n: "Poland (Polska)",
                        i: "pl"
                    }, {
                        n: "Portugal",
                        i: "pt"
                    }, {
                        n: "Puerto Rico",
                        i: "pr"
                    }, {
                        n: "Qatar (‫قطر‬‎)",
                        i: "qa"
                    }, {
                        n: "Réunion (La Réunion)",
                        i: "re"
                    }, {
                        n: "Romania (România)",
                        i: "ro"
                    }, {
                        n: "Russia (Россия)",
                        i: "ru"
                    }, {
                        n: "Rwanda",
                        i: "rw"
                    }, {
                        n: "Saint Barthélemy (Saint-Barthélemy)",
                        i: "bl"
                    }, {
                        n: "Saint Helena",
                        i: "sh"
                    }, {
                        n: "Saint Kitts and Nevis",
                        i: "kn"
                    }, {
                        n: "Saint Lucia",
                        i: "lc"
                    }, {
                        n: "Saint Martin (Saint-Martin (partie française))",
                        i: "mf"
                    }, {
                        n: "Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)",
                        i: "pm"
                    }, {
                        n: "Saint Vincent and the Grenadines",
                        i: "vc"
                    }, {
                        n: "Samoa",
                        i: "ws"
                    }, {
                        n: "San Marino",
                        i: "sm"
                    }, {
                        n: "São Tomé and Príncipe (São Tomé e Príncipe)",
                        i: "st"
                    }, {
                        n: "Saudi Arabia (‫المملكة العربية السعودية‬‎)",
                        i: "sa"
                    }, {
                        n: "Senegal (Sénégal)",
                        i: "sn"
                    }, {
                        n: "Serbia (Србија)",
                        i: "rs"
                    }, {
                        n: "Seychelles",
                        i: "sc"
                    }, {
                        n: "Sierra Leone",
                        i: "sl"
                    }, {
                        n: "Singapore",
                        i: "sg"
                    }, {
                        n: "Sint Maarten",
                        i: "sx"
                    }, {
                        n: "Slovakia (Slovensko)",
                        i: "sk"
                    }, {
                        n: "Slovenia (Slovenija)",
                        i: "si"
                    }, {
                        n: "Solomon Islands",
                        i: "sb"
                    }, {
                        n: "Somalia (Soomaaliya)",
                        i: "so"
                    }, {
                        n: "South Africa",
                        i: "za"
                    }, {
                        n: "South Georgia & South Sandwich Islands",
                        i: "gs"
                    }, {
                        n: "South Korea (대한민국)",
                        i: "kr"
                    }, {
                        n: "South Sudan (‫جنوب السودان‬‎)",
                        i: "ss"
                    }, {
                        n: "Spain (España)",
                        i: "es"
                    }, {
                        n: "Sri Lanka (ශ්‍රී ලංකාව)",
                        i: "lk"
                    }, {
                        n: "Sudan (‫السودان‬‎)",
                        i: "sd"
                    }, {
                        n: "Suriname",
                        i: "sr"
                    }, {
                        n: "Svalbard and Jan Mayen (Svalbard og Jan Mayen)",
                        i: "sj"
                    }, {
                        n: "Swaziland",
                        i: "sz"
                    }, {
                        n: "Sweden (Sverige)",
                        i: "se"
                    }, {
                        n: "Switzerland (Schweiz)",
                        i: "ch"
                    }, {
                        n: "Syria (‫سوريا‬‎)",
                        i: "sy"
                    }, {
                        n: "Taiwan (台灣)",
                        i: "tw"
                    }, {
                        n: "Tajikistan",
                        i: "tj"
                    }, {
                        n: "Tanzania",
                        i: "tz"
                    }, {
                        n: "Thailand (ไทย)",
                        i: "th"
                    }, {
                        n: "Timor-Leste",
                        i: "tl"
                    }, {
                        n: "Togo",
                        i: "tg"
                    }, {
                        n: "Tokelau",
                        i: "tk"
                    }, {
                        n: "Tonga",
                        i: "to"
                    }, {
                        n: "Trinidad and Tobago",
                        i: "tt"
                    }, {
                        n: "Tunisia (‫تونس‬‎)",
                        i: "tn"
                    }, {
                        n: "Turkey (Türkiye)",
                        i: "tr"
                    }, {
                        n: "Turkmenistan",
                        i: "tm"
                    }, {
                        n: "Turks and Caicos Islands",
                        i: "tc"
                    }, {
                        n: "Tuvalu",
                        i: "tv"
                    }, {
                        n: "Uganda",
                        i: "ug"
                    }, {
                        n: "Ukraine (Україна)",
                        i: "ua"
                    }, {
                        n: "United Arab Emirates (‫الإمارات العربية المتحدة‬‎)",
                        i: "ae"
                    }, {
                        n: "United Kingdom",
                        i: "gb"
                    }, {
                        n: "United States",
                        i: "us"
                    }, {
                        n: "U.S. Minor Outlying Islands",
                        i: "um"
                    }, {
                        n: "U.S. Virgin Islands",
                        i: "vi"
                    }, {
                        n: "Uruguay",
                        i: "uy"
                    }, {
                        n: "Uzbekistan (Oʻzbekiston)",
                        i: "uz"
                    }, {
                        n: "Vanuatu",
                        i: "vu"
                    }, {
                        n: "Vatican City (Città del Vaticano)",
                        i: "va"
                    }, {
                        n: "Venezuela",
                        i: "ve"
                    }, {
                        n: "Vietnam (Việt Nam)",
                        i: "vn"
                    }, {
                        n: "Wallis and Futuna",
                        i: "wf"
                    }, {
                        n: "Western Sahara (‫الصحراء الغربية‬‎)",
                        i: "eh"
                    }, {
                        n: "Yemen (‫اليمن‬‎)",
                        i: "ye"
                    }, {
                        n: "Zambia",
                        i: "zm"
                    }, {
                        n: "Zimbabwe",
                        i: "zw"
                    }], (function(t, e) {
                        e.name = e.n,
                        e.iso2 = e.i,
                        delete e.n,
                        delete e.i
                    }
                    ))
                }(t, window, document)
            }
            .apply(e, n)) || (t.exports = s)
        }
        ,
        960: (t, e, i) => {
            var n, s, o;
            s = [i(669)],
            void 0 === (o = "function" == typeof (n = function(t) {
                t.extend(t.fn, {
                    validate: function(e) {
                        if (this.length) {
                            var i = t.data(this[0], "validator");
                            return i || (this.attr("novalidate", "novalidate"),
                            i = new t.validator(e,this[0]),
                            t.data(this[0], "validator", i),
                            i.settings.onsubmit && (this.on("click.validate", ":submit", (function(e) {
                                i.submitButton = e.currentTarget,
                                t(this).hasClass("cancel") && (i.cancelSubmit = !0),
                                void 0 !== t(this).attr("formnovalidate") && (i.cancelSubmit = !0)
                            }
                            )),
                            this.on("submit.validate", (function(e) {
                                function n() {
                                    var n, s;
                                    return i.submitButton && (i.settings.submitHandler || i.formSubmitted) && (n = t("<input type='hidden'/>").attr("name", i.submitButton.name).val(t(i.submitButton).val()).appendTo(i.currentForm)),
                                    !(i.settings.submitHandler && !i.settings.debug) || (s = i.settings.submitHandler.call(i, i.currentForm, e),
                                    n && n.remove(),
                                    void 0 !== s && s)
                                }
                                return i.settings.debug && e.preventDefault(),
                                i.cancelSubmit ? (i.cancelSubmit = !1,
                                n()) : i.form() ? i.pendingRequest ? (i.formSubmitted = !0,
                                !1) : n() : (i.focusInvalid(),
                                !1)
                            }
                            ))),
                            i)
                        }
                        e && e.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing.")
                    },
                    valid: function() {
                        var e, i, n;
                        return t(this[0]).is("form") ? e = this.validate().form() : (n = [],
                        e = !0,
                        i = t(this[0].form).validate(),
                        this.each((function() {
                            (e = i.element(this) && e) || (n = n.concat(i.errorList))
                        }
                        )),
                        i.errorList = n),
                        e
                    },
                    rules: function(e, i) {
                        var n, s, o, r, a, l, d = this[0], c = void 0 !== this.attr("contenteditable") && "false" !== this.attr("contenteditable");
                        if (null != d && (!d.form && c && (d.form = this.closest("form")[0],
                        d.name = this.attr("name")),
                        null != d.form)) {
                            if (e)
                                switch (s = (n = t.data(d.form, "validator").settings).rules,
                                o = t.validator.staticRules(d),
                                e) {
                                case "add":
                                    t.extend(o, t.validator.normalizeRule(i)),
                                    delete o.messages,
                                    s[d.name] = o,
                                    i.messages && (n.messages[d.name] = t.extend(n.messages[d.name], i.messages));
                                    break;
                                case "remove":
                                    return i ? (l = {},
                                    t.each(i.split(/\s/), (function(t, e) {
                                        l[e] = o[e],
                                        delete o[e]
                                    }
                                    )),
                                    l) : (delete s[d.name],
                                    o)
                                }
                            return (r = t.validator.normalizeRules(t.extend({}, t.validator.classRules(d), t.validator.attributeRules(d), t.validator.dataRules(d), t.validator.staticRules(d)), d)).required && (a = r.required,
                            delete r.required,
                            r = t.extend({
                                required: a
                            }, r)),
                            r.remote && (a = r.remote,
                            delete r.remote,
                            r = t.extend(r, {
                                remote: a
                            })),
                            r
                        }
                    }
                });
                var e, i = function(t) {
                    return t.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "")
                };
                t.extend(t.expr.pseudos || t.expr[":"], {
                    blank: function(e) {
                        return !i("" + t(e).val())
                    },
                    filled: function(e) {
                        var n = t(e).val();
                        return null !== n && !!i("" + n)
                    },
                    unchecked: function(e) {
                        return !t(e).prop("checked")
                    }
                }),
                t.validator = function(e, i) {
                    this.settings = t.extend(!0, {}, t.validator.defaults, e),
                    this.currentForm = i,
                    this.init()
                }
                ,
                t.validator.format = function(e, i) {
                    return 1 === arguments.length ? function() {
                        var i = t.makeArray(arguments);
                        return i.unshift(e),
                        t.validator.format.apply(this, i)
                    }
                    : (void 0 === i || (arguments.length > 2 && i.constructor !== Array && (i = t.makeArray(arguments).slice(1)),
                    i.constructor !== Array && (i = [i]),
                    t.each(i, (function(t, i) {
                        e = e.replace(new RegExp("\\{" + t + "\\}","g"), (function() {
                            return i
                        }
                        ))
                    }
                    ))),
                    e)
                }
                ,
                t.extend(t.validator, {
                    defaults: {
                        messages: {},
                        groups: {},
                        rules: {},
                        errorClass: "error",
                        pendingClass: "pending",
                        validClass: "valid",
                        errorElement: "label",
                        focusCleanup: !1,
                        focusInvalid: !0,
                        errorContainer: t([]),
                        errorLabelContainer: t([]),
                        onsubmit: !0,
                        ignore: ":hidden",
                        ignoreTitle: !1,
                        onfocusin: function(t) {
                            this.lastActive = t,
                            this.settings.focusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, t, this.settings.errorClass, this.settings.validClass),
                            this.hideThese(this.errorsFor(t)))
                        },
                        onfocusout: function(t) {
                            this.checkable(t) || !(t.name in this.submitted) && this.optional(t) || this.element(t)
                        },
                        onkeyup: function(e, i) {
                            9 === i.which && "" === this.elementValue(e) || -1 !== t.inArray(i.keyCode, [16, 17, 18, 20, 35, 36, 37, 38, 39, 40, 45, 144, 225]) || (e.name in this.submitted || e.name in this.invalid) && this.element(e)
                        },
                        onclick: function(t) {
                            t.name in this.submitted ? this.element(t) : t.parentNode.name in this.submitted && this.element(t.parentNode)
                        },
                        highlight: function(e, i, n) {
                            "radio" === e.type ? this.findByName(e.name).addClass(i).removeClass(n) : t(e).addClass(i).removeClass(n)
                        },
                        unhighlight: function(e, i, n) {
                            "radio" === e.type ? this.findByName(e.name).removeClass(i).addClass(n) : t(e).removeClass(i).addClass(n)
                        }
                    },
                    setDefaults: function(e) {
                        t.extend(t.validator.defaults, e)
                    },
                    messages: {
                        required: "This field is required.",
                        remote: "Please fix this field.",
                        email: "Please enter a valid email address.",
                        url: "Please enter a valid URL.",
                        date: "Please enter a valid date.",
                        dateISO: "Please enter a valid date (ISO).",
                        number: "Please enter a valid number.",
                        digits: "Please enter only digits.",
                        equalTo: "Please enter the same value again.",
                        maxlength: t.validator.format("Please enter no more than {0} characters."),
                        minlength: t.validator.format("Please enter at least {0} characters."),
                        rangelength: t.validator.format("Please enter a value between {0} and {1} characters long."),
                        range: t.validator.format("Please enter a value between {0} and {1}."),
                        max: t.validator.format("Please enter a value less than or equal to {0}."),
                        min: t.validator.format("Please enter a value greater than or equal to {0}."),
                        step: t.validator.format("Please enter a multiple of {0}.")
                    },
                    autoCreateRanges: !1,
                    prototype: {
                        init: function() {
                            this.labelContainer = t(this.settings.errorLabelContainer),
                            this.errorContext = this.labelContainer.length && this.labelContainer || t(this.currentForm),
                            this.containers = t(this.settings.errorContainer).add(this.settings.errorLabelContainer),
                            this.submitted = {},
                            this.valueCache = {},
                            this.pendingRequest = 0,
                            this.pending = {},
                            this.invalid = {},
                            this.reset();
                            var e, i = this.currentForm, n = this.groups = {};
                            function s(e) {
                                var n = void 0 !== t(this).attr("contenteditable") && "false" !== t(this).attr("contenteditable");
                                if (!this.form && n && (this.form = t(this).closest("form")[0],
                                this.name = t(this).attr("name")),
                                i === this.form) {
                                    var s = t.data(this.form, "validator")
                                      , o = "on" + e.type.replace(/^validate/, "")
                                      , r = s.settings;
                                    r[o] && !t(this).is(r.ignore) && r[o].call(s, this, e)
                                }
                            }
                            t.each(this.settings.groups, (function(e, i) {
                                "string" == typeof i && (i = i.split(/\s/)),
                                t.each(i, (function(t, i) {
                                    n[i] = e
                                }
                                ))
                            }
                            )),
                            e = this.settings.rules,
                            t.each(e, (function(i, n) {
                                e[i] = t.validator.normalizeRule(n)
                            }
                            )),
                            t(this.currentForm).on("focusin.validate focusout.validate keyup.validate", ":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], [type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox'], [contenteditable], [type='button']", s).on("click.validate", "select, option, [type='radio'], [type='checkbox']", s),
                            this.settings.invalidHandler && t(this.currentForm).on("invalid-form.validate", this.settings.invalidHandler)
                        },
                        form: function() {
                            return this.checkForm(),
                            t.extend(this.submitted, this.errorMap),
                            this.invalid = t.extend({}, this.errorMap),
                            this.valid() || t(this.currentForm).triggerHandler("invalid-form", [this]),
                            this.showErrors(),
                            this.valid()
                        },
                        checkForm: function() {
                            this.prepareForm();
                            for (var t = 0, e = this.currentElements = this.elements(); e[t]; t++)
                                this.check(e[t]);
                            return this.valid()
                        },
                        element: function(e) {
                            var i, n, s = this.clean(e), o = this.validationTargetFor(s), r = this, a = !0;
                            return void 0 === o ? delete this.invalid[s.name] : (this.prepareElement(o),
                            this.currentElements = t(o),
                            (n = this.groups[o.name]) && t.each(this.groups, (function(t, e) {
                                e === n && t !== o.name && (s = r.validationTargetFor(r.clean(r.findByName(t)))) && s.name in r.invalid && (r.currentElements.push(s),
                                a = r.check(s) && a)
                            }
                            )),
                            i = !1 !== this.check(o),
                            a = a && i,
                            this.invalid[o.name] = !i,
                            this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)),
                            this.showErrors(),
                            t(e).attr("aria-invalid", !i)),
                            a
                        },
                        showErrors: function(e) {
                            if (e) {
                                var i = this;
                                t.extend(this.errorMap, e),
                                this.errorList = t.map(this.errorMap, (function(t, e) {
                                    return {
                                        message: t,
                                        element: i.findByName(e)[0]
                                    }
                                }
                                )),
                                this.successList = t.grep(this.successList, (function(t) {
                                    return !(t.name in e)
                                }
                                ))
                            }
                            this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
                        },
                        resetForm: function() {
                            t.fn.resetForm && t(this.currentForm).resetForm(),
                            this.invalid = {},
                            this.submitted = {},
                            this.prepareForm(),
                            this.hideErrors();
                            var e = this.elements().removeData("previousValue").removeAttr("aria-invalid");
                            this.resetElements(e)
                        },
                        resetElements: function(t) {
                            var e;
                            if (this.settings.unhighlight)
                                for (e = 0; t[e]; e++)
                                    this.settings.unhighlight.call(this, t[e], this.settings.errorClass, ""),
                                    this.findByName(t[e].name).removeClass(this.settings.validClass);
                            else
                                t.removeClass(this.settings.errorClass).removeClass(this.settings.validClass)
                        },
                        numberOfInvalids: function() {
                            return this.objectLength(this.invalid)
                        },
                        objectLength: function(t) {
                            var e, i = 0;
                            for (e in t)
                                void 0 !== t[e] && null !== t[e] && !1 !== t[e] && i++;
                            return i
                        },
                        hideErrors: function() {
                            this.hideThese(this.toHide)
                        },
                        hideThese: function(t) {
                            t.not(this.containers).text(""),
                            this.addWrapper(t).hide()
                        },
                        valid: function() {
                            return 0 === this.size()
                        },
                        size: function() {
                            return this.errorList.length
                        },
                        focusInvalid: function() {
                            if (this.settings.focusInvalid)
                                try {
                                    t(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").trigger("focus").trigger("focusin")
                                } catch (t) {}
                        },
                        findLastActive: function() {
                            var e = this.lastActive;
                            return e && 1 === t.grep(this.errorList, (function(t) {
                                return t.element.name === e.name
                            }
                            )).length && e
                        },
                        elements: function() {
                            var e = this
                              , i = {};
                            return t(this.currentForm).find("input, select, textarea, [contenteditable]").not(":submit, :reset, :image, :disabled").not(this.settings.ignore).filter((function() {
                                var n = this.name || t(this).attr("name")
                                  , s = void 0 !== t(this).attr("contenteditable") && "false" !== t(this).attr("contenteditable");
                                return !n && e.settings.debug && window.console && console.error("%o has no name assigned", this),
                                s && (this.form = t(this).closest("form")[0],
                                this.name = n),
                                !(this.form !== e.currentForm || n in i || !e.objectLength(t(this).rules()) || (i[n] = !0,
                                0))
                            }
                            ))
                        },
                        clean: function(e) {
                            return t(e)[0]
                        },
                        errors: function() {
                            var e = this.settings.errorClass.split(" ").join(".");
                            return t(this.settings.errorElement + "." + e, this.errorContext)
                        },
                        resetInternals: function() {
                            this.successList = [],
                            this.errorList = [],
                            this.errorMap = {},
                            this.toShow = t([]),
                            this.toHide = t([])
                        },
                        reset: function() {
                            this.resetInternals(),
                            this.currentElements = t([])
                        },
                        prepareForm: function() {
                            this.reset(),
                            this.toHide = this.errors().add(this.containers)
                        },
                        prepareElement: function(t) {
                            this.reset(),
                            this.toHide = this.errorsFor(t)
                        },
                        elementValue: function(e) {
                            var i, n, s = t(e), o = e.type, r = void 0 !== s.attr("contenteditable") && "false" !== s.attr("contenteditable");
                            return "radio" === o || "checkbox" === o ? this.findByName(e.name).filter(":checked").val() : "number" === o && void 0 !== e.validity ? e.validity.badInput ? "NaN" : s.val() : (i = r ? s.text() : s.val(),
                            "file" === o ? "C:\\fakepath\\" === i.substr(0, 12) ? i.substr(12) : (n = i.lastIndexOf("/")) >= 0 || (n = i.lastIndexOf("\\")) >= 0 ? i.substr(n + 1) : i : "string" == typeof i ? i.replace(/\r/g, "") : i)
                        },
                        check: function(e) {
                            e = this.validationTargetFor(this.clean(e));
                            var i, n, s, o, r = t(e).rules(), a = t.map(r, (function(t, e) {
                                return e
                            }
                            )).length, l = !1, d = this.elementValue(e);
                            for (n in this.abortRequest(e),
                            "function" == typeof r.normalizer ? o = r.normalizer : "function" == typeof this.settings.normalizer && (o = this.settings.normalizer),
                            o && (d = o.call(e, d),
                            delete r.normalizer),
                            r) {
                                s = {
                                    method: n,
                                    parameters: r[n]
                                };
                                try {
                                    if ("dependency-mismatch" === (i = t.validator.methods[n].call(this, d, e, s.parameters)) && 1 === a) {
                                        l = !0;
                                        continue
                                    }
                                    if (l = !1,
                                    "pending" === i)
                                        return void (this.toHide = this.toHide.not(this.errorsFor(e)));
                                    if (!i)
                                        return this.formatAndAdd(e, s),
                                        !1
                                } catch (t) {
                                    throw this.settings.debug && window.console && console.log("Exception occurred when checking element " + e.id + ", check the '" + s.method + "' method.", t),
                                    t instanceof TypeError && (t.message += ".  Exception occurred when checking element " + e.id + ", check the '" + s.method + "' method."),
                                    t
                                }
                            }
                            if (!l)
                                return this.objectLength(r) && this.successList.push(e),
                                !0
                        },
                        customDataMessage: function(e, i) {
                            return t(e).data("msg" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase()) || t(e).data("msg")
                        },
                        customMessage: function(t, e) {
                            var i = this.settings.messages[t];
                            return i && (i.constructor === String ? i : i[e])
                        },
                        findDefined: function() {
                            for (var t = 0; t < arguments.length; t++)
                                if (void 0 !== arguments[t])
                                    return arguments[t]
                        },
                        defaultMessage: function(e, i) {
                            "string" == typeof i && (i = {
                                method: i
                            });
                            var n = this.findDefined(this.customMessage(e.name, i.method), this.customDataMessage(e, i.method), !this.settings.ignoreTitle && e.title || void 0, t.validator.messages[i.method], "<strong>Warning: No message defined for " + e.name + "</strong>")
                              , s = /\$?\{(\d+)\}/g;
                            return "function" == typeof n ? n = n.call(this, i.parameters, e) : s.test(n) && (n = t.validator.format(n.replace(s, "{$1}"), i.parameters)),
                            n
                        },
                        formatAndAdd: function(t, e) {
                            var i = this.defaultMessage(t, e);
                            this.errorList.push({
                                message: i,
                                element: t,
                                method: e.method
                            }),
                            this.errorMap[t.name] = i,
                            this.submitted[t.name] = i
                        },
                        addWrapper: function(t) {
                            return this.settings.wrapper && (t = t.add(t.parent(this.settings.wrapper))),
                            t
                        },
                        defaultShowErrors: function() {
                            var t, e, i;
                            for (t = 0; this.errorList[t]; t++)
                                i = this.errorList[t],
                                this.settings.highlight && this.settings.highlight.call(this, i.element, this.settings.errorClass, this.settings.validClass),
                                this.showLabel(i.element, i.message);
                            if (this.errorList.length && (this.toShow = this.toShow.add(this.containers)),
                            this.settings.success)
                                for (t = 0; this.successList[t]; t++)
                                    this.showLabel(this.successList[t]);
                            if (this.settings.unhighlight)
                                for (t = 0,
                                e = this.validElements(); e[t]; t++)
                                    this.settings.unhighlight.call(this, e[t], this.settings.errorClass, this.settings.validClass);
                            this.toHide = this.toHide.not(this.toShow),
                            this.hideErrors(),
                            this.addWrapper(this.toShow).show()
                        },
                        validElements: function() {
                            return this.currentElements.not(this.invalidElements())
                        },
                        invalidElements: function() {
                            return t(this.errorList).map((function() {
                                return this.element
                            }
                            ))
                        },
                        showLabel: function(e, i) {
                            var n, s, o, r, a = this.errorsFor(e), l = this.idOrName(e), d = t(e).attr("aria-describedby");
                            a.length ? (a.removeClass(this.settings.validClass).addClass(this.settings.errorClass),
                            this.settings && this.settings.escapeHtml ? a.text(i || "") : a.html(i || "")) : (a = t("<" + this.settings.errorElement + ">").attr("id", l + "-error").addClass(this.settings.errorClass),
                            this.settings && this.settings.escapeHtml ? a.text(i || "") : a.html(i || ""),
                            n = a,
                            this.settings.wrapper && (n = a.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()),
                            this.labelContainer.length ? this.labelContainer.append(n) : this.settings.errorPlacement ? this.settings.errorPlacement.call(this, n, t(e)) : n.insertAfter(e),
                            a.is("label") ? a.attr("for", l) : 0 === a.parents("label[for='" + this.escapeCssMeta(l) + "']").length && (o = a.attr("id"),
                            d ? d.match(new RegExp("\\b" + this.escapeCssMeta(o) + "\\b")) || (d += " " + o) : d = o,
                            t(e).attr("aria-describedby", d),
                            (s = this.groups[e.name]) && (r = this,
                            t.each(r.groups, (function(e, i) {
                                i === s && t("[name='" + r.escapeCssMeta(e) + "']", r.currentForm).attr("aria-describedby", a.attr("id"))
                            }
                            ))))),
                            !i && this.settings.success && (a.text(""),
                            "string" == typeof this.settings.success ? a.addClass(this.settings.success) : this.settings.success(a, e)),
                            this.toShow = this.toShow.add(a)
                        },
                        errorsFor: function(e) {
                            var i = this.escapeCssMeta(this.idOrName(e))
                              , n = t(e).attr("aria-describedby")
                              , s = "label[for='" + i + "'], label[for='" + i + "'] *";
                            return n && (s = s + ", #" + this.escapeCssMeta(n).replace(/\s+/g, ", #")),
                            this.errors().filter(s)
                        },
                        escapeCssMeta: function(t) {
                            return void 0 === t ? "" : t.replace(/([\\!"#$%&'()*+,./:;<=>?@\[\]^`{|}~])/g, "\\$1")
                        },
                        idOrName: function(t) {
                            return this.groups[t.name] || (this.checkable(t) ? t.name : t.id || t.name)
                        },
                        validationTargetFor: function(e) {
                            return this.checkable(e) && (e = this.findByName(e.name)),
                            t(e).not(this.settings.ignore)[0]
                        },
                        checkable: function(t) {
                            return /radio|checkbox/i.test(t.type)
                        },
                        findByName: function(e) {
                            return t(this.currentForm).find("[name='" + this.escapeCssMeta(e) + "']")
                        },
                        getLength: function(e, i) {
                            switch (i.nodeName.toLowerCase()) {
                            case "select":
                                return t("option:selected", i).length;
                            case "input":
                                if (this.checkable(i))
                                    return this.findByName(i.name).filter(":checked").length
                            }
                            return e.length
                        },
                        depend: function(t, e) {
                            return !this.dependTypes[typeof t] || this.dependTypes[typeof t](t, e)
                        },
                        dependTypes: {
                            boolean: function(t) {
                                return t
                            },
                            string: function(e, i) {
                                return !!t(e, i.form).length
                            },
                            function: function(t, e) {
                                return t(e)
                            }
                        },
                        optional: function(e) {
                            var i = this.elementValue(e);
                            return !t.validator.methods.required.call(this, i, e) && "dependency-mismatch"
                        },
                        elementAjaxPort: function(t) {
                            return "validate" + t.name
                        },
                        startRequest: function(e) {
                            this.pending[e.name] || (this.pendingRequest++,
                            t(e).addClass(this.settings.pendingClass),
                            this.pending[e.name] = !0)
                        },
                        stopRequest: function(e, i) {
                            this.pendingRequest--,
                            this.pendingRequest < 0 && (this.pendingRequest = 0),
                            delete this.pending[e.name],
                            t(e).removeClass(this.settings.pendingClass),
                            i && 0 === this.pendingRequest && this.formSubmitted && this.form() && 0 === this.pendingRequest ? (t(this.currentForm).trigger("submit"),
                            this.submitButton && t("input:hidden[name='" + this.submitButton.name + "']", this.currentForm).remove(),
                            this.formSubmitted = !1) : !i && 0 === this.pendingRequest && this.formSubmitted && (t(this.currentForm).triggerHandler("invalid-form", [this]),
                            this.formSubmitted = !1)
                        },
                        abortRequest: function(e) {
                            var i;
                            this.pending[e.name] && (i = this.elementAjaxPort(e),
                            t.ajaxAbort(i),
                            this.pendingRequest--,
                            this.pendingRequest < 0 && (this.pendingRequest = 0),
                            delete this.pending[e.name],
                            t(e).removeClass(this.settings.pendingClass))
                        },
                        previousValue: function(e, i) {
                            return i = "string" == typeof i && i || "remote",
                            t.data(e, "previousValue") || t.data(e, "previousValue", {
                                old: null,
                                valid: !0,
                                message: this.defaultMessage(e, {
                                    method: i
                                })
                            })
                        },
                        destroy: function() {
                            this.resetForm(),
                            t(this.currentForm).off(".validate").removeData("validator").find(".validate-equalTo-blur").off(".validate-equalTo").removeClass("validate-equalTo-blur").find(".validate-lessThan-blur").off(".validate-lessThan").removeClass("validate-lessThan-blur").find(".validate-lessThanEqual-blur").off(".validate-lessThanEqual").removeClass("validate-lessThanEqual-blur").find(".validate-greaterThanEqual-blur").off(".validate-greaterThanEqual").removeClass("validate-greaterThanEqual-blur").find(".validate-greaterThan-blur").off(".validate-greaterThan").removeClass("validate-greaterThan-blur")
                        }
                    },
                    classRuleSettings: {
                        required: {
                            required: !0
                        },
                        email: {
                            email: !0
                        },
                        url: {
                            url: !0
                        },
                        date: {
                            date: !0
                        },
                        dateISO: {
                            dateISO: !0
                        },
                        number: {
                            number: !0
                        },
                        digits: {
                            digits: !0
                        },
                        creditcard: {
                            creditcard: !0
                        }
                    },
                    addClassRules: function(e, i) {
                        e.constructor === String ? this.classRuleSettings[e] = i : t.extend(this.classRuleSettings, e)
                    },
                    classRules: function(e) {
                        var i = {}
                          , n = t(e).attr("class");
                        return n && t.each(n.split(" "), (function() {
                            this in t.validator.classRuleSettings && t.extend(i, t.validator.classRuleSettings[this])
                        }
                        )),
                        i
                    },
                    normalizeAttributeRule: function(t, e, i, n) {
                        /min|max|step/.test(i) && (null === e || /number|range|text/.test(e)) && (n = Number(n),
                        isNaN(n) && (n = void 0)),
                        n || 0 === n ? t[i] = n : e === i && "range" !== e && (t["date" === e ? "dateISO" : i] = !0)
                    },
                    attributeRules: function(e) {
                        var i, n, s = {}, o = t(e), r = e.getAttribute("type");
                        for (i in t.validator.methods)
                            "required" === i ? ("" === (n = e.getAttribute(i)) && (n = !0),
                            n = !!n) : n = o.attr(i),
                            this.normalizeAttributeRule(s, r, i, n);
                        return s.maxlength && /-1|2147483647|524288/.test(s.maxlength) && delete s.maxlength,
                        s
                    },
                    dataRules: function(e) {
                        var i, n, s = {}, o = t(e), r = e.getAttribute("type");
                        for (i in t.validator.methods)
                            "" === (n = o.data("rule" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase())) && (n = !0),
                            this.normalizeAttributeRule(s, r, i, n);
                        return s
                    },
                    staticRules: function(e) {
                        var i = {}
                          , n = t.data(e.form, "validator");
                        return n.settings.rules && (i = t.validator.normalizeRule(n.settings.rules[e.name]) || {}),
                        i
                    },
                    normalizeRules: function(e, i) {
                        return t.each(e, (function(n, s) {
                            if (!1 !== s) {
                                if (s.param || s.depends) {
                                    var o = !0;
                                    switch (typeof s.depends) {
                                    case "string":
                                        o = !!t(s.depends, i.form).length;
                                        break;
                                    case "function":
                                        o = s.depends.call(i, i)
                                    }
                                    o ? e[n] = void 0 === s.param || s.param : (t.data(i.form, "validator").resetElements(t(i)),
                                    delete e[n])
                                }
                            } else
                                delete e[n]
                        }
                        )),
                        t.each(e, (function(t, n) {
                            e[t] = "function" == typeof n && "normalizer" !== t ? n(i) : n
                        }
                        )),
                        t.each(["minlength", "maxlength"], (function() {
                            e[this] && (e[this] = Number(e[this]))
                        }
                        )),
                        t.each(["rangelength", "range"], (function() {
                            var t;
                            e[this] && (Array.isArray(e[this]) ? e[this] = [Number(e[this][0]), Number(e[this][1])] : "string" == typeof e[this] && (t = e[this].replace(/[\[\]]/g, "").split(/[\s,]+/),
                            e[this] = [Number(t[0]), Number(t[1])]))
                        }
                        )),
                        t.validator.autoCreateRanges && (null != e.min && null != e.max && (e.range = [e.min, e.max],
                        delete e.min,
                        delete e.max),
                        null != e.minlength && null != e.maxlength && (e.rangelength = [e.minlength, e.maxlength],
                        delete e.minlength,
                        delete e.maxlength)),
                        e
                    },
                    normalizeRule: function(e) {
                        if ("string" == typeof e) {
                            var i = {};
                            t.each(e.split(/\s/), (function() {
                                i[this] = !0
                            }
                            )),
                            e = i
                        }
                        return e
                    },
                    addMethod: function(e, i, n) {
                        t.validator.methods[e] = i,
                        t.validator.messages[e] = void 0 !== n ? n : t.validator.messages[e],
                        i.length < 3 && t.validator.addClassRules(e, t.validator.normalizeRule(e))
                    },
                    methods: {
                        required: function(e, i, n) {
                            if (!this.depend(n, i))
                                return "dependency-mismatch";
                            if ("select" === i.nodeName.toLowerCase()) {
                                var s = t(i).val();
                                return s && s.length > 0
                            }
                            return this.checkable(i) ? this.getLength(e, i) > 0 : null != e && e.length > 0
                        },
                        email: function(t, e) {
                            return this.optional(e) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(t)
                        },
                        url: function(t, e) {
                            return this.optional(e) || /^(?:(?:(?:https?|ftp):)?\/\/)(?:(?:[^\]\[?\/<~#`!@$^&*()+=}|:";',>{ ]|%[0-9A-Fa-f]{2})+(?::(?:[^\]\[?\/<~#`!@$^&*()+=}|:";',>{ ]|%[0-9A-Fa-f]{2})*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\u00a1-\uffff][a-z0-9\u00a1-\uffff_-]{0,62})?[a-z0-9\u00a1-\uffff]\.)+(?:[a-z\u00a1-\uffff]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(t)
                        },
                        date: (e = !1,
                        function(t, i) {
                            return e || (e = !0,
                            this.settings.debug && window.console && console.warn("The `date` method is deprecated and will be removed in version '2.0.0'.\nPlease don't use it, since it relies on the Date constructor, which\nbehaves very differently across browsers and locales. Use `dateISO`\ninstead or one of the locale specific methods in `localizations/`\nand `additional-methods.js`.")),
                            this.optional(i) || !/Invalid|NaN/.test(new Date(t).toString())
                        }
                        ),
                        dateISO: function(t, e) {
                            return this.optional(e) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(t)
                        },
                        number: function(t, e) {
                            return this.optional(e) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(t)
                        },
                        digits: function(t, e) {
                            return this.optional(e) || /^\d+$/.test(t)
                        },
                        minlength: function(t, e, i) {
                            var n = Array.isArray(t) ? t.length : this.getLength(t, e);
                            return this.optional(e) || n >= i
                        },
                        maxlength: function(t, e, i) {
                            var n = Array.isArray(t) ? t.length : this.getLength(t, e);
                            return this.optional(e) || n <= i
                        },
                        rangelength: function(t, e, i) {
                            var n = Array.isArray(t) ? t.length : this.getLength(t, e);
                            return this.optional(e) || n >= i[0] && n <= i[1]
                        },
                        min: function(t, e, i) {
                            return this.optional(e) || t >= i
                        },
                        max: function(t, e, i) {
                            return this.optional(e) || t <= i
                        },
                        range: function(t, e, i) {
                            return this.optional(e) || t >= i[0] && t <= i[1]
                        },
                        step: function(e, i, n) {
                            var s, o = t(i).attr("type"), r = "Step attribute on input type " + o + " is not supported.", a = new RegExp("\\b" + o + "\\b"), l = function(t) {
                                var e = ("" + t).match(/(?:\.(\d+))?$/);
                                return e && e[1] ? e[1].length : 0
                            }, d = function(t) {
                                return Math.round(t * Math.pow(10, s))
                            }, c = !0;
                            if (o && !a.test(["text", "number", "range"].join()))
                                throw new Error(r);
                            return s = l(n),
                            (l(e) > s || d(e) % d(n) != 0) && (c = !1),
                            this.optional(i) || c
                        },
                        equalTo: function(e, i, n) {
                            var s = t(n);
                            return this.settings.onfocusout && s.not(".validate-equalTo-blur").length && s.addClass("validate-equalTo-blur").on("blur.validate-equalTo", (function() {
                                t(i).valid()
                            }
                            )),
                            e === s.val()
                        },
                        remote: function(e, i, n, s) {
                            if (this.optional(i))
                                return "dependency-mismatch";
                            s = "string" == typeof s && s || "remote";
                            var o, r, a, l = this.previousValue(i, s);
                            return this.settings.messages[i.name] || (this.settings.messages[i.name] = {}),
                            l.originalMessage = l.originalMessage || this.settings.messages[i.name][s],
                            this.settings.messages[i.name][s] = l.message,
                            n = "string" == typeof n && {
                                url: n
                            } || n,
                            a = t.param(t.extend({
                                data: e
                            }, n.data)),
                            l.old === a ? l.valid : (l.old = a,
                            o = this,
                            this.startRequest(i),
                            (r = {})[i.name] = e,
                            t.ajax(t.extend(!0, {
                                mode: "abort",
                                port: this.elementAjaxPort(i),
                                dataType: "json",
                                data: r,
                                context: o.currentForm,
                                success: function(t) {
                                    var n, r, a, d = !0 === t || "true" === t;
                                    o.settings.messages[i.name][s] = l.originalMessage,
                                    d ? (a = o.formSubmitted,
                                    o.toHide = o.errorsFor(i),
                                    o.formSubmitted = a,
                                    o.successList.push(i),
                                    o.invalid[i.name] = !1,
                                    o.showErrors()) : (n = {},
                                    r = t || o.defaultMessage(i, {
                                        method: s,
                                        parameters: e
                                    }),
                                    n[i.name] = l.message = r,
                                    o.invalid[i.name] = !0,
                                    o.showErrors(n)),
                                    l.valid = d,
                                    o.stopRequest(i, d)
                                }
                            }, n)),
                            "pending")
                        }
                    }
                });
                var n, s = {};
                return t.ajaxPrefilter ? t.ajaxPrefilter((function(e, i, n) {
                    var o = e.port;
                    "abort" === e.mode && (t.ajaxAbort(o),
                    s[o] = n)
                }
                )) : (n = t.ajax,
                t.ajax = function(e) {
                    var i = ("mode"in e ? e : t.ajaxSettings).mode
                      , o = ("port"in e ? e : t.ajaxSettings).port;
                    return "abort" === i ? (t.ajaxAbort(o),
                    s[o] = n.apply(this, arguments),
                    s[o]) : n.apply(this, arguments)
                }
                ),
                t.ajaxAbort = function(t) {
                    s[t] && (s[t].abort(),
                    delete s[t])
                }
                ,
                t
            }
            ) ? n.apply(e, s) : n) || (t.exports = o)
        }
        ,
        760: (t, e, i) => {
            var n, s, o;
            !function(r) {
                "use strict";
                s = [i(669)],
                n = function(t) {
                    var e, i = window.Slick || {};
                    (e = 0,
                    i = function(i, n) {
                        var s, o = this;
                        o.defaults = {
                            accessibility: !0,
                            adaptiveHeight: !1,
                            appendArrows: t(i),
                            appendDots: t(i),
                            arrows: !0,
                            asNavFor: null,
                            prevArrow: '<button class="slick-prev" aria-label="Previous" type="button">Previous</button>',
                            nextArrow: '<button class="slick-next" aria-label="Next" type="button">Next</button>',
                            autoplay: !1,
                            autoplaySpeed: 3e3,
                            centerMode: !1,
                            centerPadding: "50px",
                            cssEase: "ease",
                            customPaging: function(e, i) {
                                return t('<button type="button" />').text(i + 1)
                            },
                            dots: !1,
                            dotsClass: "slick-dots",
                            draggable: !0,
                            easing: "linear",
                            edgeFriction: .35,
                            fade: !1,
                            focusOnSelect: !1,
                            focusOnChange: !1,
                            infinite: !0,
                            initialSlide: 0,
                            lazyLoad: "ondemand",
                            mobileFirst: !1,
                            pauseOnHover: !0,
                            pauseOnFocus: !0,
                            pauseOnDotsHover: !1,
                            respondTo: "window",
                            responsive: null,
                            rows: 1,
                            rtl: !1,
                            slide: "",
                            slidesPerRow: 1,
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            speed: 500,
                            swipe: !0,
                            swipeToSlide: !1,
                            touchMove: !0,
                            touchThreshold: 5,
                            useCSS: !0,
                            useTransform: !0,
                            variableWidth: !1,
                            vertical: !1,
                            verticalSwiping: !1,
                            waitForAnimate: !0,
                            zIndex: 1e3
                        },
                        o.initials = {
                            animating: !1,
                            dragging: !1,
                            autoPlayTimer: null,
                            currentDirection: 0,
                            currentLeft: null,
                            currentSlide: 0,
                            direction: 1,
                            $dots: null,
                            listWidth: null,
                            listHeight: null,
                            loadIndex: 0,
                            $nextArrow: null,
                            $prevArrow: null,
                            scrolling: !1,
                            slideCount: null,
                            slideWidth: null,
                            $slideTrack: null,
                            $slides: null,
                            sliding: !1,
                            slideOffset: 0,
                            swipeLeft: null,
                            swiping: !1,
                            $list: null,
                            touchObject: {},
                            transformsEnabled: !1,
                            unslicked: !1
                        },
                        t.extend(o, o.initials),
                        o.activeBreakpoint = null,
                        o.animType = null,
                        o.animProp = null,
                        o.breakpoints = [],
                        o.breakpointSettings = [],
                        o.cssTransitions = !1,
                        o.focussed = !1,
                        o.interrupted = !1,
                        o.hidden = "hidden",
                        o.paused = !0,
                        o.positionProp = null,
                        o.respondTo = null,
                        o.rowCount = 1,
                        o.shouldClick = !0,
                        o.$slider = t(i),
                        o.$slidesCache = null,
                        o.transformType = null,
                        o.transitionType = null,
                        o.visibilityChange = "visibilitychange",
                        o.windowWidth = 0,
                        o.windowTimer = null,
                        s = t(i).data("slick") || {},
                        o.options = t.extend({}, o.defaults, n, s),
                        o.currentSlide = o.options.initialSlide,
                        o.originalSettings = o.options,
                        void 0 !== document.mozHidden ? (o.hidden = "mozHidden",
                        o.visibilityChange = "mozvisibilitychange") : void 0 !== document.webkitHidden && (o.hidden = "webkitHidden",
                        o.visibilityChange = "webkitvisibilitychange"),
                        o.autoPlay = t.proxy(o.autoPlay, o),
                        o.autoPlayClear = t.proxy(o.autoPlayClear, o),
                        o.autoPlayIterator = t.proxy(o.autoPlayIterator, o),
                        o.changeSlide = t.proxy(o.changeSlide, o),
                        o.clickHandler = t.proxy(o.clickHandler, o),
                        o.selectHandler = t.proxy(o.selectHandler, o),
                        o.setPosition = t.proxy(o.setPosition, o),
                        o.swipeHandler = t.proxy(o.swipeHandler, o),
                        o.dragHandler = t.proxy(o.dragHandler, o),
                        o.keyHandler = t.proxy(o.keyHandler, o),
                        o.instanceUid = e++,
                        o.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/,
                        o.registerBreakpoints(),
                        o.init(!0)
                    }
                    ).prototype.activateADA = function() {
                        this.$slideTrack.find(".slick-active").attr({
                            "aria-hidden": "false"
                        }).find("a, input, button, select").attr({
                            tabindex: "0"
                        })
                    }
                    ,
                    i.prototype.addSlide = i.prototype.slickAdd = function(e, i, n) {
                        var s = this;
                        if ("boolean" == typeof i)
                            n = i,
                            i = null;
                        else if (i < 0 || i >= s.slideCount)
                            return !1;
                        s.unload(),
                        "number" == typeof i ? 0 === i && 0 === s.$slides.length ? t(e).appendTo(s.$slideTrack) : n ? t(e).insertBefore(s.$slides.eq(i)) : t(e).insertAfter(s.$slides.eq(i)) : !0 === n ? t(e).prependTo(s.$slideTrack) : t(e).appendTo(s.$slideTrack),
                        s.$slides = s.$slideTrack.children(this.options.slide),
                        s.$slideTrack.children(this.options.slide).detach(),
                        s.$slideTrack.append(s.$slides),
                        s.$slides.each((function(e, i) {
                            t(i).attr("data-slick-index", e)
                        }
                        )),
                        s.$slidesCache = s.$slides,
                        s.reinit()
                    }
                    ,
                    i.prototype.animateHeight = function() {
                        var t = this;
                        if (1 === t.options.slidesToShow && !0 === t.options.adaptiveHeight && !1 === t.options.vertical) {
                            var e = t.$slides.eq(t.currentSlide).outerHeight(!0);
                            t.$list.animate({
                                height: e
                            }, t.options.speed)
                        }
                    }
                    ,
                    i.prototype.animateSlide = function(e, i) {
                        var n = {}
                          , s = this;
                        s.animateHeight(),
                        !0 === s.options.rtl && !1 === s.options.vertical && (e = -e),
                        !1 === s.transformsEnabled ? !1 === s.options.vertical ? s.$slideTrack.animate({
                            left: e
                        }, s.options.speed, s.options.easing, i) : s.$slideTrack.animate({
                            top: e
                        }, s.options.speed, s.options.easing, i) : !1 === s.cssTransitions ? (!0 === s.options.rtl && (s.currentLeft = -s.currentLeft),
                        t({
                            animStart: s.currentLeft
                        }).animate({
                            animStart: e
                        }, {
                            duration: s.options.speed,
                            easing: s.options.easing,
                            step: function(t) {
                                t = Math.ceil(t),
                                !1 === s.options.vertical ? (n[s.animType] = "translate(" + t + "px, 0px)",
                                s.$slideTrack.css(n)) : (n[s.animType] = "translate(0px," + t + "px)",
                                s.$slideTrack.css(n))
                            },
                            complete: function() {
                                i && i.call()
                            }
                        })) : (s.applyTransition(),
                        e = Math.ceil(e),
                        !1 === s.options.vertical ? n[s.animType] = "translate3d(" + e + "px, 0px, 0px)" : n[s.animType] = "translate3d(0px," + e + "px, 0px)",
                        s.$slideTrack.css(n),
                        i && setTimeout((function() {
                            s.disableTransition(),
                            i.call()
                        }
                        ), s.options.speed))
                    }
                    ,
                    i.prototype.getNavTarget = function() {
                        var e = this.options.asNavFor;
                        return e && null !== e && (e = t(e).not(this.$slider)),
                        e
                    }
                    ,
                    i.prototype.asNavFor = function(e) {
                        var i = this.getNavTarget();
                        null !== i && "object" == typeof i && i.each((function() {
                            var i = t(this).slick("getSlick");
                            i.unslicked || i.slideHandler(e, !0)
                        }
                        ))
                    }
                    ,
                    i.prototype.applyTransition = function(t) {
                        var e = this
                          , i = {};
                        !1 === e.options.fade ? i[e.transitionType] = e.transformType + " " + e.options.speed + "ms " + e.options.cssEase : i[e.transitionType] = "opacity " + e.options.speed + "ms " + e.options.cssEase,
                        !1 === e.options.fade ? e.$slideTrack.css(i) : e.$slides.eq(t).css(i)
                    }
                    ,
                    i.prototype.autoPlay = function() {
                        var t = this;
                        t.autoPlayClear(),
                        t.slideCount > t.options.slidesToShow && (t.autoPlayTimer = setInterval(t.autoPlayIterator, t.options.autoplaySpeed))
                    }
                    ,
                    i.prototype.autoPlayClear = function() {
                        this.autoPlayTimer && clearInterval(this.autoPlayTimer)
                    }
                    ,
                    i.prototype.autoPlayIterator = function() {
                        var t = this
                          , e = t.currentSlide + t.options.slidesToScroll;
                        t.paused || t.interrupted || t.focussed || (!1 === t.options.infinite && (1 === t.direction && t.currentSlide + 1 === t.slideCount - 1 ? t.direction = 0 : 0 === t.direction && (e = t.currentSlide - t.options.slidesToScroll,
                        t.currentSlide - 1 == 0 && (t.direction = 1))),
                        t.slideHandler(e))
                    }
                    ,
                    i.prototype.buildArrows = function() {
                        var e = this;
                        !0 === e.options.arrows && (e.$prevArrow = t(e.options.prevArrow).addClass("slick-arrow"),
                        e.$nextArrow = t(e.options.nextArrow).addClass("slick-arrow"),
                        e.slideCount > e.options.slidesToShow ? (e.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),
                        e.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),
                        e.htmlExpr.test(e.options.prevArrow) && e.$prevArrow.prependTo(e.options.appendArrows),
                        e.htmlExpr.test(e.options.nextArrow) && e.$nextArrow.appendTo(e.options.appendArrows),
                        !0 !== e.options.infinite && e.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true")) : e.$prevArrow.add(e.$nextArrow).addClass("slick-hidden").attr({
                            "aria-disabled": "true",
                            tabindex: "-1"
                        }))
                    }
                    ,
                    i.prototype.buildDots = function() {
                        var e, i, n = this;
                        if (!0 === n.options.dots && n.slideCount > n.options.slidesToShow) {
                            for (n.$slider.addClass("slick-dotted"),
                            i = t("<ul />").addClass(n.options.dotsClass),
                            e = 0; e <= n.getDotCount(); e += 1)
                                i.append(t("<li />").append(n.options.customPaging.call(this, n, e)));
                            n.$dots = i.appendTo(n.options.appendDots),
                            n.$dots.find("li").first().addClass("slick-active")
                        }
                    }
                    ,
                    i.prototype.buildOut = function() {
                        var e = this;
                        e.$slides = e.$slider.children(e.options.slide + ":not(.slick-cloned)").addClass("slick-slide"),
                        e.slideCount = e.$slides.length,
                        e.$slides.each((function(e, i) {
                            t(i).attr("data-slick-index", e).data("originalStyling", t(i).attr("style") || "")
                        }
                        )),
                        e.$slider.addClass("slick-slider"),
                        e.$slideTrack = 0 === e.slideCount ? t('<div class="slick-track"/>').appendTo(e.$slider) : e.$slides.wrapAll('<div class="slick-track"/>').parent(),
                        e.$list = e.$slideTrack.wrap('<div class="slick-list"/>').parent(),
                        e.$slideTrack.css("opacity", 0),
                        !0 !== e.options.centerMode && !0 !== e.options.swipeToSlide || (e.options.slidesToScroll = 1),
                        t("img[data-lazy]", e.$slider).not("[src]").addClass("slick-loading"),
                        e.setupInfinite(),
                        e.buildArrows(),
                        e.buildDots(),
                        e.updateDots(),
                        e.setSlideClasses("number" == typeof e.currentSlide ? e.currentSlide : 0),
                        !0 === e.options.draggable && e.$list.addClass("draggable")
                    }
                    ,
                    i.prototype.buildRows = function() {
                        var t, e, i, n, s, o, r, a = this;
                        if (n = document.createDocumentFragment(),
                        o = a.$slider.children(),
                        a.options.rows > 0) {
                            for (r = a.options.slidesPerRow * a.options.rows,
                            s = Math.ceil(o.length / r),
                            t = 0; t < s; t++) {
                                var l = document.createElement("div");
                                for (e = 0; e < a.options.rows; e++) {
                                    var d = document.createElement("div");
                                    for (i = 0; i < a.options.slidesPerRow; i++) {
                                        var c = t * r + (e * a.options.slidesPerRow + i);
                                        o.get(c) && d.appendChild(o.get(c))
                                    }
                                    l.appendChild(d)
                                }
                                n.appendChild(l)
                            }
                            a.$slider.empty().append(n),
                            a.$slider.children().children().children().css({
                                width: 100 / a.options.slidesPerRow + "%",
                                display: "inline-block"
                            })
                        }
                    }
                    ,
                    i.prototype.checkResponsive = function(e, i) {
                        var n, s, o, r = this, a = !1, l = r.$slider.width(), d = window.innerWidth || t(window).width();
                        if ("window" === r.respondTo ? o = d : "slider" === r.respondTo ? o = l : "min" === r.respondTo && (o = Math.min(d, l)),
                        r.options.responsive && r.options.responsive.length && null !== r.options.responsive) {
                            for (n in s = null,
                            r.breakpoints)
                                r.breakpoints.hasOwnProperty(n) && (!1 === r.originalSettings.mobileFirst ? o < r.breakpoints[n] && (s = r.breakpoints[n]) : o > r.breakpoints[n] && (s = r.breakpoints[n]));
                            null !== s ? null !== r.activeBreakpoint ? (s !== r.activeBreakpoint || i) && (r.activeBreakpoint = s,
                            "unslick" === r.breakpointSettings[s] ? r.unslick(s) : (r.options = t.extend({}, r.originalSettings, r.breakpointSettings[s]),
                            !0 === e && (r.currentSlide = r.options.initialSlide),
                            r.refresh(e)),
                            a = s) : (r.activeBreakpoint = s,
                            "unslick" === r.breakpointSettings[s] ? r.unslick(s) : (r.options = t.extend({}, r.originalSettings, r.breakpointSettings[s]),
                            !0 === e && (r.currentSlide = r.options.initialSlide),
                            r.refresh(e)),
                            a = s) : null !== r.activeBreakpoint && (r.activeBreakpoint = null,
                            r.options = r.originalSettings,
                            !0 === e && (r.currentSlide = r.options.initialSlide),
                            r.refresh(e),
                            a = s),
                            e || !1 === a || r.$slider.trigger("breakpoint", [r, a])
                        }
                    }
                    ,
                    i.prototype.changeSlide = function(e, i) {
                        var n, s, o = this, r = t(e.currentTarget);
                        switch (r.is("a") && e.preventDefault(),
                        r.is("li") || (r = r.closest("li")),
                        n = o.slideCount % o.options.slidesToScroll != 0 ? 0 : (o.slideCount - o.currentSlide) % o.options.slidesToScroll,
                        e.data.message) {
                        case "previous":
                            s = 0 === n ? o.options.slidesToScroll : o.options.slidesToShow - n,
                            o.slideCount > o.options.slidesToShow && o.slideHandler(o.currentSlide - s, !1, i);
                            break;
                        case "next":
                            s = 0 === n ? o.options.slidesToScroll : n,
                            o.slideCount > o.options.slidesToShow && o.slideHandler(o.currentSlide + s, !1, i);
                            break;
                        case "index":
                            var a = 0 === e.data.index ? 0 : e.data.index || r.index() * o.options.slidesToScroll;
                            o.slideHandler(o.checkNavigable(a), !1, i),
                            r.children().trigger("focus");
                            break;
                        default:
                            return
                        }
                    }
                    ,
                    i.prototype.checkNavigable = function(t) {
                        var e, i;
                        if (i = 0,
                        t > (e = this.getNavigableIndexes())[e.length - 1])
                            t = e[e.length - 1];
                        else
                            for (var n in e) {
                                if (t < e[n]) {
                                    t = i;
                                    break
                                }
                                i = e[n]
                            }
                        return t
                    }
                    ,
                    i.prototype.cleanUpEvents = function() {
                        var e = this;
                        e.options.dots && null !== e.$dots && (t("li", e.$dots).off("click.slick", e.changeSlide).off("mouseenter.slick", t.proxy(e.interrupt, e, !0)).off("mouseleave.slick", t.proxy(e.interrupt, e, !1)),
                        !0 === e.options.accessibility && e.$dots.off("keydown.slick", e.keyHandler)),
                        e.$slider.off("focus.slick blur.slick"),
                        !0 === e.options.arrows && e.slideCount > e.options.slidesToShow && (e.$prevArrow && e.$prevArrow.off("click.slick", e.changeSlide),
                        e.$nextArrow && e.$nextArrow.off("click.slick", e.changeSlide),
                        !0 === e.options.accessibility && (e.$prevArrow && e.$prevArrow.off("keydown.slick", e.keyHandler),
                        e.$nextArrow && e.$nextArrow.off("keydown.slick", e.keyHandler))),
                        e.$list.off("touchstart.slick mousedown.slick", e.swipeHandler),
                        e.$list.off("touchmove.slick mousemove.slick", e.swipeHandler),
                        e.$list.off("touchend.slick mouseup.slick", e.swipeHandler),
                        e.$list.off("touchcancel.slick mouseleave.slick", e.swipeHandler),
                        e.$list.off("click.slick", e.clickHandler),
                        t(document).off(e.visibilityChange, e.visibility),
                        e.cleanUpSlideEvents(),
                        !0 === e.options.accessibility && e.$list.off("keydown.slick", e.keyHandler),
                        !0 === e.options.focusOnSelect && t(e.$slideTrack).children().off("click.slick", e.selectHandler),
                        t(window).off("orientationchange.slick.slick-" + e.instanceUid, e.orientationChange),
                        t(window).off("resize.slick.slick-" + e.instanceUid, e.resize),
                        t("[draggable!=true]", e.$slideTrack).off("dragstart", e.preventDefault),
                        t(window).off("load.slick.slick-" + e.instanceUid, e.setPosition)
                    }
                    ,
                    i.prototype.cleanUpSlideEvents = function() {
                        var e = this;
                        e.$list.off("mouseenter.slick", t.proxy(e.interrupt, e, !0)),
                        e.$list.off("mouseleave.slick", t.proxy(e.interrupt, e, !1))
                    }
                    ,
                    i.prototype.cleanUpRows = function() {
                        var t, e = this;
                        e.options.rows > 0 && ((t = e.$slides.children().children()).removeAttr("style"),
                        e.$slider.empty().append(t))
                    }
                    ,
                    i.prototype.clickHandler = function(t) {
                        !1 === this.shouldClick && (t.stopImmediatePropagation(),
                        t.stopPropagation(),
                        t.preventDefault())
                    }
                    ,
                    i.prototype.destroy = function(e) {
                        var i = this;
                        i.autoPlayClear(),
                        i.touchObject = {},
                        i.cleanUpEvents(),
                        t(".slick-cloned", i.$slider).detach(),
                        i.$dots && i.$dots.remove(),
                        i.$prevArrow && i.$prevArrow.length && (i.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""),
                        i.htmlExpr.test(i.options.prevArrow) && i.$prevArrow.remove()),
                        i.$nextArrow && i.$nextArrow.length && (i.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""),
                        i.htmlExpr.test(i.options.nextArrow) && i.$nextArrow.remove()),
                        i.$slides && (i.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each((function() {
                            t(this).attr("style", t(this).data("originalStyling"))
                        }
                        )),
                        i.$slideTrack.children(this.options.slide).detach(),
                        i.$slideTrack.detach(),
                        i.$list.detach(),
                        i.$slider.append(i.$slides)),
                        i.cleanUpRows(),
                        i.$slider.removeClass("slick-slider"),
                        i.$slider.removeClass("slick-initialized"),
                        i.$slider.removeClass("slick-dotted"),
                        i.unslicked = !0,
                        e || i.$slider.trigger("destroy", [i])
                    }
                    ,
                    i.prototype.disableTransition = function(t) {
                        var e = this
                          , i = {};
                        i[e.transitionType] = "",
                        !1 === e.options.fade ? e.$slideTrack.css(i) : e.$slides.eq(t).css(i)
                    }
                    ,
                    i.prototype.fadeSlide = function(t, e) {
                        var i = this;
                        !1 === i.cssTransitions ? (i.$slides.eq(t).css({
                            zIndex: i.options.zIndex
                        }),
                        i.$slides.eq(t).animate({
                            opacity: 1
                        }, i.options.speed, i.options.easing, e)) : (i.applyTransition(t),
                        i.$slides.eq(t).css({
                            opacity: 1,
                            zIndex: i.options.zIndex
                        }),
                        e && setTimeout((function() {
                            i.disableTransition(t),
                            e.call()
                        }
                        ), i.options.speed))
                    }
                    ,
                    i.prototype.fadeSlideOut = function(t) {
                        var e = this;
                        !1 === e.cssTransitions ? e.$slides.eq(t).animate({
                            opacity: 0,
                            zIndex: e.options.zIndex - 2
                        }, e.options.speed, e.options.easing) : (e.applyTransition(t),
                        e.$slides.eq(t).css({
                            opacity: 0,
                            zIndex: e.options.zIndex - 2
                        }))
                    }
                    ,
                    i.prototype.filterSlides = i.prototype.slickFilter = function(t) {
                        var e = this;
                        null !== t && (e.$slidesCache = e.$slides,
                        e.unload(),
                        e.$slideTrack.children(this.options.slide).detach(),
                        e.$slidesCache.filter(t).appendTo(e.$slideTrack),
                        e.reinit())
                    }
                    ,
                    i.prototype.focusHandler = function() {
                        var e = this;
                        e.$slider.off("focus.slick blur.slick").on("focus.slick", "*", (function(i) {
                            var n = t(this);
                            setTimeout((function() {
                                e.options.pauseOnFocus && n.is(":focus") && (e.focussed = !0,
                                e.autoPlay())
                            }
                            ), 0)
                        }
                        )).on("blur.slick", "*", (function(i) {
                            t(this),
                            e.options.pauseOnFocus && (e.focussed = !1,
                            e.autoPlay())
                        }
                        ))
                    }
                    ,
                    i.prototype.getCurrent = i.prototype.slickCurrentSlide = function() {
                        return this.currentSlide
                    }
                    ,
                    i.prototype.getDotCount = function() {
                        var t = this
                          , e = 0
                          , i = 0
                          , n = 0;
                        if (!0 === t.options.infinite)
                            if (t.slideCount <= t.options.slidesToShow)
                                ++n;
                            else
                                for (; e < t.slideCount; )
                                    ++n,
                                    e = i + t.options.slidesToScroll,
                                    i += t.options.slidesToScroll <= t.options.slidesToShow ? t.options.slidesToScroll : t.options.slidesToShow;
                        else if (!0 === t.options.centerMode)
                            n = t.slideCount;
                        else if (t.options.asNavFor)
                            for (; e < t.slideCount; )
                                ++n,
                                e = i + t.options.slidesToScroll,
                                i += t.options.slidesToScroll <= t.options.slidesToShow ? t.options.slidesToScroll : t.options.slidesToShow;
                        else
                            n = 1 + Math.ceil((t.slideCount - t.options.slidesToShow) / t.options.slidesToScroll);
                        return n - 1
                    }
                    ,
                    i.prototype.getLeft = function(t) {
                        var e, i, n, s, o = this, r = 0;
                        return o.slideOffset = 0,
                        i = o.$slides.first().outerHeight(!0),
                        !0 === o.options.infinite ? (o.slideCount > o.options.slidesToShow && (o.slideOffset = o.slideWidth * o.options.slidesToShow * -1,
                        s = -1,
                        !0 === o.options.vertical && !0 === o.options.centerMode && (2 === o.options.slidesToShow ? s = -1.5 : 1 === o.options.slidesToShow && (s = -2)),
                        r = i * o.options.slidesToShow * s),
                        o.slideCount % o.options.slidesToScroll != 0 && t + o.options.slidesToScroll > o.slideCount && o.slideCount > o.options.slidesToShow && (t > o.slideCount ? (o.slideOffset = (o.options.slidesToShow - (t - o.slideCount)) * o.slideWidth * -1,
                        r = (o.options.slidesToShow - (t - o.slideCount)) * i * -1) : (o.slideOffset = o.slideCount % o.options.slidesToScroll * o.slideWidth * -1,
                        r = o.slideCount % o.options.slidesToScroll * i * -1))) : t + o.options.slidesToShow > o.slideCount && (o.slideOffset = (t + o.options.slidesToShow - o.slideCount) * o.slideWidth,
                        r = (t + o.options.slidesToShow - o.slideCount) * i),
                        o.slideCount <= o.options.slidesToShow && (o.slideOffset = 0,
                        r = 0),
                        !0 === o.options.centerMode && o.slideCount <= o.options.slidesToShow ? o.slideOffset = o.slideWidth * Math.floor(o.options.slidesToShow) / 2 - o.slideWidth * o.slideCount / 2 : !0 === o.options.centerMode && !0 === o.options.infinite ? o.slideOffset += o.slideWidth * Math.floor(o.options.slidesToShow / 2) - o.slideWidth : !0 === o.options.centerMode && (o.slideOffset = 0,
                        o.slideOffset += o.slideWidth * Math.floor(o.options.slidesToShow / 2)),
                        e = !1 === o.options.vertical ? t * o.slideWidth * -1 + o.slideOffset : t * i * -1 + r,
                        !0 === o.options.variableWidth && (n = o.slideCount <= o.options.slidesToShow || !1 === o.options.infinite ? o.$slideTrack.children(".slick-slide").eq(t) : o.$slideTrack.children(".slick-slide").eq(t + o.options.slidesToShow),
                        e = !0 === o.options.rtl ? n[0] ? -1 * (o.$slideTrack.width() - n[0].offsetLeft - n.width()) : 0 : n[0] ? -1 * n[0].offsetLeft : 0,
                        !0 === o.options.centerMode && (n = o.slideCount <= o.options.slidesToShow || !1 === o.options.infinite ? o.$slideTrack.children(".slick-slide").eq(t) : o.$slideTrack.children(".slick-slide").eq(t + o.options.slidesToShow + 1),
                        e = !0 === o.options.rtl ? n[0] ? -1 * (o.$slideTrack.width() - n[0].offsetLeft - n.width()) : 0 : n[0] ? -1 * n[0].offsetLeft : 0,
                        e += (o.$list.width() - n.outerWidth()) / 2)),
                        e
                    }
                    ,
                    i.prototype.getOption = i.prototype.slickGetOption = function(t) {
                        return this.options[t]
                    }
                    ,
                    i.prototype.getNavigableIndexes = function() {
                        var t, e = this, i = 0, n = 0, s = [];
                        for (!1 === e.options.infinite ? t = e.slideCount : (i = -1 * e.options.slidesToScroll,
                        n = -1 * e.options.slidesToScroll,
                        t = 2 * e.slideCount); i < t; )
                            s.push(i),
                            i = n + e.options.slidesToScroll,
                            n += e.options.slidesToScroll <= e.options.slidesToShow ? e.options.slidesToScroll : e.options.slidesToShow;
                        return s
                    }
                    ,
                    i.prototype.getSlick = function() {
                        return this
                    }
                    ,
                    i.prototype.getSlideCount = function() {
                        var e, i, n, s = this;
                        return n = !0 === s.options.centerMode ? Math.floor(s.$list.width() / 2) : 0,
                        i = -1 * s.swipeLeft + n,
                        !0 === s.options.swipeToSlide ? (s.$slideTrack.find(".slick-slide").each((function(n, o) {
                            var r, a;
                            if (r = t(o).outerWidth(),
                            a = o.offsetLeft,
                            !0 !== s.options.centerMode && (a += r / 2),
                            i < a + r)
                                return e = o,
                                !1
                        }
                        )),
                        Math.abs(t(e).attr("data-slick-index") - s.currentSlide) || 1) : s.options.slidesToScroll
                    }
                    ,
                    i.prototype.goTo = i.prototype.slickGoTo = function(t, e) {
                        this.changeSlide({
                            data: {
                                message: "index",
                                index: parseInt(t)
                            }
                        }, e)
                    }
                    ,
                    i.prototype.init = function(e) {
                        var i = this;
                        t(i.$slider).hasClass("slick-initialized") || (t(i.$slider).addClass("slick-initialized"),
                        i.buildRows(),
                        i.buildOut(),
                        i.setProps(),
                        i.startLoad(),
                        i.loadSlider(),
                        i.initializeEvents(),
                        i.updateArrows(),
                        i.updateDots(),
                        i.checkResponsive(!0),
                        i.focusHandler()),
                        e && i.$slider.trigger("init", [i]),
                        !0 === i.options.accessibility && i.initADA(),
                        i.options.autoplay && (i.paused = !1,
                        i.autoPlay())
                    }
                    ,
                    i.prototype.initADA = function() {
                        var e = this
                          , i = Math.ceil(e.slideCount / e.options.slidesToShow)
                          , n = e.getNavigableIndexes().filter((function(t) {
                            return t >= 0 && t < e.slideCount
                        }
                        ));
                        e.$slides.add(e.$slideTrack.find(".slick-cloned")).attr({
                            "aria-hidden": "true",
                            tabindex: "-1"
                        }).find("a, input, button, select").attr({
                            tabindex: "-1"
                        }),
                        null !== e.$dots && (e.$slides.not(e.$slideTrack.find(".slick-cloned")).each((function(i) {
                            var s = n.indexOf(i);
                            if (t(this).attr({
                                role: "tabpanel",
                                id: "slick-slide" + e.instanceUid + i,
                                tabindex: -1
                            }),
                            -1 !== s) {
                                var o = "slick-slide-control" + e.instanceUid + s;
                                t("#" + o).length && t(this).attr({
                                    "aria-describedby": o
                                })
                            }
                        }
                        )),
                        e.$dots.attr("role", "tablist").find("li").each((function(s) {
                            var o = n[s];
                            t(this).attr({
                                role: "presentation"
                            }),
                            t(this).find("button").first().attr({
                                role: "tab",
                                id: "slick-slide-control" + e.instanceUid + s,
                                "aria-controls": "slick-slide" + e.instanceUid + o,
                                "aria-label": s + 1 + " of " + i,
                                "aria-selected": null,
                                tabindex: "-1"
                            })
                        }
                        )).eq(e.currentSlide).find("button").attr({
                            "aria-selected": "true",
                            tabindex: "0"
                        }).end());
                        for (var s = e.currentSlide, o = s + e.options.slidesToShow; s < o; s++)
                            e.options.focusOnChange ? e.$slides.eq(s).attr({
                                tabindex: "0"
                            }) : e.$slides.eq(s).removeAttr("tabindex");
                        e.activateADA()
                    }
                    ,
                    i.prototype.initArrowEvents = function() {
                        var t = this;
                        !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && (t.$prevArrow.off("click.slick").on("click.slick", {
                            message: "previous"
                        }, t.changeSlide),
                        t.$nextArrow.off("click.slick").on("click.slick", {
                            message: "next"
                        }, t.changeSlide),
                        !0 === t.options.accessibility && (t.$prevArrow.on("keydown.slick", t.keyHandler),
                        t.$nextArrow.on("keydown.slick", t.keyHandler)))
                    }
                    ,
                    i.prototype.initDotEvents = function() {
                        var e = this;
                        !0 === e.options.dots && e.slideCount > e.options.slidesToShow && (t("li", e.$dots).on("click.slick", {
                            message: "index"
                        }, e.changeSlide),
                        !0 === e.options.accessibility && e.$dots.on("keydown.slick", e.keyHandler)),
                        !0 === e.options.dots && !0 === e.options.pauseOnDotsHover && e.slideCount > e.options.slidesToShow && t("li", e.$dots).on("mouseenter.slick", t.proxy(e.interrupt, e, !0)).on("mouseleave.slick", t.proxy(e.interrupt, e, !1))
                    }
                    ,
                    i.prototype.initSlideEvents = function() {
                        var e = this;
                        e.options.pauseOnHover && (e.$list.on("mouseenter.slick", t.proxy(e.interrupt, e, !0)),
                        e.$list.on("mouseleave.slick", t.proxy(e.interrupt, e, !1)))
                    }
                    ,
                    i.prototype.initializeEvents = function() {
                        var e = this;
                        e.initArrowEvents(),
                        e.initDotEvents(),
                        e.initSlideEvents(),
                        e.$list.on("touchstart.slick mousedown.slick", {
                            action: "start"
                        }, e.swipeHandler),
                        e.$list.on("touchmove.slick mousemove.slick", {
                            action: "move"
                        }, e.swipeHandler),
                        e.$list.on("touchend.slick mouseup.slick", {
                            action: "end"
                        }, e.swipeHandler),
                        e.$list.on("touchcancel.slick mouseleave.slick", {
                            action: "end"
                        }, e.swipeHandler),
                        e.$list.on("click.slick", e.clickHandler),
                        t(document).on(e.visibilityChange, t.proxy(e.visibility, e)),
                        !0 === e.options.accessibility && e.$list.on("keydown.slick", e.keyHandler),
                        !0 === e.options.focusOnSelect && t(e.$slideTrack).children().on("click.slick", e.selectHandler),
                        t(window).on("orientationchange.slick.slick-" + e.instanceUid, t.proxy(e.orientationChange, e)),
                        t(window).on("resize.slick.slick-" + e.instanceUid, t.proxy(e.resize, e)),
                        t("[draggable!=true]", e.$slideTrack).on("dragstart", e.preventDefault),
                        t(window).on("load.slick.slick-" + e.instanceUid, e.setPosition),
                        t(e.setPosition)
                    }
                    ,
                    i.prototype.initUI = function() {
                        var t = this;
                        !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && (t.$prevArrow.show(),
                        t.$nextArrow.show()),
                        !0 === t.options.dots && t.slideCount > t.options.slidesToShow && t.$dots.show()
                    }
                    ,
                    i.prototype.keyHandler = function(t) {
                        var e = this;
                        t.target.tagName.match("TEXTAREA|INPUT|SELECT") || (37 === t.keyCode && !0 === e.options.accessibility ? e.changeSlide({
                            data: {
                                message: !0 === e.options.rtl ? "next" : "previous"
                            }
                        }) : 39 === t.keyCode && !0 === e.options.accessibility && e.changeSlide({
                            data: {
                                message: !0 === e.options.rtl ? "previous" : "next"
                            }
                        }))
                    }
                    ,
                    i.prototype.lazyLoad = function() {
                        var e, i, n, s = this;
                        function o(e) {
                            t("img[data-lazy]", e).each((function() {
                                var e = t(this)
                                  , i = t(this).attr("data-lazy")
                                  , n = t(this).attr("data-srcset")
                                  , o = t(this).attr("data-sizes") || s.$slider.attr("data-sizes")
                                  , r = document.createElement("img");
                                r.onload = function() {
                                    e.animate({
                                        opacity: 0
                                    }, 100, (function() {
                                        n && (e.attr("srcset", n),
                                        o && e.attr("sizes", o)),
                                        e.attr("src", i).animate({
                                            opacity: 1
                                        }, 200, (function() {
                                            e.removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading")
                                        }
                                        )),
                                        s.$slider.trigger("lazyLoaded", [s, e, i])
                                    }
                                    ))
                                }
                                ,
                                r.onerror = function() {
                                    e.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),
                                    s.$slider.trigger("lazyLoadError", [s, e, i])
                                }
                                ,
                                r.src = i
                            }
                            ))
                        }
                        if (!0 === s.options.centerMode ? !0 === s.options.infinite ? n = (i = s.currentSlide + (s.options.slidesToShow / 2 + 1)) + s.options.slidesToShow + 2 : (i = Math.max(0, s.currentSlide - (s.options.slidesToShow / 2 + 1)),
                        n = s.options.slidesToShow / 2 + 1 + 2 + s.currentSlide) : (i = s.options.infinite ? s.options.slidesToShow + s.currentSlide : s.currentSlide,
                        n = Math.ceil(i + s.options.slidesToShow),
                        !0 === s.options.fade && (i > 0 && i--,
                        n <= s.slideCount && n++)),
                        e = s.$slider.find(".slick-slide").slice(i, n),
                        "anticipated" === s.options.lazyLoad)
                            for (var r = i - 1, a = n, l = s.$slider.find(".slick-slide"), d = 0; d < s.options.slidesToScroll; d++)
                                r < 0 && (r = s.slideCount - 1),
                                e = (e = e.add(l.eq(r))).add(l.eq(a)),
                                r--,
                                a++;
                        o(e),
                        s.slideCount <= s.options.slidesToShow ? o(s.$slider.find(".slick-slide")) : s.currentSlide >= s.slideCount - s.options.slidesToShow ? o(s.$slider.find(".slick-cloned").slice(0, s.options.slidesToShow)) : 0 === s.currentSlide && o(s.$slider.find(".slick-cloned").slice(-1 * s.options.slidesToShow))
                    }
                    ,
                    i.prototype.loadSlider = function() {
                        var t = this;
                        t.setPosition(),
                        t.$slideTrack.css({
                            opacity: 1
                        }),
                        t.$slider.removeClass("slick-loading"),
                        t.initUI(),
                        "progressive" === t.options.lazyLoad && t.progressiveLazyLoad()
                    }
                    ,
                    i.prototype.next = i.prototype.slickNext = function() {
                        this.changeSlide({
                            data: {
                                message: "next"
                            }
                        })
                    }
                    ,
                    i.prototype.orientationChange = function() {
                        this.checkResponsive(),
                        this.setPosition()
                    }
                    ,
                    i.prototype.pause = i.prototype.slickPause = function() {
                        this.autoPlayClear(),
                        this.paused = !0
                    }
                    ,
                    i.prototype.play = i.prototype.slickPlay = function() {
                        var t = this;
                        t.autoPlay(),
                        t.options.autoplay = !0,
                        t.paused = !1,
                        t.focussed = !1,
                        t.interrupted = !1
                    }
                    ,
                    i.prototype.postSlide = function(e) {
                        var i = this;
                        i.unslicked || (i.$slider.trigger("afterChange", [i, e]),
                        i.animating = !1,
                        i.slideCount > i.options.slidesToShow && i.setPosition(),
                        i.swipeLeft = null,
                        i.options.autoplay && i.autoPlay(),
                        !0 === i.options.accessibility && (i.initADA(),
                        i.options.focusOnChange && t(i.$slides.get(i.currentSlide)).attr("tabindex", 0).focus()))
                    }
                    ,
                    i.prototype.prev = i.prototype.slickPrev = function() {
                        this.changeSlide({
                            data: {
                                message: "previous"
                            }
                        })
                    }
                    ,
                    i.prototype.preventDefault = function(t) {
                        t.preventDefault()
                    }
                    ,
                    i.prototype.progressiveLazyLoad = function(e) {
                        e = e || 1;
                        var i, n, s, o, r, a = this, l = t("img[data-lazy]", a.$slider);
                        l.length ? (i = l.first(),
                        n = i.attr("data-lazy"),
                        s = i.attr("data-srcset"),
                        o = i.attr("data-sizes") || a.$slider.attr("data-sizes"),
                        (r = document.createElement("img")).onload = function() {
                            s && (i.attr("srcset", s),
                            o && i.attr("sizes", o)),
                            i.attr("src", n).removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading"),
                            !0 === a.options.adaptiveHeight && a.setPosition(),
                            a.$slider.trigger("lazyLoaded", [a, i, n]),
                            a.progressiveLazyLoad()
                        }
                        ,
                        r.onerror = function() {
                            e < 3 ? setTimeout((function() {
                                a.progressiveLazyLoad(e + 1)
                            }
                            ), 500) : (i.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),
                            a.$slider.trigger("lazyLoadError", [a, i, n]),
                            a.progressiveLazyLoad())
                        }
                        ,
                        r.src = n) : a.$slider.trigger("allImagesLoaded", [a])
                    }
                    ,
                    i.prototype.refresh = function(e) {
                        var i, n, s = this;
                        n = s.slideCount - s.options.slidesToShow,
                        !s.options.infinite && s.currentSlide > n && (s.currentSlide = n),
                        s.slideCount <= s.options.slidesToShow && (s.currentSlide = 0),
                        i = s.currentSlide,
                        s.destroy(!0),
                        t.extend(s, s.initials, {
                            currentSlide: i
                        }),
                        s.init(),
                        e || s.changeSlide({
                            data: {
                                message: "index",
                                index: i
                            }
                        }, !1)
                    }
                    ,
                    i.prototype.registerBreakpoints = function() {
                        var e, i, n, s = this, o = s.options.responsive || null;
                        if ("array" === t.type(o) && o.length) {
                            for (e in s.respondTo = s.options.respondTo || "window",
                            o)
                                if (n = s.breakpoints.length - 1,
                                o.hasOwnProperty(e)) {
                                    for (i = o[e].breakpoint; n >= 0; )
                                        s.breakpoints[n] && s.breakpoints[n] === i && s.breakpoints.splice(n, 1),
                                        n--;
                                    s.breakpoints.push(i),
                                    s.breakpointSettings[i] = o[e].settings
                                }
                            s.breakpoints.sort((function(t, e) {
                                return s.options.mobileFirst ? t - e : e - t
                            }
                            ))
                        }
                    }
                    ,
                    i.prototype.reinit = function() {
                        var e = this;
                        e.$slides = e.$slideTrack.children(e.options.slide).addClass("slick-slide"),
                        e.slideCount = e.$slides.length,
                        e.currentSlide >= e.slideCount && 0 !== e.currentSlide && (e.currentSlide = e.currentSlide - e.options.slidesToScroll),
                        e.slideCount <= e.options.slidesToShow && (e.currentSlide = 0),
                        e.registerBreakpoints(),
                        e.setProps(),
                        e.setupInfinite(),
                        e.buildArrows(),
                        e.updateArrows(),
                        e.initArrowEvents(),
                        e.buildDots(),
                        e.updateDots(),
                        e.initDotEvents(),
                        e.cleanUpSlideEvents(),
                        e.initSlideEvents(),
                        e.checkResponsive(!1, !0),
                        !0 === e.options.focusOnSelect && t(e.$slideTrack).children().on("click.slick", e.selectHandler),
                        e.setSlideClasses("number" == typeof e.currentSlide ? e.currentSlide : 0),
                        e.setPosition(),
                        e.focusHandler(),
                        e.paused = !e.options.autoplay,
                        e.autoPlay(),
                        e.$slider.trigger("reInit", [e])
                    }
                    ,
                    i.prototype.resize = function() {
                        var e = this;
                        t(window).width() !== e.windowWidth && (clearTimeout(e.windowDelay),
                        e.windowDelay = window.setTimeout((function() {
                            e.windowWidth = t(window).width(),
                            e.checkResponsive(),
                            e.unslicked || e.setPosition()
                        }
                        ), 50))
                    }
                    ,
                    i.prototype.removeSlide = i.prototype.slickRemove = function(t, e, i) {
                        var n = this;
                        if (t = "boolean" == typeof t ? !0 === (e = t) ? 0 : n.slideCount - 1 : !0 === e ? --t : t,
                        n.slideCount < 1 || t < 0 || t > n.slideCount - 1)
                            return !1;
                        n.unload(),
                        !0 === i ? n.$slideTrack.children().remove() : n.$slideTrack.children(this.options.slide).eq(t).remove(),
                        n.$slides = n.$slideTrack.children(this.options.slide),
                        n.$slideTrack.children(this.options.slide).detach(),
                        n.$slideTrack.append(n.$slides),
                        n.$slidesCache = n.$slides,
                        n.reinit()
                    }
                    ,
                    i.prototype.setCSS = function(t) {
                        var e, i, n = this, s = {};
                        !0 === n.options.rtl && (t = -t),
                        e = "left" == n.positionProp ? Math.ceil(t) + "px" : "0px",
                        i = "top" == n.positionProp ? Math.ceil(t) + "px" : "0px",
                        s[n.positionProp] = t,
                        !1 === n.transformsEnabled ? n.$slideTrack.css(s) : (s = {},
                        !1 === n.cssTransitions ? (s[n.animType] = "translate(" + e + ", " + i + ")",
                        n.$slideTrack.css(s)) : (s[n.animType] = "translate3d(" + e + ", " + i + ", 0px)",
                        n.$slideTrack.css(s)))
                    }
                    ,
                    i.prototype.setDimensions = function() {
                        var t = this;
                        !1 === t.options.vertical ? !0 === t.options.centerMode && t.$list.css({
                            padding: "0px " + t.options.centerPadding
                        }) : (t.$list.height(t.$slides.first().outerHeight(!0) * t.options.slidesToShow),
                        !0 === t.options.centerMode && t.$list.css({
                            padding: t.options.centerPadding + " 0px"
                        })),
                        t.listWidth = t.$list.width(),
                        t.listHeight = t.$list.height(),
                        !1 === t.options.vertical && !1 === t.options.variableWidth ? (t.slideWidth = Math.ceil(t.listWidth / t.options.slidesToShow),
                        t.$slideTrack.width(Math.ceil(t.slideWidth * t.$slideTrack.children(".slick-slide").length))) : !0 === t.options.variableWidth ? t.$slideTrack.width(5e3 * t.slideCount) : (t.slideWidth = Math.ceil(t.listWidth),
                        t.$slideTrack.height(Math.ceil(t.$slides.first().outerHeight(!0) * t.$slideTrack.children(".slick-slide").length)));
                        var e = t.$slides.first().outerWidth(!0) - t.$slides.first().width();
                        !1 === t.options.variableWidth && t.$slideTrack.children(".slick-slide").width(t.slideWidth - e)
                    }
                    ,
                    i.prototype.setFade = function() {
                        var e, i = this;
                        i.$slides.each((function(n, s) {
                            e = i.slideWidth * n * -1,
                            !0 === i.options.rtl ? t(s).css({
                                position: "relative",
                                right: e,
                                top: 0,
                                zIndex: i.options.zIndex - 2,
                                opacity: 0
                            }) : t(s).css({
                                position: "relative",
                                left: e,
                                top: 0,
                                zIndex: i.options.zIndex - 2,
                                opacity: 0
                            })
                        }
                        )),
                        i.$slides.eq(i.currentSlide).css({
                            zIndex: i.options.zIndex - 1,
                            opacity: 1
                        })
                    }
                    ,
                    i.prototype.setHeight = function() {
                        var t = this;
                        if (1 === t.options.slidesToShow && !0 === t.options.adaptiveHeight && !1 === t.options.vertical) {
                            var e = t.$slides.eq(t.currentSlide).outerHeight(!0);
                            t.$list.css("height", e)
                        }
                    }
                    ,
                    i.prototype.setOption = i.prototype.slickSetOption = function() {
                        var e, i, n, s, o, r = this, a = !1;
                        if ("object" === t.type(arguments[0]) ? (n = arguments[0],
                        a = arguments[1],
                        o = "multiple") : "string" === t.type(arguments[0]) && (n = arguments[0],
                        s = arguments[1],
                        a = arguments[2],
                        "responsive" === arguments[0] && "array" === t.type(arguments[1]) ? o = "responsive" : void 0 !== arguments[1] && (o = "single")),
                        "single" === o)
                            r.options[n] = s;
                        else if ("multiple" === o)
                            t.each(n, (function(t, e) {
                                r.options[t] = e
                            }
                            ));
                        else if ("responsive" === o)
                            for (i in s)
                                if ("array" !== t.type(r.options.responsive))
                                    r.options.responsive = [s[i]];
                                else {
                                    for (e = r.options.responsive.length - 1; e >= 0; )
                                        r.options.responsive[e].breakpoint === s[i].breakpoint && r.options.responsive.splice(e, 1),
                                        e--;
                                    r.options.responsive.push(s[i])
                                }
                        a && (r.unload(),
                        r.reinit())
                    }
                    ,
                    i.prototype.setPosition = function() {
                        var t = this;
                        t.setDimensions(),
                        t.setHeight(),
                        !1 === t.options.fade ? t.setCSS(t.getLeft(t.currentSlide)) : t.setFade(),
                        t.$slider.trigger("setPosition", [t])
                    }
                    ,
                    i.prototype.setProps = function() {
                        var t = this
                          , e = document.body.style;
                        t.positionProp = !0 === t.options.vertical ? "top" : "left",
                        "top" === t.positionProp ? t.$slider.addClass("slick-vertical") : t.$slider.removeClass("slick-vertical"),
                        void 0 === e.WebkitTransition && void 0 === e.MozTransition && void 0 === e.msTransition || !0 === t.options.useCSS && (t.cssTransitions = !0),
                        t.options.fade && ("number" == typeof t.options.zIndex ? t.options.zIndex < 3 && (t.options.zIndex = 3) : t.options.zIndex = t.defaults.zIndex),
                        void 0 !== e.OTransform && (t.animType = "OTransform",
                        t.transformType = "-o-transform",
                        t.transitionType = "OTransition",
                        void 0 === e.perspectiveProperty && void 0 === e.webkitPerspective && (t.animType = !1)),
                        void 0 !== e.MozTransform && (t.animType = "MozTransform",
                        t.transformType = "-moz-transform",
                        t.transitionType = "MozTransition",
                        void 0 === e.perspectiveProperty && void 0 === e.MozPerspective && (t.animType = !1)),
                        void 0 !== e.webkitTransform && (t.animType = "webkitTransform",
                        t.transformType = "-webkit-transform",
                        t.transitionType = "webkitTransition",
                        void 0 === e.perspectiveProperty && void 0 === e.webkitPerspective && (t.animType = !1)),
                        void 0 !== e.msTransform && (t.animType = "msTransform",
                        t.transformType = "-ms-transform",
                        t.transitionType = "msTransition",
                        void 0 === e.msTransform && (t.animType = !1)),
                        void 0 !== e.transform && !1 !== t.animType && (t.animType = "transform",
                        t.transformType = "transform",
                        t.transitionType = "transition"),
                        t.transformsEnabled = t.options.useTransform && null !== t.animType && !1 !== t.animType
                    }
                    ,
                    i.prototype.setSlideClasses = function(t) {
                        var e, i, n, s, o = this;
                        if (i = o.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden", "true"),
                        o.$slides.eq(t).addClass("slick-current"),
                        !0 === o.options.centerMode) {
                            var r = o.options.slidesToShow % 2 == 0 ? 1 : 0;
                            e = Math.floor(o.options.slidesToShow / 2),
                            !0 === o.options.infinite && (t >= e && t <= o.slideCount - 1 - e ? o.$slides.slice(t - e + r, t + e + 1).addClass("slick-active").attr("aria-hidden", "false") : (n = o.options.slidesToShow + t,
                            i.slice(n - e + 1 + r, n + e + 2).addClass("slick-active").attr("aria-hidden", "false")),
                            0 === t ? i.eq(i.length - 1 - o.options.slidesToShow).addClass("slick-center") : t === o.slideCount - 1 && i.eq(o.options.slidesToShow).addClass("slick-center")),
                            o.$slides.eq(t).addClass("slick-center")
                        } else
                            t >= 0 && t <= o.slideCount - o.options.slidesToShow ? o.$slides.slice(t, t + o.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false") : i.length <= o.options.slidesToShow ? i.addClass("slick-active").attr("aria-hidden", "false") : (s = o.slideCount % o.options.slidesToShow,
                            n = !0 === o.options.infinite ? o.options.slidesToShow + t : t,
                            o.options.slidesToShow == o.options.slidesToScroll && o.slideCount - t < o.options.slidesToShow ? i.slice(n - (o.options.slidesToShow - s), n + s).addClass("slick-active").attr("aria-hidden", "false") : i.slice(n, n + o.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false"));
                        "ondemand" !== o.options.lazyLoad && "anticipated" !== o.options.lazyLoad || o.lazyLoad()
                    }
                    ,
                    i.prototype.setupInfinite = function() {
                        var e, i, n, s = this;
                        if (!0 === s.options.fade && (s.options.centerMode = !1),
                        !0 === s.options.infinite && !1 === s.options.fade && (i = null,
                        s.slideCount > s.options.slidesToShow)) {
                            for (n = !0 === s.options.centerMode ? s.options.slidesToShow + 1 : s.options.slidesToShow,
                            e = s.slideCount; e > s.slideCount - n; e -= 1)
                                i = e - 1,
                                t(s.$slides[i]).clone(!0).attr("id", "").attr("data-slick-index", i - s.slideCount).prependTo(s.$slideTrack).addClass("slick-cloned");
                            for (e = 0; e < n + s.slideCount; e += 1)
                                i = e,
                                t(s.$slides[i]).clone(!0).attr("id", "").attr("data-slick-index", i + s.slideCount).appendTo(s.$slideTrack).addClass("slick-cloned");
                            s.$slideTrack.find(".slick-cloned").find("[id]").each((function() {
                                t(this).attr("id", "")
                            }
                            ))
                        }
                    }
                    ,
                    i.prototype.interrupt = function(t) {
                        t || this.autoPlay(),
                        this.interrupted = t
                    }
                    ,
                    i.prototype.selectHandler = function(e) {
                        var i = this
                          , n = t(e.target).is(".slick-slide") ? t(e.target) : t(e.target).parents(".slick-slide")
                          , s = parseInt(n.attr("data-slick-index"));
                        s || (s = 0),
                        i.slideCount <= i.options.slidesToShow ? i.slideHandler(s, !1, !0) : i.slideHandler(s)
                    }
                    ,
                    i.prototype.slideHandler = function(t, e, i) {
                        var n, s, o, r, a, l = null, d = this;
                        if (e = e || !1,
                        !(!0 === d.animating && !0 === d.options.waitForAnimate || !0 === d.options.fade && d.currentSlide === t))
                            if (!1 === e && d.asNavFor(t),
                            n = t,
                            l = d.getLeft(n),
                            r = d.getLeft(d.currentSlide),
                            d.currentLeft = null === d.swipeLeft ? r : d.swipeLeft,
                            !1 === d.options.infinite && !1 === d.options.centerMode && (t < 0 || t > d.getDotCount() * d.options.slidesToScroll))
                                !1 === d.options.fade && (n = d.currentSlide,
                                !0 !== i && d.slideCount > d.options.slidesToShow ? d.animateSlide(r, (function() {
                                    d.postSlide(n)
                                }
                                )) : d.postSlide(n));
                            else if (!1 === d.options.infinite && !0 === d.options.centerMode && (t < 0 || t > d.slideCount - d.options.slidesToScroll))
                                !1 === d.options.fade && (n = d.currentSlide,
                                !0 !== i && d.slideCount > d.options.slidesToShow ? d.animateSlide(r, (function() {
                                    d.postSlide(n)
                                }
                                )) : d.postSlide(n));
                            else {
                                if (d.options.autoplay && clearInterval(d.autoPlayTimer),
                                s = n < 0 ? d.slideCount % d.options.slidesToScroll != 0 ? d.slideCount - d.slideCount % d.options.slidesToScroll : d.slideCount + n : n >= d.slideCount ? d.slideCount % d.options.slidesToScroll != 0 ? 0 : n - d.slideCount : n,
                                d.animating = !0,
                                d.$slider.trigger("beforeChange", [d, d.currentSlide, s]),
                                o = d.currentSlide,
                                d.currentSlide = s,
                                d.setSlideClasses(d.currentSlide),
                                d.options.asNavFor && (a = (a = d.getNavTarget()).slick("getSlick")).slideCount <= a.options.slidesToShow && a.setSlideClasses(d.currentSlide),
                                d.updateDots(),
                                d.updateArrows(),
                                !0 === d.options.fade)
                                    return !0 !== i ? (d.fadeSlideOut(o),
                                    d.fadeSlide(s, (function() {
                                        d.postSlide(s)
                                    }
                                    ))) : d.postSlide(s),
                                    void d.animateHeight();
                                !0 !== i && d.slideCount > d.options.slidesToShow ? d.animateSlide(l, (function() {
                                    d.postSlide(s)
                                }
                                )) : d.postSlide(s)
                            }
                    }
                    ,
                    i.prototype.startLoad = function() {
                        var t = this;
                        !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && (t.$prevArrow.hide(),
                        t.$nextArrow.hide()),
                        !0 === t.options.dots && t.slideCount > t.options.slidesToShow && t.$dots.hide(),
                        t.$slider.addClass("slick-loading")
                    }
                    ,
                    i.prototype.swipeDirection = function() {
                        var t, e, i, n, s = this;
                        return t = s.touchObject.startX - s.touchObject.curX,
                        e = s.touchObject.startY - s.touchObject.curY,
                        i = Math.atan2(e, t),
                        (n = Math.round(180 * i / Math.PI)) < 0 && (n = 360 - Math.abs(n)),
                        n <= 45 && n >= 0 || n <= 360 && n >= 315 ? !1 === s.options.rtl ? "left" : "right" : n >= 135 && n <= 225 ? !1 === s.options.rtl ? "right" : "left" : !0 === s.options.verticalSwiping ? n >= 35 && n <= 135 ? "down" : "up" : "vertical"
                    }
                    ,
                    i.prototype.swipeEnd = function(t) {
                        var e, i, n = this;
                        if (n.dragging = !1,
                        n.swiping = !1,
                        n.scrolling)
                            return n.scrolling = !1,
                            !1;
                        if (n.interrupted = !1,
                        n.shouldClick = !(n.touchObject.swipeLength > 10),
                        void 0 === n.touchObject.curX)
                            return !1;
                        if (!0 === n.touchObject.edgeHit && n.$slider.trigger("edge", [n, n.swipeDirection()]),
                        n.touchObject.swipeLength >= n.touchObject.minSwipe) {
                            switch (i = n.swipeDirection()) {
                            case "left":
                            case "down":
                                e = n.options.swipeToSlide ? n.checkNavigable(n.currentSlide + n.getSlideCount()) : n.currentSlide + n.getSlideCount(),
                                n.currentDirection = 0;
                                break;
                            case "right":
                            case "up":
                                e = n.options.swipeToSlide ? n.checkNavigable(n.currentSlide - n.getSlideCount()) : n.currentSlide - n.getSlideCount(),
                                n.currentDirection = 1
                            }
                            "vertical" != i && (n.slideHandler(e),
                            n.touchObject = {},
                            n.$slider.trigger("swipe", [n, i]))
                        } else
                            n.touchObject.startX !== n.touchObject.curX && (n.slideHandler(n.currentSlide),
                            n.touchObject = {})
                    }
                    ,
                    i.prototype.swipeHandler = function(t) {
                        var e = this;
                        if (!(!1 === e.options.swipe || "ontouchend"in document && !1 === e.options.swipe || !1 === e.options.draggable && -1 !== t.type.indexOf("mouse")))
                            switch (e.touchObject.fingerCount = t.originalEvent && void 0 !== t.originalEvent.touches ? t.originalEvent.touches.length : 1,
                            e.touchObject.minSwipe = e.listWidth / e.options.touchThreshold,
                            !0 === e.options.verticalSwiping && (e.touchObject.minSwipe = e.listHeight / e.options.touchThreshold),
                            t.data.action) {
                            case "start":
                                e.swipeStart(t);
                                break;
                            case "move":
                                e.swipeMove(t);
                                break;
                            case "end":
                                e.swipeEnd(t)
                            }
                    }
                    ,
                    i.prototype.swipeMove = function(t) {
                        var e, i, n, s, o, r, a = this;
                        return o = void 0 !== t.originalEvent ? t.originalEvent.touches : null,
                        !(!a.dragging || a.scrolling || o && 1 !== o.length) && (e = a.getLeft(a.currentSlide),
                        a.touchObject.curX = void 0 !== o ? o[0].pageX : t.clientX,
                        a.touchObject.curY = void 0 !== o ? o[0].pageY : t.clientY,
                        a.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(a.touchObject.curX - a.touchObject.startX, 2))),
                        r = Math.round(Math.sqrt(Math.pow(a.touchObject.curY - a.touchObject.startY, 2))),
                        !a.options.verticalSwiping && !a.swiping && r > 4 ? (a.scrolling = !0,
                        !1) : (!0 === a.options.verticalSwiping && (a.touchObject.swipeLength = r),
                        i = a.swipeDirection(),
                        void 0 !== t.originalEvent && a.touchObject.swipeLength > 4 && (a.swiping = !0,
                        t.preventDefault()),
                        s = (!1 === a.options.rtl ? 1 : -1) * (a.touchObject.curX > a.touchObject.startX ? 1 : -1),
                        !0 === a.options.verticalSwiping && (s = a.touchObject.curY > a.touchObject.startY ? 1 : -1),
                        n = a.touchObject.swipeLength,
                        a.touchObject.edgeHit = !1,
                        !1 === a.options.infinite && (0 === a.currentSlide && "right" === i || a.currentSlide >= a.getDotCount() && "left" === i) && (n = a.touchObject.swipeLength * a.options.edgeFriction,
                        a.touchObject.edgeHit = !0),
                        !1 === a.options.vertical ? a.swipeLeft = e + n * s : a.swipeLeft = e + n * (a.$list.height() / a.listWidth) * s,
                        !0 === a.options.verticalSwiping && (a.swipeLeft = e + n * s),
                        !0 !== a.options.fade && !1 !== a.options.touchMove && (!0 === a.animating ? (a.swipeLeft = null,
                        !1) : void a.setCSS(a.swipeLeft))))
                    }
                    ,
                    i.prototype.swipeStart = function(t) {
                        var e, i = this;
                        if (i.interrupted = !0,
                        1 !== i.touchObject.fingerCount || i.slideCount <= i.options.slidesToShow)
                            return i.touchObject = {},
                            !1;
                        void 0 !== t.originalEvent && void 0 !== t.originalEvent.touches && (e = t.originalEvent.touches[0]),
                        i.touchObject.startX = i.touchObject.curX = void 0 !== e ? e.pageX : t.clientX,
                        i.touchObject.startY = i.touchObject.curY = void 0 !== e ? e.pageY : t.clientY,
                        i.dragging = !0
                    }
                    ,
                    i.prototype.unfilterSlides = i.prototype.slickUnfilter = function() {
                        var t = this;
                        null !== t.$slidesCache && (t.unload(),
                        t.$slideTrack.children(this.options.slide).detach(),
                        t.$slidesCache.appendTo(t.$slideTrack),
                        t.reinit())
                    }
                    ,
                    i.prototype.unload = function() {
                        var e = this;
                        t(".slick-cloned", e.$slider).remove(),
                        e.$dots && e.$dots.remove(),
                        e.$prevArrow && e.htmlExpr.test(e.options.prevArrow) && e.$prevArrow.remove(),
                        e.$nextArrow && e.htmlExpr.test(e.options.nextArrow) && e.$nextArrow.remove(),
                        e.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden", "true").css("width", "")
                    }
                    ,
                    i.prototype.unslick = function(t) {
                        var e = this;
                        e.$slider.trigger("unslick", [e, t]),
                        e.destroy()
                    }
                    ,
                    i.prototype.updateArrows = function() {
                        var t = this;
                        Math.floor(t.options.slidesToShow / 2),
                        !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && !t.options.infinite && (t.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false"),
                        t.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false"),
                        0 === t.currentSlide ? (t.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true"),
                        t.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false")) : (t.currentSlide >= t.slideCount - t.options.slidesToShow && !1 === t.options.centerMode || t.currentSlide >= t.slideCount - 1 && !0 === t.options.centerMode) && (t.$nextArrow.addClass("slick-disabled").attr("aria-disabled", "true"),
                        t.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false")))
                    }
                    ,
                    i.prototype.updateDots = function() {
                        var t = this;
                        null !== t.$dots && (t.$dots.find("li").removeClass("slick-active").end(),
                        t.$dots.find("li").eq(Math.floor(t.currentSlide / t.options.slidesToScroll)).addClass("slick-active"))
                    }
                    ,
                    i.prototype.visibility = function() {
                        var t = this;
                        t.options.autoplay && (document[t.hidden] ? t.interrupted = !0 : t.interrupted = !1)
                    }
                    ,
                    t.fn.slick = function() {
                        var t, e, n = this, s = arguments[0], o = Array.prototype.slice.call(arguments, 1), r = n.length;
                        for (t = 0; t < r; t++)
                            if ("object" == typeof s || void 0 === s ? n[t].slick = new i(n[t],s) : e = n[t].slick[s].apply(n[t].slick, o),
                            void 0 !== e)
                                return e;
                        return n
                    }
                }
                ,
                void 0 === (o = n.apply(e, s)) || (t.exports = o)
            }()
        }
        ,
        669: t => {
            "use strict";
            t.exports = jQuery
        }
    }
      , e = {};
    function i(n) {
        var s = e[n];
        if (void 0 !== s)
            return s.exports;
        var o = e[n] = {
            exports: {}
        };
        return t[n].call(o.exports, o, o.exports, i),
        o.exports
    }
    ( () => {
        "use strict";
        i(760),
        i(960),
        i(17),
        i(13),
        i(635),
        i(647),
        jQuery(document).ready((function(t) {
            !function(t) {
                function e() {
                    var t = document.getElementById("wpadminbar")
                      , e = t ? t.offsetHeight : 0
                      , i = document.querySelector(".site-header")
                      , n = i ? i.offsetHeight : 0;
                    document.documentElement.style.setProperty("--top-offset", e + "px"),
                    document.documentElement.style.setProperty("--header-padding-top", n + "px")
                }
                function i() {
                    var t = .01 * window.innerHeight;
                    document.documentElement.style.setProperty("--vh", "".concat(t, "px"))
                }
                window.addEventListener("resize", (function() {
                    i(),
                    e()
                }
                )),
                window.onload = function() {
                    i(),
                    e()
                }
                ,
                t(".burger-menu-button").click((function() {
                    t(this).toggleClass("active"),
                    t(this).closest("header").find(".mobile-menu").toggleClass("active"),
                    console.log(t(this).closest("header")),
                    t("body").toggleClass("lock"),
                    t(".shape-border-block").toggleClass("d-none")
                }
                ));
                var n = 0 == t("#wpadminbar").length ? 0 : t("#wpadminbar").height();
                t("a[href^='#']").on("click", (function() {
                    var e = t(this).attr("href");
                    return t("html, body").animate({
                        scrollTop: t(e).offset().top - (n + t(".site-header").height())
                    }),
                    !1
                }
                )),
                t("#mobile-menu").find("a").on("click", (function() {
                    t(this).closest("#mobile-menu").removeClass("active"),
                    t(this).closest("header").find(".mobile-menu").removeClass("active"),
                    t("body").removeClass("lock"),
                    t(".burger-menu-button").removeClass("active")
                }
                )),
                t(document).ready((function() {
                    var e = 0;
                    t(window).scroll((function() {
                        var i = t(this).scrollTop();
                        i > e ? t("header").removeClass("scroll-up").addClass("scroll-down") : t("header").removeClass("scroll-down").addClass("scroll-up"),
                        0 == i && t("header").removeClass("scroll-up scroll-down"),
                        e = i
                    }
                    ))
                }
                ))
            }(t),
            function(t) {
                t(".about-us .desc").each((function() {
                    t(this).addClass("toggle-text-activated");
                    var e = t(this).html();
                    if (e.replace(/ +(?= )/g, "").length > 800) {
                        var i = '<div class="truncate-text" style="display:block">' + e.substr(0, 800) + '<span class="moreellipses">...&nbsp;&nbsp;</span></span><p><a href="javascript:void(0);" class="read-full learn-more more">Читати повний опис</a></p></div><div class="truncate-text" style="display:none">' + e + '<p><a href="javascript:void(0);" class="read-full learn-more less">Читати короткий опис</a></span></p></div>';
                        t(this).html(i)
                    }
                }
                )),
                t(".about-us .read-full").click((function() {
                    var e = t(this)
                      , i = e.closest(".truncate-text")
                      , n = ".truncate-text";
                    return e.hasClass("less") ? (i.prev(n).toggle(),
                    i.toggle()) : (i.toggle(),
                    i.next(n).toggle()),
                    !1
                }
                ))
            }(t),
            function(t) {
                t("footer .copy").click((function() {
                    var e = t(this).prev().val();
                    console.log(e),
                    navigator.clipboard.writeText(e).then((function() {
                        console.log("Текст успешно скопирован в буфер обмена")
                    }
                    ), (function(t) {
                        console.error("Не удалось скопировать текст: ", t)
                    }
                    ))
                }
                ))
            }(t),
            function(t) {
                t("div.woocommerce").on("change", ".qty", (function() {
                    t("[name='update_cart']").trigger("click"),
                    t.ajax({
                        context: this,
                        type: "post",
                        url: ajax_object.ajax_url,
                        data: {
                            action: "update_cart"
                        },
                        beforeSend: function(t) {},
                        success: function(e) {
                            t("a.cart span").text(e)
                        }
                    }),
                    t("a.added_to_cart").each((function() {
                        t(this).text("Переглянути кошик")
                    }
                    ))
                }
                )),
                t("div.woocommerce").on("input", ".qty", (function() {
                    t("[name='update_cart']").trigger("click"),
                    t.ajax({
                        context: this,
                        type: "post",
                        url: ajax_object.ajax_url,
                        data: {
                            action: "update_cart"
                        },
                        beforeSend: function(t) {},
                        success: function(e) {
                            t("a.cart span").text(e)
                        }
                    }),
                    t("a.added_to_cart").each((function() {
                        t(this).text("Переглянути кошик")
                    }
                    ))
                }
                )),
                t(".file-upload").on("change", (function() {
                    !function(e) {
                        if (e.files && e.files[0]) {
                            var i = new FileReader;
                            i.onload = function(e) {
                                t(".profile-pic").attr("src", e.target.result)
                            }
                            ,
                            i.readAsDataURL(e.files[0])
                        }
                    }(this),
                    t(this).closest(".circle").next().slideDown()
                }
                )),
                t(".save-photo-block").find(".cancel-button").click((function() {
                    t(this).closest(".save-photo-block").slideUp()
                }
                )),
                t(".save-photo-block").find(".save-photo").click((function() {
                    t("#change-photo").submit()
                }
                )),
                t(".upload-button").click((function(e) {
                    e.originalEvent && (console.log("sons"),
                    t(this).find(".file-upload").click())
                }
                )),
                jQuery(document).ready((function(t) {
                    t(document.body).on("added_to_cart", (function() {
                        t.ajax({
                            context: this,
                            type: "post",
                            url: ajax_object.ajax_url,
                            data: {
                                action: "update_cart"
                            },
                            beforeSend: function(t) {},
                            success: function(e) {
                                t("a.cart span").text(e)
                            }
                        }),
                        t("a.added_to_cart").each((function() {
                            t(this).text("Переглянути кошик")
                        }
                        ))
                    }
                    )),
                    t(document.body).on("click", ".woocommerce .social-networks a", (function() {
                        var e = {
                            social_network: t(this).data("social"),
                            action: "update_subscription"
                        };
                        t.ajax({
                            context: this,
                            type: "post",
                            url: ajax_object.ajax_url,
                            data: e,
                            beforeSend: function(t) {},
                            success: function(e) {
                                var i = e.social_networks;
                                console.log(e);
                                var n = e.is_task_finished;
                                "1" == i[t(this).data("social")] && (console.log(t(this).data("social") + " Activated"),
                                t(this).next().addClass("active")),
                                "1" == n && (t(".referral-program-wrapper a").removeClass("disabled"),
                                t("#referral-program-menu-link").removeClass("d-none"),
                                t("#do-tasks-description").hide(),
                                t(".referral-not-allowed").hide())
                            }
                        })
                    }
                    )),
                    t(document.body).on("click", ".woocommerce .white-shadow-box a", (function() {
                        console.log("sons");
                        var e = {
                            social_network: t(this).data("social"),
                            action: "update_subscription"
                        };
                        t.ajax({
                            context: this,
                            type: "post",
                            url: ajax_object.ajax_url,
                            data: e,
                            beforeSend: function(t) {},
                            success: function(e) {
                                var i = e.social_networks;
                                console.log(e);
                                var n = e.is_task_finished;
                                if ("1" == i[t(this).data("social")]) {
                                    console.log(t(this).data("social") + " Activated");
                                    var s = t(this).data("social");
                                    t(this).addClass("disabled"),
                                    t(this).text("Виконано"),
                                    t("ul.social-networks").find("." + s).next().addClass("active")
                                }
                                "1" == n && (t(".referral-program-wrapper a").removeClass("disabled"),
                                t("#referral-program-menu-link").removeClass("d-none"),
                                t("#do-tasks-description").hide(),
                                t(".referral-not-allowed").hide())
                            }
                        })
                    }
                    ));
                    var e = new Date("October 31, 2024 23:59:59").getTime()
                      , i = setInterval((function() {
                        var n = (new Date).getTime()
                          , s = e - n
                          , o = Math.floor(s / 864e5)
                          , r = Math.floor(s % 864e5 / 36e5)
                          , a = Math.floor(s % 36e5 / 6e4)
                          , l = Math.floor(s % 6e4 / 1e3);
                        t("#countdown-timer").html(o + "д " + r + "год " + a + "хв " + l + "с "),
                        s < 0 && (clearInterval(i),
                        t("#countdown-timer").html("Програма почалася!"))
                    }
                    ), 1e3)
                      , n = new Date("December 1, 2024 00:00:00").getTime()
                      , s = setInterval((function() {
                        var e = (new Date).getTime()
                          , i = n - e
                          , o = Math.floor(i / 864e5);
                        Math.floor(i % 864e5 / 36e5),
                        Math.floor(i % 36e5 / 6e4),
                        Math.floor(i % 6e4 / 1e3),
                        t("#time-left-before-price-incease").html(o),
                        i < 0 && (clearInterval(s),
                        t("#time-left-before-price-incease").html("1 жовтня"))
                    }
                    ), 1e3)
                }
                ))
            }(t),
            function(t) {
                jQuery.extend(jQuery.validator.messages, {
                    required: "Це поле є обов'язковим."
                }),
                t("#change_account_password").validate({
                    rules: {
                        password: {
                            required: !0,
                            minlength: 5
                        },
                        confirm_password: {
                            required: !0,
                            minlength: 5,
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        password: {
                            required: "Please provide a password",
                            minlength: "Your password must be at least 5 characters long"
                        },
                        confirm_password: {
                            required: "Please provide a password",
                            minlength: "Your password must be at least 5 characters long",
                            equalTo: "Please enter the same password as above"
                        }
                    }
                }),
                t(".form-inline").validate(),
                t(".form-inline input").on("input", (function() {
                    var e = t(this).val().replace(/[^a-zA-Z0-9_]/g, "");
                    t(this).val(e)
                }
                )),
                t.validator.addMethod("validUsername", (function(t, e) {
                    return this.optional(e) || /^[a-zA-Z0-9_\-]+$/.test(t)
                }
                ), "Логін має містити лише латинські букви, цифри, підчеркивання та дефіси"),
                t("#register-form").validate({
                    rules: {
                        email: {
                            required: !0,
                            email: !0
                        },
                        password: {
                            required: !0,
                            minlength: 5
                        },
                        confirm_password: {
                            required: !0,
                            minlength: 5,
                            equalTo: "#password"
                        },
                        username: {
                            required: !0,
                            minlength: 3,
                            validUsername: !0
                        }
                    },
                    messages: {
                        password: {
                            required: "Будь-ласка, введіть пароль",
                            minlength: "Ваш пароль має містити в собі не менше, ніж 5 символів"
                        },
                        confirm_password: {
                            required: "Підтвердіть пароль",
                            minlength: "Ваш пароль має містити в собі не менше, ніж 5 символів",
                            equalTo: "Будь-ласка, підтвердіть пароль"
                        },
                        username: {
                            required: "Будь-ласка, введіть логін",
                            minlength: "Логін має містити в собі не менше, ніж 3 символи",
                            validUsername: "Логін має містити лише латинські букви, цифри, підчеркивання та дефіси"
                        }
                    }
                }),
                t("#update_account").validate({
                    rules: {
                        email: {
                            required: !0,
                            email: !0
                        }
                    },
                    messages: {
                        email: {
                            required: "Please enter your email",
                            email: "Please enter a valid email address"
                        }
                    }
                })
            }(t),
            function(t) {
                t("#register-form #country_selector").countrySelect({
                    preferredCountries: ["ua", "gb", "us"]
                });
                var e = document.querySelector("#phone_number");
                e && window.intlTelInput(e, {
                    initialCountry: "auto",
                    geoIpLookup: function(e) {
                        t.get("https://ipinfo.io", (function() {}
                        ), "jsonp").done((function(t) {
                            var i = t && t.country ? t.country : "";
                            e(i)
                        }
                        )).fail((function() {
                            console.log("Ошибка получения данных о местоположении"),
                            e("")
                        }
                        ))
                    },
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                })
            }(t),
            function(t) {
                t(document).ready((function() {
                    var e = document.getElementById("month-range-slider")
                      , i = document.getElementById("month-range")
                      , n = i.getAttribute("data-max-month")
                      , s = document.getElementById("product-count")
                      , o = future_price_range;
                    if (e && i) {
                        var r = noUiSlider.create(e, {
                            start: [1],
                            step: 1,
                            range: {
                                min: 1,
                                max: n ? parseInt(n) : 60
                            },
                            format: {
                                to: function(t) {
                                    return Math.round(t)
                                },
                                from: function(t) {
                                    return Math.round(t)
                                }
                            }
                        });
                        r.on("update", (function(e, i) {
                            t("#month-range").val(e[i]),
                            a(t("#month-range")),
                            l(t("#month-range"))
                        }
                        )),
                        i.addEventListener("change", (function() {
                            r.set(this.value),
                            a(this),
                            l(this)
                        }
                        )),
                        s.addEventListener("change", (function() {
                            a(this),
                            l(this)
                        }
                        )),
                        i.addEventListener("input", (function() {
                            r.set(this.value),
                            a(this),
                            l(this)
                        }
                        )),
                        s.addEventListener("input", (function() {
                            a(this),
                            l(this)
                        }
                        ))
                    }
                    function a(e) {
                        var i, n = t(e).closest(".product-calculator"), s = parseFloat(n.data("property-price")), o = n.find("#product-count").val() ? parseInt(n.find("#product-count").val()) : 1, r = 30 * s * ((i = parseInt(n.find("#month-range").val())) * o);
                        n.find("#economy-price .price .value").text(r),
                        (i = n.find("#future-product-price label strong")).text(i)
                    }
                    function l(e) {
                        var i, n = t(e).closest(".product-calculator"), s = parseInt(n.find("#month-range").val()), r = n.data("future-month-range") ? parseFloat(n.data("future-month-range")) : 4, a = Math.floor(s / r);
                        i = a < o.length ? o[a] : o[o.length - 1],
                        n.find("#future-product-price .price .value").text(i),
                        n.find("#future-product-price label strong").text(s)
                    }
                }
                ))
            }(t),
            function(t) {
                function e(t) {
                    return t.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")
                }
                t(document).ready((function() {
                    var i = document.getElementById("calculator-1-forever-count");
                    i && (noUiSlider.create(i, {
                        start: [1],
                        step: 100,
                        range: {
                            min: 0,
                            max: parseInt(25e3)
                        },
                        format: {
                            to: function(t) {
                                return Math.round(t)
                            },
                            from: function(t) {
                                return Math.round(t)
                            }
                        }
                    }).on("update", (function(i, n) {
                        t("#calculator-1-product-count").text(i[n]);
                        var s = t("#calculator-1").data("product-price")
                          , o = i[n]
                          , r = o * s
                          , a = .85 * o
                          , l = 8 * o
                          , d = l + a - s * o
                          , c = .035 * r
                          , u = .05 * r
                          , h = .08 * r;
                        t("#invested .value").text(e(r.toFixed(2))),
                        t("#year-income .value").text(e(a.toFixed(2))),
                        t("#price-growth-income .value").text(e(l.toFixed(2))),
                        t("#general-price .value").text(e(d.toFixed(2))),
                        t("#deposyt .value").text(e(c.toFixed(2))),
                        t("#residental-realestate-income .value").text(e(u.toFixed(2))),
                        t("#commercial-realestate-income .value").text(e(h.toFixed(2)))
                    }
                    )),
                    i.noUiSlider.pips({
                        mode: "values",
                        values: [0, 2500, 5e3, 7500, 1e4, 12500, 15e3, 17500, 2e4, 22500, 25e3],
                        density: 4,
                        filter: function(t, e) {
                            return t % 2500 == 0 || 1 == t ? 1 : 0
                        }
                    }));
                    var n = document.getElementById("calculator-2-month-income");
                    if (n) {
                        var s = function() {
                            window.innerWidth <= 767 ? n.noUiSlider.pips({
                                mode: "values",
                                values: [50, 1e3, 2e3, 3e3, 4e3, 5e3],
                                density: 500,
                                filter: function(t, e) {
                                    return t % 500 == 0 || 50 == t ? 1 : 0
                                }
                            }) : n.noUiSlider.pips({
                                mode: "values",
                                values: [50, 500, 1e3, 1500, 2e3, 2500, 3e3, 3500, 4e3, 4500, 5e3],
                                density: 500,
                                filter: function(t, e) {
                                    return t % 500 == 0 || 50 == t ? 1 : 0
                                }
                            })
                        };
                        noUiSlider.create(n, {
                            start: [1],
                            step: 50,
                            range: {
                                min: 50,
                                max: parseInt(5e3)
                            },
                            format: {
                                to: function(t) {
                                    return Math.round(t)
                                },
                                from: function(t) {
                                    return Math.round(t)
                                }
                            }
                        }).on("update", (function(i, n) {
                            t("#calculator-2-month-income-value").text(i[n].toFixed(2));
                            var s = t("#calculator-2").data("product-price")
                              , o = i[n]
                              , r = o / .85 * s * 12
                              , a = o / .00291
                              , l = o / .00416
                              , d = o / .0066;
                            t("#invest-sum .value").text(e(r.toFixed(2))),
                            t("#sum-deposyt .value").text(e(a.toFixed(2))),
                            t("#sum-residental-realestate-income .value").text(e(l.toFixed(2))),
                            t("#sum-commercial-realestate-income .value").text(e(d.toFixed(2)))
                        }
                        )),
                        window.addEventListener("resize", s),
                        window.addEventListener("load", s)
                    }
                }
                ))
            }(t),
            function(t) {
                t(".buy-forever-by-one-click button").on("click", (function() {
                    var e = {
                        product_id: t(this).prev().val(),
                        action: "add_to_cart_by_one_click"
                    };
                    t.ajax({
                        context: this,
                        type: "post",
                        url: ajax_object.ajax_url,
                        data: e,
                        beforeSend: function(e) {
                            t(this).text("Додаємо в корзину...")
                        },
                        success: function(t) {
                            t.success ? window.location.href = t.data.cart_url : alert("Ошибка при добавлении товара в корзину.")
                        }
                    })
                }
                ))
            }(t),
            function(t) {
                function e(e, i) {
                    var n = t("#" + e)
                      , s = n.find("input[name='forevers-count']")
                      , o = t("<p class='balance-feedback'></p>").insertAfter(s);
                    n.validate({
                        rules: {
                            "forevers-count": {
                                required: !0,
                                number: !0,
                                min: 1,
                                step: 1,
                                validateBalance: !0
                            }
                        },
                        messages: {
                            "forevers-count": {
                                required: "Будь-ласка, введіть кількість фореверсів",
                                number: "Кількість фореверсів має бути числом",
                                min: "Мінімальна кількість фореверсів: 1",
                                step: "Введіть ціле число"
                            }
                        },
                        submitHandler: function(e) {
                            var s = {
                                formdata: t(e).serialize(),
                                action: i
                            };
                            t.ajax({
                                url: ajax_object.ajax_url,
                                type: "POST",
                                data: s,
                                success: function(t) {
                                    t && (n.hide(),
                                    n.next().show(),
                                    setTimeout((function() {
                                        location.reload()
                                    }
                                    ), 5e3))
                                },
                                error: function(t, e, i) {
                                    alert("Произошла ошибка: " + i)
                                }
                            })
                        }
                    }),
                    t.validator.addMethod("validateBalance", (function(e, i) {
                        var n = parseFloat(t(i).data("forevers-price"))
                          , s = parseFloat(t(i).data("max-value"))
                          , r = e * n
                          , a = s - r;
                        return a >= 0 ? (o.css("color", "green").text("Вартість покупки: ".concat(r.toFixed(2), " USD, Залишок: ").concat(a.toFixed(2), " USD")),
                        !0) : (o.css("color", "red").text("Недостатньо коштів. Доступний залишок: ".concat(s.toFixed(2), " USD")),
                        !1)
                    }
                    ), "У Вас на рахунку не достатньо коштів для покупки даної кількості Фореверсів."),
                    s.on("input", (function() {
                        t(this).valid()
                    }
                    ))
                }
                t("#withdraw-income").find("select").on("change", (function() {
                    var e = t(this).val();
                    "crypto" == e ? (console.log("sons"),
                    t(this).closest(".form-group").find(".crypto-block").slideDown(),
                    t(this).closest(".form-group").find(".fop-block").slideUp()) : "fop" == e ? (t(this).closest(".form-group").find(".crypto-block").slideUp(),
                    t(this).closest(".form-group").find(".fop-block").slideDown()) : (t(this).closest(".form-group").find(".crypto-block").slideUp(),
                    t(this).closest(".form-group").find(".fop-block").slideUp())
                }
                )),
                t("#withdraw-income").validate({
                    rules: {
                        user_id: "required",
                        surname: "required",
                        first_name: "required",
                        last_name: "required",
                        sum: {
                            required: !0,
                            number: !0,
                            min: 20,
                            max: t("input[data-max-value]").data("max-value")
                        },
                        payment_method: "required",
                        crypto_wallet: {
                            required: function() {
                                return "crypto" === t('select[name="payment_method"]').val()
                            }
                        },
                        id_number: {
                            required: function() {
                                return "fop" === t('select[name="payment_method"]').val()
                            }
                        },
                        iban: {
                            required: function() {
                                return "fop" === t('select[name="payment_method"]').val()
                            }
                        }
                    },
                    messages: {
                        user_id: "ID пользователя обязателен",
                        surname: "По-батькові обов'язкове поле для заповнення",
                        first_name: "Ім'я обов'язкове поле для заповнення",
                        last_name: "Прізвище обов'язкове поле для заповнення",
                        sum: {
                            required: "Введіть суму",
                            number: "Сумма має бути числом",
                            min: "Мінімальна сума для виводу " + t("input[data-min-value]").data("min-value") + "$",
                            max: "У Вас на рахунку не достаньо коштів."
                        },
                        payment_method: "Виберіть метод оплати",
                        crypto_wallet: "Введіть номер криптогаманця",
                        id_number: "Введіть індифікаційний номер",
                        iban: "Введіть номер рахунку у формі IBAN"
                    },
                    errorElement: "div",
                    errorPlacement: function(t, e) {
                        t.addClass("invalid-feedback"),
                        e.closest(".form-group").append(t)
                    },
                    highlight: function(e, i, n) {
                        t(e).addClass("is-invalid").removeClass("is-valid")
                    },
                    unhighlight: function(e, i, n) {
                        t(e).addClass("is-valid").removeClass("is-invalid")
                    },
                    submitHandler: function(e) {
                        console.log(t(e).serialize());
                        var i = {
                            formdata: t(e).serialize(),
                            action: "withdraw_income"
                        };
                        t.ajax({
                            url: ajax_object.ajax_url,
                            type: "POST",
                            data: i,
                            success: function(e) {
                                console.log(e),
                                e && (t("#withdraw-income").css("display", "none"),
                                t(".success-block").css("display", "block"),
                                t("span#my-coin-balance").html(e.balance),
                                setTimeout((function() {
                                    location.reload()
                                }
                                ), 5e3))
                            },
                            error: function(t, e, i) {
                                alert("Произошла ошибка: " + i)
                            }
                        })
                    }
                }),
                t("#withdraw-ref-income").find("select").on("change", (function() {
                    var e = t(this).val();
                    "crypto" == e ? (console.log("sons"),
                    t(this).closest(".form-group").find(".crypto-block").slideDown(),
                    t(this).closest(".form-group").find(".fop-block").slideUp()) : "fop" == e ? (t(this).closest(".form-group").find(".crypto-block").slideUp(),
                    t(this).closest(".form-group").find(".fop-block").slideDown()) : (t(this).closest(".form-group").find(".crypto-block").slideUp(),
                    t(this).closest(".form-group").find(".fop-block").slideUp())
                }
                )),
                t("#withdraw-ref-income").validate({
                    rules: {
                        user_id: "required",
                        surname: "required",
                        first_name: "required",
                        last_name: "required",
                        sum: {
                            required: !0,
                            number: !0,
                            min: 20,
                            max: t("input[data-max-value]").data("max-value")
                        },
                        payment_method: "required",
                        crypto_wallet: {
                            required: function() {
                                return "crypto" === t('select[name="payment_method"]').val()
                            }
                        },
                        id_number: {
                            required: function() {
                                return "fop" === t('select[name="payment_method"]').val()
                            }
                        },
                        iban: {
                            required: function() {
                                return "fop" === t('select[name="payment_method"]').val()
                            }
                        }
                    },
                    messages: {
                        user_id: "ID пользователя обязателен",
                        surname: "По-батькові обов'язкове поле для заповнення",
                        first_name: "Ім'я обов'язкове поле для заповнення",
                        last_name: "Прізвище обов'язкове поле для заповнення",
                        sum: {
                            required: "Введіть суму",
                            number: "Сумма має бути числом",
                            min: "Мінімальна сума для виводу " + t("input[data-min-value]").data("min-value") + "$",
                            max: "У Вас на рахунку не достаньо коштів."
                        },
                        payment_method: "Виберіть метод оплати",
                        crypto_wallet: "Введіть номер криптогаманця",
                        id_number: "Введіть індифікаційний номер",
                        iban: "Введіть номер рахунку у формі IBAN"
                    },
                    errorElement: "div",
                    errorPlacement: function(t, e) {
                        t.addClass("invalid-feedback"),
                        e.closest(".form-group").append(t)
                    },
                    highlight: function(e, i, n) {
                        t(e).addClass("is-invalid").removeClass("is-valid")
                    },
                    unhighlight: function(e, i, n) {
                        t(e).addClass("is-valid").removeClass("is-invalid")
                    },
                    submitHandler: function(e) {
                        console.log(t(e).serialize());
                        var i = {
                            formdata: t(e).serialize(),
                            action: "withdraw_ref_income"
                        };
                        t.ajax({
                            url: ajax_object.ajax_url,
                            type: "POST",
                            data: i,
                            success: function(e) {
                                console.log(e),
                                e && (t("#withdraw-ref-income").css("display", "none"),
                                t(".success-block").css("display", "block"),
                                t("span#my-coin-balance").html(e.balance),
                                setTimeout((function() {
                                    location.reload()
                                }
                                ), 5e3))
                            },
                            error: function(t, e, i) {
                                alert("Произошла ошибка: " + i)
                            }
                        })
                    }
                }),
                e("income-to-forevers", "income_to_forevers"),
                e("bonus-to-forevers", "bonus_to_forevers")
            }(t)
        }
        ))
    }
    )()
}
)();
</script>