<?php
session_start();
if  ((isset($_SESSION['facebook_access_token'])) and (isset($_SESSION['fid']))) {
    
    include 'connect.inc.php';   
    //fetching user information from user_table
    $checkquery = "SELECT * FROM `user_table` WHERE `fid` = '{$_SESSION['fid']}'";
    $result = $pdo->query($checkquery);
    $row = $result->fetch();

    $total_points = $row['total_points'] + $row['bonus_points'];

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
		
    //fetching data for leaderboard
    $checkquery4 = "SELECT * FROM `user_table` ORDER BY `total_points` + `bonus_points` DESC LIMIT 10";
    $result4 = $pdo->query($checkquery4);
    while($row4 = $result4->fetch()) {
      $name_leaderborad[] =  $row4['name'];
      $totalpoints_leaderboard[] = $row4['total_points'] + $row4['bonus_points'];
      $fb_profile_pic_url[] = $row4['fb_profile_pic_url'];
    }
	
    	
}else{
		
    header('Location: http://clubcashkaro.com/');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Leaderboard</title>
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

<div class="container-section">
        <div class="bredcum">
            <div class="wrap">
                  <a href="#">Leaderboard</a>
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
			
			<div class="task-sec">
               
                                <div class="task-bar">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="">All Time Rankings</a></li>
                                </ul>
                                </div>

                                <div class="tab-content">
                                    <div id="overall" class="tab-pane fade in active">
                                        <div class="leaderboard">
                                        <table>
                                            <tr>
                                                <th>Rank</th><th>Name</th><th>Total Points</th>
                                            </tr>
                                            <?php
                                            if($user_rank <= 10)
                                            {
                                            for($i = 0; $i < sizeof($name_leaderborad); $i++)
                                            {
                                            if ($name_leaderborad[$i] == $row['name'])	
                                            {	
                                            ?>
                                            <tr class="user_leader_bg">
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $name_leaderborad[$i]; ?></td>
                                                <td><?php echo $totalpoints_leaderboard[$i]; ?></td>
                                            </tr>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>    
                                            <tr>
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $name_leaderborad[$i]; ?></td>
                                                <td><?php echo $totalpoints_leaderboard[$i]; ?></td>
                                            </tr>
                                            <?php
                                            }	
                                            }
                                            } 

                                            if($user_rank > 10)
                                            {
                                            for($i = 0; $i < sizeof($name_leaderborad); $i++)
                                            {
                                            if ($i == (sizeof($name_leaderborad)-1))	
                                            {	
                                            ?>
                                            <tr class="user_leader_bg">
                                                <td><?php echo $user_rank; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $total_points; ?></td>
                                            </tr> 
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                            <tr>
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $name_leaderborad[$i]; ?></td>
                                                <td><?php echo $totalpoints_leaderboard[$i]; ?></td>
                                            </tr>
                                            <?php
                                            }	
                                            }
                                            }						
                                            ?>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                        </div>		
		</div>	
            </div>
        </div>
	  
<?php include 'about_info_credentials.inc.php'; ?>    
       
</div>
<!--Container Close Here--> 

<?php include 'footer.inc.php'; ?>

<script>
 $(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
		 $(this).addClass("active");
    });
 });
</script>

</body>
</html>
