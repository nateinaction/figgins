<?php

require_once __DIR__ . '/vendor/autoload.php';

/* Create JWT token */
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$secret = 'poke123';
$signer = new Sha256();
$token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                        ->setAudience('http://example.org') // Configures the audience (aud claim)
                        ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setNotBefore(time()) // Configures the time that the token can be used (nbf claim)
                        ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                        ->set('uid', 1) // Configures a new claim, called "uid"
                        ->sign($signer, 'poke123') // creates a signature using "poke123" as key
                        ->getToken(); // Retrieves the generated token


$client_url = 'http://figgins-client.dev/wp-json/figgins_client/v1/check/';
$fields = array(
	'token' => urlencode($token),
  'filename' => 'ngay-auto-increment.php'
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

// create curl resource
$ch = curl_init($client_url);

curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

echo $output . "\n";
