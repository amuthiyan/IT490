<?php
//Define path for root to folow
$root_path = '/home/amuthiyan/git/IT490/';

require_once($root_path.'RabbitMQ/path.inc');
require_once($root_path.'RabbitMQ/get_host_info.inc');
require_once($root_path.'RabbitMQ/rabbitMQLib.inc');

function getHost(){
  $IP = shell_exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' |
                    grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1' ");
  $Hname = shell_exec("cat .$root_path.Deploy/DNS.conf | grep -i $IP | awk '{ print $2 }'");

  return $Hname;
}


//create the function to check if message is critical
function ifCrit($msg){
  $msg = strtolower($msg);
  if (preg_match('/error/',$msg) | preg_match('/critical/',$msg)
      | preg_match('/failed/',$msg) | preg_match('/successful/',$msg))
  {
    return True;
  }
  return false;
}

function Logger($status,$message,$path,$user,$Host){
  if($status == 1){
    LogMsg($message." successful",$path,$user,$Host);
  }
  else{
    LogMsg($message." failed",$path,$user,$Host);
  }
}

// Create the error logging function
function LogMsg($msg,$path,$user,$Host){
  //Define path for root to folow
  $root_path = '/home/amuthiyan/git/IT490/';

  $file = basename($_SERVER['PHP_SELF']);
  $Ffile = trim(preg_replace('/\s+/', ' ', $path));

  $logmsg = array();
  $logmsg['date'] = date("Y-m-d");
  $logmsg['day'] = date("l");
  $logmsg['time'] = date("h:i:sa");
  $logmsg['user'] = $user;
  $logmsg['machine'] = $Host;
  $logmsg['text'] = $msg;
  $logmsg['file'] = $Ffile;

  //log the message
  $msg = implode(" - ",$logmsg);
  //check if msg is critical
  if (ifCrit($msg))
  {
    //send to log server
    $client = new rabbitMQClient("/home/amuthiyan/git/Inis/logRabbitMQ.ini","testServer");
    $client->publish($msg);
  }
  //log the message
  error_log($msg.PHP_EOL,3,$root_path.'Logging/logfile.log');
}

function LogServerMsg($e)
{
  //Define path for root to folow
  $root_path = '/home/amuthiyan/git/IT490/';


  error_log($e.PHP_EOL,3,$root_path.'Logging/logfile.log');
}
?>
