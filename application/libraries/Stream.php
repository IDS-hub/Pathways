<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require "vendor/autoload.php";
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;
use Firebase\JWT\JWT;

class Stream{

	public function __construct()
	{
		$this->CI->opentok = new OpenTok('45976362', '4bea92f84ea76bcccdbd126de9021fefdb22e1aa');
	}

	public function getSessionId()
	{
		$session = $this->CI->opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));
		$sessionId = $session->getSessionId();
		return $sessionId;
	}

	public function getGenerateToken($session_id,$token_type)
	{
		if($token_type == 'publisher')
		{
			$token = $this->CI->opentok->generateToken($session_id,array(
			    'role'       => Role::PUBLISHER,
			    'expireTime' => time()+(7 * 24 * 60 * 60)
			));
		}
		if($token_type == 'moderator')
		{
			$token = $this->CI->opentok->generateToken($session_id,array(
			    'role'       => Role::MODERATOR,
			    'expireTime' => time()+(7 * 24 * 60 * 60)
			));
		}
		if($token_type == 'subscriber')
		{
			$token = $this->CI->opentok->generateToken($session_id,array(
			    'role'       => Role::SUBSCRIBER,
			    'expireTime' => time()+(7 * 24 * 60 * 60)
			));
		}

		return $token;
	}


	public function getJWTToken()
	{
		$key = "4bea92f84ea76bcccdbd126de9021fefdb22e1aa";
		$token = array(
		    "iss" => "45976362",
		    "aud" => "http://example.com",
		    "iat" => time(),
		    "exp" => 1698920139
		);

		$jwt = JWT::encode($token, $key);
		return $jwt;
	}


	public function getStreamUrl($session_id)
	{
		//echo $session_id;exit;
		$key = "4bea92f84ea76bcccdbd126de9021fefdb22e1aa";
		$token = array(
		    "iss" => "45976362",
		    "aud" => "http://example.com",
		    "iat" => time(),
		    "exp" => strtotime('tomorrow')
		);

		$jwt_token = JWT::encode($token, $key);
		//echo $jwt_token;exit;
		$service_url = 'https://api.opentok.com/v2/project/45976362/broadcast';
       	$curl = curl_init($service_url);
       	$headers = array(
		    'X-OPENTOK-AUTH: '.$jwt_token,
		    'Content-Type: application/json',
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
       	$curl_post_data = array(
            "sessionId" => $session_id,
            //"layout"=>array("type"=>"custom","stylesheet"=>"stream.instructor {width: 100%; height:50%;}")
        );
       	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       	curl_setopt($curl, CURLOPT_POST, true);
       	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
       	$curl_response = curl_exec($curl);
       	curl_close($curl);

       	return $curl_response;
       	//print_r($curl_response);exit;
	}


	public function stopBrodcasting($brodcast_id)
	{
		$key = "4bea92f84ea76bcccdbd126de9021fefdb22e1aa";
		$token = array(
		    "iss" => "45976362",
		    "aud" => "http://example.com",
		    "iat" => time(),
		    "exp" => strtotime('tomorrow')
		);

		$jwt_token = JWT::encode($token, $key);
		//echo $jwt_token;exit;
		$service_url = 'https://api.opentok.com/v2/project/45976362/broadcast/'.$brodcast_id.'/stop';
       	$curl = curl_init($service_url);
       	$headers = array(
		    'X-OPENTOK-AUTH: '.$jwt_token,
		    'Content-Type: application/json',
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
       	$curl_post_data = array(
            "sessionId" => $session_id
        );
       	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       	curl_setopt($curl, CURLOPT_POST, true);
       	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
       	$curl_response = curl_exec($curl);
       	curl_close($curl);

       	return $curl_response;
	}



}
