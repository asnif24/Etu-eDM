<?php
	$input=json_decode(file_get_contents("php://input"));
	$zip_path=$input->zip_path;
	$task_name=$input->task_name;
	$company_name=$input->company_name;
	
	$python='python ../python/get_zip_and_send_edm.py';
	// $python='python ../get_zip_test.py';
	// $python='python check_zip_format_py.py';
	
	$command=$python+'\ '+$zip_path+'\ '+$task_name+'\ '+$company_name;
	// $command='python check_zip_format_py.py a b c';
	// $command='python ../get_zip_test.py a b c';

	$output=shell_exec($command);
	echo $output;
	// echo json_encode($output);
	// echo json_encode("{'status':'SUCCESS', 'task_id':'task_1234567890'}");
?>