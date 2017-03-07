<?php

require_once "common.php";

echo "전송 내역 조회<br/>";

$param = array(
	"msg_serial" => "M100001_00000000000000",
	"list_count" => 10,
	"page" => 1,
);

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->getMessage($param);

/******************************************************************************
- 결과값
메뉴얼 참조
******************************************************************************/
echo "<pre>";
var_dump($result);
echo "</pre>";

?>