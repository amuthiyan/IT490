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
    case "check_alive":
      LogMsg('Checking alive',$PathArray[4]);
      return True;
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("AliveRabbit.ini","AliveServer");
$server->process_requests('requestProcessor');
exit();
?>
