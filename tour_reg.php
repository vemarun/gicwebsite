<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';

?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Custom script as written on bootstrap page -->
		<link href="css/style_tour.css" rel="stylesheet">
		<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/pace.css" rel="stylesheet">
		<script src="bootstrap/js/pace.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
		<title>Home - GIC </title>

		<script>
			$(document).ready(function() {

				var num = 80;
				$(window).scroll(function() {
					//if you hard code, then use console
					//.log to determine when you want the 
					//nav bar to stick.  
					console.log($(window).scrollTop())
					if ($(window).scrollTop() > num) {
						$('#header-inner').addClass('navbar-fixed');
					}
					if ($(window).scrollTop() < num) {
						$('#header-inner').removeClass('navbar-fixed');
					}
				});

			});

		</script>

	</head>

	<body>

		<header>
			<div id="header-inner">
				<a href="index.php" id="logo"></a>
				<nav>
					<a href="#" id="menu-icon"></a>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="servers.php">Servers</a></li>
						<li><a href="download.php">Downloads</a></li>
						<li><a href="bans/">Sourcebans</a></li>
						<li><a href="about.php">About us</a></li><br>




						<div id="login">
							<?php
if(!isset($_SESSION['steamid'])) {

     loginbutton('rectangle');//login button

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
    //Protected content
?>
								<div class="dropdown">
									<?php echo "<img src='".$_SESSION['steam_avatar']."'>"; ?>
									<div class="dropdown-content">
										<a href="profile.php" class="drop">Dashboard</a>
										<a href="clans.php" class="drop">Clans</a>
										<a href="#" class="drop">
											<?php logoutbutton(); } ?>
										</a>


									</div>
								</div>
						</div>

						<?php
    function getdetail($id){
        $url="http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=  &steamids=$id";
        $get_json=file_get_contents($url);
        $decode=json_decode($get_json,true);
            
        if(!empty($decode)){
            foreach($decode['response']['players'] as $detail ){
            
                $avatar=$detail['avatarmedium'];
                    $name=$detail['personaname'];
                $profile_url=$detail['profileurl'];
            }
        }
        
        echo "<a href=$profile_url target='_blank'><img src=$avatar>  $name";
    }
        ?>

					</ul>
				</nav>
			</div>
		</header>
		<div class="alert alert-info">
			Click On Team tag to fetch member list
		</div>
		<table class="table table-responsive">
			<tr>
				<th>Team Tag</th>
				<th>Team Name</th>
				<th>Team Captain</th>
				<th>Date Registered</th>
			</tr>
			<?php
			 if(isset($_GET["tour_name"]))
    {
        $data = $_GET["tour_name"];
		$q="select clanid, clan_name,clan_owner, time_created from $data";
		$r=mysqli_query($db,$q);
		while($row=mysqli_fetch_array($r)){
		echo "<tr><td><a href='get_members.php?clanid=$row[0]&tour_name=$data' id='get_members'>$row[0]</a></td>";
			echo "<td>$row[1]</td>";
			echo "<td>";
			echo getdetail($row[2]);
			echo "</td>";
			echo "<td>$row[3]</td></tr>";
			
		}
			 }
?>
		</table>



	</body>

	</html>
