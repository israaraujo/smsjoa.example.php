<?php

require_once "common.php";

echo "MMS ����<br/>";

//���ϳ��� ������ ���
$contents = array(
	"msg_type" => "mms", //�޼��� ����
	"phone" => "01000000000", //���Ź�ȣ, ���������� ��� ��ǥ�� ���� ex)0111111111,0111111111
	"callback" => "0111111111", //ȸ�Ź�ȣ
	"subject" => "MMS���� ����",
	"msg" => "MMS ���� �׽�Ʈ\n������", //���۸޼���
	"image"=> "./mms/sample.jpg",
);

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