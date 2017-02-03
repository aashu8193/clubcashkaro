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
			$email_id = $filesop[1];
			$points_requested = $filesop[2];
			$gift_type = $filesop[3];
			$status = $filesop[4];
			$date_requested = $filesop[5];
			$date_paid = $filesop[6];
			
			$sql = mysqli_query($connection , "UPDATE `redeem_table` SET `status` = '{$status}' , `date_paid` = '{$date_paid}' , `gift_type` = '{$gift_type}' WHERE `fid` = '{$fid}' AND `email_id` = '{$email_id}' AND `date_requested` = '{$date_requested}' AND `points_redeemed` = '{$points_requested}'");
			
			if ($status == "paid")
			{  
		       $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$fid}'";
	           $result = $pdo->query($checkquery);
	           $row = $result->fetch();
	
	           $points_alredy_redeemed = $row['points_redeemed'];
			   $points_alredy_redeemed_new = $points_alredy_redeemed + $points_requested;
		     
			   $sql = mysqli_query($connection , "UPDATE `user_table` SET `points_redeemed` = '{$points_alredy_redeemed_new}' WHERE `fid` = '{$fid}'"); 	
			} 
		   			         
		}
		
			echo '<span style="color:white;text-align:center;">Payments Successful. Wait for page to reload to proceed further.</span>';
	        header('Refresh: 3; url= http://clubcashkaro.com/control/admin/make-payments.php');
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
<title>Make Payments</title>
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
  	       <h3>Make Payments</h3>
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
	           1. File Should Be csv ~ seperated. Columns are : fid	~ email_id ~ points_redeemed ~ gift_type ~ status ~ date_requested ~ date_paid</br>
               2. Date format : yyyy-mm-dd </br>
               3. 'status' can only be 'paid' or 'rejected'. </br>
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
