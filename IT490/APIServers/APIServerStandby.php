#!/usr/bin/php
<?php
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

  LogMsg("received request",$PathArray[4], "DevDMZ");
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    LogMsg("ERROR: unsupported message type",$PathArray[4], "DevDMZ");
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "all_cards":
      LogMsg('Sending all card tags now',$PathArray[4], "DevDMZ");
      return getCardTags();
    case "get_card":
      LogMsg('Sending requested card info now',$PathArray[4], "DevDMZ");
      return getCard($request['tag'],$request['name']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}
//define variables for logging
$file = __FILE__.PHP_EOL;
$PathArray = explode("/",$file);

LogMsg("Trying to start standby server", $PathArray[4],"DevDMZ");
$server = new rabbitMQServer($root_path."Inis/APIStandby.ini","APIServer");
LogMsg("Standby Server started", $PathArray[4],"DevDMZ");

$server->process_requests('requestProcessor');
exit();
?>
