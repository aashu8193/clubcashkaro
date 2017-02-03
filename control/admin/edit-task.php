<?php
session_start();
include 'connect.inc.php';

if (isset($_SESSION['username']))
{
    $checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$_SESSION['username']}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch();
	
	$admin_role = $row['admin_role'];
        		
    $checkquery2 = "SELECT * FROM `task_table` WHERE `task_id` = '{$_GET['task_id']}'";
	$result2 = $pdo->query($checkquery2);
	$row2 = $result2->fetch();
	
	
	
	
	
	if(isset($_POST['task_id']) && isset($_POST['short_desc']) && isset($_POST['long_desc']))
	{    
			if (isset($_POST['points'])) {
			  $query = "UPDATE `task_table` SET `points` = '{$_POST['points']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['short_desc'])) {
			  $query = "UPDATE `task_table` SET `task_short_desc` = '{$_POST['short_desc']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['long_desc'])) {
			  $query = "UPDATE `task_table` SET `task_long_desc` = '{$_POST['long_desc']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['link'])) {
			  $query = "UPDATE `task_table` SET `link` = '{$_POST['link']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['message'])) {
			  $query = "UPDATE `task_table` SET `message` = '{$_POST['message']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['object_id'])) {
			  $query = "UPDATE `task_table` SET `object_id` = '{$_POST['object_id']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['issue_date'])) {
			  $query = "UPDATE `task_table` SET `issue_date` = '{$_POST['issue_date']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['expiry_date'])) {
			  $query = "UPDATE `task_table` SET `expiry_date` = '{$_POST['expiry_date']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['platform'])) {
			  $query = "UPDATE `task_table` SET `platform` = '{$_POST['platform']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['task_type'])) {
			  $query = "UPDATE `task_table` SET `task_type` = '{$_POST['task_type']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['check_type'])) {
			  $query = "UPDATE `task_table` SET `check_type` = '{$_POST['check_type']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			if (isset($_POST['task_second_cat'])) {
			  $query = "UPDATE `task_table` SET `task_second_cat` = '{$_POST['task_second_cat']}' WHERE `task_id` = '{$_POST['task_id']}'";
		      $affectedrows = $pdo->exec($query);  
	        }
			
			
		    echo '<span style="color:white;text-align:center;">Task Updated Successfully. Wait for page to redirect to proceed further.</span>';
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
<title>Edit Task</title>
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
  	       <h3>Edit Task Details</h3>
  	         <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post">
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Task Id</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="task_id" name ="task_id" value="<?php echo $row2['task_id']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Points</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="points" name="points" value="<?php echo $row2['points']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Short Desc</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="short_desc" name="short_desc" value="<?php echo $row2['task_short_desc']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Long Desc</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="long_desc" name="long_desc" value="<?php echo $row2['task_long_desc']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Link</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="link" name="link" value="<?php echo $row2['link']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Message</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="message" name="message" value="<?php echo $row2['message']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Object Id</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="object_id" name="object_id" value="<?php echo $row2['object_id']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Issue Date</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="issue_date" name="issue_date" placeholder="YYYY-MM-DD" value="<?php echo $row2['issue_date']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Expiry Date</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" id="expiry_date" name="expiry_date" placeholder="YYYY-MM-DD" value="<?php echo $row2['expiry_date']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="admin-role" class="col-sm-2 control-label">Platform</label>
									<div class="col-sm-8"><select name="platform" id="platform" class="form-control1">
										<option <?php if($row2['platform'] == "facebook") { ?>selected <?php } ?>>facebook</option>
										<option <?php if($row2['platform'] == "twitter") { ?>selected <?php } ?>>twitter</option>
									</select></div>
								</div>
								<div class="form-group">
									<label for="admin-role" class="col-sm-2 control-label">Task Type</label>
									<div class="col-sm-8"><select name="task_type" id="task_type" class="form-control1">
										<option <?php if($row2['task_type'] == "share") { ?>selected <?php } ?>>share</option>
										<option <?php if($row2['task_type'] == "post_with_link") { ?>selected <?php } ?>>post_with_link</option>
										<option <?php if($row2['task_type'] == "post_without_link") { ?>selected <?php } ?>>post_without_link</option>
										<option <?php if($row2['task_type'] == "upload_photo") { ?>selected <?php } ?>>upload_photo</option>
										<option <?php if($row2['task_type'] == "like") { ?>selected <?php } ?>>like</option>
										<option <?php if($row2['task_type'] == "comment") { ?>selected <?php } ?>>comment</option>
									</select></div>
								</div>
								<div class="form-group">
									<label for="admin-role" class="col-sm-2 control-label">Check Type</label>
									<div class="col-sm-8"><select name="check_type" id="check_type" class="form-control1">
										<option <?php if($row2['check_type'] == "auto") { ?>selected <?php } ?>>auto</option>
										<option <?php if($row2['check_type'] == "manual") { ?>selected <?php } ?>>manual</option>
									</select></div>
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Task Secondary Category</label>			
									<div class="col-sm-8"><select name="task_second_cat" id="task_second_cat" class="form-control1">
										<option <?php if($row2['task_second_cat'] == "without_message") { ?>selected <?php } ?>>without_message</option>
										<option <?php if($row2['task_second_cat'] == "with_message") { ?>selected <?php } ?>>with_message</option>
									</select></div>
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
