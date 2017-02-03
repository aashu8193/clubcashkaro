<?php
session_start();
require_once __DIR__ . '/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1209443575843100',
  'app_secret' => '119c750393dd48e2969cb53ada8ae9b3',
  'default_graph_version' => 'v2.8',
]);

include 'connect.inc.php';    
    
    $checkquery = "SELECT * FROM `task_status_table` WHERE `status` = 'submitted' AND `check_type` = 'auto'";
    $result = $pdo->query($checkquery);
    while($row = $result->fetch()) {
      $task_id[] = $row['task_id'];
      $fid[] = $row['fid'];
      $post_id[] = $row['post_id'];
      $submission_time[] = $row['submission_time'];
      $task_type[] = $row['task_type'];
      $points[] = $row['points'];
    } 

for($i = 0; $i < sizeof($post_id); $i++)
{
    $now_time = strtotime(date("H:i:s", time()));
    $task_time = strtotime($submission_time[$i]);
    
    $hour_diff = round(abs($now_time - $task_time) / 3600,2);
    	
        if($hour_diff > 6)     
        {	
                $counter = 0;
                $checkquery2 = "SELECT * FROM `user_table` WHERE `fid` = '{$fid[$i]}'";
                $result2 = $pdo->query($checkquery2);
                $row2 = $result2->fetch();
                $token = $row2['access_token'];
                $total_points = $row2['total_points'];
                $emailid = $row2['email_id'];
                $user_type = $row2['user_type']; 

                $checkquery2 = "SELECT * FROM `user_type_points_table` WHERE `user_type` = '{$user_type}'";
                $result2 = $pdo->query($checkquery2);
                $row2 = $result2->fetch();
                $credits = $row2['credits'];

                $checkquery3 = "SELECT * FROM `task_table` WHERE `task_id` = '{$task_id[$i]}'";
                $result3 = $pdo->query($checkquery3);
                $row3 = $result3->fetch();

                $task_long_desc = $row3['task_long_desc'];

                $_SESSION['facebook_access_token'] = $token;

                $accessToken = $token;


                if (($task_type[$i] == "share") or ($task_type[$i] == "post_with_link") or ($task_type[$i] == "post_without_link") or ($task_type[$i] == "upload_photo") )
                {

                    if (isset($accessToken)) {

                        if (isset($_SESSION['facebook_access_token'])) {
                            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                        } else {
                            // getting short-lived access token
                            $_SESSION['facebook_access_token'] = (string) $accessToken;

                            // OAuth 2.0 client handler
                            $oAuth2Client = $fb->getOAuth2Client();

                            // Exchanges a short-lived access token for a long-lived one
                            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

                            $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

                            // setting default access token to be used in script
                            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                        }

                        // redirect the user back to the same page if it has "code" GET variable
                        if (isset($_GET['code'])) {
                                header('Location: ./');
                        }

                        try {
                                $posts_request = $fb->get('/me/posts?fields=privacy,id&limit=500');
                        } catch(Facebook\Exceptions\FacebookResponseException $e) {
                                // When Graph returns an error
                                echo 'Graph returned an error: ' . $e->getMessage();
                                exit;
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                // When validation fails or other local issues
                                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                exit;
                        }


                        $total_posts = array();
                        $posts_response = $posts_request->getGraphEdge();
                        if($fb->next($posts_response)) {
                                $response_array = $posts_response->asArray();
                                $total_posts = array_merge($total_posts, $response_array);
                                while ($posts_response = $fb->next($posts_response)) {	
                                        $response_array = $posts_response->asArray();
                                        $total_posts = array_merge($total_posts, $response_array);	
                                }
                            //print_r($total_posts);
                                foreach ($total_posts as $key){
                                    // echo $key['id'].'<br>';
                                    if (($key['id'] == $post_id[$i]) AND ($key['privacy']['description'] !== "Only Me")){
                                            $counter = $counter + 1;
                                    }
                                }

                        } else {
                                $posts_response = $posts_request->getGraphEdge()->asArray();	
                             //print_r($posts_response).'<br>';
                                 foreach ($posts_response as $key){
                                    //echo $key['privacy']['description'].'<br>';
                                    if (($key['id'] == $post_id[$i]) AND ($key['privacy']['description'] !== "Only Me")){
                                            $counter = $counter + 1;
                                    }
                                }	
                        }

                        if ($counter == 0)
                        {

                            $query = "UPDATE `task_assign_table` SET `status` = 'rejected' WHERE `fid` = '{$fid[$i]}' AND `task_id` = '{$task_id[$i]}'";
                            $affectedrows = $pdo->exec($query);

                            $query2 = "UPDATE `task_status_table` SET `status` = 'rejected' WHERE `fid` = '{$fid[$i]}' AND `task_id` = '{$task_id[$i]}'";
                            $affectedrows2 = $pdo->exec($query2);

                            $to = $emailid;
                            $subject = 'Captain Cash – You Could Not Successfully Complete the Task';		
                            $message = 'Hi '.$row['name'].',

                                        Greetings from Club CashKaro!

                                        Sorry – Unfortunately you were not able to Successfully Complete the assigned task '.$task_long_desc.' so we will not be able to add '.$points_earned.' to your account.

                                        You can choose to try again or work on a new task.

                                        Here is how you can earn points on your assigned task @ Club CashKaro 
                                        1.	Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account
                                        2.	You can now go to “My Tasks” section and perform the task assigned to you
                                        3.	Every assigned task has a predefined points to it which will be added to your account automatically on completion & approval of the task
                                        4.	You just need to click the ‘’Share on Facebook’’ button and Captain Cash will perform the task for you, for every successful task you will earn points 
                                        Why perform tasks at Club CashKaro? What’s in it for you?
                                        1.	Earn in Your Free time
                                        a.	Perform Tasks as simple as sharing a post on your Facebook Profile & we thank you in Gift Vouchers as Rewards 
                                        2.	Take Home Extra Bonuses
                                        a.	Perform Bonus tasks and take more every month. Sky is your limit, earn as much as you like
                                        3.	Get Marketing Experience 
                                        a.	Get live social media marketing experience. Even better! You get special access to represent your college/company at major cities

                                        Here’s how your Remuneration looks like
                                        Every task that you perform has some predefined points  10 points = Re. 1 
                                        So when you stack up the points you are actually stacking money.

                                        Here’s how can you redeem your points
                                        1.	10 Points = Re 1
                                        2.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)
                                        3.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers
                                        4.	Once redeemed you will get the Gift Vouchers within 24 hours via mail';			
                            $header = "From:noreply@clubcashkaro.com \r\n";
                            $header .= "MIME-Version: 1.0\r\n";
                            $header .= "Content-type: text/html\r\n";			
                            $retval = mail ($to,$subject,$message,$header);	


                        }
                        else 
                        {

                            $points_earned = ($credits * $points[$i]);

                            $query = "UPDATE `task_assign_table` SET `status` = 'approved' , `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid[$i]}' AND `task_id` = '{$task_id[$i]}'";
                            $affectedrows = $pdo->exec($query);

                            $query2 = "UPDATE `task_status_table` SET `status` = 'approved' , `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid[$i]}' AND `task_id` = '{$task_id[$i]}'";
                            $affectedrows2 = $pdo->exec($query2);	

                            $total_points_new = $total_points + $points_earned; 
                            $query3 = "UPDATE `user_table` SET `total_points` = '{$total_points_new}' WHERE `fid` = '{$fid[$i]}'";
                            $affectedrows = $pdo->exec($query3);

                            $to = $emailid;
                            $subject = 'Well Done Captain Cash – You Have Successfully Completed the Task';		
                            $message = 'Hi '.$row['name'].',

                                        Greetings from Club CashKaro!

                                        Great Job, Well Done - You Have Successfully Completed the assigned task '.$task_long_desc.' and have earned '.$points_earned.' for yourself. These points will be added to your account.

                                        Here’s how your Remuneration looks like
                                        Every task that you perform has some predefined points  10 points = Re. 1 
                                        So when you stack up the points you are actually stacking money.

                                        Here’s how can you redeem your points
                                        9.	10 Points = Re 1
                                        10.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)
                                        11.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers
                                        12.	Once redeemed you will get the Gift Vouchers within 24 hours via mail';			
                            $header = "From:noreply@clubcashkaro.com \r\n";
                            $header .= "MIME-Version: 1.0\r\n";
                            $header .= "Content-type: text/html\r\n";			
                            $retval = mail ($to,$subject,$message,$header);
                        }	
                    }

                }

                if (($task_type[$i] == "like") or ($task_type[$i] == "comment"))
                {
                    $points_earned = ($credits * $points[$i]);	

                    $query = "UPDATE `task_assign_table` SET `status` = 'approved' , `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid[$i]}' AND `task_id` = '{$task_id[$i]}'";
                    $affectedrows = $pdo->exec($query);

                    $query2 = "UPDATE `task_status_table` SET `status` = 'approved' , `points_earned` = '{$points_earned}' WHERE `fid` = '{$fid[$i]}' AND `task_id` = '{$task_id[$i]}'";
                    $affectedrows2 = $pdo->exec($query2);

                    $total_points_new = $total_points + $points_earned; 
                    $query3 = "UPDATE `user_table` SET `total_points` = '{$total_points_new}' WHERE `fid` = '{$fid[$i]}'";
                    $affectedrows = $pdo->exec($query3);

                    $to = $emailid;
                    $subject = 'Well Done Captain Cash – You Have Successfully Completed the Task';		
                    $message = 'Hi '.$row['name'].',

                                Greetings from Club CashKaro!

                                Great Job, Well Done - You Have Successfully Completed the assigned task '.$task_long_desc.' and have earned '.$points_earned.' for yourself. These points will be added to your account.

                                Here’s how your Remuneration looks like
                                Every task that you perform has some predefined points  10 points = Re. 1 
                                So when you stack up the points you are actually stacking money.

                                Here’s how can you redeem your points
                                9.	10 Points = Re 1
                                10.	You can redeem once you have 1000 points ( which is equivalent to Rs 100)
                                11.	You can redeem these points as Amazon.in, Flipkart or PVR Gift Vouchers
                                12.	Once redeemed you will get the Gift Vouchers within 24 hours via mail';			
                    $header = "From:noreply@clubcashkaro.com \r\n";
                    $header .= "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html\r\n";			
                    $retval = mail ($to,$subject,$message,$header);
                }	
        }	
}

echo "Success";
?>
