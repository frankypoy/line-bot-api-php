 <?php
	function pubMqtt($topic,$msg){
		$APPID= "PoyEGAT/"; //enter your appid
		$KEY = "OyzQBzyzRQLtv8G"; //enter your web key
		$SECRET = "3HgvoYqoriX4LjUgX6727CzsA"; //enter your secret
		$Topic = "$topic";
		if($msg =="on"){
			$msg = "1";
		}
		else if($msg =="off"){
			$msg = "0";
		}
		else if($msg =="เปิดไฟ"){
			$msg = "1";
		}
		else if($msg =="ปิดไฟ"){
			$msg = "0";
		}
		else if($msg =="56"){
			$msg = "1";
		}
		else{
			//
		}
		put("https://api.netpie.io/microgear/".$APPID.$Topic."?retain&auth=".$KEY.":".$SECRET,$msg);
	}
	
	function getMqttfromlineMsg($Topic,$lineMsg){
		$pos = strpos($lineMsg, ":");
		if($pos){
			$splitMsg = explode(":", $lineMsg);
			$topic = $splitMsg[0];
			$msg = $splitMsg[1];
			pubMqtt($topic,$msg);
		}else{
			$topic = $Topic;
			$msg = $lineMsg;
			pubMqtt($topic,$msg);
		}
	}
	
	function put($url,$tmsg)
	{
      $ch = curl_init($url);
	  
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	  
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  
	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	  
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $tmsg);
	  
	  //curl_setopt($ch, CURLOPT_USERPWD, "mJ7K4MfteC7p0dW:pp4gzMhCvJIqlxc66hKEvk46m");
	  $response = curl_exec($ch);
	  
	  curl_close($ch);
	  
	  echo $response . "\r\n";
	  
	  return $response;
}

?>
