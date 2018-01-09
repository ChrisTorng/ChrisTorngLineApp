<?php
  $json_str = file_get_contents('php://input'); // receive request's body
  $json_obj = json_decode($json_str); // decode json string into json object

  $myfile = fopen("log.txt", "w+") or die("Unable to open file!");
  fwrite($myfile, "\xEF\xBB\xBF".$json_str); // Turn into UTF-8 format
  fclose($myfile);
?>
