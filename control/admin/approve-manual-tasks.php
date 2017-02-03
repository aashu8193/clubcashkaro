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
			$fid = $filesop[0];
			$task_id = $filesop[1];
			$status = $filesop[2];
			$check_type = $filesop[3];
			
			
			if ($status == "approved")
			{  			   
			   $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$fid}'";
	           $result = $pdo->query($checkquery);
	           $row = $result->fetch();               			  
	           $total_points = $row['total_points'];
			   $user_type = $row['user_type'];
			   
		       $checkquery2 = "SELECT * FROM `task_table` WHERE `task_id` = '{$task_id}'";
	           $result2 = $pdo->query($checkquery2);
	           $row2 = $result2->fetch();
		       $points = $row2['points'];

			    
			   $checkquery3 = "SELECT * FROM `user_type_points_table` WHERE `user_type` = '{$user_type}'";
	           $result3 = $pdo->query($checkquery3);
	           $row3 = $result3->fetch();		   
			   $credits = $row3['credits'];
			   
			   $points_earned = $credits * $points;
			   $total_points_new = $total_points + $points_earned;
			   
			   $sql = mysqli_query($connection , "UPDATE `user_table` SET `total_points` = '{$total_points_new}' WHERE `fid` = '{$fid}'");
			   $sql = mysqli_query($connection , "UPDATE `task_status_table` SET `status` = '{$status}', `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid}' AND `task_id` = '{$task_id}'");
			   $sql = mysqli_query($connection , "UPDATE `task_assign_table` SET `status` = '{$status}', `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid}' AND `task_id` = '{$task_id}'");
			    
                $to = $row['email_id'];
			    $subject = $row['name']." Congrats. Task With Task Id ".$task_id. " Got Approved";		
			    $message = $row['name']." Congrats. Task With Task Id ".$task_id. " Got Approved";			
			    $header = "From:noreply@clubcashkaro.com \r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html\r\n";			
			    $retval = mail ($to,$subject,$message,$header);
 				
			} 
		   	if ($status == "rejected")
			{  			   			 			   
			   $points_earned = 0;
			   
			   $sql = mysqli_query($connection , "UPDATE `task_status_table` SET `status` = '{$status}', `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid}' AND `task_id` = '{$task_id}'");
			   $sql = mysqli_query($connection , "UPDATE `task_assign_table` SET `status` = '{$status}', `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid}' AND `task_id` = '{$task_id}'");
			    
               $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$fid}'";
	           $result = $pdo->query($checkquery);
	           $row = $result->fetch(); 
				
				$to = $row['email_id'];
			    $subject = $row['name']." Sorry. Task With Task Id ".$task_id. " Got Rejected";		
			    $message = $row['name']." Sorry. Task With Task Id ".$task_id. " Got Rejected";			
			    $header = "From:noreply@clubcashkaro.com \r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html\r\n";			
			    $retval = mail ($to,$subject,$message,$header);				
			}		         
		}
		
			echo '<span style="color:white;text-align:center;">Task Approval Successful. Wait for page to reload to proceed further.</span>';
	        header('Refresh: 3; url= http://clubcashkaro.com/control/admin/approve-manual-tasks.php');
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
<title>Approve Manual Tasks</title>
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
  	       <h3>Approve Manual Tasks</h3>
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
	           1. File Should Be csv ~ seperated. Columns are : fid ~ task_id ~	status ~ check_type</br>
               2. Date format : yyyy-mm-dd </br>
               3. 'status' can only be 'approved' or 'rejected'. </br>
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
