<?php

require_once "common.php";

echo "���� ���� ��ȸ<br/>";

$param = array(
	"msg_serial" => "M100001_00000000000000",
	"list_count" => 1,
	"page" => 1,
);

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->getMessage($param);

/******************************************************************************
- �����(�迭)
$result['data']['trandate'] : ���۽ð�
$result['data']['phone'] : ���Ź�ȣ
$result['data']['status'] : ���ۻ���
$result['data']['rsltcode'] : ����ڵ�
$result['data']['msg_type'] : ����Ÿ��
$result['data']['telecom'] : �̵���Ż�
$result['data']['rsltdate'] : ��� ó�� �ð�
$result['total_count'] : �� ���� �Ǽ�
******************************************************************************/
echo "<pre>";
var_dump($result);
echo "</pre>";

?>