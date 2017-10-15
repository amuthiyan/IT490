#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logscript.php');
require_once('name_print_tags.php');
require_once('get_card.php');


function requestProcessor($request)
{
  //define variables for logging
  $file = __FILE__.PHP_EOL;
  $PathArray = explode("/",$file);

  LogMsg("received request",$PathArray[4]);
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    LogMsg("ERROR: unsupported message type");
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "all_cards":
      LogMsg('Sending all card tags now',$PathArray[4]);
      return getCardTags();
    case "get_card":
      LogMsg('Sending requested card info now',$PathArray[4]);
      return getCard($request['tag'],$request['name']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("APIRabbit.ini","APIServer");
$server->process_requests('requestProcessor');
exit();
?>
