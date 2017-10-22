<?
class Categories {
	function getCategories() {
		$arr = array();
		$data = DBi::$conn->query("SELECT * FROM categories ORDER BY name");
		while($row = $data->fetch_assoc()) {
			array_push($arr, $row);
		}
		return $arr;
	}

	function getCategory($id) {
		$data = DBi::$conn->query("SELECT * FROM categories WHERE id=" . $id);
		while($row = $data->fetch_assoc()) {
			return $row;
		}
	}

	function getName($id) {
		return $this->getCategory($id)['name'];
	}
}
?>