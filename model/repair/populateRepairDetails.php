<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');

	// Execute the script if the POST request is submitted
	if(isset($_POST['repairDetailsRepairID'])){
		
		$reapirID = htmlentities($_POST['repairDetailsRepairID']);
		
		$repairDetailsSql = 'SELECT * FROM repair WHERE repairID = :repairID';
		$repairDetailsStatement = $conn->prepare($repairDetailsSql);
		$repairDetailsStatement->execute(['repairID' => $repairID]);
		
		// If data is found for the given reapirID, return it as a json object
		if($repairDetailsStatement->rowCount() > 0) {
			$row = $repairDetailsStatement->fetch(PDO::FETCH_ASSOC);
			echo json_encode($row);
		}
		$repairDetailsStatement->closeCursor();
	}
?>