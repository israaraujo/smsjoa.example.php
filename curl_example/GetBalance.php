<?php

require_once "common.php";

echo "�ܿ� �ݾ� ��ȸ<br/>";

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->getBalance();

/******************************************************************************
- �����(�迭)
$result['money'] : �ܿ��ݾ�
******************************************************************************/
echo $result['money'];

?>