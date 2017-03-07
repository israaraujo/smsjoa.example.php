<?php

require_once "common.php";

echo "SMS 전송<br/>";

//동일내용 전송
$contents = array(
	"msg_type" => "sms", //메세지 형식
	"phone" => "01000000000", //수신번호, 동보전송일 경우 쉼표로 구분 ex)0111111111,0111111111
	"callback" => "0111111111", //회신번호
	"msg" => "SMS 전송 테스트\n가나다", //전송메세지
);

//개별내용 전송(SMS형식만 지원)
/*
$msg_list = array(
	"01000000000" => "메세지 1",
	"011111112" => "메세지 2"
);

$contents = array(
	"msg_type" => "sms", //메세지 형식
	"msg_list" => $msg_list,
	"callback" => "0627167179", //회신번호
);
*/

//예약전송일 경우 예약시간 추가
/*
$contents['trandate'] = "20141130193011"; //시간 형식 : YYYYMMDDHHMMSS
*/

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->sendMessage($contents);

/******************************************************************************
- 결과값(배열)
$result['msg_serial'] : 전송 메세지 고유키(전송내역 조회 및 예약 취소를 위해 DB에 저장해서 보관하세요)
$result['total_count'] : 총 전송건수
$result['cost'] : 전송 차감 금액
******************************************************************************/
echo $result['msg_serial'];

?>