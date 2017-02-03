<?php
session_start();
set_time_limit(0);
session_start();
require_once __DIR__ . '/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1209443575843100',
  'app_secret' => '119c750393dd48e2969cb53ada8ae9b3',
  'default_graph_version' => 'v2.8',
]);

if  ((isset($_SESSION['facebook_access_token'])) and (isset($_SESSION['fid']))) {
    include 'connect.inc.php';    
    
    //fetching user information from user_table
    $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
    $result = $pdo->query($checkquery);
    $row = $result->fetch();

    $total_points = $row['total_points'] + $row['bonus_points'];
    
    //checking account_status based on ref_id_status
    if ($row['ref_id_status']=="to_submit")
    { $account_status = "IN-ACTIVE";}
    elseif($row['ref_id_status']=="approved")
    { $account_status = "ACTIVE";}
    elseif($row['ref_id_status']=="pending")
    { $account_status = "PENDING";}

    //fetching all assigned tasks to that user
    $checkquery2 = "SELECT * FROM `task_assign_table` WHERE `fid` = '{$row['fid']}' AND `status` = 'assigned'";
    $result2 = $pdo->query($checkquery2);
    while($row2 = $result2->fetch()) {
        $taskid[] = $row2['task_id'];
        $taskstatus[] = $row2['status'];
	$points_earned[] = $row2['points_earned'];
    }
	
    //fetching all Submitted tasks to that user from status table
    $checkquery5 = "SELECT * FROM `task_status_table` WHERE `fid` = '{$row['fid']}' AND (`status` = 'submitted' OR `status` = 'approved' OR `status` = 'rejected') ORDER BY `submission_date` DESC LIMIT 20";
    $result5 = $pdo->query($checkquery5);
    while($row5 = $result5->fetch()) {
       $taskid_status_table[] = $row5['task_id'];
       $taskstatus_status_table[] = $row5['status'];
       $points_earned_status_table[] = $row5['points_earned'];
       $submission_date_status_table[] = $row5['submission_date'];
       $post_id_status_table[] = $row5['post_id'];
    }
	
    
    //calculating rank for this particular user
    $checkquery4 = "SELECT * FROM `user_table` ORDER BY `total_points` + `bonus_points` DESC";
    $result4 = $pdo->query($checkquery4);
    while($row4 = $result4->fetch()) {
      $fid_rankwise[] =  $row4['fid'];
    }
    for($i = 0; $i < sizeof($fid_rankwise); $i++)
    {
        if ($row['fid'] == $fid_rankwise[$i])
        { $user_rank = $i + 1 ;}

    } 
    
    $accessToken = $_SESSION['facebook_access_token'];
    
    if (isset($accessToken))
    {

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
 
            $getPostsLikes = $fb->get('/me/posts?fields=comments.limit(1000){name,id},likes.limit(1000){name,id}&limit=100');
            $getPostsLikes = $getPostsLikes->getGraphEdge()->asArray();

              
    }

	
}else{		
	header('Location: http://clubcashkaro.com/');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>My Tasks</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato"/>
<script src="js/task.perform.js"></script>  
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/responsive.css">
</head>
<body>
    
<?php include 'header.inc.php'; ?>

<!--Container Start Here-->  
<div class="container-section">
    <div class="bredcum">
        <div class="wrap">
	    <a href="#">My Tasks</a>
        </div>
    </div>
    <div class="welcome-section">
	    <div class="wrap">
                <div class="welcome">
                    <div class="profile">
                        <img src="<?php echo $row['fb_profile_pic_url']; ?>">
                        <h1><?php echo $row['name']; ?></h1>
                        <div class="points">
                          <div class="point-left">
                               <span>Total Points Earned</span>
                               <strong><?php echo $total_points; ?></strong>
                          </div>
                          <div class="point-right">
                               <span>Rank</span>
                               <strong><?php echo $user_rank; ?></strong>
                          </div>
                        </div>
                    </div>
			
	            <?php if (($row['ref_id_status']=="to_submit") or ($row['ref_id_status']=="pending")) { ?>
		    <div class="pending-cash">
                            <img src="images/pending.png">
                            <div class="pending-text">
                                You are now one step
                                away from becoming
                                Captain Cash! 
                            </div>
                            <div class="image-text">
                                <p>Your account will be activated within 24 hours from the time of submission of details.</p>
                            </div>
                    </div>
                    <?php } 
                    elseif ($row['ref_id_status']=="rejected") { ?>
                    <div class="rejected">
                            <img src="images/rejected.png">
                            <div class="rejected-text">           
                               Ooops!! You missed
                                being Captain Cash by
                                a few friends!!
                            </div>
                            <div class="image-text">
                                <p>Thank you for showing interest in CashKaro Ambassador Program, however the minimum requisite to enrol for CashKaro club is having at least 300 friends on Facebook. Every new friend is a new adventure - come back & join us once you have the same. Be the next Captain Cash!</p>
                            </div>
                    </div>
                    <?php }		
                    elseif ($row['ref_id_status']=="approved") { ?> 			
		    <div class="task-sec">
                            <h3>TASKS</h3><img class="info-togg" src="images/task-icon.png">
			    <div class="info-togg-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                            <div class="task-bar">
                                 <ul class="nav nav-tabs">
                                    <li class="active"><a href="#assigned">Assigned Tasks</a></li>
                                    <li><a href="#submitted">Submitted Tasks</a></li>
                                 </ul>
                            </div>

                            <div class="tab-content">
                                    <div id="assigned" class="tab-pane fade in active">
                                    <?php
                                    for($i = 0; $i < sizeof($taskid); $i++)
                                    {  if (($taskstatus[$i]=="assigned")) {
                                        
                                        $checkquery3 = "SELECT * FROM `task_table` WHERE `task_id` = '{$taskid[$i]}'";
                                        $result3 = $pdo->query($checkquery3);
                                        $row3 = $result3->fetch();  

                                        $datenow = date_create("now"); 
                                        $date2 = date_create($row3['expiry_date']);
                                        $diff = date_diff($datenow,$date2);
                                        $daydiff = $diff->format('%R%a days');
                                        $hourdiff = $diff->format('%R%h hours'); 
                                        
                                        if($daydiff>0 Or $hourdiff>0)
                                        {   $daydiffwrite = $diff->format('%a days'); 
                                            $hourdiffwrite = $diff->format('%h hours');
                                            if($daydiffwrite<1)
                                            { $expirytext = $hourdiffwrite; }
                                            else 
                                            { $expirytext = $daydiffwrite;}										

                                            if ($row3['task_second_cat'] == "without_message")
                                            {							
                                        ?>
                                            <div class="First-task <?php if  ($row3['check_type'] == "manual") {echo "bg";} ?>">
                                                    <h3>Task ID : <?php echo $taskid[$i]; ?></h3><h4>Expires In <?php echo $expirytext; ?></h4><?php if  ($row3['check_type'] == "manual") { ?><h5>Bonus Task</h5><?php } ?>
                                                    <strong><?php echo $row3['task_short_desc']; ?></strong><span>POINTS<br><?php echo $row3['points']; ?></span>
                                                    <p><?php echo $row3['task_long_desc']; ?></p>
                                                    <div class="task-perform-button">				
                                                    <form>
                                                        <input type="hidden" value="<?php echo $taskid[$i]; ?>" name="taskid">
                                                        <input type="hidden" value="<?php echo $row3['platform']; ?>" name="platform">
                                                        <input type="hidden" value="<?php echo $row3['task_type']; ?>" name="task_type">
                                                        <?php if($row3['tag_friend'] == 'yes') { ?>
                                                        <input type="text" name="tag_friend_name" placeholder="Friend Name To Tag">
                                                        <?php } ?>
                                                        <input type="submit" id="myBtn" value="CLICK TO DO THIS FACEBOOK TASK">
                                                    </form>						  
                                                    </div>                
                                                    <p>Task Done will be confirmed in 24 Hours</p>
                                            </div>

                                        <?php }
                                        elseif ($row3['task_second_cat'] == "with_message") {
                                        ?>

                                            <div class="First-task <?php if  ($row3['check_type'] == "manual") {echo "bg";} ?>">
                                                    <h3>Task ID : <?php echo $taskid[$i]; ?></h3><h4>Expires In <?php echo $expirytext; ?></h4><?php if  ($row3['check_type'] == "manual") { ?><h5>Bonus Task</h5><?php } ?>
                                                    <strong><?php echo $row3['task_short_desc']; ?></strong><span>POINTS<br><?php echo $row3['points']; ?></span>
                                                    <p><?php echo $row3['task_long_desc']; ?></p>
                                                    <div class="task-perform-button">				
                                                    <form>
                                                        <textarea name="message" id="message"><?php echo $row3['message']; ?></textarea>
                                                        <input type="hidden" value="<?php echo $taskid[$i]; ?>" name="taskid">
                                                        <input type="hidden" value="<?php echo $row3['platform']; ?>" name="platform">
                                                        <input type="hidden" value="<?php echo $row3['task_type']; ?>" name="task_type">
                                                        <?php if($row3['tag_friend'] == 'yes') { ?>
                                                        <input type="text" name="tag_friend_name" placeholder="Friend Name To Tag">
                                                        <?php } ?>
                                                        <input type="submit" id="myBtn" value="CLICK TO DO THIS FACEBOOK TASK">
                                                    </form>						  
                                                    </div>
                                                <p>Task Done will be confirmed in 24 Hours</p>
                                            </div>

                                    <?php							
                                    } } } } ?>
                                    </div>
                
				
				    <div id="submitted" class="tab-pane fade">
				    <?php
				    for($i = 0; $i < sizeof($taskid_status_table); $i++)
                                    {  if (($taskstatus_status_table[$i]=="submitted") or ($taskstatus_status_table[$i]=="approved") or ($taskstatus_status_table[$i]=="rejected")) {
                                                $checkquery6 = "SELECT * FROM `task_table` WHERE `task_id` = '{$taskid_status_table[$i]}'";
                                                $result6 = $pdo->query($checkquery6);
                                                $row6 = $result6->fetch();     

                                                // printing likes data as per requirements
                                                foreach ($getPostsLikes as $key) {
                                                    if( $key['id'] == $post_id_status_table[$i])
                                                    {    
                                                        if (isset($key['likes'])) {
                                                            $no_likes = count($key['likes']);             
                                                        }
                                                        else
                                                        {
                                                            $no_likes = 0;
                                                        }    
                                                        if (isset($key['comments'])) {
                                                            $no_comments = count($key['comments']);
                                                        }
                                                        else
                                                        {
                                                            $no_comments = 0;
                                                        }	
                                                    }

                                               } 

                                    ?>	
				            <div class="First-task <?php if  ($row6['check_type'] == "manual") {echo "bg";} ?>">
                                                    <h3>Task ID : <?php echo $taskid_status_table[$i]; ?></h3><h4>Submitted On : <?php echo $submission_date_status_table[$i]; ?></h4><?php if  ($row6['check_type'] == "manual") { ?><h5>Bonus Task</h5><?php } ?>
                                                    <strong><?php echo $row6['task_short_desc']; ?></strong><span>POINTS EARNED<br><?php echo $points_earned_status_table[$i]; ?></span>
                                                    <p><?php echo $row6['task_long_desc']; ?></p>
                                                    <?php
                                                        if ($taskstatus_status_table[$i] == "submitted")
                                                        { $submitted_button_text = "WAITING FOR APPROVAL";}
                                                        elseif ($taskstatus_status_table[$i] == "approved")
                                                        { $submitted_button_text = "APPROVED";}
                                                        elseif ($taskstatus_status_table[$i] == "rejected")
                                                        { $submitted_button_text = "REJECTED";}
								
                                                    ?>
                                                    <div class="share-facebook">
                                                        <button type="button" disabled><?php echo $submitted_button_text; ?></button>
                                                    </div>
                                                    <h3>Likes : <?php echo $no_likes; ?></h3><h4>Comments : <?php echo $no_comments; ?></h4>
                                            </div>
				    <?php } } ?>
                                    </div>
                            </div>


                    </div>
                    <?php							
                    } ?>
		</div>    	
            </div>
    </div>
	
    
<?php include 'about_info_credentials.inc.php'; ?>    
    
    
</div>
<!--Container Close Here--> 
<?php include 'footer.inc.php'; ?>

<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
    <div class="modal-content">
        <h1 id="modalh1">Sit Back & Relax Captain Cash is doing your Facebook Task.</h1>
        <div class="cashkaro-image">	
             <img src="images/logopop.png" />
             <img src="images/intermediary.gif" />
             <img src="images/facebook-26dec.jpg" />
        </div>   
    </div>
</div>

<script>
 $(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
		$(this).addClass("active");
    });
 });
</script>

<script>
$(document).ready(function(){
    $(".info-togg").click(function(){
        $(".info-togg-text").toggle();
    });
});
</script>


</body>
</html>
