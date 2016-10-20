<?php
	$input=json_decode(file_get_contents("php://input"));
	$zip_path=$input->zip_path;
	$task_name=$input->task_name;
	$company_name=$input->company_name;
	
	$python='python get_zip_and_send_edm.py {zip_path} {task_name} {company_name} ';
	$command='';
	$output=shell_exec($command);
	print $output;
?>