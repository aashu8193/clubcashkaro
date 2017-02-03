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
			$task_id = $filesop[0];
			$fid = $filesop[1];
			$assign_date = $filesop[2];
			$status = $filesop[3];
		 
		    $checkquery2 = "SELECT * FROM `task_assign_table` WHERE `fid` = '{$fid}' AND `task_id` = '{$task_id}'";
	        $result2 = $pdo->query($checkquery2);
	        $rowno2 = $result2->rowCount();
			
			if ($rowno2 == 0)				
	    	{
				$insertquery = "INSERT INTO `task_assign_table` SET
				`task_id` = :task_id,
				`fid` = :fid,
				`assign_date` = :assign_date,
				`status` = :status";
                 				
			    $insert = $pdo->prepare($insertquery);
			    $insert->bindvalue(':task_id', $task_id);
                           $insert->bindvalue(':fid', $fid);
                           $insert->bindvalue(':assign_date', $assign_date);
                          $insert->bindvalue(':status', $status);				
                          $insert->execute();	
				
				$checkquery3 = "SELECT * FROM `user_table` WHERE `fid` = '{$fid}'";
	            $result3 = $pdo->query($checkquery3);
	            $row3 = $result3->fetch();
	            
	            $checkquery4 = "SELECT * FROM `task_table` WHERE `task_id` = '{$task_id}'";
	            $result4 = $pdo->query($checkquery4);
	            $row4 = $result4->fetch();
	            
				
				$to = $row3['email_id'];
			    $subject = 'Captain Cash – Your Next Mission Should You choose to Accept It!';		
			    $message = 'Hi '.$row3['name'].',

Greetings from Club CashKaro!

Your task of the day is '.$row4['task_long_desc'].' which is of '.$row4['points'].'
Task done will be confirmed in 24 hours. 

Here is how you can earn points on your assigned task @ Club CashKaro 
5.	Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account
6.	You can now go to “My Tasks” section and perform the task assigned to you
7.	Every assigned task has a corresponding points to it which will be added to your account automatically on completion & approval of the task
8.	You just need to click the ‘’Share on Facebook’’ button and Captain Cash will perform the task for you, for every successful task you will earn points 
Why perform tasks at Club CashKaro? What’s in it for you?
4.	Earn in Your Free time
a.	Perform Tasks as simple as sharing a post on your Facebook Profile & we thank you in Gift Vouchers as Rewards 
5.	Take Home Extra Bonuses
a.	Perform Bonus tasks and take more every month. Sky is your limit, earn as much as you like
6.	Get Marketing Experience 
a.	Get live social media marketing experience. Even better! You get special access to represent your college/company at major cities
Here’s how your Remuneration looks like
Every task that you perform has some predefined points  10 points = Re. 1 
So when you stack up the points you are actually stacking money.

Here’s how can you redeem your points
5.	10 Points = Re 1
6.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)
7.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers
8.	Once redeemed you will get the Gift Vouchers within 24 hours via mail
  
Don’t hold back for you are just one step away from becoming RICH';			
			    $header = "From:noreply@clubcashkaro.com \r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html\r\n";			
			    $retval = mail ($to,$subject,$message,$header);
		  
				
						
			}
	     
   		
		}
		
		
			echo '<span style="color:white;text-align:center;">Task Assigned Successfully. Wait for page to reload to proceed further.</span>';
	        header('Refresh: 2; url= http://clubcashkaro.com/control/admin/task-assign.php');
				
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
<title>Task Assign</title>
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
  	       <h3>Task Assign</h3>
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
	           1. File Should Be csv ~ seperated. Columns are : task_id	~ fid ~ assign_date ~ status</br>
               2. Date format : yyyy-mm-dd </br>
               3. 'status' can only be 'assigned'. </br>
               4. Be careful with 'fid' column should be converted to text </br>
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
