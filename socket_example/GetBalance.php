<?php

require_once "common.php";

echo "잔여 금액 조회<br/>";

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->getBalance();

/******************************************************************************
- 결과값
$result->money : 잔여금액
******************************************************************************/
echo $result->money;

?>