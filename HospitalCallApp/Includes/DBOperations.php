<?php

	class DbOperations{
		private $con;
		
		function __construct(){
			require_once dirname(__FILE__).'/DbConnect.php';
			$db = new DbConnects();
			$this->con = $db->connect();
			
		}
		
		public function getOnCallDoctor($current){
			$Doctor = $this->searchErCallSchedule($current);
			$DoctorInfo = $this->getDoctorFromID($Doctor);
			return $DoctorInfo;
		}
		
		
		private function getDoctorFromID($ID){
			$stmt = $this->con->prepare("SELECT * FROM `individual` WHERE `Doctor_ID` = ?");
			$stmt->bind_param("i",$ID);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			return $row;
		}
		
		
		private function searchErCallSchedule($CurrentTime){
			$stmt2 = "SELECT `Individual_Doctor_ID` FROM `er call schedule` WHERE `Day/Time Start` <= ? AND `DAY/TIME End` >= ?";
			$stmt = $this->con->prepare($stmt2);
			$stmt->bind_param("ss",$CurrentTime,$CurrentTime);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			$DocID = array_values($row)[0];
			return $DocID;
		}
		
		
		public function createEntryForErCallSchedule($Start, $End, $IsOriginal, $Nurse, $Doctor, $Hospital){
			
			if($this->isStartOrEndUnique($Start,$End)){
				return 0;
			}else{
				$stmt = $this->con->prepare("INSERT INTO `er call schedule` (`Schedule_ID`, `Day/Time Start`, `DAY/TIME End`, `Is_Original_Doctor`, `Nurses_Nurse_ID`, `Individual_Doctor_ID`, `Hospital_Hospital_ID`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
				$stmt->bind_param("ssssss",$Start,$End,$IsOriginal,$Nurse,$Doctor,$Hospital);
				if($stmt->execute()){
					return 1;
				}else{
					return 2;
				}
			}
		}
		
		
		//ensures that theres no duplicate er start end times
		private function isStartOrEndUnique($Start, $End){
			$stmt = $this->con->prepare("SELECT `Schedule_ID` FROM `er call schedule` WHERE `Day/Time Start` = ? OR `DAY/TIME End` = ?");
			$stmt->bind_param("ss",$Start,$End);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}
		
		
		
		
		//gets number of groups from Database
		public function getNumGroups(){
			$stmt = $this->con->prepare("SELECT COUNT(*) FROM `group`");
			$stmt->execute();
			$number = $stmt->get_result();
			$val = $number->fetch_array(MYSQLI_NUM);
			$value = array_values($val)[0];
			return $value;
		}
		
		
		
		
		
		public function getOnCallGroupDoctors($currentTime){
			$doctorsArray = $this->searchGroupCallSchedule($currentTime);
			$result = $this->getAllDoctorsFromArray($doctorsArray);
			return $result;
			
			
		}
		
		
		
		private function getAllDoctorsFromArray($docarray){
			$doctors = array();
			foreach ($docarray as $item){
				
				$value = $this->getDoctorFromID($item);
				
				array_push($doctors, $value);
			}
			return $doctors;
			
		}
		
		
		
		public function getGroupName($groupID){
			$stmt = $this->con->prepare("SELECT `GroupName` FROM `group` WHERE `GroupID` = ?");
			$stmt->bind_param("i",$groupID);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			$groupName = array_values($row)[0];
			return $groupName;
		}
		
		
		public function getDoctors(){
			$response = array();
			$response1 = array();
			$response2 = array();
			$stmt = $this->con->prepare("SELECT `Doctor_Name`, `Doctor_ID` FROM `individual`");
			$stmt->execute();
			$result = $stmt->get_result();
			foreach($result as $item){
				array_push($response1,$item['Doctor_Name']);
				array_push($response2,$item['Doctor_ID']);
			}
			$response['Doctors'] = $response1;
			$response['IDs'] = $response2;
			return $response;
		}
		
		public function getDoctorsAll(){
			$response = array();
			$response1 = array();
			$response2 = array();
			$stmt = $this->con->prepare("SELECT `Doctor_Name`, `Phone_num` FROM `individual`");
			$stmt->execute();
			$result = $stmt->get_result();
			foreach($result as $item){
				array_push($response1,$item['Doctor_Name']);
				array_push($response2,$item['Phone_num']);
			}
			$response['Doctors'] = $response1;
			$response['PhoneNumbers'] = $response2;
			return $response;
			
		}
		
		
		private function searchGroupCallSchedule($CurrentTime){
			$response = array();
			$stmt2 = "SELECT `Individual_Doctor_ID` FROM `group call schedule` WHERE `Start` <= ? AND `End` >= ?";
			$stmt = $this->con->prepare($stmt2);
			$stmt->bind_param("ss",$CurrentTime,$CurrentTime);
			$stmt->execute();
			$result = $stmt->get_result();  //getting all Doctor Ids within this time period
			foreach( $result as $item ) {
					array_push($response,$item['Individual_Doctor_ID']);
					}
						
			return $response;
		}
		
		
		
		
		
	}
		



?>