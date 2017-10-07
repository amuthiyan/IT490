#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logscript.php');

function getCardInfo($type,$tag,$name)
{
  $client = new rabbitMQClient("APIRabbit.ini","APIServer");
  $request = array();
  $request['type'] = $type;
  $request['tag'] = $tag;
  $request['name'] = $name;
  //$request['message'] = $msg;
  $response = $client->send_request($request);
  //$response = $client->publish($request);

  $response = json_decode($response);
  LogMsg("client received response: ");
  echo "client received response: ".PHP_EOL;
  print_r($response);
  echo "\n\n";
  return $response;
}

getCardInfo("get_card","LTGY-EN035","Harpie Channeler");

//echo $argv[0]." END".PHP_EOL;
?>
