<?php  
	$input=json_decode(file_get_contents("php://input"));
	$task_id=$input->task_id;
	
	$python='python /opt/edm/email/src/get_edm_report.py';
	
	$command=$python.' '.$task_id;
	$output=shell_exec($command);
	echo $output;
?>
