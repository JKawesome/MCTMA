<?
require "../load.php";

$users = new Users;
$tasks = new Tasks;

$user = 1;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>MCTMA</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="../css/monthly.css">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
		<script src="https://use.fontawesome.com/2afc24b26d.js"></script>
		<script src="tinysort.min.js"></script>
	</head>
	<body>
		<div class="overlay" onclick="closemodal()"></div>
		<div class="modal"></div>
		<div id="sidebar" class="sidebar">
			<a href="javascript:void(0)" class="close" onclick="closesidebar()">&times;</a>
			<a href="#"><i class="fa fa-user"></i>Profile</a>
			<a href="#"><i class="fa fa-cog"></i>Settings</a>
			<a href="#"><i class="fa fa-sign-out"></i>Sign Out</a>
		</div>
		<div class="header">
			<div class="circularbuttonleft" onclick="opensidebar()">
				<i class="fa fa-user fa-lg"></i>
			</div>
			<div class="circularbuttonleft" onclick="gotopage('home')" id="back" style="opacity: 0;display: none;">
				<i class="fa fa-user fa-arrow-left"></i>
			</div>
			<h2>MCTMA</h2>
		</div>
		<div class="page active" id="home">
			<div class="boxes">
				<div class="box" onclick="gotopage('calendar')">
					<i class="fa fa-calendar fa-2x"></i>
					<div class="text">Calendar</div>
				</div>
				<div class="box" onclick="gotopage('tasks')">
					<i class="fa fa-list-ul fa-2x"></i>
					<div class="text">Tasks</div>
				</div>
				<div class="box" onclick="gotopage('statistics')">
					<i class="fa fa-bar-chart fa-2x"></i>
					<div class="text">Statistics</div>
				</div>
				<div class="box" onclick="gotopage('contact')">
					<i class="fa fa-paper-plane-o fa-2x"></i>
					<div class="text">Contact Supervisor</div>
				</div>
			</div>
		</div>
		<div class="page" id="calendar">
			<div class="left small">
				<div class="card">
					
				</div>
			</div>
			<div class="right big">
				<div class="card">
					<div class="monthly" id="mycalendar"></div>
				</div>
			</div>
		</div>
		<div class="page" id="tasks">
			<div class="task head"><span>Task Name</span><span>Date Due</span></div>
			<?
			$tasks = new Tasks;
			$arr = $tasks->getUserTasks($user);
			foreach($arr as $task) {
				echo '<div class="task" onclick="showtask(' . $task['id'] . ')"><span>' . $task['name'] . '</span><span>' . date("F d, Y", $task['due']) . '</span></div>';
			}
			?>
		</div>
		<div class="page" id="statistics">
			<div class="list">
				<?
				$arr = $users->getAllUsers();
				foreach($arr as $row) {
					echo '
				<div class="listitem" data-score="' . $users->calculateScore($row['id']) . '" data-rank="' . $row['rank'] . '" data-tasks="' . $users->getTotalTasks($row['id']) . '">
					<div class="img" style="background-image: url(\'../insignia/' . $users->displayRank($row['rank']) . '.png\');"></div>
					<div class="text">' . $users->displayFullName($row['id']) . '</div>
					<div class="label">' . $users->displayRank($row['rank']) . '</div>
					<div class="number">' . $users->calculateScore($row['id']) . '</div>
				</div>';
				}
				?>
			</div>
		</div>
		<div class="page" id="contact">
			<div class="form-group">
				<textarea required="required"></textarea>
				<label for="textarea" class="input-label">Textarea</label><i class="bar"></i>
			</div>
		</div>
		<script type="text/javascript" src="../js/monthly.js"></script>
		<script type="text/javascript">
		    $(window).load( function() {
		        $('#mycalendar').monthly({
		        	mode: 'event',
		        	dataType: 'json',
		        	jsonUrl: '../calendar.json'
		        });
		    });
		</script>
		<script>
			function opensidebar() {
				$("#sidebar").width(250);
			}

			function closesidebar() {
				$("#sidebar").width(0);
			}

			function gotopage(page) {
				if(page == "home") {
					$("#back").animate({opacity: 0}, 400);
					$(".page.active").animate({left: "100%"}, 400);
				} else {
					$("#back").show();
					$("#back").animate({opacity: 1}, 400);
					$(".page.active").animate({left: "-100%"}, 400);
				}
				$(".page#" + page).animate({left: "0"}, 400);
				$(".page").removeClass("active");
				$(".page#" + page).addClass("active");
				setTimeout(function() {
					if(page == "home") {
						$("#back").hide();
					}
				}, 400);
			}

			function openmodal(url) {
				$(".modal").load(url);
				$(".modal").show();
				$(".overlay").show();
			}

			function closemodal() {
				$(".modal").hide();
				$(".overlay").hide();
			}

		    function showprofile(id) {
		    	openmodal("profile.php?id=" + id);
		    }

		    function showtask(id) {
		    	openmodal("task.php?id=" + id);
		    }
		</script>
	</body>
</html>