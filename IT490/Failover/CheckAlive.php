<?php
/*
This Script contains functions to check if a server is down, and to route it
appropriately to the secondary server if this is the case.
*/

require_once('../RabbitMQ/path.inc');
require_once('../RabbitMQ/get_host_info.inc');
require_once('../RabbitMQ/get_host_info.inc');
require_once('../RabbitMQ/rabbitMQLib.inc');
require_once('../Logging/logscript.php');


/*
Function CheckAlive pings an IP address to check if it is on the network
Parameters:
ip - The IP address of the server it is going to ping
Returns:
Returns a boolean True/False. True if the machine is on the network and the
ping was succesfull, and False if the ping failed.
*/
function CheckAlive($ip)
{
  exec("ping -c 1 " . $ip,$output,$result);
  if ($result == 0)
  {
    //echo "Ping successful".PHP_EOL;
    return True;
  }
  else
  {
    //echo "Fail so hard".PHP_EOL;
    return False;
  }
}

/*
Function SendToConsumer checks if the primary listener is available to receive
requests, and if not, sends the messages to the secondary listener.
Parameters:
ini1 - The .ini file that is associated with the primary listener
ini2 - The .ini file that is associated with the secondary listener
server_name - The name of the server that the listeners are on.
Returns:
A client that the message will be sent through.
*/
function SendToConsumer($ini1,$ini2,$server_name)
{
  //Check first rabbitmq server
  $ini1_array = parse_ini_file($ini1,$process_sections=true);
  $ip1 = $ini1_array[$server_name]["BROKER_HOST"];
  //echo $ip1.PHP_EOL;
  $Check_1 = CheckAlive($ip1);

  if($Check_1)
  {
    echo "Sending to primary server".PHP_EOL;
    $client = new rabbitMQClient($ini1,$server_name);
    echo "Done".PHP_EOL;
    return $client;
  }
  else
  {
    //Check second rabbitmqserver
    $ini2_array = parse_ini_file($ini2,$process_sections=true);
    $ip2 = $ini2_array[$server_name]["BROKER_HOST"];
    $Check_2 = CheckAlive($ip2);
    if($Check_2)
    {
      echo "Trying backup instead".PHP_EOL;
      $client = new rabbitMQClient($ini2,$server_name);
      return $client;
    }
    else
    {
      echo "Both Servers down, system cannot function".PHP_EOL;
    }
  }
}

?>
