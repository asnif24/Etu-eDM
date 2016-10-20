<?php 
	$input = json_decode(file_get_contents("php://input"));
	// $input = file_get_contents("php://input");

	// $file_n=$request->file;
	$data=json_decode(file_get_contents('accounts.json'));
	array_push($data, $input);
	file_put_contents('accounts.json', json_encode($data));
	echo json_encode($data);
?>