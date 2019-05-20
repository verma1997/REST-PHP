<?php

$request_method = $_SERVER['REQUEST_METHOD'];

$response = array();

switch($request_method){
	case "GET":
		response(doGet());
	break;
	case "POST":
		response(doPost());
	break;
	case "DELETE":
		response(doDelete());
	break;
	case "PUT":
		response(doPut());
	break;
}

function doGet(){
	if(@$_GET['ID']){
		@$ID = $_GET['ID'];
		$where = "WHERE ID=".$ID;
	} else{
		$ID = 0;
		$where = "";
	}	

	$db_connect = mysqli_connect("localhost","root","","employee_details");
	$query = mysqli_query($db_connect,"SELECT * FROM employees ".$where);
	while($data = mysqli_fetch_assoc($query)){
		$response[] = array("ID"=>$data['ID'],"first_name"=>$data['first_name'],"last_name"=>$data['last_name'],"department"=>$data['department'],"DOB"=>$data['DOB'],"DOJ"=>$data['DOJ'],"salary"=>$data['salary']);
	}
	return $response;
}
function doPost(){
	if(@$_POST) {
		$db_connect = mysqli_connect("localhost","root","","employee_details");
		$query = mysqli_query($db_connect,"INSERT INTO `employees` (first_name,last_name,department,DOB,DOJ,salary) VALUES ('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['department']."','".$_POST['DOB']."','".$_POST['DOJ']."','".$_POST['salary']."')");

		if($query == true){
			$response = array("message"=>"successfully inserted");
		} else{
			$response = array("message"=>"insert failed");
		}	
	}

	return $response;
}
function doDelete(){
	if(@$_GET['ID']) {
		$db_connect = mysqli_connect("localhost","root","","employee_details");
		$query = mysqli_query($db_connect,"DELETE FROM employees WHERE ID='".$_GET['ID']."'");

		if($query == true){
			$response = array("message"=>"successfully deleted");
		} else{
			$response = array("message"=>"delete failed");
		}	
	}

	return $response;
}
function doPut(){

	parse_str(file_get_contents('php://input'), $_PUT);


	if(@$_PUT) {
		$db_connect = mysqli_connect("localhost","root","","employee_details");
		
		$query = mysqli_query($db_connect,"UPDATE employees SET first_name='".$_PUT['first_name']."',last_name='".$_PUT['last_name']."',department='".$_PUT['department']."',DOB='".$_PUT['DOB']."',DOJ='".$_PUT['DOJ']."',salary='".$_PUT['salary']."' 
			WHERE ID = '".$_GET['ID']."'
			");

		if($query == true){
			$response = array("message"=>"successfully updated");
		} else{
			$response = array("message"=>"update failed");
		}	
	}

	return $response;

}
//output
function response($response){
	echo json_encode(array("status"=>"200","data"=>$response));		
}

?>