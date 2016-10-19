<?php
// if(isset($_POST['data'])){
//     $data = $_POST['data'];
//     $result = array(
//         'msg'=>$data
//     );
//     echo json_encode($result);
// }
	// $output .= 'console.log('aaa');';
	// $data = $_POST['data'];
	// $postdata = file_get_contents("php://input");
	// $request = json_decode($postdata);
	// $data = $request->data;
	// $com = $request->com;
	// $num=$_POST['param'];
	// $dd=array($data,$num);

	// $output1 .=json_encode($dd);
	// $output1 .=json_encode(array('data'=>$data,'com'=>$com));
	// $output2 .=json_encode($num);
	// echo $output1;
	// echo $output1;
	// echo $output1;
	// $name=file_get_contents("php://input");
	$request = json_decode(file_get_contents("php://input"));
	$file_n=$request->file;

	echo $file_n;

	// echo $output2;
?>

