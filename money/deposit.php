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
<div class="row">
    <div class="col">
      <div class="h-100 p-3">
        <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('<?=$settings['url']?>assets/front/img/curved-images/curved1.jpg');">
          <span class="mask bg-gradient-info"></span>
          <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
            <h5 class="text-white font-weight-bolder mb-4 pt-2">Deposit Money</h5>
            <p class="text-white">Add Fund, we are offering various method for adding fund, let try it which will suitable for you.</p>
            <?php
            $hide_form=0;
			if(isset($_POST['deposit'])) {
			$FormBTN = protect($_POST['deposit']);
            if($FormBTN == "deposit") {
                $amount = protect($_POST['amount']);
                $currency = protect($_POST['currency']);
                $gateway = protect($_POST['deposit_via']);
                
                $query_gateway = $db->query("SELECT * FROM gateways WHERE type='1' and id='$gateway'");
                $row_gateway = $query_gateway->fetch_assoc();
                $max = $row_gateway['max_amount']; // 200 usd
                $min = $row_gateway['min_amount']; // 1 usd
                if ($currency !== "$row_gateway[currency]") {
                    $amount = currencyConvertor($amount,$currency,$row_gateway[currency]);
                    $currency = $row_gateway['currency'];
                    echo info("$row_gateway[name] Supports $row_gateway[currency] only. Amount has been converted to relevant currency automatically.");
                }
                
                if(empty($amount)) {
                    echo error($lang['error_6']);
                } elseif(!is_numeric($amount)) {
                    echo error($lang['error_7']);
                } elseif($amount<0) {
                    echo error($lang['error_7']);
                }else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $amount)) {
                    echo error("Invalid Amount");
                } elseif($amount == "0") {
                    echo error($lang['error_7']);
                } elseif($amount > $max) {
                    echo error("Maximum Deposit via $row_gateway[name] is $currency $max.");
                } elseif($amount < $min) {
                    echo error("Minimum Deposit via $row_gateway[name] is $currency $min.");
                } elseif($currency !== $row_gateway['currency']) {
                    echo error("$row_gateway[name] Supports $row_gateway[currency] only.");
                } else {
                    $amount = number_format($amount, 2, '.', '');
                    $txid = strtoupper(randomHash(10));
                    $time = time();
                    $reference_number = $currency.strtoupper(randomHash(10)); 
                    $description = 'Deposit '.$amount.' '.$currency.' to '.idinfo($_SESSION['uid'],"email");
                    $create_deposit = $db->query("INSERT deposits (uid,txid,method,amount,currency,requested_on,processed_on,reference_number,status) VALUES ('$_SESSION[uid]','$txid','$gateway','$amount','$currency','$time','0','$reference_number','3')");
                    $GetDeposit = $db->query("SELECT * FROM deposits WHERE uid='$_SESSION[uid]' ORDER BY id DESC LIMIT 1");
                    $getd = $GetDeposit->fetch_assoc();
                    $create_transaction = $db->query("INSERT transactions (txid,type,sender,recipient,description,deposit_via,amount,currency,fee,status,created) VALUES ('$txid','3','$_SESSION[uid]','$getd[id]','$description','$gateway','$amount','$currency','','3','$time')");
                    $insert_activity = $db->query("INSERT activity (txid,type,uid,deposit_via,u_field_1,amount,currency,status,created) VALUES ('$txid','3','$_SESSION[uid]','$gateway','$getd[id]','$amount','$currency','3','$time')");
                    echo getPaymentForm($getd['id'],$gateway);
                    $hide_form=1;
                }
            }
			}
            if($hide_form==0) {
            ?>
                <form class="user-connected-from user-login-form" action="" method="POST">
                    <div class="input-group input-pw-amount">
                        <input type="text" class="form-control" name="amount" placeholder="0.00" aria-label="Amount (to the nearest dollar)" required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <select class="form-control" name="currency" required>
                                    <option value="<?=$settings['default_currency']?>"><?=$settings['default_currency']?></option>';
                                </select>
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                      <label style="color:white;"><?php echo htmlspecialchars($lang['field_3'], ENT_QUOTES, 'UTF-8'); ?></label>
                        <select class="form-control" id="choices-button" name="deposit_via" required>
                            <option value="">Select Payment Gateway <i class="ni ni-zoom-split-in"></i></option>
                            <?php
                            $GetGateways = $db->query("SELECT * FROM gateways WHERE type='1' and status='1' ORDER BY id");
                            if($GetGateways->num_rows>0) {
                                while($get = $GetGateways->fetch_assoc()) {
                                    echo '<option value="'.$get['id'].'">'.$get['name'].'</option>';
                                }
                            }
                            ?>
                            
                        </select>
                    </div>
                    <button type="submit" name="deposit" value="deposit" class="btn btn-primary"><?php echo htmlspecialchars($lang['btn_10'], ENT_QUOTES, 'UTF-8'); ?></button>
                </form>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <div class="card-header pb-0">
              <h6>How to Deposit money?</h6>
            </div>
            <div class="card-body p-3">
              <div class="timeline timeline-one-side">
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-money-coins text-success text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Amount</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Enter any amount you want to deposit.</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-diamond text-danger text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Currency</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Select currency in which you want to deposit.</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-world text-info text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Gateway</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Select gateway, we offering multiple ways of deposit fund. Select one which will be suitable.</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-cart text-warning text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Deposit</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Click on deposit, Its will redirect you to payment page and complete the process.</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-satisfied text-primary text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Share & Like our Profiles</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Like and Share our website on Facebook and any other social media site.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

</div>