<?php

require_once "common.php";

echo "SMS ����<br/>";

//���ϳ��� ����
$contents = array(
	"msg_type" => "sms", //�޼��� ����
	"phone" => "01000000000", //���Ź�ȣ, ���������� ��� ��ǥ�� ���� ex)0111111111,0111111111
	"callback" => "0111111111", //ȸ�Ź�ȣ
	"msg" => "SMS ���� �׽�Ʈ\n������", //���۸޼���
);

//�������� ����(SMS���ĸ� ����)
/*
$msg_list = array(
	"01000000000" => "�޼��� 1",
	"011111112" => "�޼��� 2"
);

$contents = array(
	"msg_type" => "sms", //�޼��� ����
	"msg_list" => $msg_list,
	"callback" => "0627167179", //ȸ�Ź�ȣ
);
*/

//���������� ��� ����ð� �߰�
/*
$contents['trandate'] = "20141130193011"; //�ð� ���� : YYYYMMDDHHMMSS
*/

$MessagingService = new MessagingService($client_id, $api_key);
$result = $MessagingService->sendMessage($contents);

/******************************************************************************
- �����(�迭)
$result['msg_serial'] : ���� �޼��� ����Ű(���۳��� ��ȸ �� ���� ��Ҹ� ���� DB�� �����ؼ� �����ϼ���)
$result['total_count'] : �� ���۰Ǽ�
$result['cost'] : ���� ���� �ݾ�
******************************************************************************/
echo $result['msg_serial'];

?>