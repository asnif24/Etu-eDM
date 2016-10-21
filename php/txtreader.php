<?php
getMsgResult();

function getMsgResult(){

//modified!!!
$fileName_input=json_decode(file_get_contents("php://input"));
$logfile = $fileName_input->fileName;
//modified!!!



$log_handle = fopen($logfile,"r");

if ($log_handle) {

	$delivered= 0;
	$undelivered =0;	
	$msg_fail = 0;
	$total_msg = 0;
	static $log_search;
	
	while(!feof($log_handle)){
	     
	     $kmsgid = fgets($log_handle,20);
             //echo "search:".$kmsgid."<br>";
	     //$kmsgid = trim($kmsgid);
	     
	     if(!is_null($kmsgid)){
	     $kmsgid_temp = '/'.$kmsgid.'/';
	     $kmsgid_temp = preg_replace('/\s(?=)/', '', $kmsgid_temp);
	     //echo "search temp:".$kmsgid_temp."<br>";
	     }
	    
	    $handle = fopen("retccmoapi.txt", "r");
	    while(!feof($handle)){
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
	     if($log_search == 0 && $kmsgid_temp != "//"){
		//echo "We haven't receivce ".$kmsgid." log yet</br>";		
	     	$undelivered++;
		$total_msg++;
	     
	     }
	     else if($kmsgid_temp != "//")
             $total_msg++;
	     fclose($handle);
	     
	} 

	//echo"Total send msg :".$total_msg."</br>";
	//echo"Total delivered msg :".$delivered.", Percent:".(($delivered/$total_msg)*100)."% </br>";
        //echo"Total undelivered msg :".$undelivered.", Percent: ".(($undelivered/$total_msg)*100)."% </br>";	
	//echo"Total fail msg :".$msg_fail.", Percent: ".(($msg_fail/$total_msg)*100)."% </br>";
	
	$delivered_rate = ($delivered/$total_msg)*100;
	$undelivered_rate = ($undelivered/$total_msg)*100;
	$sendMsgFail_rate = ($msg_fail/$total_msg)*100;
	
	//modified!!!
	// echo "Delivered Rate:".$delivered_rate."% </br> Undelivered Rate:".$undelivered_rate."%</br> Fail Rate:".$sendMsgFail_rate."%";
	$resultSms=json_encode(array(
		'delivered_rate'=>$delivered_rate,
		'undelivered_rate'=>$undelivered_rate,
		'sendMsgFail_rate'=>$sendMsgFail_rate
	);
	echo $resultSms;
	//modified!!!

} 

else{
	echo "Not open file already";
}
fclose($log_handle); 
}   

?>

