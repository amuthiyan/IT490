#!/usr/bin/php
<?php
require_once('../RabbitMQ/path.inc');
require_once('../RabbitMQ/get_host_info.inc');
require_once('../RabbitMQ/rabbitMQLib.inc');
require_once('../Logging/logscript.php');
require_once('../Failover/CheckAlive.php');

function getAllCards()
{
  $client = new rabbitMQClient("../Inis/APIRabbit.ini","APIServer");
  $request = array();
  $request['type'] = "all_cards";
  //$request['message'] = $msg;
  $response = $client->send_request($request);
  //$response = $client->publish($request);

  $response = json_decode($response,true);
  LogMsg("client received response: ");
  echo "client received response: ".PHP_EOL;
  print_r($response);
  echo "\n\n";
  return $response;
}

function getCard($tag,$name)
{
  $file = __FILE__.PHP_EOL;
  $PathArray = explode("/",$file);
  //$client = new rabbitMQClient("APIRabbit.ini","APIServer");
  $client = SendToConsumer("../Inis/APIRabbit.ini","APIStandby.ini","APIServer");
  $request = array();
  $request['type'] = "get_card";
  $request['tag'] = $tag;
  $request['name'] = $name;
  //$request['message'] = $msg;
  $response = $client->send_request($request);
  //$response = $client->publish($request);

  $card = json_decode($response,true);
  LogMsg("client received response: ",$PathArray[4]);
  echo "client received response: ".PHP_EOL;
  //print_r($card);
  //echo "\n\n";
  return $card;
}

//$all_cards = getAllCards();
$card = getCard("LTGY-EN035","Harpie Channeler");
var_dump($card);

//var_dump($card);


//echo $argv[0]." END".PHP_EOL;
?>
