
<?php

$kmsg_user="etusolution";
$kmsg_password="etubigdata2016";
$kmsg_server="https://api.kotsms.com.tw/kotsmsapi-1.php?";
$kmsg_response="http://210.63.38.221/response.php";

$target_file=$_POST['file'];
$row = 1;
if (($handle = fopen($target_file, "r")) !== FALSE) {
    
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<p> $num data in message $row: <br/></p>\n";
        $row++;
        
	for ($c=0; $c < $num; $c++) {
		if($c > 1){
		$data[1] = $data[1]." ".$data[$c];		
		}            
		echo $data[$c] . "<br/>\n";
        }
	
	if(preg_match('/^09[0-9]{8}$/',$data[0]))
	{
		mb_convert_encoding($data[1], "BIG5"); 
		echo"<p>".$data[0]." ".$data[1]."<br/></p>";
		
		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL,$kmsg_server);
    		curl_setopt($ch, CURLOPT_POST,true);
  	
     		curl_setopt($ch, CURLOPT_POSTFIELDS,
        	http_build_query(array ("username"=>$kmsg_user,"password"=>$kmsg_password,"dstaddr"=>$data[0],"smbody"=>$data[1],"response"=>$kmsg_response)));

  		curl_exec($ch);
  		curl_exec($ch);

		
	}else{
		echo "Cell phone format is incorrect";
	}
    }
    fclose($handle);
}
else{
	echo "file open fail";
}

?>

