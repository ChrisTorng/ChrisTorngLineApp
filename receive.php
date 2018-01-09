<?php
 echo("1<br>");
 $json_str = file_get_contents('php://input'); //接收REQUEST的BODY
 echo($json_str);
 $json_obj = json_decode($json_str); //轉JSON格式
 echo("<br>2<br>");

 $myfile = fopen("log.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
 fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前加入\xEF\xBB\xBF轉成utf8格式
 fclose($myfile);
 echo("3<br>");

 //產生回傳給line server的格式
 $sender_userid = $json_obj->events[0]->source->userId;
 $sender_txt = $json_obj->events[0]->message->text;
 $sender_replyToken = $json_obj->events[0]->replyToken;
 $response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "text",
						"text" => "Hello, YOU SAY ".$sender_txt
					)
				)
		);
 echo("4<br>");
 echo($response);
 echo("<br>");

 $myfile = fopen("log2.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
 fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前加入\xEF\xBB\xBF轉成utf8格式
 fclose($myfile);
 echo("5<br>");

 //回傳給line server
 $header[] = "Content-Type: application/json";
 $header[] = "Authorization: Bearer FjeOS2XLpz35RcwPQ8TVgtUF8znXP8CKYqQmJLkhNM3xdwVaUg2kfkRqKIifkGm0HMaO1Eq/LjuZxzcDy1sLutRhi/y0EPVjO/UYpBEiD8VLlMWHyvAUMDNJ0Q0tF0XCRtubA5l1kQJ9v6UFnNDr6AdB04t89/1O/w1cDnyilFU=";
 $ch = curl_init("https://api.line.me/v2/bot/message/reply");                                                                      
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
 $result = curl_exec($ch);
 curl_close($ch); 
 echo("6<br>");
 echo($result);

?>
