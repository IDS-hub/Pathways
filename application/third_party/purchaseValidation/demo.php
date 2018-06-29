<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
require_once './vendor/autoload.php';
use ReceiptValidator\GooglePlay\Validator as PlayValidator;
// google authencation 
$applicationName = 'PathwaysTest';
$scope = ['https://www.googleapis.com/auth/androidpublisher'];
$configLocation = './pathways_service_account.json';
// receipt data
$packageName = "com.pathways.pathwayspainrelieftest";
$productId = "pathways_test";
//$purchaseToken = "ahlljmenbajpfdgegeijogjh.AO-J1OzHa9iu9a3gkWAekofCb5co9bq3W-uC91OWTK46Zp6oRUH1VG1rCWYSUgcsWHoxfTcY33GXSWOHrJ9lyCCqATsJOWegE2n324pGY6t4aby5Hbeb8BWXCXOBlMIDZ3ZuybxuNOyre0IsencPMxKItEGMg9jZfQ";
$purchaseToken = "djfjbcpmoodacgnonneiamld.AO-J1OxL6Fiepky8s06wmXw45KNweQ4aXmfQ1AZw_tbUa7A2wQAKqg3Stg4ggwmY6x9f79RMYfdu3bWr4e1TFKNXFR7Y-D3rvo-HfcHFDfTWAhUGpkG5ayvY7XHOSi1nZgCfWyy0TAt9vcu__vsBzEuuRoaW4XlLEg"; /////////////OLD Token

$purchaseToken = "nllneafchjeebekggjonnpkf.AO-J1OxMuxRVAea1R3m7hxLZrvo99HFutQGV1dDltQWu85oXeP2hhQPlDcYP9THlweEv2yVMBypma36wmzSoP-yfDzNzsJpppXR1vjxBsZ9lqr7kaRYkjCViE7WnUKp-a4y3IUnuKPUsJaXcW1IX7jqw9N3z4Qp1Ug";////////////New susbscription

$purchaseToken = "ejgojebffoajcnelfnpfdhhc.AO-J1OxAHHy3AfueyNyLpJoZxb29G_IpZ3OzGxsrjaR1EuWalcjN-0xupA-ctV2Sbgm_Z8VOUssBvubl902AAcFePtZMDC720cJmH2HcCsjrxA_saeD9KnwPHLxgj05NdYjFwo8-3AIyH7Go9Vnu9hezsQk_G-NSYg";   ///////////After cancel subscription then again continue Subscrption


$client = new \Google_Client();
$client->setApplicationName($applicationName);
$client->setAuthConfig($configLocation);
$client->setScopes($scope);
//var_dump($client);
$validator = new PlayValidator(new \Google_Service_AndroidPublisher($client));
//echo "<pre>";
//print_r($validator);
try{
	$service = new Google_Service_AndroidPublisher($client);
	$subscription = $service->purchases_subscriptions->get($packageName, $productId, $purchaseToken);
	echo '<pre>'; print_r($subscription); //die();
	$expiryTimeMillis = $subscription->expiryTimeMillis;
	$cancelReason = $subscription->cancelReason;
	$userCancellationTimeMillis = $subscription->userCancellationTimeMillis;
	if($userCancellationTimeMillis!='') { //////////////////////That menas user cancel the subscription
		$isSubscribed = 0;
	} 
	
}catch(Exception $e){
    echo 'got error = ' . $e->getMessage() . PHP_EOL;	
}



/***try {
    $response = $validator->setPackageName($packageName)->setProductId($productId)->setPurchaseToken($purchaseToken)->validateSubscription();
    echo '<pre>'; print_r($response);
    exit;
} catch (Exception $e) {
  echo 'got error = ' . $e->getMessage() . PHP_EOL;
}*****/