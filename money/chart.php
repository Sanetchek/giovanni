<?php
// MLM - PHP Script
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}
?>
<div class="container-fluid py-4">
    <?php
	$statement = "referral_membership";
	$query = $db->query("SELECT * FROM {$statement}");
	if($query->num_rows>0) {
	while($row = $query->fetch_assoc()) {
	    $pks_1 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_1'");
	    $rpks_1 = $pks_1->fetch_assoc();
	    
	    $pks_2 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_2'");
	    $rpks_2 = $pks_2->fetch_assoc();
	    
	    $pks_3 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_3'");
	    $rpks_3 = $pks_3->fetch_assoc();
	    
	    $pks_4 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_4'");
	    $rpks_4 = $pks_4->fetch_assoc();
	    
	    $pks_5 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_5'");
	    $rpks_5 = $pks_5->fetch_assoc();
	    
	    $pks_6 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_6'");
	    $rpks_6 = $pks_6->fetch_assoc();
	    
	    $pks_7 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_7'");
	    $rpks_7 = $pks_7->fetch_assoc();
	    
	    $pks_8 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_8'");
	    $rpks_8 = $pks_8->fetch_assoc();
	    
	    $pks_9 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_9'");
	    $rpks_9 = $pks_9->fetch_assoc();
	    
	    $pks_10 = $db->query("SELECT * FROM levels WHERE mem_id='$row[id]' and name='lvl_10'");
	    $rpks_10 = $pks_10->fetch_assoc();
	?>
    <table class="table table-bordered table-striped bg-gradient-dark text-white">
        <thead>
            <tr>
                <th class="bg-gradient-info"><?=$row['name']?></th>
                <?php if ($row['levels_allow'] >= 1) { ?>
                    <th class="text-center"> Level 1</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 2) { ?>
                    <th class="text-center"> Level 2</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 3) { ?>
                    <th class="text-center"> Level 3</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 4) { ?>
                    <th class="text-center"> Level 4</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 5) { ?>
                    <th class="text-center"> Level 5</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 6) { ?>
                    <th class="text-center"> Level 6</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 7) { ?>
                    <th class="text-center"> Level 7</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 8) { ?>
                    <th class="text-center"> Level 8</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 9) { ?>
                    <th class="text-center"> Level 9</th>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 10) { ?>
                    <th class="text-center"> Level 10</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="text-nowrap text-white" scope="row">Fixed Commission</th>
                <?php if ($row['levels_allow'] >= 1) { ?>
                    <td class="text-center text-white">$<?=$rpks_1['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 2) { ?>
                    <td class="text-center text-white">$<?=$rpks_2['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 3) { ?>
                    <td class="text-center text-white">$<?=$rpks_3['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 4) { ?>
                    <td class="text-center text-white">$<?=$rpks_4['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 5) { ?>
                    <td class="text-center text-white">$<?=$rpks_5['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 6) { ?>
                    <td class="text-center text-white">$<?=$rpks_6['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 7) { ?>
                    <td class="text-center text-white">$<?=$rpks_7['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 8) { ?>
                    <td class="text-center text-white">$<?=$rpks_8['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 9) { ?>
                    <td class="text-center text-white">$<?=$rpks_9['fix_com']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 10) { ?>
                    <td class="text-center text-white">$<?=$rpks_10['fix_com']?></td>
                <?php } ?>
            </tr>
            <tr>
                <th class="text-nowrap" scope="row">Percentage Commission</th>
                <?php if ($row['levels_allow'] >= 1) { ?>
                    <td class="text-center"><?=$rpks_1['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 2) { ?>
                    <td class="text-center"><?=$rpks_2['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 3) { ?>
                    <td class="text-center"><?=$rpks_3['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 4) { ?>
                    <td class="text-center"><?=$rpks_4['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 5) { ?>
                    <td class="text-center"><?=$rpks_5['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 6) { ?>
                    <td class="text-center"><?=$rpks_6['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 7) { ?>
                    <td class="text-center"><?=$rpks_7['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 8) { ?>
                    <td class="text-center"><?=$rpks_8['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 9) { ?>
                    <td class="text-center"><?=$rpks_9['per_com']?>%</td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 10) { ?>
                    <td class="text-center"><?=$rpks_10['per_com']?>%</td>
                <?php } ?>
            </tr>
            <tr>
                <th class="text-nowrap text-white" scope="row">Referral Allowed</th>
                <?php if ($row['levels_allow'] >= 1) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 2) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 3) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 4) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 5) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 6) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 7) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 8) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 9) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
                <?php if ($row['levels_allow'] >= 10) { ?>
                    <td class="text-center text-white"><?=$row['limits']?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
    <?php } } ?>
</div>