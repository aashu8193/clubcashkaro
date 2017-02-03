<?php
session_start();
include 'connect.inc.php';  
    if (isset($_POST['emailid'])) {    
        $emailid = $_POST['emailid'];
       	$ref_id_status = "pending";   
	        
        $query = "UPDATE `user_table` SET `ck_email_id` = '{$emailid}' , `ref_id_status` = '{$ref_id_status}' WHERE `fid` = '{$_SESSION['fid']}'";     
        $affectedrows = $pdo->exec($query);
		
	$checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch(); 		
		
	               
		        
                $to = $emailid;
                $subject = 'Congratulations! You are now one step away to become Captain Cash';		
                $message = 'Wuhooo – Congratulations you have submitted your details to become the next Captain Cash.</br>
                            It’ll take 24 hours to align tasks for you and approve your status at Club CashKaro.

                            Why wait for status approval at CashKaro club? What’s in it for you?</br>
                            1.	Earn in Your Free time</br>
                            Perform Tasks as simple as sharing a post on your Facebook Profile & we thank you in Gift Vouchers as Rewards </br>
                            2.	Take Home Extra Bonuses</br>
                            Perform Bonus tasks and take more every month. Sky is your limit, earn as much as you like</br>
                            3.	Get Marketing Experience </br>
                            Get live social media marketing experience. Even better! You get special access to represent your college/company at major cities</br>
                            Here’s how your Remuneration looks like</br>
                            Every task that you perform has some predefined points ? 10 points = Re. 1 </br>
                            So when you stack up the points you are actually stacking money.</br>

                            Here’s how can you redeem your points</br>
                            1.	10 Points = Re 1</br>
                            2.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)</br>
                            3.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers</br>
                            4.	Once redeemed you will get the Gift Vouchers within 24 hours via mail</br>

                            Don’t hold back for you are just one step away from becoming Captain Cash & Earning';			
                $header = "From:noreply@clubcashkaro.com \r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html\r\n";			
                $retval = mail ($to,$subject,$message,$header);		
		
			
        echo "Success";

   } else {
	   echo "Failed";
   }

?>