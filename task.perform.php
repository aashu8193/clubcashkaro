<?php
session_start();
require_once __DIR__ . '/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1209443575843100',
  'app_secret' => '119c750393dd48e2969cb53ada8ae9b3',
  'default_graph_version' => 'v2.8',
]);

include 'connect.inc.php';    
  
$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','publish_actions','user_posts']; // optional
	
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();

  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
}

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
    
    //************************************************************************************************************************************
	//GETTING USER INFORMATION FROM USER_TABLE
	$checkquery2 = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
	$result2 = $pdo->query($checkquery2);
	$row2 = $result2->fetch();
        $fid = $row2['fid'];
	
        //USING DATA SENT BY TASK FORM & GETTING TASK INFO FROM TASK TABLE
        $taskid = $_POST['taskid'];	
        $platform = $_POST['platform'];
	$task_type = $_POST['task_type'];	
	
	if (isset($_POST['message'])) {
		$message = $_POST['message'];		
	}
        
        if (isset($_POST['tag_friend_name'])) {
		$tag_friend_name = $_POST['tag_friend_name'];
         
                try {
                        $requestFriends = $fb->get('/me/taggable_friends?fields=id,name&limit=1000');
                        $friends = $requestFriends->getGraphEdge();
                } catch(Facebook\Exceptions\FacebookResponseException $e) {
                        // When Graph returns an error
                        echo 'Graph returned an error: ' . $e->getMessage();
                        exit;
                } catch(Facebook\Exceptions\FacebookSDKException $e) {
                        // When validation fails or other local issues
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                }

                // if have more friends than 1000 as we defined the limit above on line no. 68
                if ($fb->next($friends)) {
                        $allFriends = array();
                        $friendsArray = $friends->asArray();
                        $allFriends = array_merge($friendsArray, $allFriends);
                        while ($friends = $fb->next($friends)) {
                                $friendsArray = $friends->asArray();
                                $allFriends = array_merge($friendsArray, $allFriends);
                        }
                        foreach ($allFriends as $key) {
                            //echo $key['name'] . "<br>";
                            if ($key['name'] == $tag_friend_name)
                            { 
                                $tag_friend_id = $key['id'];
     
                            } 
                            
                        }
         
                } else {
                        $allFriends = $friends->asArray();
                        $totalFriends = count($allFriends);
                        foreach ($allFriends as $key) {
                            // echo $key['name'] . "<br>";
                            if ($key['name'] == $tag_friend_name)
                            { 
                                $tag_friend_id = $key['id'];
     
                            }
                        }              
                }
          
	}
	
	$checkquery = "SELECT * FROM `task_table` WHERE `task_id` = '{$taskid}'";
	$result = $pdo->query($checkquery);
	$row = $result->fetch();
	$link = $row['link'];
	
	$object_id = $row['object_id'];

	if (substr($link, 0, 19) == "http://cashkaro.com") {
		
		$link = $link."/r=".$row2['ck_ref_id'];
	}
		
	$points = $row['points'];
	$check_type = $row['check_type'];
        $tag_friend = $row['tag_friend'];
 	
    //************************************************************************************************************************************
	
    // POSTING WITH LINK on user timeline using publish_actions permission
    if (($task_type == "post_with_link") and ($check_type == "auto")) {
	try {
		// message must come from the user-end
                if ($tag_friend == 'yes')
                {  $data = ['link' => $link , 'message' => $message , 'tags' => $tag_friend_id]; }
                else { $data = ['link' => $link , 'message' => $message]; } 		
		$request = $fb->post('/me/feed', $data);
		$response = $request->getGraphNode();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
        $responseid = $response['id'];
	  	
	$status = "submitted";
 	$query = "UPDATE `task_assign_table` SET `status` = '{$status}' WHERE `fid` = '{$fid}' AND `task_id` = '{$taskid}'";
        $affectedrows = $pdo->exec($query);
	
	$insertquery = "INSERT INTO `task_status_table` SET
			        `fid` = :fid,
				`task_id` = :task_id,
				`post_id` = :post_id,
				`status` = :status,
				`task_type` = :task_type,
				`platform` = :platform,
				`submission_date` = CURDATE(),
				`submission_time` = CURTIME(),
				`check_type` = :check_type,
				`points` = :points";
                 				
			$insert = $pdo->prepare($insertquery);
                        $insert->bindvalue(':fid', $fid);
			$insert->bindvalue(':task_id', $taskid);
			$insert->bindvalue(':post_id', $responseid);
                        $insert->bindvalue(':points', $points);
			$insert->bindvalue(':status', $status);
			$insert->bindvalue(':platform', $platform);
			$insert->bindvalue(':task_type', $task_type);
			$insert->bindvalue(':check_type', $check_type);
                        $insert->execute();	
	echo "Success";	
    }
	
    //************************************************************************************************************************************
	
    // POSTING WITHOUT LINK on user timeline using publish_actions permission
    if (($task_type == "post_without_link") and ($check_type == "auto")) {
	try {
		// message must come from the user-end
                if ($tag_friend == 'yes')
                { $data = ['message' => $message , 'tags' => $tag_friend_id]; }
                else{ $data = ['message' => $message]; }
		$request = $fb->post('/me/feed', $data);
		$response = $request->getGraphNode();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
        $responseid = $response['id'];
	  	
	$status = "submitted";
 	$query = "UPDATE `task_assign_table` SET `status` = '{$status}' WHERE `fid` = '{$fid}' AND `task_id` = '{$taskid}'";
        $affectedrows = $pdo->exec($query);
	
                $insertquery = "INSERT INTO `task_status_table` SET
			    `fid` = :fid,
				`task_id` = :task_id,
				`post_id` = :post_id,
				`status` = :status,
				`task_type` = :task_type,
				`platform` = :platform,
				`submission_date` = CURDATE(),
				`submission_time` = CURTIME(),
				`check_type` = :check_type,
				`points` = :points";
                 				
			$insert = $pdo->prepare($insertquery);
                        $insert->bindvalue(':fid', $fid);
			$insert->bindvalue(':task_id', $taskid);
			$insert->bindvalue(':post_id', $responseid);
                        $insert->bindvalue(':points', $points);
			$insert->bindvalue(':status', $status);
			$insert->bindvalue(':platform', $platform);
			$insert->bindvalue(':task_type', $task_type);
			$insert->bindvalue(':check_type', $check_type);
                        $insert->execute();	
	echo "Success";	
    }
	
    //************************************************************************************************************************************
	
    //SHARING on user timeline using publish_actions permission
	
    if (($task_type == "share") and ($check_type == "auto")) {
	try {
		// message must come from the user-end
                if ($tag_friend == 'yes')
                { $data = ['link' => $link , 'tags' => $tag_friend_id]; }    
                else{ $data = ['link' => $link]; }
		$request = $fb->post('/me/feed', $data);
		$response = $request->getGraphNode();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}    
        $responseid = $response['id'];
	
	$status = "submitted";
 	$query = "UPDATE `task_assign_table` SET `status` = '{$status}' WHERE `fid` = '{$fid}' AND `task_id` = '{$taskid}'";
        $affectedrows = $pdo->exec($query);
	
	$insertquery = "INSERT INTO `task_status_table` SET
                                `fid` = :fid,
				`task_id` = :task_id,
				`post_id` = :post_id,
				`status` = :status,
				`task_type` = :task_type,
				`platform` = :platform,
				`submission_date` = CURDATE(),
				`submission_time` = CURTIME(),
				`check_type` = :check_type,
				`points` = :points";
                 				
			$insert = $pdo->prepare($insertquery);
                        $insert->bindvalue(':fid', $fid);
			$insert->bindvalue(':task_id', $taskid);
			$insert->bindvalue(':post_id', $responseid);
                        $insert->bindvalue(':points', $points);
			$insert->bindvalue(':status', $status);
			$insert->bindvalue(':platform', $platform);
			$insert->bindvalue(':task_type', $task_type);
			$insert->bindvalue(':check_type', $check_type);
                        $insert->execute();	
	
	echo "Success";	
    }
	
    //UPLOADING PICTURE on user timeline using publish_actions permission
	
    if (($task_type == "upload_photo") and ($check_type == "auto")) {
	try {
		// message must come from the user-end
                if ($tag_friend == 'yes')
                { $data = ['source' => $fb->fileToUpload($link), 'message' => $message , 'tags' => $tag_friend_id]; }    
                else{ $data = ['source' => $fb->fileToUpload($link), 'message' => $message]; }
		$request = $fb->post('/me/photos', $data);
		$response = $request->getGraphNode()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	$responseid = $response['id'];
	
	$post_id = $fid."_".$response['id'];
	
	$status = "submitted";
 	$query = "UPDATE `task_assign_table` SET `status` = '{$status}' WHERE `fid` = '{$fid}' AND `task_id` = '{$taskid}'";
        $affectedrows = $pdo->exec($query);
	
	$insertquery = "INSERT INTO `task_status_table` SET
			    `fid` = :fid,
				`task_id` = :task_id,
				`post_id` = :post_id,
				`status` = :status,
				`task_type` = :task_type,
				`platform` = :platform,
				`submission_date` = CURDATE(),
				`submission_time` = CURTIME(),
				`check_type` = :check_type,
				`points` = :points";
                 				
			$insert = $pdo->prepare($insertquery);
                        $insert->bindvalue(':fid', $fid);
			$insert->bindvalue(':task_id', $taskid);
			$insert->bindvalue(':post_id', $post_id);
                        $insert->bindvalue(':points', $points);
			$insert->bindvalue(':status', $status);
			$insert->bindvalue(':platform', $platform);
			$insert->bindvalue(':task_type', $task_type);
			$insert->bindvalue(':check_type', $check_type);
                        $insert->execute();	
	
	echo "Success";	
    }
	
    //LIKING A POST
	
    if (($task_type == "like") and ($check_type == "auto")) {
	$like = $fb->post('/' . $object_id . '/likes'); // liking that post on facebook
	$like->getGraphNode()->asArray();
	
	$status = "submitted";
 	$query = "UPDATE `task_assign_table` SET `status` = '{$status}' WHERE `fid` = '{$fid}' AND `task_id` = '{$taskid}'";
        $affectedrows = $pdo->exec($query);
	
	$insertquery = "INSERT INTO `task_status_table` SET
			        `fid` = :fid,
				`task_id` = :task_id,
				`status` = :status,
				`post_id` = :post_id,
				`task_type` = :task_type,
				`platform` = :platform,
				`submission_date` = CURDATE(),
				`submission_time` = CURTIME(),
				`check_type` = :check_type,
				`points` = :points";
                 				
			$insert = $pdo->prepare($insertquery);
                        $insert->bindvalue(':fid', $fid);
			$insert->bindvalue(':task_id', $taskid);
                        $insert->bindvalue(':points', $points);
			$insert->bindvalue(':post_id', 'no_post_id');
			$insert->bindvalue(':status', $status);
			$insert->bindvalue(':platform', $platform);
			$insert->bindvalue(':task_type', $task_type);
			$insert->bindvalue(':check_type', $check_type);
                        $insert->execute();	
	
	echo "Success";	
    }
	
    //COMMENT on user timeline using publish_actions permission
	
    if (($task_type == "comment") and ($check_type == "auto")) {
	try {
		// message must come from the user-end
		$post = $fb->post('/' . $object_id . '/comments', array('message' =>  $message));
		$post = $post->getGraphNode()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	$status = "submitted";
 	$query = "UPDATE `task_assign_table` SET `status` = '{$status}' WHERE `fid` = '{$fid}' AND `task_id` = '{$taskid}'";
        $affectedrows = $pdo->exec($query);
	
	$insertquery = "INSERT INTO `task_status_table` SET
                                `fid` = :fid,
				`task_id` = :task_id,
				`status` = :status,
				`post_id` = :post_id,
				`task_type` = :task_type,
				`platform` = :platform,
				`submission_date` = CURDATE(),
				`submission_time` = CURTIME(),
				`check_type` = :check_type,
				`points` = :points";
                 				
			$insert = $pdo->prepare($insertquery);
                        $insert->bindvalue(':fid', $fid);
			$insert->bindvalue(':task_id', $taskid);
                        $insert->bindvalue(':points', $points);
			$insert->bindvalue(':post_id', 'no_post_id');
			$insert->bindvalue(':status', $status);
			$insert->bindvalue(':platform', $platform);
			$insert->bindvalue(':task_type', $task_type);
			$insert->bindvalue(':check_type', $check_type);
                        $insert->execute();	
	
	echo "Success";	
    }
	
    //MANUAL TASKS OR BONUS TASKS
	
    if ($check_type == "manual") 
    {

	$status = "submitted";
 	$query = "UPDATE `task_assign_table` SET `status` = '{$status}' WHERE `fid` = '{$fid}' AND `task_id` = '{$taskid}'";
        $affectedrows = $pdo->exec($query);
	
	$insertquery = "INSERT INTO `task_status_table` SET
                                `fid` = :fid,
				`task_id` = :task_id,
				`status` = :status,
				`post_id` = :post_id,
				`task_type` = :task_type,
				`platform` = :platform,
				`submission_date` = CURDATE(),
				`submission_time` = CURTIME(),
				`check_type` = :check_type,
				`points` = :points";
                 				
			$insert = $pdo->prepare($insertquery);
                        $insert->bindvalue(':fid', $fid);
			$insert->bindvalue(':task_id', $taskid);
                        $insert->bindvalue(':points', $points);
			$insert->bindvalue(':post_id', 'no_post_id');
			$insert->bindvalue(':status', $status);
			$insert->bindvalue(':platform', $platform);
			$insert->bindvalue(':task_type', $task_type);
			$insert->bindvalue(':check_type', $check_type);
                        $insert->execute();	
	
	echo "Manual";	
    }
	
    // Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']

} else {
    // replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
    $loginUrl = $helper->getLoginUrl('http://clubcashkaro.com/', $permissions);	
}
?>