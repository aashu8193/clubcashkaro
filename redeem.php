<?php
session_start();
if  ((isset($_SESSION['facebook_access_token'])) and (isset($_SESSION['fid']))) {
include 'connect.inc.php';   
    //fetching user information from user_table
    $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
    $result = $pdo->query($checkquery);
    $row = $result->fetch();

    $total_points = $row['total_points'] + $row['bonus_points'];

    $points_redeemed = $row['points_redeemed'];

    //calculating rank for this particular user
    $checkquery4 = "SELECT * FROM `user_table` ORDER BY `total_points` + `bonus_points` DESC";
    $result4 = $pdo->query($checkquery4);
    while($row4 = $result4->fetch()) {
      $fid_rankwise[] =  $row4['fid'];
    }
    for($i = 0; $i < sizeof($fid_rankwise); $i++)
    {
            if ($row['fid'] == $fid_rankwise[$i])
            { $user_rank = $i + 1 ;}

    }
 	
    //checking redeem_table if already requested
    $checkquery3 = "SELECT * FROM `redeem_table` WHERE `fid` = '{$row['fid']}' AND `status` = 'requested'";
    $result3 = $pdo->query($checkquery3);
    $rowno3 = $result3->rowCount();
    $row3 = $result3->fetch();	
	
}else{
		
    header('Location: http://clubcashkaro.com/');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Redeem Points</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato"/>
<script src="js/redeem.points.js"></script> 
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/responsive.css">
</head>

<body>
<?php include 'header.inc.php'; ?>

<!--Container Start Here-->  
<div class="container-section">
            <div class="bredcum">
                <div class="wrap">
                      <a href="#">Redeem</a>
                </div>
            </div>
            <div class="welcome-section">
                <div class="wrap">
                    <div class="welcome">
                            <div class="profile">
                                <img src="<?php echo $row['fb_profile_pic_url']; ?>">
                                <h1><?php echo $row['name']; ?></h1>
                                <div class="points">
                                    <div class="point-left">
                                        <span>Total Points Earned</span>
                                        <strong><?php echo $total_points; ?></strong>
                                    </div>
                                    <div class="point-right">
                                        <span>Rank</span>
                                        <strong><?php echo $user_rank; ?></strong>
                                    </div>
                                </div>
                            </div>
			
			    <div class="redeem-sec">
                                <h1>REDEEM POINTS</h1>
                                <div class="redeem">
                                        <div class="point-avaiable">
                                            <div class="point-avaiable-left">
                                                <p>Points Available for Redemption</p>
                                                <strong><?php echo ($total_points - $points_redeemed); ?></strong>
                                            </div>
                                            <div class="point-avaiable-right">
                                                <p>Points Already Redeemed</p>
                                                <strong><?php echo $points_redeemed; ?></strong>
                                            </div>
                                        </div>

                                        <?php if($rowno3 == 0) { ?>
                                        <div class="task-perform-button">
                                            <form>
                                                <span>Enter Points to Redeem</span>
                                                <input type="text" placeholder="Enter Points To Redeem" name="points_requested">                 
                                                <div class="voucher">
                                                <h3>As Vouchers</h3>
                                                <div class="amazon"><input type="radio" name="gift_type" value="amazon_gift" checked><img src="images/amazon.png"></div>
                                                <div class="radio"><input type="radio" name="gift_type" value="flipkart_gift"><img src="images/flipkart.png"></div>       
                                                <div class="pvr"> <input type="radio" name="gift_type" value="pvr_gift"><img class="pvr" src="images/pvr.png"></div>
                                                </div>
                                                <input type="submit" id="myBtn" value="REQUEST FOR REWARDS">
                                            </form>
                                        </div>
                                        <?php }elseif ($rowno3 == 1) { ?>
                                        <div class="task-perform-button">
                                            <form>
                                                <span>You Already Have A Redeem Request</span>
                                                <input type="text" value="<?php echo $row3['points_redeemed']; ?>" name="points_requested" disabled>                                      
                                                <input type="submit" id="myBtn" value="POINTS ALREADY REQUETED" disabled>
                                            </form>
                                        </div>
                                        <?php } ?> 	
                                        <div class="point-text">
                                            <ol>
                                             <li>10 points are equivalent to 1 Rupee.</li>
                                             <li>You can Redeem once you have over 1000 Points avaiable for redemption that is 100 Rs.</li>
                                             <li>You can Redeem these points via Amazon, Flipkart & PVR Gift Vouchers.</li>
                                             <li>Once Opted for redemption points cannot be added back.</li>
                                             <li>Once Redeemed, you will get your Gift Voucher via Email within 24 Hours.</li>
                                             <li>In case you do not get your Voucher write to us at Students@Cashkaro.com</li>
                                            </ol>
                                        </div>
                                </div>	
                            </div>			
		    </div>    	
                </div>
            </div>
	   
<?php include 'about_info_credentials.inc.php'; ?>    
    
    
</div>
<!--Container Close Here--> 

<?php include 'footer.inc.php'; ?>

<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content redeem-modal">
   <h1 class="modal-text" id="modalh1">Redeem Request.</h1>
    <div class="redeem-popup-image">
	 <img src="images/logopop.png" />
    </div> 
  </div>
</div>

</body>
</html>
