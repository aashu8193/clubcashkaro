<?php
session_start();
if  ((isset($_SESSION['facebook_access_token'])) and (isset($_SESSION['fid']))) {
include 'connect.inc.php';    
    
    //fetching user information from user_table
    $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
    $result = $pdo->query($checkquery);
    $row = $result->fetch();

    if ($row['user_type'] == "bronze")
    { $user_type = "Bronze"; }
    elseif ($row['user_type'] == "silver")
    { $user_type = "Silver"; }
    elseif ($row['user_type'] == "gold")
    { $user_type = "Gold"; }

    $total_points = $row['total_points'] + $row['bonus_points'];
    
    //checking account_status based on ref_id_status
    if ($row['ref_id_status']=="to_submit")
    { $account_status = "IN-ACTIVE";}
    elseif($row['ref_id_status']=="approved")
    { $account_status = "ACTIVE";}
    elseif($row['ref_id_status']=="pending")
    { $account_status = "PENDING";}

    //fetching all tasks to that user
    $checkquery2 = "SELECT * FROM `task_assign_table` WHERE `fid` = '{$row['fid']}'";
    $result2 = $pdo->query($checkquery2);
    while($row2 = $result2->fetch()) {
	$taskid[] = $row2['task_id'];
        $taskstatus[] = $row2['status'];
	$points_earned[] = $row2['points_earned'];
    }
    
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
<title>My Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato"/>
<script src="js/profile.edit.js"></script>  
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/responsive.css">

</head>

<body>
<?php include 'header.inc.php'; ?>

<!--Container Start Here-->  
<div class="container-section">
        <div class="bredcum">
            <div class="wrap">
                  <a href="#">Profile</a>
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
			
			<div class="profile-page-sec">
         		<h1>Profile</h1>
                                <div class="profile-sec">
                                    <form>
                                        <div class="name">
                                            <span>Name:</span>
                                            <input type="text" value="<?php echo $row['name']; ?>" disabled name="name">
                                        </div>
                                        <div class="name">
                                            <span>Email:</span>
                                            <input type="text" value="<?php echo $row['email_id']; ?>" disabled name="email">
                                        </div>
                                        <div class="name">
                                            <span>Date Of Birth:</span>
                                            <input type="text" value="<?php echo $row['user_bday']; ?>" disabled name="dob">
                                        </div>
                                        <div class="name">
                                            <span>Account Type:</span>
                                            <input type="text" value="<?php echo $user_type; ?>" disabled name="user_type">
                                        </div>
                                        <div class="name">
                                            <span>CashKaro Referral ID:</span>
                                            <input type="text" value="<?php echo $row['ck_ref_id']; ?>" disabled name="ck_ref_id">
                                        </div>
                                        <div class="name">
                                            <span>No Of Friends:</span>
                                            <input type="text" value="<?php echo $row['no_of_friends']; ?>" disabled name="no_of_friends">
                                        </div>
                                        <div class="name">
                                            <span>City:</span>
                                            <input type="text" value="<?php echo $row['hometown']; ?>" name="hometown">
                                        </div>
                                        <div class="name">
                                            <span>College:</span>
                                            <input type="text" value="<?php echo $row['college']; ?>" name="college">
                                        </div>
                                        <div class="name">
                                            <span>Mobile:</span>
                                            <input type="text" value="<?php echo $row['mobile']; ?>" name="mobile">
                                        </div>
                                        <input type="submit" value="Save Changes">
                                    </form>
                                </div>	
                        </div>			
		</div>    	
            </div>
        </div>
	   
<?php include 'about_info_credentials.inc.php'; ?>    
     
</div>
<!--Container Close Here--> 

<?php include 'footer.inc.php'; ?>
</body>
</html>
