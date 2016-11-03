<?php
  if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {

    $html = "Upload(name): " . $_FILES["file"]["name"] . "<br />";
    $html .= "Type: " . $_FILES["file"]["type"] . "<br />";
    $html .= "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    $html .= "Stored in: " . $_FILES["file"]["tmp_name"] . "<br />";

    //將上傳成功的檔案放到指定路徑下
    $moveRes = move_uploaded_file($_FILES["file"]["tmp_name"],
    "../smsFiles/". $_FILES["file"]["name"]);

    $html .= "Uploaded file is moved to /smsFiles". "<br />";
    echo $html;
  }
  else {
    echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }

?>
