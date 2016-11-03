<?php
	$input = json_decode(file_get_contents("php://input"));
	$data=json_decode(file_get_contents('../json/edmTasks.json'));
	
	array_push($data, $input);
	file_put_contents('../json/edmTasks.json', json_encode($data));
	echo json_encode($data);
?>