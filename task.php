<?

require "load.php";

$users = new Users;
$tasks = new Tasks;
$categories = new Categories;
$id = $_GET['id'];

$task = $tasks->getTask($id);

?>
<div class="title">View Task</div>
<div class="profile">
	<h2><? echo $tasks->getName($id); ?></h2>
	<div class="label">
		Due <? echo date("F d, Y", $task['due']); ?>
	</div>
	<div class="left">
		<div class="sentence">
			<span><? echo $tasks->getName($id); ?></span> at <span><? echo $tasks->getLocation($id); ?></span> <br>on/by <span><? echo date("F d, Y", $tasks->getDue($id)); ?></span> <br>at <span><? echo date("H:i:m", $tasks->getDue($id)); ?></span>. <br>#<span><? echo $categories->getName($tasks->getCategory($id)); ?></span>
		</div><br>
		<div class="smallnumber">Status: <span><? echo $tasks->getStatus($id); ?></span></div>
		<? if($tasks->getReason() != "") { ?>
		<div class="smallnumber">Reason: <span><? echo $tasks->getReason($id); ?></span></div>
		<? } ?>
	</div>
	<div class="right">
		<div class="scorelabel">Due in</div>
		<div class="number">
			<? echo $tasks->getTimeUntilDue($id); ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
