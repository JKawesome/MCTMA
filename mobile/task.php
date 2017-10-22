<?

require "../load.php";

$users = new Users;
$tasks = new Tasks;
$categories = new Categories;
$id = $_GET['id'];

$task = $tasks->getTask($id);

?>
<div class="title">View Task<div class="fa fa-times fa-lg" onclick="closemodal()"></div></div>
<div class="profile">
	<h2><? echo $tasks->getName($id); ?></h2>
	<div class="sentence">
		<span><? echo $tasks->getName($id); ?></span> at <span><? echo $tasks->getLocation($id); ?></span> <br>on/by <span><? echo date("F d, Y", $tasks->getDue($id)); ?></span> <br>at <span><? echo date("H:i:m", $tasks->getDue($id)); ?></span>. <br>#<span><? echo $categories->getName($tasks->getCategory($id)); ?></span>
	</div><br>
	<div class="smallnumber">Status: <span><? echo $tasks->getStatus($id); ?></span></div>
	<div class="scorelabel">Due in</div>
	<div class="number">
		<? echo $tasks->getTimeUntilDue($id); ?>
	</div>
	<br>
	<? if($tasks->getStatus($id) == "Pending") { ?>
	<div class="button green" onclick="accept()">Accept</div>
	<div class="button" onclick="decline()">Decline</div>
	<? } else if($tasks->getStatus($id) == "In Progress") { ?>
	<div class="button green" style="width: 100px;" onclick="complete()">Complete</div>
	<? } ?>
</div>
<script>
function accept() {
	if(confirm("Are you sure you want to accept?")) {
		$.ajax({
			url: "../action.php?action=accept",
			data: "id=<? echo $id; ?>",
			type: "post",
			success: function() {
				$(".profile .button").remove();
				$(".profile").append('<div class="button green" style="width: 100px;" onclick="complete()">Complete</div>');
			}
		});
	}
}

function decline() {
	var reason = prompt("Please provide a reason for declining.");
	if(reason != "") {
		$.ajax({
			url: "../action.php?action=decline",
			data: "id=<? echo $id; ?>&reason=" + reason,
			type: "post",
			success: function() {
				closemodal();
			}
		});
	}
}

function complete() {
	$.ajax({
		url: "../action.php?action=complete",
		data: "id=<? echo $id; ?>",
		type: "post",
		success: function() {
			$(".profile .button").remove();
			$(".profile").append('<div class="button green">Complete</div>');
		}
	});
}
</script>