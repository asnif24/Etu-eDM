<?php  
	$input=json_decode(file_get_contents("php://input"));
	$task_id=$input->task_id;
	
	$python='get_edm_report.py';
	$command='';
	$output=shell_exec($command);
	echo $output;
?>