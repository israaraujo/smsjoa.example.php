<?php

require_once "common.php";

echo "���� ���<br/>";

$param = array(
	"msg_serial" => "M100001_00000000000000",
);

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->cancelReservation($param);

/******************************************************************************
- �����(�迭)
$result['msg_serial'] : ��� �Ϸ�� �޼��� ����Ű

******************************************************************************/
echo "<pre>";
var_dump($result);
echo "</pre>";

?>