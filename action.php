<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "load.php";
/*
ID = SK310ef6ae611348af11c1245d909b9545
SECRET = yvCU0FGeXc1uxl1Wm9kGEjYJRnK23ibX

curl  -X POST \
--data-urlencode 'To=+17143308860' \
--data-urlencode 'From=+16193042085' \
--data-urlencode 'Body=Hello' \
-u AC0119e05542141f4a339a24b59a0b7306:42e3e0f8b50f6cb57367b769b560015f*/

// Get the PHP helper library from twilio.com/docs/php/install
require 'twilio-php-master/Twilio/autoload.php';
use Twilio\Rest\Client;

// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "AC0119e05542141f4a339a24b59a0b7306";
$token = "42e3e0f8b50f6cb57367b769b560015f";
$client = new Client($sid, $token);


if($_GET['action'] == "newtask") {
	$user = $_POST['user'];
	$name = $_POST['name'];
	$location = $_POST['location'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$users = new Users;
	$category = $_POST['category'];
	DBi::$conn->query("INSERT INTO tasks (date, name, location, user, category, due) VALUES (" . time() . ", '" . $name . "', '" . $location . "', " . $user . ", " . $category . ", " . strtotime($date . " " . $time) . ")");
	echo "INSERT INTO tasks (date, name, location, user, category, due) VALUES (" . time() . ", '" . $name . "', '" . $location . "', " . $user . ", " . $category . ", " . strtotime($date . " " . $time) . ")";
	$client->messages
	    ->create(
	        "+1" . $users->getPhone($user),
	        array(
	            "from" => "+16193042085",
	            "body" => "You have received a new task due on " . $date . " at " . $time . ": " . $name
	        )
	    );
}
if($_GET['action'] == "accept") {
	DBi::$conn->query("UPDATE tasks SET accepted = " . time() . " WHERE id = " . $_POST['id']);
}
if($_GET['action'] == "decline") {
	DBi::$conn->query("UPDATE tasks SET reason = " . $_POST['reason'] . " WHERE id = " . $_POST['id']);
}
if($_GET['action'] == "complete") {
	DBi::$conn->query("UPDATE tasks SET completed = " . time() . " WHERE id = " . $_POST['id']);
}