<?php
session_start();
include 'connect.inc.php';

if (isset($_SESSION['username']))
{
    $checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$_SESSION['username']}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch();
	
	$admin_role = $row['admin_role'];
    
    if ($admin_role !== "Owner")	
	{ header('Location: http://clubcashkaro.com/control/admin/');}

    $checkquery2 = "SELECT * FROM `admin_users_table`";
	$result2 = $pdo->query($checkquery2);
	while($row2 = $result2->fetch()) {
	  $admin_id[] = $row2['admin_user_id'];
	  $name_table[] = $row2['Name'];
      $username_table[] = $row2['username'];
      $role_table[] = $row2['admin_role'];	  
	}
	
    if(isset($_POST['admin_user_id']))
    {
		$sql = "DELETE FROM `admin_users_table` WHERE `admin_user_id`= '{$_POST['admin_user_id']}'";
		$pdo->exec($sql);
		
		echo '<span style="color:white;text-align:center;">Admin User Deleted Successfully. Wait for page to reload to proceed further.</span>';
		header('Refresh: 2; url= http://clubcashkaro.com/control/admin/manage-admin-users.php');
				
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
<title>Manage Admin Users</title>
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
  	       <h3>Manage Admin Users</h3>
  	         <div class="tab-content">
	            <div class="panel-body no-padding">
					<table class="table table-striped">
						<thead>
							<tr class="warning">
								<th>Admin Id</th>
								<th>Name</th>
								<th>Username</th>
								<th>Admin Role</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for($i = 0; $i < sizeof($admin_id); $i++)
							{
							?>
							<tr>
								<td><?php echo $admin_id[$i]; ?></td>
								<td><?php echo $name_table[$i]; ?></td>
								<td><?php echo $username_table[$i]; ?></td>
								<td><?php echo $role_table[$i]; ?></td>
								<td><a href="edit-admin-user.php?adminid=<?php echo $admin_id[$i]; ?>" class="edit-button">Edit</a></td>
								<td><form class="delete-button" method="post">
                                       <input type="hidden" value="<?php echo $admin_id[$i]; ?>" name="admin_user_id">
                                       <input type="submit" value="Delete">
                                     </form>
								</td>
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






