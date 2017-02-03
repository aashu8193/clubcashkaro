<?php
session_start();
include 'connect.inc.php';

if (isset($_SESSION['username']))
{
    $checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$_SESSION['username']}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch();
	
	$admin_role = $row['admin_role'];
        		
    if(isset($_POST['search_by']) && isset($_POST['search_by_value']) && isset($_POST['bonus_points'])) {
    
	    if($_POST['search_by'] == "facebook email")
	    { 
	      $checkquery2 = "SELECT * FROM `user_table` WHERE `email_id` = '{$_POST['search_by_value']}'";
	      $result2 = $pdo->query($checkquery2);
	      $row2 = $result2->fetch();
		  $bonus_points_new = $_POST['bonus_points'] + $row2['bonus_points'];
		  
		  $query = "UPDATE `user_table` SET `bonus_points` = '{$bonus_points_new}' WHERE `email_id` = '{$_POST['search_by_value']}'";
		  $affectedrows = $pdo->exec($query);
		  
		        
		        
		        $to = $row2['email_id'];
			    $subject = 'Captain Cash – We have added'. $_POST['bonus_points'] .'Bonus points to your account';	
			    $message = 'Dear'.$row2['name'].'</br>

It’s time to make merry Captain Cash – we have just added 100 Bonus points in your Club CashKaro Account.</br>

Here is how you can earn more points with club CashKaro </br>
1.	Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account</br>
2.	You can now go to “My Tasks” section and perform the task assigned to you</br>
3.	Every assigned task has a corresponding points to it which will be added to your account automatically on completion & approval of the task</br>
4.	You just need to click the ‘’Share on Facebook’’ button and Captain Cash will perform the task for you, for every successful task you will earn points </br>
Why perform tasks at Club CashKaro? What’s in it for you?</br>
1.	Earn in Your Free time</br>
a.	Perform Tasks as simple as sharing a post on your Facebook Profile & we thank you in Gift Vouchers as Rewards </br>
2.	Take Home Extra Bonuses</br>
a.	Perform Bonus tasks and take more every month. Sky is your limit, earn as much as you like</br>
3.	Get Marketing Experience </br>
a.	Get live social media marketing experience. Even better! You get special access to represent your college/company at major cities</br>
Here’s how your Remuneration looks like</br>
Every task that you perform has some predefined points ? 10 points = Re. 1 </br>
So when you stack up the points you are actually stacking money.</br>
</br>
Here’s how can you redeem your points</br>
1.	10 Points = Re 1</br>
2.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)</br>
3.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers</br>
4.	Once redeemed you will get the Gift Vouchers within 24 hours via mail</br>
  
Don’t hold back for you are just one step away from becoming RICH';

			    
			    			
			    $header = "From:noreply@clubcashkaro.com \r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html\r\n";			
			    $retval = mail ($to,$subject,$message,$header);
		  
    	}
        elseif($_POST['search_by'] == "facebook id")
	    { 
	      $checkquery2 = "SELECT * FROM `user_table` WHERE `fid` = '{$_POST['search_by_value']}'";
	      $result2 = $pdo->query($checkquery2);
	      $row2 = $result2->fetch();
		  $bonus_points_new = $_POST['bonus_points'] + $row2['bonus_points'];
		  
		  $query = "UPDATE `user_table` SET `bonus_points` = '{$bonus_points_new}' WHERE `fid` = '{$_POST['search_by_value']}'";
		  $affectedrows = $pdo->exec($query);
		  
		        
		        
		       $to = $row2['email_id'];
			    $subject = 'Captain Cash – We have added'. $_POST['bonus_points'] .'Bonus points to your account';		
			    $message = 'Dear'.$row2['name'].'</br>

It’s time to make merry Captain Cash – we have just added 100 Bonus points in your Club CashKaro Account.</br>

Here is how you can earn more points with club CashKaro </br>
1.	Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account</br>
2.	You can now go to “My Tasks” section and perform the task assigned to you</br>
3.	Every assigned task has a corresponding points to it which will be added to your account automatically on completion & approval of the task</br>
4.	You just need to click the ‘’Share on Facebook’’ button and Captain Cash will perform the task for you, for every successful task you will earn points </br>
Why perform tasks at Club CashKaro? What’s in it for you?</br>
1.	Earn in Your Free time</br>
a.	Perform Tasks as simple as sharing a post on your Facebook Profile & we thank you in Gift Vouchers as Rewards </br>
2.	Take Home Extra Bonuses</br>
a.	Perform Bonus tasks and take more every month. Sky is your limit, earn as much as you like</br>
3.	Get Marketing Experience </br>
a.	Get live social media marketing experience. Even better! You get special access to represent your college/company at major cities</br>
Here’s how your Remuneration looks like</br>
Every task that you perform has some predefined points ? 10 points = Re. 1 </br>
So when you stack up the points you are actually stacking money.</br>
</br>
Here’s how can you redeem your points</br>
1.	10 Points = Re 1</br>
2.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)</br>
3.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers</br>
4.	Once redeemed you will get the Gift Vouchers within 24 hours via mail</br>
  
Don’t hold back for you are just one step away from becoming RICH';

			    
			    			
			    $header = "From:noreply@clubcashkaro.com \r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html\r\n";			
			    $retval = mail ($to,$subject,$message,$header);
		  
    	}		
	    echo '<span style="color:white;text-align:center;">Bonus Added successfully.Wait for page to reload.</span>';
		header('Refresh: 3; url= http://clubcashkaro.com/control/admin/add-bonus.php');
	}
    
}	
else
{
    header('Location: http://clubcashkaro.com/control/admin/');		
}



?>

<!DOCTYPE HTML>
<html>
<head>
<title>Add Bonus</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!----webfonts--->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<!---//webfonts--->  
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</head>
<body>
<div id="wrapper">
<?php include 'navigation.inc.php'; ?>
     <!-- Navigation -->
        
        <div id="page-wrapper">
        <div class="graphs">
	     <div class="xs">
  	       <h3>Add Bonus Points</h3>
  	         <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" action="add-bonus.php" method="post">
								<div class="form-group">
									<label for="admin-role" class="col-sm-2 control-label">Search User By</label>
									<div class="col-sm-8"><select name="search_by" id="search_by" class="form-control1">
										<option>facebook email</option>
										<option>facebook id</option>
									</select></div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Enter email or id</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="search_by_value" name ="search_by_value">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Enter bonus points to add</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="bonus_points" name="bonus_points">
									</div>
								</div>
								<div class="col-sm-8 col-sm-offset-2">
							    <input type="submit" value="Add Bonus Points">
								</div>
							</form>
						</div>												
					</div> 
  </div>

  </div>
      </div>
      <!-- /#page-wrapper -->
   </div>
    <!-- /#wrapper -->
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
