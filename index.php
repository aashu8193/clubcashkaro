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

$permissions = ['email','publish_actions','user_posts','user_friends','user_birthday','user_hometown','user_education_history']; // optional
	
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

	// getting basic info about user
	try {
		$profile_request = $fb->get('/me?fields=name,email,id,hometown,gender,about,birthday,education');
		$profile = $profile_request->getGraphNode()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to app login page
		header("Location: ./");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	
	try {
		$requestPicture = $fb->get('/me/picture?redirect=false&height=300'); //getting user picture
		$picture = $requestPicture->getGraphUser();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
		
	// get list of friends' names
	try {
		$requestFriends = $fb->get('/me/taggable_friends?fields=name&limit=1000');
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

	// if have more friends more than 1000 as we defined the limit above on line no. 99
	if ($fb->next($friends)) {
		$allFriends = array();
		$friendsArray = $friends->asArray();
		$allFriends = array_merge($friendsArray, $allFriends);
		while ($friends = $fb->next($friends)) {
			$friendsArray = $friends->asArray();
			$allFriends = array_merge($friendsArray, $allFriends);
		}
		$totalFriends = count($allFriends);
	} else {
		$allFriends = $friends->asArray();
		$totalFriends = count($allFriends);
	
	}
	
	
               $fid = $profile['id'];
               $_SESSION['fid'] = $fid;		   
               $accessToken2 = $_SESSION['facebook_access_token'];

               $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$fid}'";
               $result = $pdo->query($checkquery);
               $rowno = $result->rowCount();

	    
		if($rowno == 0) {
			
			$insertquery = "INSERT INTO `user_table` SET
				`fid` = :f_id,
				`user_type` = 'bronze',
				`access_token` = :access_token,
				`date_joining` = CURDATE()";
                 				
			$insert = $pdo->prepare($insertquery);
			$insert->bindvalue(':access_token', $accessToken2);
                        $insert->bindvalue(':f_id', $fid);			
                        $insert->execute();
			
			
			if (isset($profile['email'])) {
                          $emailid = $profile['email'];
                          $query = "UPDATE `user_table` SET `email_id` = '{$emailid}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);  
                        }
			
			if (isset($profile['name'])) {
                          $name = $profile['name'];
                          $query = "UPDATE `user_table` SET `name` = '{$name}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
                        }
			
			if (isset($picture['url'])) {
                          $fb_profile_pic_url = $picture['url'];
                          $query = "UPDATE `user_table` SET `fb_profile_pic_url` = '{$fb_profile_pic_url}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
                        }

                        if (isset($allFriends)) {
                              $no_of_friends = count($allFriends);
                              $query = "UPDATE `user_table` SET `no_of_friends` = '{$no_of_friends}' WHERE `fid` = '{$fid}'";
                              $affectedrows = $pdo->exec($query);
			  
                              if ($no_of_friends < 300) {
                                $query = "UPDATE `user_table` SET `ref_id_status` = 'rejected' WHERE `fid` = '{$fid}'";
                                $affectedrows = $pdo->exec($query); 

                                $to = $emailid;
                                $subject = 'Welcome to CashKaro Club (Rejected)';		
                                $message = '</br>Dear '.$name.'</br>
                                            Welcome to CashKaro club. You are now just one step away from becoming Captain Cash.</br>
                                            Here are simple steps you need to follow for the same</br>
                                            1. Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account</br>
                                            2. Perform the Mandatory task to activate your account at Club CashKaro</br>
                                            •	Click on the Link and Register @ CashKaro.com – <Link></br>
                                            •	Once registered on CashKaro.com you can now submit the same email id you registered with</br>
                                            3. Your account will be activated within 24 hours of submission</br>
                                            Why complete Registration with CashKaro club? What’s in it for you?</br>
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
                                            Don’t hold back for you are just one step away from becoming Captain Cash & Earning :D</br>';			
                                $header = "From:noreply@clubcashkaro.com \r\n";
                                $header .= "MIME-Version: 1.0\r\n";
                                $header .= "Content-type: text/html\r\n";			
                                $retval = mail ($to,$subject,$message,$header);
      				  				  
			      }else {
				$query = "UPDATE `user_table` SET `ref_id_status` = 'to_submit' WHERE `fid` = '{$fid}'";
		                $affectedrows = $pdo->exec($query); 

                                $to = $emailid;
                                $subject = 'Welcome to CashKaro Club';		
                                $message = '</br>Dear '.$name.'</br>
                                            Welcome to CashKaro club. You are now just one step away from becoming Captain Cash.</br>
                                            Here are simple steps you need to follow for the same</br>
                                            1. Go to CashKaro Club’s website – www.clubcashkaro.com and Connect with your Facebook Account</br>
                                            2. Perform the Mandatory task to activate your account at Club CashKaro</br>
                                            •	Click on the Link and Register @ CashKaro.com – <Link></br>
                                            •	Once registered on CashKaro.com you can now submit the same email id you registered with</br>
                                            3. Your account will be activated within 24 hours of submission</br>
                                            Why complete Registration with CashKaro club? What’s in it for you?</br>
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
                                            Don’t hold back for you are just one step away from becoming Captain Cash & Earning :D</br>';			
                                $header = "From:noreply@clubcashkaro.com \r\n";
                                $header .= "MIME-Version: 1.0\r\n";
                                $header .= "Content-type: text/html\r\n";			
                                $retval = mail ($to,$subject,$message,$header);
         				  
			      }  
	                }
			
			if (isset($profile['about'])) {
                          $about_user = $profile['about'];
                          $query = "UPDATE `user_table` SET `about_user` = '{$about_user}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
                        }
			
			if (isset($profile['birthday'])) {
                          $user_bday = $profile['birthday']->format('Y-m-d');
                          $query = "UPDATE `user_table` SET `user_bday` = '{$user_bday}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
	        
			  $date1=date_create($user_bday);
                          $date2=$datenow = date_create("now");
                          $diff=date_diff($date1,$date2);
                          $age = floor(($diff->format("%R%a days"))/365);	
			  $query = "UPDATE `user_table` SET `age` = '{$age}' WHERE `fid` = '{$fid}'";
		          $affectedrows = $pdo->exec($query);
			}
			
			if (isset($profile['gender'])) {
        	          $gender = $profile['gender'];
                          $query = "UPDATE `user_table` SET `gender` = '{$gender}' WHERE `fid` = '{$fid}'";
		          $affectedrows = $pdo->exec($query);
	                }
 
                        if (isset($profile['hometown']['name'])) {
                          $hometown = $profile['hometown']['name'];
                          $query = "UPDATE `user_table` SET `hometown` = '{$hometown}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
                        }
			
			if (isset($profile['about'])) {
                          $about_user = $profile['about'];
                          $query = "UPDATE `user_table` SET `about` = '{$about_user}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
                        }
			
			if (isset($profile['education'][1]['school']['name'])) {
                          $college = $profile['education'][1]['school']['name'];
                          $query = "UPDATE `user_table` SET `college` = '{$college}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
                        }
       
  
                }
		
		if($rowno == 1) {
			$query = "UPDATE `user_table` SET `access_token` = '{$accessToken2}' WHERE `fid` = '{$fid}'";
		        $affectedrows = $pdo->exec($query);       
        
		        if (isset($profile['birthday'])) {
                          $user_bday = $profile['birthday']->format('Y-m-d');
                          $query = "UPDATE `user_table` SET `user_bday` = '{$user_bday}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
	        
			 		  
			  $date1=date_create($user_bday);
                          $date2=$datenow = date_create("now");
                          $diff=date_diff($date1,$date2);
                          $age = floor(($diff->format("%R%a days"))/365);	
			  $query = "UPDATE `user_table` SET `age` = '{$age}' WHERE `fid` = '{$fid}'";
		          $affectedrows = $pdo->exec($query);			
			}
			
                        if (isset($allFriends)) {
                          $no_of_friends = count($allFriends);;
                          $query = "UPDATE `user_table` SET `no_of_friends` = '{$no_of_friends}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);			  
                        }  	 
		
		        if (isset($picture['url'])) {
                          $fb_profile_pic_url = $picture['url'];
                          $query = "UPDATE `user_table` SET `fb_profile_pic_url` = '{$fb_profile_pic_url}' WHERE `fid` = '{$fid}'";
                          $affectedrows = $pdo->exec($query);
                        }
		}
		
  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
	   header('Location: http://clubcashkaro.com/welcome.php');
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('http://clubcashkaro.com/', $permissions);	
}
?>
<!DOCTYPE html>
<html>
<head>
<title>CashKaro Ambassadors Program</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/responsive.css">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>

<!--Header Start Here-->  
<div class="header-section">
    <div class="wrap">
    	<div class="header">
            <div class="logo">
            	<a href="/"><img src="images/logo.png" alt=""></a>
            </div>
            <div class="connect-with-us">
            	<a href="<?php echo $loginUrl; ?>">Connect With Us</a>
            </div>
        </div>
    </div>
</div>
<!--Header Close Here--> 

<!--HomePage Banner Start Here--> 
<div class="home-page-banner-section">
	<img class="desktop" src="images/banner-9.jpg" alt="">
	<img class="mobile" src="images/HomePage.jpg" alt=""> 
    <div class="home-page-banner-text">
    	<img src="images/cashkaro-ambessdor-program.png">
        <h3>Get Involved. Earn Rewards</h3>
        <p>Become the voice of CashKaro and Earn Rewards for a Lifetime</p>        
        <div class="connect-btn"><a href="<?php echo $loginUrl; ?>">Connect With Facebook</a><img src="images/facebook.png"></div>
    </div>	
</div>
<!--HomePage Banner Ends Here-->


<!--Container Start Here-->  
<div class="container-section">
	
<?php include 'about_info_credentials.inc.php'; ?>
    
</div>

<!--Container Close Here-->   

<?php include 'footer.inc.php'; ?>

</body>
</html>

