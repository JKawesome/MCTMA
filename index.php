<? 
require "load.php";
$users = new Users;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Marine Corps Task Management App</title>
		<link href="style.css" rel="stylesheet" />
		<link href="default.css" rel="stylesheet" />
		<link href="default.date.css" rel="stylesheet" />
		<link href="default.time.css" rel="stylesheet" />
		<link rel="stylesheet" href="css/monthly.css">
		<script type="text/javascript" src="js/jquery.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
		<script src="https://use.fontawesome.com/2afc24b26d.js"></script>
		<script src="tinysort.min.js"></script>
		<script src="picker.js"></script>
		<script src="picker.date.js"></script>
		<script src="picker.time.js"></script>
	</head>
	<body>
		<div class="overlay" onclick="closemodal()"></div>
		<div class="modal"></div>
		<div class="header">
			<div class="circularbuttonleft" onclick="gotopage('home')">
				<i class="fa fa-arrow-left fa-lg"></i>
			</div>
			<h2>MCTMA</h2>
			<div class="circularbuttonright">
				<i class="fa fa-ellipsis-v fa-lg"></i>
			</div>
		</div>
		<div class="page active" id="home">
			<div class="left big">
				<div class="buttons">
					<div class="button click" onclick="gotopage('newtask')">Assign New Task</div>
					<div class="button click" onclick="gotopage('viewtasks')">View Existing Tasks</div>
					<div class="button click" onclick="gotopage('calendar')">Calendar</div>
				</div>
				<div class="clear"></div>
				<div class="split">
					<div class="left">
						<div class="card">
							<div class="title">Recently Completed</div>
							<div class="list">
								<?
								$data = DBi::$conn->query("SELECT * FROM tasks WHERE completed > 0 ORDER BY completed DESC LIMIT 10");
								while($row = $data->fetch_assoc()) {
									echo '
								<div class="listitem click" onclick="showtask(' . $row['id'] . ')">
									<div class="img" style="background-image: url(\'insignia/' . $users->displayRank($users->getRank($row['user'])) . '.png\');"></div>
									<div class="text">' . $row['name'] . '</div>
									<div class="label">' . $users->displayFullName($row['user']) . '</div>
									<div class="number">' . date("F d, Y", $row['completed']) . '</div>
								</div>';
								}
								?>
							</div>
						</div>
					</div>
					<div class="right">
						<div class="card">
							<div class="title">Trends</div>
							<canvas id="myChart" width="400" height="260"></canvas>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="right small">
				<div class="card">
					<div class="title">
						Statistics
						<div class="circularbuttonright drop">
							<i class="fa fa-sort-amount-asc fa-lg"></i>
							<ul>
								<li><a href="#" onclick="tinysort('#statistics>.listitem')">Alphabetical</a></li>
								<li><a href="#" onclick="tinysort('#statistics>.listitem',{attr:'data-rank', order:'desc'})">Rank</a></li>
								<li><a href="#" onclick="tinysort('#statistics>.listitem',{attr:'data-score', order:'desc'})">Score</a></li>
								<li><a href="#" onclick="tinysort('#statistics>.listitem',{attr:'data-tasks', order:'desc'})">Tasks</a></li>
						    </ul>
						</div>
					</div>
					<div class="list" id="statistics">
						<?
						$arr = $users->getAllUsers();
						foreach($arr as $row) {
							echo '
						<div class="listitem click" onclick="showprofile(' . $row['id'] . ')" data-score="' . $users->calculateScore($row['id']) . '" data-rank="' . $row['rank'] . '" data-tasks="' . $users->getTotalTasks($row['id']) . '">
							<div class="img" style="background-image: url(\'insignia/' . $users->displayRank($row['rank']) . '.png\');"></div>
							<div class="text">' . $users->displayFullName($row['id']) . '</div>
							<div class="label">' . $users->displayRank($row['rank']) . '</div>
							<div class="number">' . $users->calculateScore($row['id']) . '</div>
						</div>';
						}
						?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="page" id="newtask">
			<div class="left small">
				<div class="card">
					<div class="title">
						Categories
						<div class="circularbuttonright"><i class="fa fa-plus"></i></div>
					</div>
					<div class="list">
						<?
						$categories = new Categories;
						$arr = $categories->getCategories();

						foreach($arr as $cat) {
							echo '
						<div class="listitem">
							<div class="img"><i class="fa fa-' . $cat['icon'] . '"></i></div>
							<div class="text">' . $cat['name'] . '</div>
							<div class="label">' . $cat['description'] . '</div>
							<div class="number">' . $cat['weight'] . '</div>
						</div>';
						}
						?>
					</div>
				</div>
			</div>
			<div class="right big">
				<div class="card">
					<div class="title">Assign New Task</div>
					<div class="fields">
						<div class="form-group">
							<select name="user" required>
								<option value="" disabled>Select</option>
								<?
								$data = DBi::$conn->query("SELECT * FROM users ORDER BY fname");
								while($row = $data->fetch_assoc()) {
									echo '<option value="' . $row['id'] . '">' . $users->displayFullName($row['id']) . '</option>';		
								}
								?>
							</select>
							<label for="select" class="input-label">Name</label><i class="bar"></i>
						</div>, <br>
						<div class="form-group">
							<input type="text" name="name" required>
							<label for="input" class="input-label">Task</label><i class="bar"></i>
						</div> at 
						<div class="form-group">
							<input type="text" name="location" required>
							<label for="input" class="input-label">Location</label><i class="bar"></i>
						</div><br> on/by 
						<div class="form-group">
							<input type="text" name="date" class="has-value" required>
							<label for="input" class="input-label active-label">Date</label><i class="bar"></i>
						</div> at 
						<div class="form-group">
							<input type="text" name="time" class="has-value" required>
							<label for="input" class="input-label active-label">Time</label><i class="bar"></i>
						</div>.<br> #
						<div class="form-group">
							<select name="category" required>
								<option value="" disabled>Select</option>
								<?
								$categories = new Categories;
								$arr = $categories->getCategories();
								foreach($arr as $cat) {
									echo '<option value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
								}
								?>
							</select>
							<label for="select" class="input-label">Category</label><i class="bar"></i>
						</div>
						<div class="button click" onclick="submitnewtask()">Submit</div>
					</div>
				</div>
			</div>
		</div>
		<div class="page" id="viewtasks">
			<div class="right full">
				<div class="card">
					<div class="title">View Existing Tasks</div>
					<div class="tab-wrap">
  
					    <input type="radio" name="tabs" id="tab1" checked>
					    <div class="tab-label-content" id="tab1-content">
							<label for="tab1">Pending</label>
							<div class="tab-content">
								<div class="task"><span>User</span><span>Task Name</span><span>Date Assigned</span><span>Date Due</span></div>
								<?
								$tasks = new Tasks;
								$arr = $tasks->getPendingTasks();
								foreach($arr as $task) {
									echo '<div class="task" onclick="showtask(' . $task['id'] . ')"><span>' . $users->displayFullName($task['user']) . '</span><span>' . $task['name'] . '</span><span>' . date("F d, Y", $task['date']) . '</span><span>' . date("F d, Y", $task['due']) . '</span></div>';
								}
								?>
							</div>
					    </div>
					     
					    <input type="radio" name="tabs" id="tab2">
					    <div class="tab-label-content" id="tab2-content">
							<label for="tab2">In Progress</label>
							<div class="tab-content">
								<div class="task"><span>User</span><span>Task Name</span><span>Date Assigned</span><span>Date Due</span></div>
								<?
								$tasks = new Tasks;
								$arr = $tasks->getInProgressTasks();
								foreach($arr as $task) {
									echo '<div class="task" onclick="showtask(' . $task['id'] . ')"><span>' . $users->displayFullName($task['user']) . '</span><span>' . $task['name'] . '</span><span>' . date("F d, Y", $task['date']) . '</span><span>' . date("F d, Y", $task['due']) . '</span></div>';
								}
								?>
							</div>
					    </div>
					    
					    <input type="radio" name="tabs" id="tab3">
					    <div class="tab-label-content" id="tab3-content">
							<label for="tab3">Completed</label>
							<div class="tab-content">
								<div class="task"><span>User</span><span>Task Name</span><span>Date Assigned</span><span>Date Due</span></div>
								<?
								$tasks = new Tasks;
								$arr = $tasks->getCompletedTasks();
								foreach($arr as $task) {
									echo '<div class="task" onclick="showtask(' . $task['id'] . ')"><span>' . $users->displayFullName($task['user']) . '</span><span>' . $task['name'] . '</span><span>' . date("F d, Y", $task['date']) . '</span><span>' . date("F d, Y", $task['due']) . '</span></div>';
								}
								?>
							</div>
					    </div>
					  
					     <input type="radio" name="tabs" id="tab4">
					     <div class="tab-label-content" id="tab4-content">
							<label for="tab4">Overdue</label>
							<div class="tab-content">
								<div class="task"><span>User</span><span>Task Name</span><span>Date Assigned</span><span>Date Due</span></div>
								<?
								$tasks = new Tasks;
								$arr = $tasks->getOverdueTasks();
								foreach($arr as $task) {
									echo '<div class="task" onclick="showtask(' . $task['id'] . ')"><span>' . $users->displayFullName($task['user']) . '</span><span>' . $task['name'] . '</span><span>' . date("F d, Y", $task['date']) . '</span><span>' . date("F d, Y", $task['due']) . '</span></div>';
								}
								?>
							</div>
					    </div>
					  
					     <input type="radio" name="tabs" id="tab5">
					     <div class="tab-label-content" id="tab5-content">
							<label for="tab5">Expired</label>
							<div class="tab-content">
								<div class="task"><span>User</span><span>Task Name</span><span>Date</span></div>
								<?
								$tasks = new Tasks;
								$arr = $tasks->getExpiredTasks();
								foreach($arr as $task) {
									echo '<div class="task" onclick="showtask(' . $task['id'] . ')"><span>' . $users->displayFullName($task['user']) . '</span><span>' . $task['name'] . '</span><span>' . date("F d, Y", $task['date']) . '</span><span>' . date("F d, Y", $task['due']) . '</span></div>';
								}
								?>
							</div>
					    </div>
					    
					    <div class="slide"></div>
					  
					</div>
					<div class="toolbar">
						<div class="tab active">Pending</div>
						<div class="tab">In Progress</div>
						<div class="tab">Completed</div>
						<div class="tab">Overdue</div>
						<div class="tab">Expired</div>
					</div>
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
					<div class="title">Calendar</div>
					<div class="monthly" id="mycalendar"></div>
				</div>
			</div>
		</div>
		<div class="footer"></div>
		<script type="text/javascript" src="js/monthly.js"></script>
		<script type="text/javascript">
		    $(window).load( function() {
		        $('#mycalendar').monthly({
		        	mode: 'event',
		        	dataType: 'json',
		        	jsonUrl: 'calendar.json'
		        });
		    });
		</script>
		<script>
			$(".fields [name='date']").pickadate({
				format: 'yyyy/mm/dd'
			});
			$(".fields [name='time']").pickatime({
				format: 'HH:i'
			});

			function openmodal(url) {
				$(".modal").load(url);
				$(".modal").show();
				$(".overlay").show();
			}

			function closemodal() {
				$(".modal").hide();
				$(".overlay").hide();
			}

			function gotopage(page) {
				if(page == "home") {
					$(".circularbuttonleft").animate({opacity: 0}, 400);
					$(".page.active").animate({left: "100%"}, 400);
				} else {
					$(".circularbuttonleft").show();
					$(".circularbuttonleft").animate({opacity: 1}, 400);
					$(".page.active").animate({left: "-100%"}, 400);
				}
				$(".page#" + page).animate({left: "0"}, 400);
				$(".page").removeClass("active");
				$(".page#" + page).addClass("active");
				setTimeout(function() {
					if(page == "home") {
						$(".circularbuttonleft").hide();
					}
				}, 400);
			}

			$(".click").on("click", function(e) {
			    var x = e.pageX;
			    var y = e.pageY;
			    var clickY = y - $(this).offset().top;
			    var clickX = x - $(this).offset().left;
				var box = this;

				var setX = parseInt(clickX);
				var setY = parseInt(clickY);
				$(this).find("svg").remove();
				$(this).append('<svg><circle cx="'+setX+'" cy="'+setY+'" r="'+0+'"></circle></svg>');

				var c = $(box).find("circle");
				c.animate({"r" : $(box).outerWidth()}, {
					easing: "easeOutQuad",
					duration: 400,
					step : function(val) {
						c.attr("r", val);
					}
				});

				setTimeout(function() {
					$(c).parent().animate({
						opacity: 0
					});
				}, 400);

				setTimeout(function() {
					$(c).parent().remove();
				}, 800);
			});

			var ctx = document.getElementById("myChart").getContext('2d');
			var myChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
			        datasets: [{
			            label: '# of Votes',
			            data: [12, 19, 3, 5, 2, 3],
			            backgroundColor: [
			                'rgba(255, 99, 132, 0.2)',
			                'rgba(54, 162, 235, 0.2)',
			                'rgba(255, 206, 86, 0.2)',
			                'rgba(75, 192, 192, 0.2)',
			                'rgba(153, 102, 255, 0.2)',
			                'rgba(255, 159, 64, 0.2)'
			            ],
			            borderColor: [
			                'rgba(255,99,132,1)',
			                'rgba(54, 162, 235, 1)',
			                'rgba(255, 206, 86, 1)',
			                'rgba(75, 192, 192, 1)',
			                'rgba(153, 102, 255, 1)',
			                'rgba(255, 159, 64, 1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    options: {
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});

		    function showprofile(id) {
		    	openmodal("profile.php?id=" + id);
		    }

		    function showtask(id) {
		    	openmodal("task.php?id=" + id);
		    }

		    function submitnewtask() {
		    	var user = $(".fields [name='user']").val();
		    	var name = $(".fields [name='name']").val();
		    	var location = $(".fields [name='location']").val();
		    	var date = $(".fields [name='date']").val();
		    	var time = $(".fields [name='time']").val();
		    	var category = $(".fields [name='category']").val();
		    	$.ajax({
		    		url: "action.php?action=newtask",
		    		data: "user=" + user + "&name=" + name + "&location=" + location + "&date=" + date + "&time=" + time + "&category=" + category,
		    		type: "post",
		    		success: function(data) {
		    			gotopage("home");
		    			gotopage("viewtasks");
		    		}
		    	});
		    }
		</script>
	</body>
</html>