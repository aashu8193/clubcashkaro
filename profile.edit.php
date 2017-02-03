<?php
session_start();
include 'connect.inc.php'; 

    //fetching user information from user_table
    $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
    $result = $pdo->query($checkquery);
    $row = $result->fetch();
	
    if (isset($_POST['hometown'])) {    
        
        $hometown = $_POST['hometown'];
       	$college = $_POST['college'];
        $mobile = $_POST['mobile'];
	        
        $query = "UPDATE `user_table` SET `hometown` = '{$hometown}' , `college` = '{$college}' , `mobile` = '{$mobile}' WHERE `fid` = '{$row['fid']}'";     
        $affectedrows = $pdo->exec($query);		
        echo "Success";

    } else {
       echo "Failed";
    }

?>