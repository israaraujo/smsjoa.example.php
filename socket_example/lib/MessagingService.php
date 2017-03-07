<?php

/**
* @file MessagingService.php
* @brief REST API Messaging module (euc-kr)
* @author INNOPOST (tech@innopost.com)
*/

class MessagingService {

	var $ServiceURL = "api.smsjoa.com";
	var $Version = "1";

	var $UserID;
	var $APIKey;
	var $Token;
	var $JSON;
	var $Boundary;


	function MessagingService($UserID, $APIKey) {
		$this->JSON = new Services_JSON();

		$this->UserID = $UserID;
		$this->APIKey = $APIKey;

		$this->Token = $this->getToken();
		$this->Boundary = sha1(1);

		register_shutdown_function(array(&$this, "deleteToken"));
	}


	function execute($uri, $method, $authorization, $data=NULL, $isMultiPart=FALSE) {
		$fp = fsockopen("ssl://".$this->ServiceURL, 443, $errno, $errstr, 10);

		if (!$fp) {
			echo $errstr." (".$errno.")";
			exit;
		}
		else {
			$http = "";
			$http .= strtoupper($method)." /".$this->Version."/".$uri." HTTP/1.1\r\n";
			$http .= "Host: ".$this->ServiceURL."\r\n";
			$http .= "Accept: */*\r\n";
			$http .= "Authorization: ".$authorization."\r\n";

			if ($isMultiPart)
				$http .= "Content-Type: multipart/form-data; boundary=".$this->Boundary."\r\n";
			else
				$http .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8;\r\n";

			$http .= "Content-Length: ".strlen($data)."\r\n";
			$http .= "Connection: close\r\n\r\n";
			$http .= $data;

			fwrite($fp, $http);

			$temp = "";

			while (!feof($fp)) {
				$temp .= fgets($fp);
			}

			fclose($fp);
		}

		$temp = explode("\r\n\r\n", $temp, 2);

		$header = isset($temp[0]) ? $temp[0] : "";
		$content = isset($temp[1]) ? $temp[1] : "";

		$temp1 = explode("\r\n", $header);
		$temp2 = explode(" ", $temp1[0]);

		$status = $temp2[1];
		$content = $this->JSON->decode($content);

		if ($status == "200")
			$result = array("content" => $content);
		else
			$result = array("content" => "Error ".$content->code." ".iconv("UTF-8", "EUC-KR", $content->message));

		return $result;
	}


	function setContent($contents, $isPost=FALSE, $isMultiPart=FALSE) {
		$result = "";

		if ($isPost) {
			if ($isMultiPart) {
				foreach ($contents as $key => $val) {
					$result .= "--".$this->Boundary."\r\n";

					if ($key == "image") {
						$val = realpath($val);
						$image = file_get_contents($val);
						$result .= "Content-Disposition: form-data; name=\"".$key."\"; filename=\"".$val."\"\r\n";
						$result .= "Content-Type: application/octet-stream\r\n\r\n";
						$result .= $image."\r\n";
					}
					else {
						$result .= "Content-Disposition: form-data; name=\"".$key."\"\r\n\r\n";
						$result .= $this->convertEuckrToUtf8($val)."\r\n";
					}
				}

				$result .= "--".$this->Boundary."--";
			}
			else {
				foreach ($contents as $key => $val) {
					if ($key == "msg_list")
						$result .= $key."=".urlencode($this->JSON->encode($this->convertEuckrToUtf8($val)))."&";
					else
						$result .= $key."=".urlencode($this->convertEuckrToUtf8($val))."&";
				}

				$result = substr($result, 0, -1);
			}
		}
		else {
			foreach ($contents as $val)
				$result .= "/".urlencode($val);
		}

		return $result;
	}


	function convertEuckrToUtf8($str) {
		if (is_array($str)) {
			foreach ($str as $key => $val)
				$returnResult[$key] = mb_convert_encoding($val, "UTF-8", "EUC-KR");
		}
		else {
			$returnResult = mb_convert_encoding($str, "UTF-8", "EUC-KR");
		}

		return $returnResult;
	}


	function getToken() {
		$result = $this->execute("token", "post", "Basic ".base64_encode($this->UserID.":".$this->APIKey));
		return $result['content']->token;
	}


	function deleteToken() {
		$this->execute("token", "delete", "Bearer ".$this->Token);
		$this->JSON = NULL;
		unset($this->JSON);
	}


	function getBalance() {
		$result = $this->execute("balance", "get", "Bearer ".$this->Token);
		return $result['content'];
	}


	function sendMessage($contents) {
		$isMulitPart = ($contents['msg_type'] == "mms") ? TRUE : FALSE;
		$result = $this->execute("send", "post", "Bearer ".$this->Token, $this->setContent($contents, TRUE, $isMulitPart), $isMulitPart);
		return $result['content'];
	}


	function getMessage($param) {
		$result = $this->execute("send".$this->setContent($param), "get", "Bearer ".$this->Token);
		return $result['content'];
	}


	function cancelReservation($param){
		$result = $this->execute("reservation".$this->setContent($param), "delete", "Bearer ".$this->Token);
		return $result['content'];
	}

}


?>
