<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	// Check if the POST request is received and if so, execute the script
	if(isset($_POST['textBoxValue'])){
		$output = '';
		$repairItemString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		
		// Construct the SQL query to get the item name
		$sql = 'SELECT repairItem FROM repair WHERE repairItem LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$repairItemString]);
		
		// If we receive any results from the above query, then display them in a list
		if($stmt->rowCount() > 0){
			$output = '<ul class="list-unstyled suggestionsList" id="repairDetailsRepairItemSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['repairItem'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>