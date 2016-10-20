<?php 
	// $last_line = system('ls', $retval);
	$a='ls';
	$b='python check_zip_format_py.py a b c';
	// $last_line =system($b, $retval);
	// $aaa=json_encode($last_line);
	// echo $last_line ;
	$output=shell_exec('python check_zip_format_py.py a b c');
	// exec('python check_zip_format_py.py a b c', $output, $return_var);
	// $output=json_decode($output)
	// $out=$output->'status';
	echo $output;
	// echo $out;
	// echo json_encode($output);
 ?>