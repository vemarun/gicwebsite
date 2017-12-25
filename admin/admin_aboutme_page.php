<?php 
require 'steamauth/steamauth.php';
include_once '../db_connect.php';
extract($_POST);
$status="";
if(isset($_POST['sub'])){
	$query="update mods 
	set steamid='".$steamid."',position='".$position."'";
	
	if($result=mysqli_query($db,$query)){
		$status="<h4 style='background-color:cyan'>Data Successfully submitted</h4>";
	}
		else
		{
		$status="<h4 style='background-color:cyan'>Something went wrong. Contact support.</h4>";
		  die(mysqli_error($db));
		}

	
}
if(isset($_POST['del'])){
	$query="delete from mods where steamid=$steamid";
	if($result=mysqli_query($db,$query)){
		$status="<h4 style='background-color:cyan'>User Removed</h4>";
	}
		else
		{
		$status="<h4 style='background-color:cyan'>Something went wrong. Contact support.</h4>";
		  die(mysqli_error($db));
		}
}
if(isset($_POST['add'])){
	$query="insert into mods(steamid,position) values('$steamid','$position')";
	if($result=mysqli_query($db,$query)){
		$status="<h4 style='background-color:cyan'>User added</h4>";
	}
		else
		{
		$status="<h4 style='background-color:cyan'>Something went wrong. Contact support.</h4>";
		  die(mysqli_error($db));
		}
}
?>
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
        
        return "<a href=$profile_url target='_blank'><img src=$avatar>  $name";
    }
        ?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>GIC Administrative zone</title>



		<!-- BOOTSTRAP STYLES-->
		<link href="assets/css/bootstrap.css" rel="stylesheet" />
		<!-- FONTAWESOME STYLES-->
		<link href="assets/css/font-awesome.css" rel="stylesheet" />
		<!-- CUSTOM STYLES-->
		<link href="assets/css/custom.css" rel="stylesheet" />
		<!-- GOOGLE FONTS-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
		<link href="../css/pace.css" rel="stylesheet">
		<script src="../bootstrap/js/pace.min.js" type="text/javascript"></script>
		<!-- /. WRAPPER  -->
		<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->

		<!-- BOOTSTRAP SCRIPTS -->
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- CUSTOM SCRIPTS -->
		<script src="assets/js/custom.js"></script>
		<!-- JQUERY SCRIPTS -->
		<script src="assets/js/jquery-1.10.2.js"></script>
	</head>

	<body>



		<div id="wrapper">
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="adjust-nav">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
						<a class="navbar-brand" href="../index.php">
							<img src="assets/img/logo.png" id="logo" />

						</a>

					</div>


					<span class="logout-spn">
                 <?php
						$q="select group_id from users where steamid=$_SESSION[steamid]";
						$res=mysqli_query($db,$q);
						$row=mysqli_fetch_array($res);
if(!isset($_SESSION['steamid']) || $row[0]<>3) {

     header("location:../index.php");//login button

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array

    //Protected content
?>
                  <a href="#" style="color:#fff;"><?php logoutbutton(); ?></a>  

                </span>
					<?php }?>
				</div>
			</div>
			<!-- /. NAV TOP  -->
			<nav class="navbar-default navbar-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav" id="main-menu">



						<li>
							<a href="index.php"><i class="fa fa-desktop "></i>Dashboard <span class="badge">#</span></a>
						</li>


						<li>
							<a href="admin_match.php"><i class="fa fa-calendar "></i>Match Schedules  <span class="badge"></span></a>
						</li>
						<li>
							<a href="admin_users.php"><i class="fa fa-users "></i>See Users  <span class="badge"></span></a>
						</li>


						<li>
							<a href="admin_clans.php"><i class="fa fa-qrcode "></i>Clans</a>
						</li>
						<li>
							<a href="../member.php"><i class="fa fa-bar-chart-o"></i>Your profile</a>
						</li>

						<li>
							<a href="../rcon2.php"><i class="fa fa-laptop "></i>Server Control </a>
						</li>
						<li>
							<a href="admin_adduser.php"><i class="fa fa-table "></i>Register user Manually</a>
						</li>
						<li>
							<a href="settings.php"><i class="fa fa-gear "></i>Settings </a>
						</li>

					</ul>
				</div>

			</nav>
			<!-- /. NAV SIDE  -->
			<div id="page-wrapper">
				<div id="page-inner">
					<div id="status">
						<?php echo $status; ?>
					</div>

					<table class="table table responsive">
						<tr>
							<th>Profile</th>
							<th>Steam ID</th>
							<th>Position</th>

						</tr>

						<?php
		$q="select steamid,position from mods order by position";
		$r=mysqli_query($db,$q); 
		while($row=mysqli_fetch_array($r)){
			
			
			echo "<form method='post'><tr><td>";
			echo getdetail($row['steamid']);
			echo "</td>";
			echo "<td>";
			echo "<input type='text' name='steamid' value='".$row['steamid']."'></td>"; 
			echo "<td><input type='text' name='position' value='".$row['position']."'></td>"; 
			echo "<td><input type='submit' name='del' value='Delete'></td>"; 
			echo "<td><input type='submit' name='sub' value='save'></td></tr></form>"; } ?>


					</table>
					<h3>Add New</h3>
					<form method="post">
						<input type="text" name="steamid" placeholder="Steamid(64)">
						<input type="text" name="position" placeholder="Position">
						<input type="submit" name="add" value="Add">
					</form>
				</div>
			</div>
		</div>


	</body>

	</html>
