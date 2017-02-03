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
	{   
		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$c = 0;
		while(($filesop = fgetcsv($handle, 100000, "~")) !== false)
		{
			$task_id = $filesop[0];
			$points = $filesop[1];
			$short_desc = $filesop[2];
			$long_desc = $filesop[3];
			$link = $filesop[4];
			$message = $filesop[5];
            $object_id = $filesop[6];			
			$issue_date = $filesop[7];
			$expiry_date = $filesop[8];
			$platform = $filesop[9];
			$task_type = $filesop[10];
			$check_type = $filesop[11];
			$task_second_cat = $filesop[12];
			
			$checkquery3 = "SELECT * FROM `task_table` WHERE `task_id` = '{$task_id}'";
	        $result3 = $pdo->query($checkquery3);
		    $rowno3 = $result3->rowCount();
		
		    if ($rowno3 == 0)
		    {
			$insertquery = "INSERT INTO `task_table` SET
				`task_id` = :task_id";
                 				
			$insert = $pdo->prepare($insertquery);
            $insert->bindvalue(':task_id', $task_id);
            $insert->execute();
			
			if (isset($points)) {
			  $query = "UPDATE `task_table` SET `points` = '{$points}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($short_desc)) {
			  $query = "UPDATE `task_table` SET `task_short_desc` = '{$short_desc}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($long_desc)) {
			  $query = "UPDATE `task_table` SET `task_long_desc` = '{$long_desc}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($link)) {
			  $query = "UPDATE `task_table` SET `link` = '{$link}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($message)) {
			  $query = "UPDATE `task_table` SET `message` = '{$message}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($object_id)) {
			  $query = "UPDATE `task_table` SET `object_id` = '{$object_id}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($issue_date)) {
			  $query = "UPDATE `task_table` SET `issue_date` = '{$issue_date}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($expiry_date)) {
			  $query = "UPDATE `task_table` SET `expiry_date` = '{$expiry_date}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($platform)) {
			  $query = "UPDATE `task_table` SET `platform` = '{$platform}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($task_type)) {
			  $query = "UPDATE `task_table` SET `task_type` = '{$task_type}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($check_type)) {
			  $query = "UPDATE `task_table` SET `check_type` = '{$check_type}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($task_second_cat)) {
			  $query = "UPDATE `task_table` SET `task_second_cat` = '{$task_second_cat}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			
			
		    }
			elseif ($rowno3 !== 0)
		    {
		
			if (isset($points)) {
			  $query = "UPDATE `task_table` SET `points` = '{$points}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($short_desc)) {
			  $query = "UPDATE `task_table` SET `task_short_desc` = '{$short_desc}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($long_desc)) {
			  $query = "UPDATE `task_table` SET `task_long_desc` = '{$long_desc}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($link)) {
			  $query = "UPDATE `task_table` SET `link` = '{$link}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($message)) {
			  $query = "UPDATE `task_table` SET `message` = '{$message}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($object_id)) {
			  $query = "UPDATE `task_table` SET `object_id` = '{$object_id}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($issue_date)) {
			  $query = "UPDATE `task_table` SET `issue_date` = '{$issue_date}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($expiry_date)) {
			  $query = "UPDATE `task_table` SET `expiry_date` = '{$expiry_date}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($platform)) {
			  $query = "UPDATE `task_table` SET `platform` = '{$platform}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($task_type)) {
			  $query = "UPDATE `task_table` SET `task_type` = '{$task_type}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($check_type)) {
			  $query = "UPDATE `task_table` SET `check_type` = '{$check_type}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($task_second_cat)) {
			  $query = "UPDATE `task_table` SET `task_second_cat` = '{$task_second_cat}' WHERE `task_id` = '{$task_id}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			
			
		    }
			
			
			
		}
		
			
		    echo '<span style="color:white;text-align:center;">New Tasks imported successfully.Wait for page to reload to proceed further.</span>';
		    //echo "You database has imported successfully. You have processed ". ($c - 1)." records. Wait for page to reload.";
			header('Refresh: 4; url= http://clubcashkaro.com/control/admin/bulk-upload-tasks.php');
			
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
<title>Bulk Upload Tasks</title>
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
  	       <h3>Bulk Upload Tasks</h3>
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
	           1. File Should Be csv ~ seperated. Columns are : task_id	~ points ~ task_short_desc ~ task_long_desc ~ link ~ message ~ object_id ~ issue_date ~	expiry_date ~ platform ~ task_type ~ check_type ~ task_second_cat</br>
               2. Date format : yyyy-mm-dd </br>
               3. 'platform' can only be 'facebook' for now. </br>
               4. 'check_type' can only be 'auto' or 'manual'. </br>
			   5. 'task_second_cat' can only be 'with_message' or 'without_message'. </br>
			   6. 'task_type' can only be 'share' or 'post_with_link' or 'post_without_link' or	'upload_photo' or 'comment'. </br>
               7. All Columns are CASE SENSITIVE  			   
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
