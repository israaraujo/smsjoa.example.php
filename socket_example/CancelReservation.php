<?php

require_once "common.php";

echo "예약 취소<br/>";

$param = array(
	"msg_serial" => "M100001_00000000000000",
);

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->cancelReservation($param);

/******************************************************************************
- 결과값
$result->msg_serial : 취소 완료된 메세지 고유키

******************************************************************************/
echo "<pre>";
var_dump($result);
echo "</pre>";

?>