<?php
	$input=json_decode(file_get_contents("php://input"));
	//$zip_path=$input->zip_path;
	$task_id=$input->task_id;
	$task_name=$input->task_name;
	$company_id=$input->company_id;
	$company_name=$input->company_name;
	
	//$zip_path="../edmFiles/".$zip_path;

	$python='python /opt/edm/email/src/send_edm.py';
	
	$command=$python.' '.$task_id.' '.$task_name.' '.$company_id.' '.$company_name.' 2>/tmp/error_htlin';

	$output=shell_exec($command);
	$output.=$task_id.$task_name.$company_id.$company_name;
	echo $output;
?>
