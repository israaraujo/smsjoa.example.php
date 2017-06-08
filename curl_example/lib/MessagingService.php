<?php

/**
* @file MessagingService.php
* @brief REST API Messaging module (euc-kr)
* @author INNOPOST (tech@innopost.com)
*/

class MessagingService
{
	private $ServiceURL = "https://api.smsjoa.com";
	private $Version = "1";

	private $UserID;
	private $APIKey;
	private $Token;

	public function __construct($UserID, $APIKey){
		$this->UserID = $UserID;
		$this->APIKey = $APIKey;

		$this->Token = $this->getToken();
	}

	public function __destruct(){
		$this->deleteToken();
	}

	private function executeCURL($uri, $method = null, $header = array(), $postdata = null, $isMultiPart = false){
		$http = curl_init($this->ServiceURL."/".$this->Version."/".$uri);

		if($isMultiPart) {
			$header[] = "Content-Type:multipart/form-data";
		}else{
			$header[] = "Content-Type:application/x-www-form-urlencoded;charset=utf-8;";

			if($postdata){
				foreach($postdata as $k => $v){
					if( $k == "msg" || $k == "subject" ) $v = urlencode($v);
					$temp[] = $k."=".$v;				
				}
				
				$postdata = implode($temp,"&");
			}
		}

		$isPost = ($method == "POST")?true:false;


		$options = array(
			CURLOPT_POST => $isPost,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSLVERSION => 3,
			CURLOPT_HEADER => 0,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_POSTFIELDS => $postdata,
		);

		@curl_setopt_array($http, $options);

		$responseJson = curl_exec($http);

		$http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);

		curl_close($http);

		if($http_status != 200){
			throw new APIException($responseJson);
			//¿À·ùÄÚµå ¹è¿­·Î ¼ö½Å
			//$result = new APIException($responseJson);
			//$returnResult = $result->toArray();
		}else{
			$returnResult = json_decode($responseJson ,true);
		}

		return $returnResult;
	}

	private function convertEuckrToUtf8($str) {
		if(is_array($str)){
			foreach($str as $key => $val){
				$returnResult[$key] = mb_convert_encoding($val,"UTF-8","EUC-KR");
			}
		}else{
			$returnResult = mb_convert_encoding($str,"UTF-8","EUC-KR");
		}

		return $returnResult;
	}

	private function setContent($contents,$isPost=false){
		$result = null;

		if($isPost)
		{
			$result = array();
			foreach($contents as $key => $val)
			{
				if($key == "image")
					$result[$key] = (!empty($val))?"@".realpath("./$val"):"";
				else{
					if(preg_match("/^(msg|msg_list|subject)$/", $key)){
						$val = $this->convertEuckrToUtf8($val);
					}

					if($key == "msg_list"){
						$val = json_encode($val);
					}

					$result[$key] = $val;
				}
			}
		}
		else
		{
			foreach($contents as $val){
				$result .= "/".urlencode($val);
			}
		}

		return $result;
	}

	private function getToken(){
		try{
			$header = array();
			$header[] = "Authorization: Basic ".base64_encode($this->UserID.":".$this->APIKey);
			$result = $this->executeCURL("token","POST",$header);
			return $result['token'];
		}catch(APIException $e){
			echo $e;
			exit;
		}
	}

	private function deleteToken(){
		$header = array();
		$header[] = "Authorization: Bearer ".$this->Token;
		$this->executeCURL("token","DELETE",$header);
	}

	public function getBalance(){
		try{
			$header = array();
			$header[] = "Authorization: Bearer ".$this->Token;
			return $this->executeCURL("balance","GET",$header);
		}catch(APIException $e){
			echo $e;
			exit;
		}
	}

	public function sendMessage($contents){
		try{
			$header = array();
			$header[] = "Authorization: Bearer ".$this->Token;
			$isMulitPart = ($contents['msg_type'] == "mms")?true:false;
			return $this->executeCURL("send","POST",$header,$this->setContent($contents,true),$isMulitPart);
		}catch(APIException $e){
			echo $e;
			exit;
		}
	}

	public function getMessage($contents){
		try{
			$header = array();
			$header[] = "Authorization: Bearer ".$this->Token;
			return $this->executeCURL("send".$this->setContent($contents),"GET",$header);
		}catch(APIException $e){
			echo $e;
			exit;
		}
	}

	public function cancelReservation($contents){
		try{
			$header = array();
			$header[] = "Authorization: Bearer ".$this->Token;
			return $this->executeCURL("reservation".$this->setContent($contents),"DELETE",$header);
		}catch(APIException $e){
			echo $e;
			exit;
		}
	}
}

class APIException extends Exception
{
	public function __construct($response,$code = 10000, Exception $previous = null) {
		$Err = json_decode($response);

		if(is_null($Err)) {
			parent::__construct($response, $code );
		}
		else {
			parent::__construct($Err->message, $Err->code);
		}
	}

	public function toArray(){
		$result = array(
			"code" => $this->code,
			"message" => mb_convert_encoding($this->message,"EUC-KR","UTF-8"),
		);
		return $result;
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: ".mb_convert_encoding($this->message,"EUC-KR","UTF-8")."\n";
	}
}

?>
