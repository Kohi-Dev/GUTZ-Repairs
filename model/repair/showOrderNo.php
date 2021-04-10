<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	// Check if the POST request is received and if so, execute the script
	if(isset($_POST['textBoxValue'])){
		$output = '';
		$orderNoString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		
		// Construct the SQL query to get the OrderNo
		$sql = 'SELECT orderNo FROM repair  WHERE orderNo LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$orderNoString]);
		
		// If we receive any results from the above query, then display them in a list
		if($stmt->rowCount() > 0){
			
			// Given orderNo is available in DB. Hence create the dropdown list
			$output = '<ul class="list-unstyled suggestionsList" id="reapirDetailsOrderNoSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['orderNo'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>