<?php
session_start();
include 'connect.inc.php'; 

    //fetching user information from user_table
    $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
    $result = $pdo->query($checkquery);
    $row = $result->fetch();

    $total_points = $row['total_points'] + $row['bonus_points'];

    $points_redeemed = $row['points_redeemed'];
    
    $points_available = $total_points - $points_redeemed;

    $points_requested = $_POST['points_requested'];
	
    $gift_type = $_POST['gift_type'];
 
    if (($points_available >= 1000) AND ($points_requested < $points_available)) {    
        
        $status = "requested";
        $insertquery = "INSERT INTO `redeem_table` SET
				`fid` = :fid,
				`email_id` = :email_id,
				`points_redeemed` = :points_redeemed,
				`gift_type` = :gift_type,
				`status` = :status,
				`date_requested` = CURDATE()";
                 				
            $insert = $pdo->prepare($insertquery);
            $insert->bindvalue(':fid', $row['fid']);
            $insert->bindvalue(':email_id', $row['email_id']);
            $insert->bindvalue(':points_redeemed', $points_requested);
            $insert->bindvalue(':gift_type', $gift_type);
            $insert->bindvalue(':status', $status);			
            $insert->execute();		
        echo "Success";
        
        $worth_rs = $points_requested/10;
        
        $to = $row['email_id'];
	$subject = 'Captain Cash – Time to Party!';		
	$message = 'Hi '.$row['name'].',

                    Greetings from Club CashKaro!

                    Captain Cash - You have been amazing and we salute you for all your hard-work.

                    We have received your request for <Amazon.in/ Flipkart/PVR> Gift Vouchers for earned '.$points_requested.' which are worth Rs '.$worth_rs.'

                    You will get the Gift Vouchers within 48 hours via mail.

                    Enjoy!

                    PS – In case you don’t get your Gift Voucher within 24 hours, please mail us at students@cashkaro.com';			
	$header = "From:noreply@clubcashkaro.com \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";			
        $retval = mail ($to,$subject,$message,$header);	
        
   } else {
        echo "Failed";
   }

?>