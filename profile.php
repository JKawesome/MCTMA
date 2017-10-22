<?

require "load.php";

$users = new Users;
$id = $_GET['id'];

$user = $users->getUser($id);

?>
<div class="title">User Profile</div>
<div class="profile">
	<h2><? echo $users->displayFullName($id); ?></h2>
	<div class="label">
		<? echo $users->displayRank($user['rank']); ?>
		<img src="insignia/<? echo $users->displayRank($user['rank']); ?>.png" />
	</div>
	<div class="phone">
		<? echo '('.substr($user['phone'], 0, 3).') '.substr($user['phone'], 3, 3).'-'.substr($user['phone'], 6); ?>
	</div>
	<div class="left">
		<div class="scorelabel">Score</div>
		<div class="number">
			<? echo $users->calculateScore($user['id']); ?>
		</div>
		<div class="smallnumber"><span><? echo $users->getMonthAchievedTasks($id); ?></span> tasks achieved this month</div>
		<div class="smallnumber"><span><? echo $users->getOverdueTasks($id); ?></span> overdue tasks</div>
		<div class="smallnumber"><span><? echo $users->getTotalTasks($id); ?></span> total tasks</div>
		<div class="smallnumber"><span><? echo $users->getCurrentTasks($id); ?></span> current tasks</div>
	</div>
	<div class="right">
		<canvas id="usertrend" width="400" height="200"></canvas>
	</div>
	<div class="clear"></div>
</div>
<script>
var ctx = document.getElementById("usertrend").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["May", "June", "July", "August", "September", "October"],
        datasets: [{
            label: 'Score',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: 'rgba(255,99,132,0.3)',
            borderColor: 'rgba(255,99,132,1)',
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
</script>