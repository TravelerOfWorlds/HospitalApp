<?php
	require_once '../Includes/DbOperations.php';
	$response = array();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['CurrentTime'])){
			$db = new DbOperations();
			$result = $db->getOnCallDoctor($_POST['CurrentTime']);
			
			$response['Name'] = array_values($result)[1];
			$response['Phone'] = array_values($result)[2];
			$response['Group'] = array_values($result)[3];
			$response['Affiliation'] = array_values($result)[4];
			$response['Doctor_Image'] = base64_encode(array_values($result)[5]);    

		}else{
			$response['error'] = true;
			$response['DoctorName'] == "Error1";
			$response['message'] = "Required Fields Missing";
		}
		
	}else{
		$response['error'] = true;
		$response['DoctorName'] == "Error2";
		$response['message'] = "Invalid Request";
	}
	
	echo json_encode($response);
	
?>