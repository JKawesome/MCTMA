<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DBi {
    public static $conn;
}
DBi::$conn = new mysqli("localhost", "root", "root", "marine");

require "classes/users.php";
require "classes/stats.php";
require "classes/tasks.php";
require "classes/categories.php";
?>