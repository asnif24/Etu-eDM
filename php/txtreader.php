<?php
getMsgResult();

function getMsgResult(){
/*Connect Mysql Server*/
$dbhost = "127.0.0.1";
$user = "root";
$password = "5u4wj6";
$database = "EDM";
$datatable = "taskSMS";

/*Task Information*/
$taskid;
//$taskname = $_POST['taskname'];
$taskname = 'SMS test';
$companyid = "etu";
date_default_timezone_set('Asia/Taipei');
$datetime = date("Y-m-d H:i:s");

/*Get Message Result*/

//modified!!!
$ui_input = $_POST['fileName'];
$ui_input = explode(";",$ui_input);
//$ui_input=json_decode(file_get_contents("php://input"));
$taskname = $ui_input[1];
$logfile = $ui_input[0];

$log_handle = fopen($logfile,"r");

if ($log_handle) {

	$delivered= 0;
	$undelivered =0;	
	$msg_fail = 0;
	$total_msg = 0;
	static $log_search;
	static $currect;
	static $Tasktime;
	
	while(!feof($log_handle)){
	     
	     $kmsgid = fgets($log_handle,32);
             $kmsgid = preg_replace('/\s(?=)/', '', $kmsgid);
	     //echo "search:".$kmsgid."<br>";
	     
	     if((!preg_match('/-/',$kmsgid)) && preg_match('/kmsgid/',$kmsgid)){
	     $kmsgid_temp = '/'.$kmsgid.'/';
	     //$kmsgid_temp = preg_replace('/\s(?=)/', '', $kmsgid_temp);
	     //echo "search temp:".$kmsgid_temp."<br>";
	     $currect = true;
	     }
	     
	     if((!preg_match('/-/',$kmsgid)) && (!preg_match('/kmsgid/',$kmsgid)) && (!preg_match('/phoneformaterror/',$kmsgid)) && empty($kmsgid)==false && (!preg_match('/TASKid/i',$kmsgid))){
	     $Tasktime=$kmsgid;
	     //echo "Task Time:".$kmsgid."<br>";
	     $currect = false;
	     }
	     
	     if(preg_match('/-/',$kmsgid)){
	     //echo $kmsgid."<br>";
	     $currect = false;
	     }
	     
	     if(preg_match('/TASKid/i',$kmsgid)){
	     $taskid=$kmsgid;
	     //echo $taskid."<br>";
	     $currect = false;
             }
	     
	     if(preg_match('/phoneformaterror/',$kmsgid)){
	     //echo $kmsgid."<br>";
	     $currect = false;
	     }
	     
	     if(empty($kmsgid)==true){
	     //echo $kmsgid."<br>";
	     $currect = false;
	     }
	    
	     $handle = fopen("retccmoapi.txt", "r");
	    while(!feof($handle) && $currect == true){
		$contents = fgets($handle,1024);	        
                $log_search = 0;
                
                if(preg_match($kmsgid_temp,$contents) && $kmsgid_temp !="//"){       
	  	  
 		  //echo "Receive ".$kmsgid." log,";	
	  	  $statusstr= explode("	",$contents);	  	  
		  //echo $statusstr[4]."</br>";		  
		  $log_search = 1;
		  
		  //echo "<br>log_search in while:".$log_search."</br>";
		  if( preg_match("/statusstr=DELIVRD/",$statusstr[4]) ){
			$delivered++;
		  }
		  else{
			$msg_fail++;
		  }	
		break;  
		}

	     }            
	     //echo "log_search out while:".$log_search."</br>";
	     if($log_search == 0 && $kmsgid_temp != "//" && $currect == true){
		//echo "We haven't receivce ".$kmsgid." log yet</br>";		
	     	$undelivered++;
		$total_msg++;
	     
	     }
	     else if($kmsgid_temp != "//"&& $currect == true)
             $total_msg++;
	     fclose($handle);
	     
	} 
	/*
	echo"Total send msg :".$total_msg."</br>";
	echo"Total delivered msg :".$delivered.", Percent:".(($delivered/$total_msg)*100)."% </br>";
        echo"Total undelivered msg :".$undelivered.", Percent: ".(($undelivered/$total_msg)*100)."% </br>";	
	echo"Total fail msg :".$msg_fail.", Percent: ".(($msg_fail/$total_msg)*100)."% </br>";
	*/
	$delivered_rate = ($delivered/$total_msg);
	$undelivered_rate = ($undelivered/$total_msg);
	$sendMsgFail_rate = ($msg_fail/$total_msg);
	
	$delivered_rate_T = number_format($delivered_rate*100,2)."%";
        $undelivered_rate_T = number_format($undelivered_rate*100,2)."%";
        $sendMsgFail_rate_T = number_format($sendMsgFail_rate*100,2)."%";

	//modified!!!
	/*
	echo "Task ID:".$taskid.
	     "</br>Task time:".$Tasktime.
	     "</br> Delivered Rate:".$delivered_rate."% </br> Undelivered Rate:".$undelivered_rate."%</br> Fail Rate:".$sendMsgFail_rate."%";
	*/
	
        $resultSms=array('Total_msg'=>$total_msg,'delivered_rate'=>$delivered_rate_T,'undelivered_rate'=>$undelivered_rate_T,'sendMsgFail_rate'=>$sendMsgFail_rate_T);
	echo json_encode($resultSms);
	
	//modified!!!
	
	/*Connect MySQL Server*/
	
	$connect = mysqli_connect($dbhost,$user,$password,$database)or die("Connect failed:".mysqli_connect_error()); 
	 mysql_query("SET NAMES 'utf-8'");
    	 mysqli_select_db($connect,$database);
	
	
	$sql_cmdcoln = "REPLACE INTO "
			.$datatable." (task_id,task_name,company_id,campaign_id,delivered_rate,undelivered_rate,sendMsgFail_rate,Tasktime,Logtime)";
	$sql_value = " Values ('".$taskid."', '".$taskname."', '".$companyid."', '".$logfile."', '"
			.$delivered_rate."', '".$undelivered_rate."', '".$sendMsgFail_rate."', '".$Tasktime."', '".$datetime."');";
	
	$sql_insert = $sql_cmdcoln.$sql_value;
        //echo "<br>".$sql_insert."</br>";:
	mysqli_query($connect,$sql_insert)or die("insert fail".mysqli_error());
	mysqli_close($connect);
	
	} 

	else{
		echo "Not open file already";
	}
	fclose($log_handle);
	
	

}
   
?>

