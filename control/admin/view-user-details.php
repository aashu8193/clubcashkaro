<?php
session_start();
include 'connect.inc.php';

if (isset($_SESSION['username']))
{
	//echo $_GET['search_by_value'];
	//echo $_GET['search_by'];
    $checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$_SESSION['username']}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch();
	
	$admin_role = $row['admin_role'];
	

    $checkquery2 = "SELECT * FROM `user_table`";
	$result2 = $pdo->query($checkquery2);
	while($row2 = $result2->fetch()) {
	  $email_id[] = $row2['email_id'];
	  $fid[] = $row2['fid'];
      $name[] = $row2['name'];
      $user_type[] = $row2['user_type'];
      $age[] = $row2['age'];
	  $no_of_friends[] = $row2['no_of_friends'];
	  $hometown[] = $row2['hometown'];
	  $gender[] = $row2['gender'];
	  $college[] = $row2['college'];  
	}
	
    
	if(isset($_POST["submit"]))
	{
		$filename = "downloads/view_user_details_".strtotime("now").'.csv';
          
        $connection = mysqli_connect('localhost', 'aashu8193', 'peterpan0804', 'fb_student_main_db');      	
		
		$sql = mysqli_query($connection , "SELECT `email_id` , `fid` , `name` , `user_type` , `age` , `no_of_friends` , `hometown` , `gender` , `college` FROM `user_table`");

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
<title>View User Details</title>
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
  	       <h3>View User Details</h3>
		        <div class="tab-content">						
	                <form name="export" method="post">
    			     <input type="submit" value="Export" name="submit">
    		        </form> 		 			 
			    </div>	
  	            <div class="tab-content">
				<div class="panel-body no-padding">
					<table class="table table-striped">
						<thead>
							<tr class="warning">
								<th>Email ID</th>
								<th>Fid</th>
								<th>Name</th>
								<th>User_type</th>
								<th>Age</th>
								<th>FB Friends</th>
								<th>Hometown</th>
								<th>Gender</th>
								<th>College</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for($i = 0; $i < sizeof($fid); $i++)
							{			
							?>
							<tr>
								<td><?php echo $email_id[$i]; ?></td>
								<td><?php echo $fid[$i]; ?></td>
								<td><?php echo $name[$i]; ?></td>
								<td><?php echo $user_type[$i]; ?></td>
								<td><?php echo $age[$i]; ?></td>
								<td><?php echo $no_of_friends[$i]; ?></td>
								<td><?php echo $hometown[$i]; ?></td>
								<td><?php echo $gender[$i]; ?></td>
								<td><?php echo $college[$i]; ?></td>
							</tr>
							<?php
                            } 
                            ?> 							
						</tbody>
					</table>
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
