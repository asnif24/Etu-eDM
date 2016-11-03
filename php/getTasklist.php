<?php
$dbhost = "127.0.0.1";
$user = "etu_SMS";
$password = "sms5u4wj6";
$database = "EDM";
$datatable = "taskSMS";

/*Task Information*/
$companyid = "etu";

$connect = mysql_connect($dbhost,$user,$password,$database)or die("Connect failed:".mysql_connect_error()); 
	 mysql_query("SET NAMES 'utf-8'");
    	 mysql_select_db($database);

$sql = "SELECT task_name,campaign_id FROM taskSMS WHERE company_id='".$companyid."';";
//echo $sql."</br>";

$result = mysql_query($sql) or die("Select fail:".mysql_error());
//echo "result:".$result."</br>";

$M = array();
$i=0;


while($data = mysql_fetch_array($result))
{
	//echo "row1:". $data["task_name"]." ". $data["campaign_id"]."</br>";
	$temp_tn = $data["task_name"];
	$temp_fn = $data["campaign_id"];
	//$M[$i][0] = $temp_tn;
	//$M[$i][1] = $temp_fn;
	//$i++;
	array_push($M,array("taskName"=>$temp_tn,"logName"=>$temp_fn));
		 
}

//print_r($M);
$json=json_encode($M);
echo$json;

mysql_close($connect);
?>
