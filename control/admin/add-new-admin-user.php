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


    if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['admin-role'])) {
        $name = $_POST['name'];
		$role = $_POST['admin-role'];
		$username = $_POST['username'];
		$password = $_POST['password'];

		$checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$username}'";
	    $result = $pdo->query($checkquery);
	    $rowno = $result->rowCount();
		$row = $result->fetch();
	 
	    
		if($rowno == 0) {              
			
			$insertquery = "INSERT INTO `admin_users_table` SET
				`name` = :name,
				`username` = :username,
				`password` = :password,
				`admin_role` = :role";
                 				
			$insert = $pdo->prepare($insertquery);
			$insert->bindvalue(':name', $name);
            $insert->bindvalue(':username', $username);			
            $insert->bindvalue(':password', $password);
            $insert->bindvalue(':role', $role);			 
			$insert->execute();
			
			
			$invalid = "New User Successfully Added.";
		} else {
			$invalid = "UserName Already Exists.";
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
<title>Add New Admin User</title>
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
  	       <h3>Add New Admin User</h3>
  	         <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" action="add-new-admin-user.php" method="post">
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="name" name ="name" placeholder="Enter Name">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Username</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="username" name="username" placeholder="Enter Username">
									</div>
								</div>
								<div class="form-group">
									<label for="inputPassword" class="col-sm-2 control-label">Password</label>
									<div class="col-sm-8">
										<input type="password" class="form-control1" name="password" id="password" placeholder="Password">
									</div>
								</div>
								<div class="form-group">
									<label for="admin-role" class="col-sm-2 control-label">Select Admin Role</label>
									<div class="col-sm-8"><select name="admin-role" id="admin-role" class="form-control1">
										<option>Owner</option>
										<option>Manager</option>
										<option>Associate</option>
									</select></div>
								</div>
								<div class="col-sm-8 col-sm-offset-2">
							    <input type="submit" value="Add New User">
								</div>
							</form>
						</div>
						<ul class="new">
			                  <li class="new_left"><p><?php if (isset($invalid)){echo $invalid; }?></p></li>
			                  <div class="clearfix"></div>
		                </ul>
						
						
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
