<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$baseImageFolder = '../../data/repair_images/';
	$repairImageFolder = '';

	if(isset($_POST['repairDetailsItemNumber'])){
		
		$orderNo = htmlentities($_POST['repairDetailsOrderNo']);
		$repairItem = htmlentities($_POST['repairDetailsRepairItem']);
		$serialNo = htmlentities($_POST['repairDetailsSerialNo']);
		$customerID = htmlentities($_POST['repairDetailsCustomerID']);
		$customerName = htmlentities($_POST['repairDetailsCustomerName']);
		$quantity = htmlentities($_POST['repairDetailsQuantity']);
		$dateIssued = htmlentities($_POST['repairDetailsDateIssued']);
		$dateRepaired = htmlentities($_POST['repairDetailsDateRepaired']);
		$status = htmlentities($_POST['repairDetailsStatus']);
		$category = htmlentities($_POST['repairDetailsCategory']);
		$accessories = htmlentities($_POST['repairDetailsAccessories'])
		$problem = htmlentities($_POST['repairDetailsProblem']);
		

		// Check if mandatory fields are not empty
		if(!empty($orderNo) && !empty($serialNo) && isset($dateIssued) && isset($repairItem) && isset($quantity)){

			// Sanitize orderNo
			$orderNo = filter_var($orderNo, FILTER_SANITIZE_STRING);

			// Check if repairItem is empty or not
			if($repairItem == ''){
				// Repair Item is empty
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Repair Item.</div>';
				exit();
			}

			// Check if problem is empty or not
			if($problem == ''){
				// problem is empty
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please indicate the problem.</div>';
				exit();
			}

			// Validate customerID
			if(filter_var($customerID, FILTER_VALIDATE_INT) === 0 || filter_var($customerID, FILTER_VALIDATE_INT)){
				// Valid customerID
			} else {
				// customerID is not a valid number
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid Customer ID</div>';
				exit();
			}

			// Check if Accessories is empty or not
			if($accessories == ''){
				// problem is empty
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter the accessories that came with the repair Item.</div>';
				exit();
			}
			
			// Validate item quantity. It has to be a number
			if(filter_var($quantity, FILTER_VALIDATE_INT) === 1 || filter_var($quantity, FILTER_VALIDATE_INT)){
				// Valid quantity
			} else {
				// Quantity is not a valid number
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for quantity</div>';
				exit();
			}


			// Create image folder for uploading images
			$repairImageFolder = $baseImageFolder . $orderNo;
			if(is_dir($repairImageFolder)){
				// Folder already exist. Hence, do nothing
			} else {
				// Folder does not exist, Hence, create it
				mkdir($repairImageFolder);
			}


			// Check if the customer is in DB
			$customerSql = 'SELECT * FROM customer WHERE customerID = :customerID';
			$customerStatement = $conn->prepare($customerSql);
			$customerStatement->execute(['customerID' => $customerID]);
					
			if($customerStatement->rowCount() > 0){
				// Customer exits. That means customer is available. Hence start INSERT and UPDATE
				$customerRow = $customerStatement->fetch(PDO::FETCH_ASSOC);
				$customerName = $customerRow['fullName'];
				
				// INSERT data to sale table
				$insertReapirSql = 'INSERT INTO `repair` (`repairID`, `orderNo`, `customerID`, `customerName`, `repairItem`, `serialNo`, `quantity` ,`dateIssued`, `dateRepaired`, `status`, `accessories`, `problem`, `category`) VALUES
					(:repairID, :orderNo, :customerID, :customerName, :repairItem, :serialNo`, :quantity ,:dateIssued, :dateRepaired, :status, :accessories, :problem, :category)';
				$insertRepairStatement = $conn->prepare($insertRepairSql);
				$insertRepairStatement->execute(['repairID' => $repairID, 'orderNo' => $orderNo, 'customerID' => $customerID, 'customerName' => $customerName, 'repairItem' => $repairItem, 'serialNo' => $serialNo, 'quantity' => $quantity, 'dateIssued' => $dateIssued, 'dateRepaired' => $dateRepaired, 'status' => $status, 'accessories' => $accessories, 'problem' => $problem, 'category' => $category]);
						
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Repair details added to the Database.</div>';
				exit();

			} else {
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Customer does not exist.</div>';
				exit();
			}

		} else {
			// One or more mandatory fields are empty. Therefore, display a the error message
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter all fields marked with a (*)</div>';
			exit();
		}


	}
?>