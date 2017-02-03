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
	

    $checkquery2 = "SELECT * FROM `task_table` WHERE `{$_GET['search_by']}` = '{$_GET['search_by_value']}' AND `issue_date` >= '{$_GET['issue_date_start']}' AND `issue_date` <= '{$_GET['issue_date_end']}'";
	$result2 = $pdo->query($checkquery2);
	while($row2 = $result2->fetch()) {
	  $task_id[] = $row2['task_id'];
	  $short_desc[] = $row2['task_short_desc'];
      $long_desc[] = $row2['task_long_desc'];
      $task_type[] = $row2['task_type'];
      $expiry_date[] = $row2['expiry_date'];	  
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
<title>Get Task Details</title>
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
  	       <h3>Get Task Details</h3>
  	            <div class="tab-content">
				<div class="panel-body no-padding">
					<table class="table table-striped">
						<thead>
							<tr class="warning">
								<th>TaskId</th>
								<th>Short Desc</th>
								<th>Long Desc</th>
								<th>Task Type</th>
								<th>Expiry Date</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for($i = 0; $i < sizeof($task_id); $i++)
							{			
							?>
							<tr>
								<td><?php echo $task_id[$i]; ?></td>
								<td><?php echo $short_desc[$i]; ?></td>
								<td><?php echo $long_desc[$i]; ?></td>
								<td><?php echo $task_type[$i]; ?></td>
								<td><?php echo $expiry_date[$i]; ?></td>
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
