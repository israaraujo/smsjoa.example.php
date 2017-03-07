<?php

require_once "common.php";

echo "전송 내역 조회<br/>";

$param = array(
	"msg_serial" => "M100001_00000000000000",
	"list_count" => 1,
	"page" => 1,
);

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->getMessage($param);

/******************************************************************************
- 결과값(배열)
$result['data']['trandate'] : 전송시간
$result['data']['phone'] : 수신번호
$result['data']['status'] : 전송상태
$result['data']['rsltcode'] : 결과코드
$result['data']['msg_type'] : 문자타입
$result['data']['telecom'] : 이동통신사
$result['data']['rsltdate'] : 결과 처리 시간
$result['total_count'] : 총 전송 건수
******************************************************************************/
echo "<pre>";
var_dump($result);
echo "</pre>";

?>