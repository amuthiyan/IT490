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
    echo "Ping successful".PHP_EOL;
    return True;
  }
  else
  {
    echo "Fail so hard".PHP_EOL;
    return False;
  }
}

function SendToConsumer($ini,$server_name)
{
  
}
//CheckAlive("192.168.43.221");
?>
