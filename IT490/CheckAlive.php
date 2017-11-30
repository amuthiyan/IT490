<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logscript.php');


//pings server we are about to message to see if it is up
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

function SendToConsumer($ini1,$ini2,$server_name)
{
  //Check first rabbitmq server
  $ini1 = parse_ini_file($ini1,$process_sections=true);
  $ip1 = $ini1[$server_name]["BROKER_HOST"];
  $Check_1 = CheckAlive($ip1);

  if($Check_1)
  {
    echo "Sending to primary server".PHP_EOL;
    $client = new rabbitMQClient($ini1,$server_name);
    return $client;
  }
  else
  //Check second rabbitmqserver
  $ini2 = parse_ini_file($ini2,$process_sections=true);
  $ip2 = $ini2[$server_name]["BROKER_HOST"];
  $Check_2 = CheckAlive($ip2);
  {
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

  echo "IP 1: ".$ip1.PHP_EOL;
  echo "IP 2: ".$ip2.PHP_EOL;
}

//CheckAlive("192.168.43.221");
//SendToConsumer("DeckRabbit.ini","DeckStandby.ini","DeckServer");
?>
