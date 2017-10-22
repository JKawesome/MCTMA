<?
class Users {
	function getUser($id) {
		$data = DBi::$conn->query("SELECT * FROM users WHERE id = " . $id);
		while($row = $data->fetch_assoc()) {
			return $row;
		}
	}

	function getAllUsers() {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM users ORDER BY id");
		while($row = $data->fetch_assoc()) {
			array_push($arr, $row);
		}
		return $arr;
	}

	function getFname($id) {
		return $this->getUser($id)['fname'];
	}

	function getMname($id) {
		return $this->getUser($id)['mname'];
	}

	function getLname($id) {
		return $this->getUser($id)['lname'];
	}

	function getPhone($id) {
		return $this->getUser($id)['phone'];
	}

	function getRank($id) {
		return $this->getUser($id)['rank'];
	}

	function addUser($fname, $mname, $lname, $phone, $rank) {
		DBi::$conn->query("INSERT INTO users (date, fname, mname, lname, phone, rank) VALUES (" . time() . ", '" . $fname . "', '" . $mname . "', '" . $lname . "', '" . $phone . "', " . $rank . ")");
	}

	function displayRank($rank) {
		switch($rank) {
			case 1:
				return "Private";
			case 2:
				return "Private First Class";
			case 3:
				return "Lance Corporal";
			case 4:
				return "Corporal";
			case 5:
				return "Sergeant";
			case 6:
				return "Staff Sergeant";
			case 7:
				return "Gunnery Sergeant";
		}
	}

	function calculateScore($id) {
		if($this->getTotalTasks($id) == 0) {
			return 0;
		}
		return 10 * $this->getTotalAchievedTasks($id) / $this->getTotalTasks($id);
	}

	function displayFullName($id) {
		$name = $this->getUser($id)['fname'];
		if($this->getUser($id)['mname'] != "") {
			$name .= " " . $this->getUser($id)['mname'];
		}
		$name .= " " . $this->getUser($id)['lname'];
		return $name;
	}

	function getMonthAchievedTasks($id) {
		$data = DBi::$conn->query("SELECT * FROM tasks WHERE user = " . $id . " AND completed > " . strtotime("-1 month"));
		return $data->num_rows;
	}

	function getTotalAchievedTasks($id) {
		$data = DBi::$conn->query("SELECT * FROM tasks WHERE user = " . $id . " AND completed <= due");
		return $data->num_rows;
	}

	function getOverdueTasks($id) {
		$data = DBi::$conn->query("SELECT * FROM tasks WHERE user = " . $id . " AND completed = 0 AND due <= " . time());
		return $data->num_rows;
	}

	function getTotalTasks($id) {
		$data = DBi::$conn->query("SELECT * FROM tasks WHERE user = " . $id);
		return $data->num_rows;
	}

	function getCurrentTasks($id) {
		$data = DBi::$conn->query("SELECT * FROM tasks WHERE user = " . $id . " AND completed = 0 AND due > " . time());
		return $data->num_rows;
	}
}
?>