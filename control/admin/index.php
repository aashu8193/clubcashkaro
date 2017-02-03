<?php
session_start();
include 'connect.inc.php';

if (isset($_SESSION['username']))
{
	header('Location: http://clubcashkaro.com/control/admin/dashboard.php');
}	
else
{
	if(isset($_POST['username']) && isset($_POST['password'])) {

		$username = $_POST['username'];
		$password = $_POST['password'];

		$checkquery = "SELECT * FROM `admin_users_table` WHERE `username` = '{$username}' AND `password` = '{$password}'";
	    $result = $pdo->query($checkquery);
	    $rowno = $result->rowCount();
		$row = $result->fetch();
	 
	    
		if($rowno == 1) {              
			$_SESSION['username'] = $username;
			header('Location: http://clubcashkaro.com/control/admin/dashboard.php');
		} else {
			$invalid = "Invalid Username and Password";
		}

	}
	
}	

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Admin Panel</title>
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
<body id="login">
  <div class="login-logo">
    <a href="index.php"><img src="images/logo.png" alt=""/></a>
  </div>
  <h2 class="form-heading">login</h2>
  <div class="app-cam">
	  <form id="login" action="index.php" method="post">
		<input type="text" class="text" name="username" value="Username" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Username';}">
		<input type="password" value="Password" name="password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}">
		<div class="submit"><input type="submit" value="Login"></div>
	  </form>
	   <ul class="new">
			<li class="new_left"><p><?php if (isset($invalid)){echo $invalid; }?></p></li>
			<div class="clearfix"></div>
		</ul>
  </div>
  <div class="copy_layout login">
      <p>Copyright &copy; 2017 Modern. All Rights Reserved!</p>
  </div>
</body>
</html>
