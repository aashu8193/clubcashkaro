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
		$filename = "downloads/manaul_submitted_tasks_".strtotime("now").'.csv';
          
        $connection = mysqli_connect('localhost', 'aashu8193', 'peterpan0804', 'fb_student_main_db');      	
		
		$sql = mysqli_query($connection , "SELECT `fid`, `task_id`, `status`, `check_type` FROM `task_status_table` WHERE `status` = 'submitted' AND `check_type` = 'manual'");

		$num_rows = mysqli_num_rows($sql);
		
			$row = mysqli_fetch_assoc($sql);
			$fp = fopen($filename, "w");
			$seperator = "";
			$comma = "";

			foreach ($row as $name => $value)
				{
					$seperator .= $comma . '' .str_replace('', '""', $name);
					$comma = "~";
				}

			$seperator .= "\n";
			fputs($fp, $seperator);
	
			mysqli_data_seek($sql, 0);
			while($row = mysqli_fetch_assoc($sql))
				{
					$seperator = "";
					$comma = "";

					foreach ($row as $name => $value) 
						{
							$seperator .= $comma . '' .str_replace('', '""', $value);
							$comma = "~";
						}

					$seperator .= "\n";
					fputs($fp, $seperator);
				}
	
			fclose($fp);
	
        header('Location: http://clubcashkaro.com/control/admin/'.$filename);
	    //header($filename);
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
<title>Download Manual Submitted Tasks</title>
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
  	       <h3>Download Manual Submitted Tasks</h3>
  	         <div class="tab-content">						
	          <form name="export" method="post">
    			<input type="submit" value="Click To Download" name="submit">
    		  </form> 		 			 
			 </div>					
		 </div>
    </div>
    <div class="graphs">
	     <div class="xs">
  	       <h3>Check Points : </h3>
  	         <div class="tab-content">						
	           1. DO NOT Open this file by directly clicking on it as it has 'fid' column</br>
                   2. Open a new excel sheet and import text of downloaded file in this new excel sheet</br>
               3. Import Text -----> Delimit by ~ -----> Convert 'fid' to text before finish</br>
               4. All Columns are CASE SENSITIVE </br>			   
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
