<?php
require_once '../Includes/DbOperations.php';
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$db = new DbOperations();
		$result = $db->getDoctors();
		$response = $result;
		
		
		

	}else{
		$response['error'] = true;
		$response['DoctorName'] == "Error2";
		$response['message'] = "Invalid Request";
	}
	
	echo json_encode($response);
	
?>