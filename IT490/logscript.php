<?php
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
  error_log($msg."\n",3,"./logfile.log");
}
LogMsg('test')
?>
