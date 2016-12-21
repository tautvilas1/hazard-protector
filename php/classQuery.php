<?php
class query {

	public static function insert($conn,$table,$columns,$values) {
		for($i = 0;$i < count($columns);$i++) {
			if($i == count($columns)-1) {
				$strColumn = $strColumn . $columns[$i];
				$strValue = $strValue .'?';
			}
			else {
			$strColumn = $strColumn . $columns[$i] . ', ';
			$strValue = $strValue . '?, ';
			}
		}

		
		$sql = 'INSERT INTO '.$table.' ('.$strColumn.') VALUES ('.$strValue.')';
		$q = $conn->prepare($sql);
		for($b = 1; $b < count($values)+1;$b++) {
			$q->bindParam($b,$values[$b-1]);
		}
		return $q->execute();
		
	}

	public static function delete($conn,$table,$where,$values) {
		for($z = 0;$z < count($where);$z++) {
			if($z == count($where)-1) {
				$strWhere = $strWhere . $where[$z]." = ?";
			}
			else {
				$strWhere = $strWhere . $where[$z]." = ? AND ";
			}
		}
		$sql = "DELETE FROM ".$table." WHERE ".$strWhere." ";
		$q = $conn->prepare($sql);
		for($b = 1; $b < count($values)+1;$b++) {
			$q->bindParam($b,$values[$b-1]);
		}
		return $q->execute();
		
	}

	public static function select($conn,$table,$where,$values) {
		if(empty($where)) {
			$strWhere = "";
		}
		else {
		$strWhere = " WHERE ";
		for($w = 0;$w < count($where);$w++) {
			if($w == count($where)-1) {
				$strWhere = $strWhere . $where[$w]." = ?";
			}
			else {
				$strWhere = $strWhere . $where[$w]." = ? AND ";
			}
		}
		}
		$sql = "SELECT * FROM ".$table."".$strWhere." ";
		$q = $conn->prepare($sql);
		for($t = 1; $t < count($values)+1;$t++) {
			$q->bindParam($t,$values[$t-1]);
		}
		$q->execute();
		$q->setFetchMode(PDO::FETCH_ASSOC);
		return $q;
	}

	public static function update($conn,$table,$columns,$where,$columnValues,$whereValues) {

		if(empty($where)) {
			$strWhere = "";
		}

		else {

		//Get the WHERE section
		$strWhere = " WHERE ";
		for($w = 0;$w < count($where);$w++) {
			if($w == count($where)-1) {
				$strWhere = $strWhere . $where[$w]." = ?";
			}
			else {
				$strWhere = $strWhere . $where[$w]." = ? AND ";
			}
		}

		}

		//Get the COLUMNS section
		for($o = 0;$o < count($columns);$o++) {
			//If last
			if($o == count($columns)-1) {
				$strCol = $strCol . $columns[$o] . ' = ?';
			}
			//If not last
			else {
				$strCol = $strCol . $columns[$o] . ' = ? , ';
			}
		}

		$sql = "UPDATE ".$table." SET ".$strCol." ".$strWhere." ";
		$q = $conn->prepare($sql);
		//First bind the column values
		for($m = 0;$m < count($columns)+1;$m++) {
			$q->bindParam($m,$columnValues[$m-1]);
		}

		//Get the next number for binding
		$nextBind = count($columns)+1;
		$c = 0;
		//Next bind the where values
		if(!empty($where)) {
		for($y = $nextBind;$y < count($where)+$nextBind;$y++) {
			$q->bindParam($y,$whereValues[$c]);
			$c++;
		}
		}
		return $q->execute();
	}

}

?>