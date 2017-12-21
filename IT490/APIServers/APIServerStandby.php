#!/usr/bin/php
<?php
/*
A Server for handling requests for card data. It receives requests from a client
through RabbitMQ, and responds according to the request type given.
*/

//Define path for root to folow
$root_path = '/home/amuthiyan/git/IT490/';

require_once($root_path.'RabbitMQ/path.inc');
require_once($root_path.'RabbitMQ/get_host_info.inc');
require_once($root_path.'RabbitMQ/rabbitMQLib.inc');
require_once($root_path.'Logging/logscript.php');
//require_once('name_print_tags.php');
require_once($root_path.'APIServers/get_card.php');


function requestProcessor($request)
{
  //define variables for logging
  $file = __FILE__.PHP_EOL;
  $PathArray = explode("/",$file);

  LogMsg("received request",$PathArray[4], "amuthiyan", "DevDMZStandby");
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    LogMsg("ERROR: unsupported message type",$PathArray[4], "amuthiyan", "DevDMZStandby");
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "all_cards":
      LogMsg('Sending all card tags now',$PathArray[4], "amuthiyan", "DevDMZStandby");
      return getCardTags();
    case "get_card":
      LogMsg('Sending requested card info now',$PathArray[4], "amuthiyan", "DevDMZStandby");
      return getCard($request['tag'],$request['name']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}
//define variables for logging
$file = __FILE__.PHP_EOL;
$PathArray = explode("/",$file);

LogMsg("Trying to start primary server test",$PathArray[4], "amuthiyan","DevDMZStandby");
echo 'Staring primary'.PHP_EOL;

$server = new rabbitMQServer("/home/amuthiyan/git/Inis/APIStandby.ini","APIServer");
//LogMsg("Primary Server started", $PathArray[4],"DevDMZ");

$server->process_requests('requestProcessor');
exit();
?>
