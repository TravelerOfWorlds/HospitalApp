<?php
	require_once '../Includes/DbOperations.php';
	$response = array();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['CurrentTime'])){
			
			
			
			
			$db = new DbOperations();
			$result = $db->getOnCallGroupDoctors($_POST['CurrentTime']);
			//$row = $result->fetch_array(MYSQLI_NUM);
			//$response['item'] = "s";
			$response['Doctors'] = [];
			$response['PhoneNumbers'] = [];
			$response['Group'] = [];
			$response['Images'] = [];
			foreach ($result as $item){
				array_push($response['Doctors'],array_values($item)[1]);
				array_push($response['PhoneNumbers'],array_values($item)[2]);
				$groupID = $db->getGroupName(array_values($item)[3]);
				array_push($response['Group'],$groupID);
				array_push($response['Images'],base64_encode(array_values($item)[5]));
				
			}
			
			
					

			//echo sizeof($row);
			
			
			
			
			   

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