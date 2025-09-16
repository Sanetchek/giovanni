<?php
// MLM - PHP Script

if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
if ($m["deposit"] !== "1") {
    $redirect = $settings['url']."account/summary";
    header("Location: $redirect");
}
?>
<div class="container-fluid py-4">
    <div class="h-100 p-3">
        
        <?php
        
        	if(isset($_POST['reward_id'])) {
        		$FormBTN = protect($_POST['reward_id']);
                if($FormBTN > 0) {
                    $reward_id = $FormBTN;
                    $rew_query = $db->query("SELECT * FROM reward WHERE id='$reward_id'");
                    if ($rew_query->num_rows>0) {
                        $rew_row_query = $rew_query->fetch_assoc();
                        $ply_rew_query = $db->query("SELECT * FROM reward_log WHERE uid='$_SESSION[uid]' and reward_id='$reward_id'");
                        if ($ply_rew_query->num_rows>0) {
                            //Player already claimed.
                            echo info("You already has claimed this reward.");
                        } else {
                            $ply_rew_row_query = $ply_rew_query->fetch_assoc();
                            
                            $CheckMembership_2 = $db->query("SELECT * FROM membership_log WHERE uid='$_SESSION[uid]'");
                            $mem_row_2 = $CheckMembership_2->fetch_assoc();
                            $date = date('Y-m-d');
                            if ($date < $mem_row_2['end_date']) {
                                $txid = strtoupper(randomHash(10));
						        $time = time();
                                if ($rew_row_query['status'] == 1) {
                                    
                                    $queryper = $db->query("SELECT * FROM users WHERE ref1='$_SESSION[uid]' or ref2='$_SESSION[uid]' or ref3='$_SESSION[uid]' or ref4='$_SESSION[uid]' or ref5='$_SESSION[uid]' or ref6='$_SESSION[uid]' or ref7='$_SESSION[uid]' or ref8='$_SESSION[uid]' or ref9='$_SESSION[uid]' or ref10='$_SESSION[uid]' ");
		                            $sage = $queryper->num_rows;
                                    
                                    if ($sage >= $rew_row_query['reward_limit']) {
                                        
                                        $prize = $rew_row_query['reward'];
                					    $prize = number_format($prize, 2, '.', '');
                                        
                                        $description = "Reward of your claimed acheivement.";
                                        
                                        UpdateUserWallet($_SESSION['uid'],$prize,'USD',1);
            							$insert_bonus_logs = $db->query("INSERT reward_log (uid,reward_id,amount,date,txid) VALUES ('$_SESSION[uid]','$reward_id','$prize','$date','$txid')");
            							
            							$create_transaction = $db->query("INSERT transactions (txid,type,sender,description,amount,currency,status,created) 
            							VALUES ('$txid','301','$_SESSION[uid]','$description','$prize','USD','1','$time')");
                        
            							$insert_activity = $db->query("INSERT activity (txid,type,uid,amount,currency,status,created) 
            							VALUES ('$txid','301','$_SESSION[uid]','$prize','USD','1','$time')");
                							
                                        echo success("Reward has been claimed.");
                                        header("Refresh:0");
                                        
                                    } else {
                                        echo error("You need to get more referral to claim this reward.");
                                    }
                                } else {
                                    echo error("Reward is not active. Come back later.");
                                }
                            } else {
                                echo error("You doesn't have active membership.");
                            }
                        }
                    }
                }
        	}
        	
        ?>
        
        
        <?php
		$statement = "reward";
		$query = $db->query("SELECT * FROM {$statement} WHERE status='1'");
		if($query->num_rows>0) {
		while($row = $query->fetch_assoc()) {
		$queryper = $db->query("SELECT * FROM users WHERE ref1='$_SESSION[uid]' or ref2='$_SESSION[uid]' or ref3='$_SESSION[uid]' or ref4='$_SESSION[uid]' or ref5='$_SESSION[uid]' or ref6='$_SESSION[uid]' or ref7='$_SESSION[uid]' or ref8='$_SESSION[uid]' or ref9='$_SESSION[uid]' or ref10='$_SESSION[uid]' ");
		$sage= $queryper->num_rows;
		$percentage = ($sage/$row['reward_limit'])*100;
		if ($percentage >= 100) {
		    $percentage = "100";
		}
		?>
        <div class="col-md-6 overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('<?=$settings['url']?>assets/front/img/curved-images/curved1.jpg');">
            <span class="mask bg-gradient-dark"></span>
            <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                <div class="progress-wrapper">
                  <div class="progress-info">
                    <div class="progress-percentage">
                        <div class="d-flex flex-column">
                            <h3><?=$row['name']?></h3>
                            <h3>To get <?=$row['reward']?>$</h3>
                         </div>
                      <span class="text-sm font-weight-bold text-white">You need to Reach <?=$row['reward_limit']?> Referrals <b style="float:right;"><?=$percentage?>%</b></span>
                    </div>
                  </div>
                  <div class="progress" style="height:15px;">
                    <div class="progress-bar bg-gradient-<?php if ($percentage <= 50) { echo "warning"; } elseif ($percentage <= 75) { echo "info"; } elseif ($percentage > 75) { echo "success"; }?>" role="progressbar" aria-valuenow="<?=$percentage?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percentage?>%;"></div>
                  </div>
                </div>
                <br>
                <?php 
                $ply_rew_query = $db->query("SELECT * FROM reward_log WHERE uid='$_SESSION[uid]' and reward_id='$row[id]'");
                if ($ply_rew_query->num_rows>0) {
                    //echo info("You already has claimed this reward.");
                } else {
                ?>
                <?php if ($percentage == "100") { ?>
                    <form action="" method="POST">
                        <button type="submit" name="reward_id" value="<?=$row['id']?>" class="btn btn-info" style="width:100%;font-size:15px;">&#128076; Claim</button>
                    </form>
                <?php } } ?>
            </div>
        </div>
        <br>
        <?php } } ?>
        
    </div>
</div>