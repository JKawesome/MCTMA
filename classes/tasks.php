<?
class Tasks {
	function getTask($id) {
		$data = DBi::$conn->query("SELECT * FROM tasks WHERE id = " . $id);
		while($row = $data->fetch_assoc()) {
			return $row;
		}
	}

	function getName($id) {
		return $this->getTask($id)['name'];
	}

	function getDate($id) {
		return $this->getTask($id)['date'];
	}

	function getLocation($id) {
		return $this->getTask($id)['location'];
	}

	function getUser($id) {
		return $this->getTask($id)['user'];
	}

	function getCategory($id) {
		return $this->getTask($id)['category'];
	}

	function getCompleted($id) {
		return $this->getTask($id)['completed'];
	}

	function getDue($id) {
		return $this->getTask($id)['due'];
	}

	function getAccepted($id) {
		return $this->getTask($id)['accepted'];
	}

	function getReason($id) {
		return $this->getTask($id)['reason'];
	}

	function getStatus($id) {
		$row = $this->getTask($id);
		if($row['accepted'] == 0 && $row['due'] > time()) {
			return "Pending";
		} else if($row['accepted'] > 0 && $row['due'] > time() && $row['completed'] == 0) {
			return "In Progress";
		} else if($row['completed'] > 0) {
			return "Completed";
		} else if($row['completed'] == 0 && $row['due'] <= time()) {
			return "Overdue";
		} else if($row['accepted'] == 0 && $row['due'] <= time()) {
			return "Expired";
		}
	}

	function getPendingTasks() {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM tasks");
		while($row = $data->fetch_assoc()) {
			if($row['accepted'] == 0 && $row['due'] > time()) {
				array_push($arr, $row);
			}
		}
		return $arr;
	}

	function getInProgressTasks() {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM tasks");
		while($row = $data->fetch_assoc()) {
			if($row['accepted'] > 0 && $row['due'] > time() && $row['completed'] == 0) {
				array_push($arr, $row);
			}
		}
		return $arr;
	}

	function getCompletedTasks() {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM tasks");
		while($row = $data->fetch_assoc()) {
			if($row['completed'] > 0) {
				array_push($arr, $row);
			}
		}
		return $arr;
	}

	function getOverdueTasks() {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM tasks");
		while($row = $data->fetch_assoc()) {
			if($row['completed'] == 0 && $row['due'] <= time()) {
				array_push($arr, $row);
			}
		}
		return $arr;
	}

	function getExpiredTasks() {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM tasks");
		while($row = $data->fetch_assoc()) {
			if(($row['accepted'] == 0 && $row['due'] <= time()) || $row['reason'] != "") {
				array_push($arr, $row);
			}
		}
		return $arr;
	}

	function getTimeUntilDue($id) {
		$due = $this->getDue($id);
		$diff = $due - time();
		if($diff < 60) {
			$s = "s";
			if($diff == 1) {
				$s = "";
			}
			return "" . $diff . " second" . $s;
		} else if($diff < 3600) {
			$s = "s";
			if(floor($diff / 60) == 1) {
				$s = "";
			}
			return "" . floor($diff / 60) . " minute" . $s;
		} else if($diff < 86400) {
			$s = "s";
			if(floor($diff / 3600) == 1) {
				$s = "";
			}
			return "" . floor($diff / 3600) . " hour" . $s;
		} else {
			$s = "s";
			if(floor($diff / 86400) == 1) {
				$s = "";
			}
			return "" . floor($diff / 86400) . " day" . $s;
		}
	}

	function getUserTasks($user) {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM tasks WHERE user = " . $user);
		while($row = $data->fetch_assoc()) {
			array_push($arr, $row);
		}
		return $arr;
	}
}
?>