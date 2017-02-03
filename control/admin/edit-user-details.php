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
	
	if ($_GET['search_by'] == "facebook_email")
	{ $checkquery2 = "SELECT * FROM `user_table` WHERE `email_id` = '{$_GET['search_by_value']}'"; }
    elseif ($_GET['search_by'] == "facebook_id")
	{ $checkquery2 = "SELECT * FROM `user_table` WHERE `fid` = '{$_GET['search_by_value']}'"; }
	
	$result2 = $pdo->query($checkquery2);
	$row2 = $result2->fetch();
	
	
    	
    if(isset($_POST['name'])) 
	{   
		$query = "UPDATE `user_table` SET `name` = '{$_POST['name']}' , `ref_id_status` = '{$_POST['ref_id_status']}' , `user_type` = '{$_POST['user_type']}' , `hometown` = '{$_POST['hometown']}' WHERE `fid` = '{$row2['fid']}'";
		$affectedrows = $pdo->exec($query);
		
        $query = "UPDATE `user_table` SET `college` = '{$_POST['college']}' , `gender` = '{$_POST['gender']}' , `mobile` = '{$_POST['mobile']}' WHERE `fid` = '{$row2['fid']}'";
		$affectedrows = $pdo->exec($query);
		
	    echo '<span style="color:white;text-align:center;">User Details Updated Successfully. Wait for page to redirect to proceed further.</span>';
	    header('Refresh: 3; url= http://clubcashkaro.com/control/admin/manage-users.php');
	
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
<title>Edit User Details</title>
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
  	       <h3>Edit User</h3>
  	         <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post">
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="name" name ="name" value="<?php echo $row2['name']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Email Id</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="email_id" name="email_id" value="<?php echo $row2['email_id']; ?>" disabled>
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Facebook Id</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="fid" name="fid" value="<?php echo $row2['fid']; ?>" disabled>
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Cashkaro Ref Id</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="ck_ref_id" name="ck_ref_id" value="<?php echo $row2['ck_ref_id']; ?>" disabled>
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Account Status</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="ref_id_status" name="ref_id_status" value="<?php echo $row2['ref_id_status']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Cashkaro Email Id</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="ck_email_id" name="ck_email_id" value="<?php echo $row2['ck_email_id']; ?>" disabled>
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">User Type</label>
									<div class="col-sm-8"><select name="user_type" id="user_type" class="form-control1">
										<option <?php if($row2['user_type'] == "bronze") { ?>selected <?php } ?>>bronze</option>
										<option <?php if($row2['user_type'] == "silver") { ?>selected <?php } ?>>silver</option>
										<option <?php if($row2['user_type'] == "gold") { ?>selected <?php } ?>>gold</option>										
									</select></div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Hometown</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="hometown" name="hometown" value="<?php echo $row2['hometown']; ?>">
									</div>
								</div>

								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Gender</label>
									<div class="col-sm-8"><select name="gender" id="gender" class="form-control1">
										<option <?php if($row2['gender'] == "male") { ?>selected <?php } ?>>male</option>
										<option <?php if($row2['gender'] == "female") { ?>selected <?php } ?>>female</option>								
									</select></div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">College</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="college" name="college" value="<?php echo $row2['college']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Mobile</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="mobile" name="mobile" value="<?php echo $row2['mobile']; ?>">
									</div>
								</div>
								
								
								<div class="col-sm-8 col-sm-offset-2">
							      <input type="submit" value="Save Changes">
								</div>
							</form>
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
