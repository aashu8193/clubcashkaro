<?php
session_start();
include 'connect.inc.php';

if (isset($_SESSION['username']))
{
    $checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$_SESSION['username']}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch();
	$admin_role = $row['admin_role'];
    
    if(isset($_POST["submit"]))
	{   $connection = mysqli_connect('localhost', 'aashu8193', 'peterpan0804', 'fb_student_main_db'); 
		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$c = 0;
		while(($filesop = fgetcsv($handle, 100000, "~")) !== false)
		{
			$user_id = $filesop[0];
			$fid = $filesop[1];
			$ck_ref_id = $filesop[2];
			$ref_id_status = $filesop[3];
			$ck_email_id = $filesop[4];
			$bonus_points = $filesop[5];
			
			$sql = mysqli_query($connection , "UPDATE `user_table` SET `ck_ref_id` = '{$ck_ref_id}' , `ref_id_status` = '{$ref_id_status}' , `bonus_points` = '{$bonus_points}' WHERE `fid` = '{$fid}'");
			
			$checkquery2 = "SELECT * FROM `user_table` WHERE `fid` = '{$fid}'";
	                $result2 = $pdo->query($checkquery2);
	                $row2 = $result2->fetch();
			
			if($ref_id_status == 'approved')
			{	
			   
			    
		        $to = $row2['email_id'];
			    $subject = 'Congratulations! You are now Captain Cash and your status is approved';		
			    $message = 'Dear' . $row2['name'].'</br>

It’s time for celebrations for you are now captain cash!</br>
You are now eligible to perform tasks and earn with Club CashKaro. </br>

Here is how you can earn points on your assigned tasks @ Club CashKaro </br>
1.	Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account</br>
2.	You can now go to “My Tasks” section and perform any task you like</br>
3.	You just need to click and Captain Cash will perform the task for you, for every successful task you will earn points </br>
Why perform tasks with CashKaro club? What’s in it for you?</br>
1.	Earn in Your Free time</br>
Perform Tasks as simple as sharing a post on your Facebook Profile & we thank you in Gift Vouchers as Rewards </br>
2.	Take Home Extra Bonuses</br>
Perform Bonus tasks and take more every month. Sky is your limit, earn as much as you like</br>
3.	Get Marketing Experience </br>
Get live social media marketing experience. Even better! You get special access to represent your college/company at major cities</br>
Here’s how your Remuneration looks like</br>
Every task that you perform has some predefined points ? 10 points = Re. 1 </br>
So when you stack up the points you are actually stacking money.</br>

Here’s how can you redeem your points</br>
1.	10 Points = Re 1</br>
2.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)</br>
3.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers</br>
4.	Once redeemed you will get the Gift Vouchers within 24 hours via mail';			
			    $header = "From:noreply@clubcashkaro.com \r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html\r\n";			
			    $retval = mail ($to,$subject,$message,$header);
			    
			}
			elseif($ref_id_status == 'rejected')
			{
				$checkquery5 = "SELECT * FROM `auto_mails_table` WHERE `auto_mails_name` = 'approve_user'";
	                    $result5 = $pdo->query($checkquery5);
	                    $row5 = $result5->fetch();
		        
		        $to = $row2['email_id'];
			    $subject = 'Congratulations! You are now Captain Cash and your status is approved';		
			    $message = 'Dear' . $row2['name'].'</br>

It’s time for celebrations for you are now captain cash!</br>
You are now eligible to perform tasks and earn with Club CashKaro. </br>

Here is how you can earn points on your assigned tasks @ Club CashKaro </br>
1.	Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account</br>
2.	You can now go to “My Tasks” section and perform any task you like</br>
3.	You just need to click and Captain Cash will perform the task for you, for every successful task you will earn points </br>
Why perform tasks with CashKaro club? What’s in it for you?</br>
1.	Earn in Your Free time</br>
Perform Tasks as simple as sharing a post on your Facebook Profile & we thank you in Gift Vouchers as Rewards </br>
2.	Take Home Extra Bonuses</br>
Perform Bonus tasks and take more every month. Sky is your limit, earn as much as you like</br>
3.	Get Marketing Experience </br>
Get live social media marketing experience. Even better! You get special access to represent your college/company at major cities</br>
Here’s how your Remuneration looks like</br>
Every task that you perform has some predefined points ? 10 points = Re. 1 </br>
So when you stack up the points you are actually stacking money.</br>

Here’s how can you redeem your points</br>
1.	10 Points = Re 1</br>
2.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)</br>
3.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers</br>
4.	Once redeemed you will get the Gift Vouchers within 24 hours via mail';			
			    $header = "From:noreply@clubcashkaro.com \r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html\r\n";			
			    $retval = mail ($to,$subject,$message,$header);
			}
			
			$c = $c + 1;
		}
		
			if($sql){
				echo '<span style="color:white;text-align:center;">You database has imported successfully.Wait for page to reload.</span>';
				header('Refresh: 2; url= http://clubcashkaro.com/control/admin/approve-users.php');
			}else{
				echo "Sorry! There is some problem. Try Again";
			}

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
<title>Approve Pending Users</title>
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
  	       <h3>Approve Pending Users</h3>
  	         <div class="tab-content">						
	           <form name="import" method="post" enctype="multipart/form-data">
    	            <input type="file" name="file" /><br />
                    <input type="submit" name="submit" value="Submit" />
               </form> 		 			 
			 </div>					
		 </div>
    </div>
    <div class="graphs">
	     <div class="xs">
  	       <h3>Check Points : </h3>
  	         <div class="tab-content">						
	           1. File Should Be csv ~ seperated. Columns are : user_id ~ fid ~ ck_ref_id ~ ref_id_status ~ ck_email_id ~ bonus_points</br>
               2. Be careful with 'fid' column should be converted to text while opening 'Pending Users' file. </br>
               3. ref_id_status should be changed from 'pending' to either 'approved' or 'rejected' </br>
               4. Don't forget to add 100 bonus points </br>
               5. All Columns are CASE SENSITIVE </br>			   
			 </div>					
		 </div>
    </div>
 </div>
 </div>
      <!-- /#page-wrapper -->
   
    <!-- /#wrapper -->
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
