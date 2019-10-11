<?php

error_reporting(E_ALL & ~E_NOTICE);

include 'database.php';

$pdo = Database::connect();
$pdo -> exec("set names utf8");

$star5 = 0; $star4 = 0; $star3 = 0; $star2 = 0; $star1 = 0;
// All
$sql = "SELECT COUNT(*) AS Count FROM history";
$stmt = $pdo->prepare($sql);
$stmt->execute(array());
$result = $stmt->fetch( PDO::FETCH_ASSOC );
$countall = $result['Count'];

$sql2 = "SELECT COUNT(*) AS CountRes FROM history WHERE email IS NOT NULL AND respone_text IS NULL";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute(array());
$result2 = $stmt2->fetch( PDO::FETCH_ASSOC );
$countexist = $result2['CountRes'];


// 5
$sql = "SELECT COUNT(*) AS Count FROM history WHERE rate = :rate";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':rate' => 5));
$result = $stmt->fetch( PDO::FETCH_ASSOC );
$star5 = $result['Count'];

// 4

$stmt->execute(array(':rate' => 4));
$result = $stmt->fetch( PDO::FETCH_ASSOC );
$star4 = $result['Count'];

//3
$stmt->execute(array(':rate' => 3));
$result = $stmt->fetch( PDO::FETCH_ASSOC );
$star3 = $result['Count'];

//2
$stmt->execute(array(':rate' => 2));
$result = $stmt->fetch( PDO::FETCH_ASSOC );
$star2 = $result['Count'];

//1
$stmt->execute(array(':rate' => 1));
$result = $stmt->fetch( PDO::FETCH_ASSOC );
$star1 = $result['Count'];

Database::disconnect();

$countText = "จำนวนผู้ตอบแบบสอบถามทั้งหมด ".$countall." ครั้ง \n";
$countText .= "พึงพอใจมากที่สุด : ".$star5." ครั้ง \n";
$countText .= "พึงพอใจมาก : ".$star4." ครั้ง \n";
$countText .= "พึงพอใจปานกลาง : ".$star3." ครั้ง \n";
$countText .= "พึงพอใจน้อย : ".$star2." ครั้ง \n";
$countText .= "พึงพอใจน้อยที่สุด : ".$star1." ครั้ง \n";

$countRes = "จำนวนที่ยังไม่ได้รับการตอบกลับ ".$countexist." ครั้ง \n";

$recomendText = "กรุณาพิมพ์คำสั่ง: \n";
$recomendText .= "ภาพรวม : เพื่อดูจำนวนการตอบกลับ" \n;
$recomendText .= "ค้างตอบ : เพื่อดูจำนวนค้างตอบกลับ Email" \n;

?>


<?php 
	/*Get Data From POST Http Request*/
	$datas = file_get_contents('php://input');
	/*Decode Json From LINE Data Body*/
	$deCode = json_decode($datas,true);
	file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);
	$replyToken = $deCode['events'][0]['replyToken'];

	$replyText = $deCode['events'][0]['message']['text'];

	$messages = [];
	$messages['replyToken'] = $replyToken;

	if($replyText == "ภาพรวม") {
		$messages['messages'][0] = getFormatTextMessage($countText);
	} elseif ($replyText == "ค้างตอบ") {
		$messages['messages'][0] = getFormatTextMessage($countRes);
	} else {
		$messages['messages'][0] = getFormatTextMessage($recomendText);
	}

	/*if($replyText == "ภาพรวม") {
		$messages['messages'][0] = getFormatTextMessage($countText);
	} else {
		$messages['messages'][0] = getFormatTextMessage($recomendText);
	}*/

	//$messages['messages'][0] = getFormatTextMessage("BNK48");

	$encodeJson = json_encode($messages);
	$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "ksK4gfXi/SLgV9PzWXB0jta1XgPvQIGxnwQigpAfxZzlpgXwUEMK/p1DzsBgeqLsimw9D4sF0S8QQj+1jBQ4iKbaU+w1e75VUGLNKXSABr7Ggix+ZXHJklyLJdebbaWgWIse6u0HekvUzwRWABESBgdB04t89/1O/w1cDnyilFU=";
  	$results = sentMessage($encodeJson,$LINEDatas);
	/*Return HTTP Request 200*/
	http_response_code(200);
	function getFormatTextMessage($text)
	{
		$datas = [];
		$datas['type'] = 'text';
		$datas['text'] = $text;
		return $datas;
	}
	function sentMessage($encodeJson,$datas)
	{
		$datasReturn = [];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $datas['url'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_PROXY => 'http://proxy.egat.co.th:8080',
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $encodeJson,
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer ".$datas['token'],
		    "cache-control: no-cache",
		    "content-type: application/json; charset=UTF-8",
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		    $datasReturn['result'] = 'E';
		    $datasReturn['message'] = $err;
		} else {
		    if($response == "{}"){
			$datasReturn['result'] = 'S';
			$datasReturn['message'] = 'Success';
		    }else{
			$datasReturn['result'] = 'E';
			$datasReturn['message'] = $response;
		    }
		}
		return $datasReturn;
	}
?>