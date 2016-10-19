<html>
     <head>
        <title>Message Send</title>
     </head>
	<body>
<?php
sendMsg();

function sendMsg(){
$kmsg_user="etusolution";
$kmsg_password="etubigdata2016";
$kmsg_server="https://api.kotsms.com.tw/kotsmsapi-1.php?";
$kmsg_response="http://210.63.38.221/response.php";

//modified!!
$targer_input=json_decode(file_get_contents("php://input"));
$target_file=$targer_input->fileName;
//modified!!!


$row = 1;

//$target_file="test.csv";

date_default_timezone_set('Asia/Taipei');

if (($handle = fopen($target_file, "r")) !== FALSE) {
    
    $csvfilename=explode(".",$target_file);
    $datetime = date("YmdHi");
    $log = fopen($csvfilename[0].$datetime.".txt","a+");
    //echo "Log filename:".$log."<br/>";
   
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        //echo "<p> $num data in message $row: <br/></p>\n";
        $row++;
        
	for ($c=0; $c < $num; $c++) {
		if($c > 1){
		$data[1] = $data[1]." ".$data[$c];		
		}            
		//echo $data[$c] . "<br/>\n";
        }
	
	if(preg_match('/^09[0-9]{8}$/',$data[0]))
	{
		mb_convert_encoding($data[1], "BIG5"); 
		//echo"<p>".$data[0]." ".$data[1]."<br/></p>";
		
		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL,$kmsg_server);
    		curl_setopt($ch, CURLOPT_POST,true);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
     		curl_setopt($ch, CURLOPT_POSTFIELDS,
        	http_build_query(array ("username"=>$kmsg_user,"password"=>$kmsg_password,"dstaddr"=>$data[0],"smbody"=>$data[1],"response"=>$kmsg_response)));

  		$result=curl_exec($ch);
		
		if(!preg_match("/-/",$result))
		fwrite($log,$result);
		
		//echo "The result:".$result;
  		curl_exec($ch);

	}else{
		//echo "Cell phone format is incorrect";
	}
    }
    fclose($handle);
    fclose($log);
	echo "send Task complete.</br>";
}
else{
	echo "file open fail.</br>";
}
}
?>
	</body>	
</html>
