<?php
	
	require_once '../Includes/DbOperations.php';
	$response = array();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
		if(isset($_POST['Start']) and isset($_POST['End']) and isset($_POST['IsOriginal']) and isset($_POST['Nurse']) and isset($_POST['Doctor']) and isset($_POST['Hospital'])){
			
			//operations
			
			$db = new DbOperations();
			$result = $db->createEntryForErCallSchedule(
			$_POST['Start'],
			$_POST['End'],
			$_POST['IsOriginal'],
			$_POST['Nurse'],
			$_POST['Doctor'],
			$_POST['Hospital']	
			);
			
			if($result == 1){
				$response['error'] = false;
				$response['message'] = "Completed";
			}elseif($result == 2){
				$response['error'] = true;
				$response['message'] = "Some Error Occured";
			}elseif($result == 0){
				$response['error'] = true;
				$response['message'] = "Duplicate start or end time";
			}
			
		}else{
			$response['error'] = true;
			$response['message'] = "Required Fields Missing";
		}

	}else{
		$response['error'] = true;
		$response['message'] = "Invalid Request";
	}

echo json_encode($response);

?>