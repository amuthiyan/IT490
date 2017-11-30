#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logscript.php');
//require_once('name_print_tags.php');
require_once('save_deck.php');


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
    case "save_deck":
      LogMsg('Saving Deck now',$PathArray[4]);
      return saveCard($request['uid'],$request['name'],$request['tag'],$request['avg_price']);
    case "load_deck":
      LogMsg('Loading deck',$PathArray[4]);
      return loadDeck($request['uid']);
    case "remove_card":
      LogMsg('Removing Card',$PathArray[4]);
      return removeCard($request['uid'],$request['tag']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("DeckRabbit.ini","DeckServer");
$server->process_requests('requestProcessor');
exit();
?>
