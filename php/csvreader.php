<?php
sendMsg();

function sendMsg(){
$kmsg_user="etusolution";
$kmsg_password="etubigdata2016";
$kmsg_server="https://api.kotsms.com.tw/kotsmsapi-1.php?";
$kmsg_response="http://210.63.38.220/Etu-eDM/php/response.php";


//$target_file=$_POST['file'];
$input = json_decode(file_get_contents("php://input"));
$target_file=$input->fileName;

/*Connect Mysql Server*/
$dbhost = "127.0.0.1";
$user = "etu_SMS";
$password = "sms5u4wj6";
$database = "EDM";
$datatable = "taskSMS";

/*Task Information*/
//$taskname = "SMS test";
$taskname=$input->taskName;
$companyid = "etu";
$target_file_path="../smsFiles/".$target_file;

$row = 1;

//$target_file="test.csv";

date_default_timezone_set('Asia/Taipei');

if (($handle = fopen($target_file_path, "r")) !== FALSE) {
    
    $csvfilename=explode(".",$target_file);
      
    $datetime = date("YmdHis");
    $log = fopen($csvfilename[0].$datetime.".txt","a+");

    $logName = $csvfilename[0].$datetime.".txt";

   //echo "Log filename:".$logName."<br/>";
   
    fwrite($log,$datetime.PHP_EOL);
    $taskid ="TASKid".uniqid();
    fwrite($log,$taskid.PHP_EOL);
    
    while (($data = __fgetcsv($handle, 1000, ",")) !== FALSE) {
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
		
		/*if kmsgid is negative ,that means King Message's Server does't service*/
		
		//if(preg_match("/-/",$result))
		//fwrite($log,"fail: ".$result);
		
		fwrite($log,$result);				
		
		//echo "The result:".$result;
  		curl_exec($ch);

	}else{
		//echo "Cell phone format is incorrect";
		fwrite($log,"phone format error :".$data[0]);
	}
    }
    fclose($handle);
    fclose($log);
        
	/*Connect MySQL Server*/

        $connect = mysqli_connect($dbhost,$user,$password,$database)or die("Connect failed:".mysqli_connect_error());
         mysql_query("SET NAMES 'utf-8'");
         mysqli_select_db($connect,$database);


        $sql_cmdcoln = "REPLACE INTO "
                        .$datatable." (task_id,task_name,company_id,campaign_id,delivered_rate,undelivered_rate,sendMsgFail_rate,Tasktime,Logtime)";
        $sql_value = " Values ('".$taskid."', '".$taskname."', '".$companyid."', '".$logName."', '"
                        .$delivered_rate."', '".$undelivered_rate."', '".$sendMsgFail_rate."', '".$datetime."', '".$datetime."');";

        $sql_insert = $sql_cmdcoln.$sql_value;
        //echo "<br>".$sql_insert."</br>";:
        mysqli_query($connect,$sql_insert)or die("insert fail".mysqli_error());
        mysqli_close($connect);
      
      
	echo "send Task complete.";
}
else{
	echo "file open fail.</br>";
	echo $target_file."</br>";
}
}
function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"') {
 $d = preg_quote($d);
 $e = preg_quote($e);
 $_line = "";
 $eof=false;
 while ($eof != true) {
 $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));
 $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
 if ($itemcnt % 2 == 0){
 $eof = true;
 }
 }
 
 $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
 
 $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
 preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
 $_csv_data = $_csv_matches[1];
 
 for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
 $_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);
 $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
 }
 
 return empty ($_line) ? false : $_csv_data;
}
?>
