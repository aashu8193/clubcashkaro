<?php
session_start();
if ((isset($_SESSION['facebook_access_token'])) and (isset($_SESSION['fid']))) {
    include 'connect.inc.php';

    //fetching user information from user_table
    $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
    $result = $pdo->query($checkquery);
    $row = $result->fetch();

    $total_points = $row['total_points'] + $row['bonus_points'];
    
    //checking account_status based on ref_id_status
    if ($row['ref_id_status']=="to_submit")
    { $account_status = "IN-ACTIVE";}
    elseif($row['ref_id_status']=="approved")
    { $account_status = "ACTIVE";}
    elseif($row['ref_id_status']=="pending")
    { $account_status = "PENDING";}
    elseif($row['ref_id_status']=="rejected")
    { $account_status = "REJECTED";}
	
	
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
}else{		
	header('Location: http://clubcashkaro.com/');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/responsive.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/refid_submit.js"></script>
</head>

<body>
    
<?php include 'header.inc.php'; ?>

<!--Container Start Here-->  
<div class="container-section">
    <div class="bredcum">
        <div class="wrap">
	      <a href="#">Home</a>
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
			
			<?php if ($row['ref_id_status']=="to_submit") { ?>
			<div class="inactive-sec">
                                <div class="inactive">
                                    <img src="images/inactive.png">
                                    <div class="inactive-text">
                                      Wuhoooooo!! Congratulations!! You are now Captain Cash
                                    </div>
                                </div>
                                <div class="task">
                                    <h1>Click Below Given Link & Join CashKaro</h1>
                                    <strong>PTS <br>100</strong>
                                    <p>Sign up with CashKaro and submit your CashKaro Registered Email ID</p>
                                    <a target="_blank" href="https://cashkaro.com/join-free-now">https://cashkaro.com/join-free-now</a>
                                    <div class="reg-left" id="reg-left">
                                        <form id="register-form">
                                            <span>Your Email Id</span>
                                            <input type="email" name="emailid" placeholder="Your Email Address" required id="email1">
                                            <input type="submit" value="SUBMIT" id="submit">
                                        </form>
                                    </div>
                                    <p>You are now one step away from becoming Captain Cash!</p>
                                </div>  
                        
                        </div>
            
			<?php }
                        elseif ($row['ref_id_status']=="pending") { ?>
			<div class="pending-cash">
                            <img src="images/pending.png">
                            <div class="pending-text">
                                You are now one step
                                away from becoming
                                Captain Cash! 
                            </div>
                            <div class="image-text">
                                <p>Your account will be activated within 24 hours from the time of submission of details.</p>
                            </div>
                        </div>
                        <?php }
			elseif ($row['ref_id_status']=="approved") { ?>
			<div class="captain-cash">
                            <img src="images/image.png">
                            <div class="congrat-text">
                                Wuhoooooo!! Congratulations!! You are now Captain Cash
                            </div>
                            <div class="go-to-my-task">
                                <a href="task.php">Go To "My Tasks"</a>
                            </div>
                            <div class="image-text">
                                <p>You’re just a click away from making money. All you have to do is click and CashKaro club will perform the task for you. For every successful task done you will earn points which can be redeemed as rewards later.</p>
                            </div>
                        </div>
                        <?php }
			elseif ($row['ref_id_status']=="rejected") { ?>
                        <div class="rejected">
                            <img src="images/rejected.png">
                            <div class="rejected-text">           
                               Ooops!! You missed
                                being Captain Cash by
                                a few friends!!
                            </div>
                            <div class="image-text">
                                <p>Thank you for showing interest in CashKaro Ambassador Program, however the minimum requisite to enrol for CashKaro club is having at least 300 friends on Facebook. Every new friend is a new adventure - come back & join us once you have the same. Be the next Captain Cash!</p>
                            </div>
                        </div> 			
		        <?php }	?>
		</div>   	
        </div>
    </div>
	
    
<?php include 'about_info_credentials.inc.php'; ?>    
    
    
</div>
<!--Container Close Here--> 

<?php include 'footer.inc.php'; ?>

</body>
</html>