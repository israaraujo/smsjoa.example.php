<?php

require_once "common.php";

echo "���� ���� ��ȸ<br/>";

$param = array(
	"msg_serial" => "M100001_00000000000000",
	"list_count" => 10,
	"page" => 1,
);

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->getMessage($param);

/******************************************************************************
- �����
�޴��� ����
******************************************************************************/
echo "<pre>";
var_dump($result);
echo "</pre>";

?>