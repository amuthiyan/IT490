<?php
//create the function to check if message is critical
function checkCrit($msg)
{
  $msg = strtolower($msg);
  if (preg_match('/error/',$msg) | preg_match('/critical/',$msg))
    echo('send to log server' . "\n");
}

// Create the error logging function
function LogMsg($e)
{
  $logmsg = array();
  $logmsg['date'] = date("Y-m-d");
  $logmsg['day'] = date("l");
  $logmsg['time'] = date("h:i:sa");
  $logmsg['text'] = $e;

  //log the message
  $msg = implode(" - ",$logmsg);
  //check if msg is critical
  checkCrit($msg);
  error_log($msg."\n",3,"./logfile.log");
}

LogMsg('Critical test')
?>
