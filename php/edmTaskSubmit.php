<?php
	$input=json_decode(file_get_contents("php://input"));
	$zip_path=$input->zip_path;
	$task_name=$input->task_name;
	$company_name=$input->company_name;
	
	$zip_path="../edmFiles/".$zip_path;

	$python='python /opt/edm/email/src/get_zip.py';
	
	$command=$python.' '.$zip_path.' '.$task_name.' '.$company_name.' 2>/tmp/error_htlin_`date`';

	$output=shell_exec($command);
	echo $output;
	//echo json_encode($output);

?>
