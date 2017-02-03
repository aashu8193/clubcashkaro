<?php
session_start();
include 'connect.inc.php';

if (isset($_SESSION['username']))
{
    $checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$_SESSION['username']}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch();
	
	$admin_role = $row['admin_role'];
    
    $checkquery2 = "SELECT * FROM `task_table`";
	$result2 = $pdo->query($checkquery2);
	while($row2 = $result2->fetch()) {
	  $task_id[] = $row2['task_id'];
	  $short_desc[] = $row2['task_short_desc'];
      $long_desc[] = $row2['task_long_desc'];
      $task_type[] = $row2['task_type'];
      $expiry_date[] = $row2['expiry_date'];	  
	}
		
    if(isset($_POST['task_id_delete']))
    {
		$sql = "DELETE FROM `task_table` WHERE `task_id`= '{$_POST['task_id_delete']}'";
		$pdo->exec($sql);
		
		echo '<span style="color:white;text-align:center;">Task Deleted Successfully. Wait for page to reload to proceed further.</span>';
		header('Refresh: 2; url= http://clubcashkaro.com/control/admin/manage-valid-tasks.php');
		
		
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
<title>Manage Valid Tasks</title>
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
  	       <h3>Manage Valid Tasks</h3>
  	         <div class="tab-content">
	            <div class="panel-body no-padding">
					<table class="table table-striped">
						<thead>
							<tr class="warning">
								<th>TaskId</th>
								<th>Short Desc</th>
								<th>Long Desc</th>
								<th>Task Type</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for($i = 0; $i < sizeof($task_id); $i++)
							{
							$datenow = date_create("now"); 
                            $date2 = date_create($expiry_date[$i]);
                            $diff = date_diff($datenow,$date2);
                            $daydiff = $diff->format('%R%a days');
                            $hourdiff = $diff->format('%R%h hours');      						
						    if($daydiff>=0 Or $hourdiff>=0)
				            {	
								
								
							?>
							<tr>
								<td><?php echo $task_id[$i]; ?></td>
								<td><?php echo $short_desc[$i]; ?></td>
								<td><?php echo $long_desc[$i]; ?></td>
								<td><?php echo $task_type[$i]; ?></td>
								<td><a href="edit-task.php?task_id=<?php echo $task_id[$i]; ?>" class="edit-button">Edit</a></td>
								<td><form class="delete-button" method="post">
                                       <input type="hidden" value="<?php echo $task_id[$i]; ?>" name="task_id_delete">
                                       <input type="submit" value="Delete">
                                    </form>
								</td>
							</tr>
							<?php
                            } }
                            ?> 							
						</tbody>
					</table>
				</div>									
     	
 
              </div>

  </div>
      </div>
      <!-- /#page-wrapper -->
   </div>
</div>   
    <!-- /#wrapper -->
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>






